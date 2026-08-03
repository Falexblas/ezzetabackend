<?php

namespace App\Services;

use App\Models\DiscountCode;
use Exception;

class DiscountService
{
    public function validateDiscountCode(string $code, float $subtotal): array
    {
        try {
            $discount = DiscountCode::where('code', $code)->first();

            if (!$discount) {
                throw new Exception("Código de descuento no válido.", 404);
            }

            if (!$discount->is_active) {
                throw new Exception("Este código de descuento está desactivado.", 400);
            }

            if ($discount->expires_at && $discount->expires_at->isPast()) {
                throw new Exception("Este código de descuento ha expirado.", 400);
            }

            if ($discount->max_uses !== null && $discount->current_uses >= $discount->max_uses) {
                throw new Exception("Este código de descuento ha agotado sus usos.", 400);
            }

            if ($subtotal < $discount->min_purchase) {
                throw new Exception("Monto mínimo de compra no alcanzado (Mínimo: S/ {$discount->min_purchase}).", 400);
            }

            $discountAmount = $discount->calculateDiscount($subtotal);

            return [
                'discount_code' => $discount,
                'discount_amount' => $discountAmount
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createDiscountCode(array $data): DiscountCode
    {
        try {
            return DiscountCode::create([
                'code' => strtoupper($data['code']),
                'type' => $data['type'],
                'value' => $data['value'],
                'min_purchase' => $data['min_purchase'] ?? 0.00,
                'max_uses' => $data['max_uses'] ?? null,
                'current_uses' => 0,
                'is_active' => true,
                'expires_at' => $data['expires_at'] ?? null,
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al crear código de descuento: " . $e->getMessage());
        }
    }
}
