<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('loyalty_points')->default(0)->after('total_spent');
            $table->integer('lifetime_points')->default(0)->after('loyalty_points');
            $table->string('loyalty_tier')->nullable()->after('lifetime_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points', 'lifetime_points', 'loyalty_tier']);
        });
    }
};
