<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    public function run(): void
    {
        $flashSale = FlashSale::create([
            'name' => 'Flash Sale Weekend Festival',
            'starts_at' => now()->subHours(2),
            'ends_at' => now()->addDays(2),
            'is_active' => true,
        ]);

        $products = Product::inRandomOrder()->take(8)->get();

        foreach ($products as $product) {
            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'product_id' => $product->id,
                'sale_price' => (int) ($product->price * 0.75),
                'quota' => 50,
                'sold' => fake()->numberBetween(5, 30),
            ]);
        }
    }
}
