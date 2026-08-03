<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $order = $this->orderService->createOrder($userId, $request->validated());
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Orden de compra creada exitosamente.'
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

    public function index(): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $orders = $this->orderService->getUserOrders($userId);
            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Historial de órdenes recuperado.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $order_number): JsonResponse
    {
        try {
            $userId = auth('api')->id();
            $order = $this->orderService->getOrderByNumber($order_number, $userId);
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Detalle de orden recuperado.'
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

    public function adminOrders(): JsonResponse
    {
        try {
            // Se asume que el middleware de Admin protege esta ruta
            $orders = $this->orderService->getAllOrders();
            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Todas las órdenes recuperadas.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|string|in:pending,paid,shipped,delivered,cancelled'
            ]);

            $order = $this->orderService->updateOrderStatus($id, $request->status);
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Estado de la orden actualizado exitosamente.'
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
}
