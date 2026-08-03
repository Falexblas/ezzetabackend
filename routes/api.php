<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DiscountController;

/*
|--------------------------------------------------------------------------
| API Routes - EZZETA Clothing Store
|--------------------------------------------------------------------------
*/

// Rutas Públicas de Autenticación
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Rutas Públicas de Catálogo
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Rutas Protegidas por JWT
Route::middleware(['jwt.auth'])->group(function () {
    // Autenticación Privada
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Carrito de compras
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);

    // Validación de códigos de descuento
    Route::post('/discounts/validate', [DiscountController::class, 'validateCode']);

    // Órdenes de compra
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order_number}', [OrderController::class, 'show']);

    // Rutas exclusivas para Administradores
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        // Gestión de catálogo
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Gestión de órdenes
        Route::get('/orders', [OrderController::class, 'adminOrders']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

        // Gestión de cupones de descuento
        Route::post('/discounts', [DiscountController::class, 'store']);
    });
});
