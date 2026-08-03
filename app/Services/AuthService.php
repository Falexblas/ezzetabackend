<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthService
{
    public function register(array $data): User
    {
        try {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'district' => $data['district'] ?? null,
                'province' => $data['province'] ?? null,
                'department' => $data['department'] ?? null,
                'role' => 'client', // Por defecto, registro público es cliente
                'is_active' => true,
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al registrar el usuario: " . $e->getMessage());
        }
    }

    public function login(array $credentials): array
    {
        try {
            // Intentar autenticar y generar un token JWT
            if (!$token = auth('api')->attempt($credentials)) {
                throw new Exception("Credenciales incorrectas.", 401);
            }

            $user = auth('api')->user();

            if (!$user->is_active) {
                auth('api')->logout();
                throw new Exception("Su cuenta está inactiva.", 403);
            }

            return [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function logout(): void
    {
        try {
            auth('api')->logout();
        } catch (Exception $e) {
            throw new Exception("Error al cerrar sesión: " . $e->getMessage());
        }
    }

    public function me(): User
    {
        $user = auth('api')->user();
        if (!$user) {
            throw new Exception("Usuario no autenticado", 401);
        }
        return $user;
    }
}
