<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $cart = $this->cartService->getCart($userId);
            return response()->json([
                'success' => true,
                'data' => $cart,
                'message' => 'Carrito obtenido con éxito.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $item = $this->cartService->addItem(
                $userId,
                $request->product_variant_id,
                $request->quantity
            );
            return response()->json([
                'success' => true,
                'data' => $item,
                'message' => 'Producto agregado al carrito con éxito.'
            ], 201);
        } catch (Exception $e) {
            $code = $e->getCode() == 404 ? 404 : 400;
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $item = $this->cartService->updateItem($userId, $id, $request->quantity);
            return response()->json([
                'success' => true,
                'data' => $item,
                'message' => 'Cantidad actualizada con éxito.'
            ], 200);
        } catch (Exception $e) {
            $code = $e->getCode() == 404 ? 404 : 400;
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $this->cartService->removeItem($userId, $id);
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Producto quitado del carrito.'
            ], 200);
        } catch (Exception $e) {
            $code = $e->getCode() == 404 ? 404 : 400;
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $this->cartService->clearCart($userId);
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Carrito vaciado con éxito.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
