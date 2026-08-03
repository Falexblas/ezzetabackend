<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Exception;

class CartService
{
    public function getCart(int $userId): array
    {
        try {
            return CartItem::with(['variant.product'])
                ->where('user_id', $userId)
                ->get()
                ->toArray();
        } catch (Exception $e) {
            throw new Exception("Error al obtener el carrito: " . $e->getMessage());
        }
    }

    public function addItem(int $userId, int $variantId, int $quantity): CartItem
    {
        try {
            $variant = ProductVariant::find($variantId);
            if (!$variant) {
                throw new Exception("Variante de producto no encontrada", 404);
            }

            // Buscar si ya existe la variante en el carrito del usuario
            $cartItem = CartItem::where('user_id', $userId)
                ->where('product_variant_id', $variantId)
                ->first();

            $newQuantity = $quantity;
            if ($cartItem) {
                $newQuantity += $cartItem->quantity;
            }

            // Validar stock disponible
            if ($variant->stock < $newQuantity) {
                throw new Exception("Stock insuficiente. Disponible: {$variant->stock}.", 400);
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cartItem = CartItem::create([
                    'user_id' => $userId,
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);
            }

            return $cartItem->load('variant.product');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateItem(int $userId, int $cartItemId, int $quantity): CartItem
    {
        try {
            $cartItem = CartItem::where('user_id', $userId)->find($cartItemId);
            if (!$cartItem) {
                throw new Exception("Item del carrito no encontrado", 404);
            }

            $variant = ProductVariant::find($cartItem->product_variant_id);
            if (!$variant) {
                throw new Exception("Variante de producto no encontrada", 404);
            }

            if ($variant->stock < $quantity) {
                throw new Exception("Stock insuficiente. Disponible: {$variant->stock}.", 400);
            }

            $cartItem->update(['quantity' => $quantity]);
            return $cartItem->load('variant.product');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeItem(int $userId, int $cartItemId): void
    {
        try {
            $cartItem = CartItem::where('user_id', $userId)->find($cartItemId);
            if (!$cartItem) {
                throw new Exception("Item del carrito no encontrado o no pertenece al usuario", 404);
            }

            $cartItem->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function clearCart(int $userId): void
    {
        try {
            CartItem::where('user_id', $userId)->delete();
        } catch (Exception $e) {
            throw new Exception("Error al vaciar el carrito: " . $e->getMessage());
        }
    }
}
