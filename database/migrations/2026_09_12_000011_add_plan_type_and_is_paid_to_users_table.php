<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'plan_type')) {
                $table->string('plan_type')->nullable()->after('shop_name');
            }
            if (!Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('is_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'plan_type')) {
                $table->dropColumn('plan_type');
            }
            if (Schema::hasColumn('users', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
        });
    }
};
