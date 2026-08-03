<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear usuarios de prueba obligatorios
        // Administrador
        User::create([
            'name' => 'Admin Ezzeta',
            'email' => 'admin@ezzeta.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '999888777',
            'address' => 'Av. Larco 123',
            'district' => 'Miraflores',
            'province' => 'Lima',
            'department' => 'Lima',
            'is_active' => true,
        ]);

        // Cliente común
        User::create([
            'name' => 'Cliente Ezzeta',
            'email' => 'cliente@ezzeta.com',
            'password' => Hash::make('cliente123'),
            'role' => 'client',
            'phone' => '999777666',
            'address' => 'Calle Los Jazmines 456',
            'district' => 'San Isidro',
            'province' => 'Lima',
            'department' => 'Lima',
            'is_active' => true,
        ]);

        // 2. Ejecutar seeders secundarios
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            DiscountCodeSeeder::class,
        ]);
    }
}
