<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Shimano Stradic FM 4000 XG', 'Daiwa Certate LT 3000', 'Penn Battle III 5000',
            'Abu Garcia Revo Beast', 'Maxel Rage 60', 'Owner Hook ST-66 #2/0',
            'Joran Pancing Jigging Master', 'Reel Spinning Power Handle', 'Umpan Minnow Floating 12cm',
            'Senar PE X8 300m', 'Tas Pancing Hard Case 150cm', 'Sepatu Wading Sol Karet',
        ]).' '.fake()->unique()->numberBetween(100, 99999);

        $price = fake()->numberBetween(50000, 3500000);
        $salePrice = fake()->boolean(30) ? (int) ($price * 0.8) : null;

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'brand_id' => Brand::inRandomOrder()->first()?->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => 'SKU-'.strtoupper(Str::random(8)),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(3),
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => fake()->numberBetween(10, 100),
            'weight_gram' => fake()->numberBetween(200, 3000),
            'has_variants' => false,
            'sold_count' => fake()->numberBetween(0, 150),
            'rating_avg' => fake()->randomFloat(2, 4.0, 5.0),
            'rating_count' => fake()->numberBetween(1, 50),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
        ];
    }
}
