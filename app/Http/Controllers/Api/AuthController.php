<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Exception;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario registrado exitosamente.'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Inicio de sesión exitoso.'
            ], 200);
        } catch (Exception $e) {
            $code = $e->getCode() >= 400 && $e->getCode() <= 500 ? $e->getCode() : 400;
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Sesión cerrada exitosamente.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->me();
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Datos de usuario recuperados.'
            ], 200);
        } catch (Exception $e) {
            $code = $e->getCode() == 401 ? 401 : 400;
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], $code);
        }
    }
}
