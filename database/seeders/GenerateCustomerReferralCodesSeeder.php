<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class GenerateCustomerReferralCodesSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::whereNull('referral_code')->get();

        $count = 0;
        foreach ($customers as $customer) {
            $customer->update([
                'referral_code' => Customer::generateReferralCode(),
            ]);
            $count++;
        }

        $this->command->info("Generated referral codes for {$count} customers.");
    }
}
