<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add business-related settings
        // These will be set during setup wizard
        Setting::set('business_type', null, 'string');
        Setting::set('business_name', null, 'string');
        Setting::set('setup_completed', false, 'boolean');
        Setting::set('setup_completed_at', null, 'string');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'business_type')->delete();
        Setting::where('key', 'business_name')->delete();
        Setting::where('key', 'setup_completed')->delete();
        Setting::where('key', 'setup_completed_at')->delete();
    }
};
