<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('gateway', 20)->default('midtrans');
            $table->string('method', 50)->nullable();
            $table->string('gateway_order_id', 60)->unique();
            $table->string('gateway_transaction_id', 100)->nullable();
            $table->string('snap_token', 255)->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedBigInteger('amount');
            $table->json('payload')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
