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
            $table->string('referral_code', 20)->nullable()->unique()->after('loyalty_tier');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')
                ->constrained('customers')->nullOnDelete();
            $table->timestamp('referral_rewarded_at')->nullable()->after('referred_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['referral_code', 'referred_by_id', 'referral_rewarded_at']);
        });
    }
};
