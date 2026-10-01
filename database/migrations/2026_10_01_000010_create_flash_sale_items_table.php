<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('sale_price');
            $table->unsignedInteger('quota');
            $table->unsignedInteger('sold')->default(0);

            $table->unique(['flash_sale_id', 'product_id', 'product_variant_id'], 'flash_sale_item_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};
