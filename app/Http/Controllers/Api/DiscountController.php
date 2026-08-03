<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function validateCode(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'subtotal' => 'required|numeric|min:0'
            ]);

            $result = $this->discountService->validateDiscountCode($request->code, $request->subtotal);

            return response()->json([
                'success' => true,
                'data' => [
                    'code' => $result['discount_code']->code,
                    'type' => $result['discount_code']->type,
                    'value' => floatval($result['discount_code']->value),
                    'discount_amount' => $result['discount_amount']
                ],
                'message' => 'Código de descuento válido.'
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

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:discount_codes,code',
                'type' => 'required|string|in:percentage,fixed',
                'value' => 'required|numeric|min:0',
                'min_purchase' => 'nullable|numeric|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'expires_at' => 'nullable|date|after:now',
            ]);

            $discountCode = $this->discountService->createDiscountCode($validated);

            return response()->json([
                'success' => true,
                'data' => $discountCode,
                'message' => 'Código de descuento creado exitosamente.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
