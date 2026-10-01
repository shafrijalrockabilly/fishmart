<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'product_variant_id',
        'sale_price',
        'quota',
        'sold',
    ];

    protected function casts(): array
    {
        return [
            'sale_price' => 'integer',
            'quota' => 'integer',
            'sold' => 'integer',
        ];
    }

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
