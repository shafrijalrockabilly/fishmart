<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpec;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(20)->create()->each(function ($product) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => 'products/sample-fish.jpg',
                'alt' => $product->name,
                'is_primary' => true,
                'sort_order' => 1,
            ]);

            ProductSpec::create([
                'product_id' => $product->id,
                'label' => 'Material',
                'value' => 'Carbon High Modulus',
                'sort_order' => 1,
            ]);
            ProductSpec::create([
                'product_id' => $product->id,
                'label' => 'Berat',
                'value' => $product->weight_gram.' gram',
                'sort_order' => 2,
            ]);
        });
    }
}
