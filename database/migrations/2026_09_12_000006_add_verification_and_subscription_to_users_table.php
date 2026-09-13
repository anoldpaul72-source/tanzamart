<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('role');
            $table->boolean('subscription_active')->default(false)->after('is_verified');
            $table->timestamp('subscription_end_date')->nullable()->after('subscription_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'subscription_active', 'subscription_end_date']);
        });
    }
};
