<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();

        if ($customers->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $customer = $customers->random();
            $product = Product::inRandomOrder()->first();
            $qty = fake()->numberBetween(1, 3);
            $subtotal = $product->price * $qty;
            $shippingCost = 20000;
            $grandTotal = $subtotal + $shippingCost;

            $order = Order::create([
                'invoice_no' => 'INV/'.date('Ymd').'/'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'status' => OrderStatus::Completed,
                'recipient_name' => $customer->name,
                'recipient_phone' => $customer->phone ?? '08123456789',
                'shipping_address' => 'Jl. Pahlawan No. 123, Bandung',
                'shipping_city_id' => '151',
                'postal_code' => '40115',
                'courier' => 'jne',
                'shipping_service' => 'REG',
                'shipping_etd' => '2-3 Hari',
                'shipping_cost' => $shippingCost,
                'total_weight_gram' => $product->weight_gram * $qty,
                'subtotal' => $subtotal,
                'grand_total' => $grandTotal,
                'paid_at' => now()->subDays(rand(1, 15)),
                'completed_at' => now()->subDays(rand(0, 10)),
                'expires_at' => now()->addDay(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'weight_gram' => $product->weight_gram,
                'subtotal' => $subtotal,
            ]);
        }
    }
}
