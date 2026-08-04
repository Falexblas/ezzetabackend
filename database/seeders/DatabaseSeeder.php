<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ezzeta.com'],
            [
                'name' => 'Admin Ezzeta',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '999888777',
                'address' => 'Av. Larco 123',
                'district' => 'Miraflores',
                'province' => 'Lima',
                'department' => 'Lima',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'cliente@ezzeta.com'],
            [
                'name' => 'Cliente Ezzeta',
                'password' => Hash::make('cliente123'),
                'role' => 'client',
                'phone' => '999777666',
                'address' => 'Calle Los Jazmines 456',
                'district' => 'San Isidro',
                'province' => 'Lima',
                'department' => 'Lima',
                'is_active' => true,
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            DiscountCodeSeeder::class,
        ]);
    }
}
