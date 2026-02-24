<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_type')->nullable()->after('role');
        });

        $ownerTypes = DB::table('stores')
            ->select(['owner_id', 'business_type'])
            ->whereNotNull('owner_id')
            ->orderBy('id')
            ->get()
            ->groupBy('owner_id');

        foreach ($ownerTypes as $ownerId => $stores) {
            $businessType = $stores->first()?->business_type;

            if (! $businessType) {
                continue;
            }

            DB::table('users')
                ->where('id', $ownerId)
                ->whereNull('business_type')
                ->update(['business_type' => $businessType]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('business_type');
        });
    }
};
