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
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('total', 12, 2);
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->default('M-Pesa');
            $table->string('payment_status')->default('Pending');
            $table->string('status')->default('Pending'); // Pending, Processing, Shipped, Delivered, Cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
