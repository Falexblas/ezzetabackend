<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function createOrder(int $userId, array $data): Order
    {
        DB::beginTransaction();
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception("Usuario no encontrado", 404);
            }

            // 1. Obtener items del carrito
            $cartItems = CartItem::with(['variant.product'])->where('user_id', $userId)->get();
            if ($cartItems->isEmpty()) {
                throw new Exception("El carrito está vacío.", 400);
            }

            // Validar stock de todos los items
            $subtotal = 0.00;
            $itemsToCreate = [];

            foreach ($cartItems as $item) {
                $variant = $item->variant;
                if (!$variant) {
                    throw new Exception("Una de las variantes del producto ya no existe.", 404);
                }

                if ($variant->stock < $item->quantity) {
                    throw new Exception("Stock insuficiente para el producto {$variant->product->name} (Color: {$variant->color}, Talla: {$variant->size}). Stock disponible: {$variant->stock}.", 400);
                }

                // El precio unitario es: base_price + price_adjustment
                $unitPrice = floatval($variant->product->base_price) + floatval($variant->price_adjustment);
                $totalPrice = $unitPrice * $item->quantity;

                $subtotal += $totalPrice;

                $itemsToCreate[] = [
                    'product_variant_id' => $variant->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'variant_model' => $variant // guardar para decrementar stock luego
                ];
            }

            // 2. Validar y aplicar código de descuento si existe
            $discountAmount = 0.00;
            $discountCodeId = null;

            if (isset($data['discount_code']) && !empty($data['discount_code'])) {
                try {
                    $validation = $this->discountService->validateDiscountCode($data['discount_code'], $subtotal);
                    $discountCode = $validation['discount_code'];
                    $discountAmount = floatval($validation['discount_amount']);
                    $discountCodeId = $discountCode->id;

                    // Incrementar el uso del código
                    $discountCode->increment('current_uses');
                } catch (Exception $e) {
                    // Si el descuento no es válido, lanzamos la excepción para cancelar la transacción
                    throw new Exception("Error con el código de descuento: " . $e->getMessage(), 400);
                }
            }

            // 3. Calcular costo de envío
            // Envío GRATIS para compras con subtotal post-descuento >= S/ 200
            $postDiscountSubtotal = $subtotal - $discountAmount;
            $shippingCost = ($postDiscountSubtotal >= 200.00) ? 0.00 : 15.00;

            // 4. Calcular total
            $total = $postDiscountSubtotal + $shippingCost;

            // 5. Copiar la dirección del usuario a la orden
            // Si la dirección no está completa en el request, intentar usar la del perfil del usuario
            $shippingAddress = $data['shipping_address'] ?? $user->address;
            $shippingDistrict = $data['shipping_district'] ?? $user->district;
            $shippingProvince = $data['shipping_province'] ?? $user->province;
            $shippingDepartment = $data['shipping_department'] ?? $user->department;

            if (empty($shippingAddress) || empty($shippingDistrict) || empty($shippingProvince) || empty($shippingDepartment)) {
                throw new Exception("Debe proporcionar una dirección de envío completa.", 400);
            }

            // Si el usuario no tenía dirección en su perfil, actualizarla para futuras compras
            if (empty($user->address)) {
                $user->update([
                    'address' => $shippingAddress,
                    'district' => $shippingDistrict,
                    'province' => $shippingProvince,
                    'department' => $shippingDepartment,
                ]);
            }

            // 6. Generar número de orden: EZZ-YYYY-XXXX (secuencial de 4 dígitos relleno con ceros)
            $year = date('Y');
            $yearOrderCount = Order::whereYear('created_at', $year)->count();
            $sequential = $yearOrderCount + 1;
            $orderNumber = 'EZZ-' . $year . '-' . str_pad($sequential, 4, '0', STR_PAD_LEFT);

            // Asegurar unicidad (en caso de concurrencia)
            while (Order::where('order_number', $orderNumber)->exists()) {
                $sequential++;
                $orderNumber = 'EZZ-' . $year . '-' . str_pad($sequential, 4, '0', STR_PAD_LEFT);
            }

            // 7. Crear la orden
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'shipping_district' => $shippingDistrict,
                'shipping_province' => $shippingProvince,
                'shipping_department' => $shippingDepartment,
                'notes' => $data['notes'] ?? null,
                'discount_code_id' => $discountCodeId,
            ]);

            // 8. Crear los items de la orden y descontar stock
            foreach ($itemsToCreate as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $itemData['product_variant_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['total_price'],
                ]);

                // Descontar stock
                $variant = $itemData['variant_model'];
                $variant->decrement('stock', $itemData['quantity']);
            }

            // 9. Vaciar el carrito de compras
            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return $order->load(['items.variant.product', 'discountCode']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserOrders(int $userId): array
    {
        try {
            return Order::with(['items.variant.product'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        } catch (Exception $e) {
            throw new Exception("Error al obtener las órdenes del usuario: " . $e->getMessage());
        }
    }

    public function getOrderByNumber(string $orderNumber, int $userId): Order
    {
        try {
            // Un cliente común solo puede ver su propia orden. Un admin puede ver cualquier orden.
            $user = User::find($userId);
            $query = Order::with(['items.variant.product', 'discountCode', 'user'])
                ->where('order_number', $orderNumber);

            if ($user && $user->role !== 'admin') {
                $query->where('user_id', $userId);
            }

            $order = $query->first();

            if (!$order) {
                throw new Exception("Orden no encontrada", 404);
            }

            return $order;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllOrders(): array
    {
        try {
            return Order::with(['user', 'items.variant.product'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        } catch (Exception $e) {
            throw new Exception("Error al obtener todas las órdenes: " . $e->getMessage());
        }
    }

    public function updateOrderStatus(int $orderId, string $status): Order
    {
        try {
            $order = Order::find($orderId);
            if (!$order) {
                throw new Exception("Orden no encontrada", 404);
            }

            $validStatuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                throw new Exception("Estado de orden inválido.", 400);
            }

            $order->update(['status' => $status]);
            return $order->load(['items.variant.product', 'user']);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
