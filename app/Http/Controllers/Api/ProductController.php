<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['category', 'min_price', 'max_price', 'sizes', 'colors', 'sort', 'search']);
            $products = $this->productService->getAllProducts($filters);
            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'Productos recuperados exitosamente.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $product = $this->productService->getProductBySlug($slug);
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Detalle del producto recuperado.'
            ], 200);
        } catch (Exception $e) {
            $code = $e->getCode() == 404 ? 404 : 500;
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Producto creado con sus variantes.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(StoreProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Producto actualizado con sus variantes.'
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
            $product = $this->productService->deleteProduct($id);
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Producto desactivado (deshabilitado del catálogo) con éxito.'
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
