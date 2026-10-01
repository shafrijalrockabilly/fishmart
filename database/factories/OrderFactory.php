<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(150000, 2500000);
        $shippingCost = 20000;
        $grandTotal = $subtotal + $shippingCost;

        return [
            'invoice_no' => 'INV/'.date('Ymd').'/'.fake()->unique()->numberBetween(1000, 9999),
            'user_id' => User::factory(),
            'status' => OrderStatus::Completed,
            'recipient_name' => fake()->name(),
            'recipient_phone' => fake()->phoneNumber(),
            'shipping_address' => fake()->address(),
            'shipping_city_id' => '151',
            'postal_code' => '40115',
            'courier' => 'jne',
            'shipping_service' => 'REG',
            'shipping_etd' => '2-3 Hari',
            'shipping_cost' => $shippingCost,
            'total_weight_gram' => 1200,
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'grand_total' => $grandTotal,
            'paid_at' => now()->subDays(2),
            'completed_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
        ];
    }
}
