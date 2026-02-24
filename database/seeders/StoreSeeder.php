<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('role', 'admin')->first();
        $clinicOwner = User::query()
            ->where('role', 'owner')
            ->where('business_type', 'clinic')
            ->first();
        $gymOwner = User::query()
            ->where('role', 'owner')
            ->where('business_type', 'gym')
            ->first();

        if ($clinicOwner) {
            Store::updateOrCreate(
                ['name' => 'GlowUp Clinic'],
                [
                    'business_type' => 'clinic',
                    'owner_id' => $clinicOwner->id,
                    'is_active' => true,
                    'is_approved' => true,
                    'approved_by' => $admin?->id,
                    'approved_at' => now(),
                    'customer_portal_plan' => 'premium',
                    'latitude' => -6.2000000,
                    'longitude' => 106.8166660,
                ]
            );
        }

        if ($gymOwner) {
            Store::updateOrCreate(
                ['name' => 'GlowUp Gym'],
                [
                    'business_type' => 'gym',
                    'owner_id' => $gymOwner->id,
                    'is_active' => true,
                    'is_approved' => true,
                    'approved_by' => $admin?->id,
                    'approved_at' => now(),
                    'customer_portal_plan' => 'premium',
                    'latitude' => -6.2000000,
                    'longitude' => 106.8166660,
                ]
            );
        }
    }
}
