<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason');
            $table->string('status')->default('Open'); // Open, Under Review, Resolved, Rejected
            $table->text('resolution')->nullable();
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('phone');
            $table->string('payment_method')->default('M-Pesa');
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->string('sender_type')->default('user'); // user, admin, bot
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('disputes');
    }
};
