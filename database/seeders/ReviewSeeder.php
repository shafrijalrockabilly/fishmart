<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $orderItems = OrderItem::with('order.user')->take(10)->get();

        foreach ($orderItems as $item) {
            Review::create([
                'user_id' => $item->order->user_id,
                'product_id' => $item->product_id,
                'order_id' => $item->order_id,
                'rating' => fake()->numberBetween(4, 5),
                'comment' => 'Produk sangat berkualitas, pengiriman cepat dan packing aman. Mantap FishMart!',
                'is_visible' => true,
            ]);
        }
    }
}
