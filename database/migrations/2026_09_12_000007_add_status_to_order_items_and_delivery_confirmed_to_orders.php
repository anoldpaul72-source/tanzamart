<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('status')->default('Pending')->after('subtotal');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('delivery_confirmed')->default(false)->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_confirmed');
        });
    }
};
