<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 30)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('voucher_code', 30)->nullable();
            $table->string('status', 20)->default('pending_payment');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 20);
            $table->text('shipping_address');
            $table->string('shipping_city_id', 20);
            $table->string('postal_code', 10);
            $table->string('courier', 30);
            $table->string('shipping_service', 50);
            $table->string('shipping_etd', 30)->nullable();
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedInteger('total_weight_gram');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->string('tracking_no', 50)->nullable();
            $table->text('note')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->string('cancel_reason', 255)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'expires_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
