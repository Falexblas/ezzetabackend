<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'EZZETA10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_purchase' => 100.00,
                'expires_at' => now()->addDays(90),
            ],
            [
                'code' => 'VERANO25',
                'type' => 'percentage',
                'value' => 25.00,
                'min_purchase' => 150.00,
                'expires_at' => now()->addDays(60),
            ],
            [
                'code' => 'FIJO50',
                'type' => 'fixed',
                'value' => 50.00,
                'min_purchase' => 200.00,
                'expires_at' => now()->addDays(45),
            ],
        ];

        foreach ($coupons as $coupon) {
            DiscountCode::updateOrCreate(
                ['code' => $coupon['code']],
                [
                    'type' => $coupon['type'],
                    'value' => $coupon['value'],
                    'min_purchase' => $coupon['min_purchase'],
                    'max_uses' => 500,
                    'current_uses' => 0,
                    'is_active' => true,
                    'expires_at' => $coupon['expires_at'],
                ]
            );
        }
    }
}
