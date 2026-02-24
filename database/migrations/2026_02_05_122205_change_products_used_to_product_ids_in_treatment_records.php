<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Note: This changes products_used from storing product names (text array)
     * to storing product IDs (integer array). Old data will be cleared.
     */
    public function up(): void
    {
        // Clear existing data since we're changing from text to IDs
        // The structure stays the same (JSON array), just the content type changes
        \DB::table('treatment_records')->update(['products_used' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migration back is not possible since IDs don't map back to original text
    }
};
