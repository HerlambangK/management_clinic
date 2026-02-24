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
        Schema::table('member_attendances', function (Blueprint $table) {
            $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
            $table->decimal('checkout_latitude', 10, 7)->nullable()->after('checked_out_at');
            $table->decimal('checkout_longitude', 10, 7)->nullable()->after('checkout_latitude');
            $table->unsignedInteger('checkout_accuracy')->nullable()->after('checkout_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_attendances', function (Blueprint $table) {
            $table->dropColumn([
                'checked_out_at',
                'checkout_latitude',
                'checkout_longitude',
                'checkout_accuracy',
            ]);
        });
    }
};
