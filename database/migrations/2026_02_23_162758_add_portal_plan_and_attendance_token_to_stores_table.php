<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('customer_portal_plan')->default('premium')->after('feature_overrides');
            $table->string('attendance_token', 64)->nullable()->unique()->after('customer_portal_plan');
        });

        $stores = DB::table('stores')
            ->select(['id', 'attendance_token'])
            ->get();

        foreach ($stores as $store) {
            if ($store->attendance_token) {
                continue;
            }

            DB::table('stores')
                ->where('id', $store->id)
                ->update(['attendance_token' => Str::random(40)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropUnique(['attendance_token']);
            $table->dropColumn(['attendance_token', 'customer_portal_plan']);
        });
    }
};
