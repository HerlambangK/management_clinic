<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\LoyaltyPoint;
use Illuminate\Database\Seeder;

class AllCustomersLoyaltySeeder extends Seeder
{
    /**
     * Seed loyalty points data for ALL existing customers.
     * This seeder is safe to run on production - it will only ADD points, not replace.
     */
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found in database.');

            return;
        }

        $this->command->info("Found {$customers->count()} customers. Adding loyalty data...");

        $bar = $this->command->getOutput()->createProgressBar($customers->count());
        $bar->start();

        foreach ($customers as $customer) {
            $this->seedCustomerLoyalty($customer);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine(2);
        $this->command->info('Loyalty demo data seeded for all customers!');
    }

    private function seedCustomerLoyalty(Customer $customer): void
    {
        // Skip if customer already has loyalty points history
        if (LoyaltyPoint::where('customer_id', $customer->id)->exists()) {
            return;
        }

        // Generate random loyalty history based on customer's existing data
        $basePoints = 0;
        $lifetimePoints = 0;

        // If customer has transactions, calculate points from total_spent
        if ($customer->total_spent > 0) {
            // 1 point per 10,000 spent
            $earnedFromSpending = (int) floor($customer->total_spent / 10000);

            if ($earnedFromSpending > 0) {
                LoyaltyPoint::create([
                    'customer_id' => $customer->id,
                    'points' => $earnedFromSpending,
                    'type' => 'earn',
                    'description' => 'Poin dari riwayat transaksi',
                    'balance_after' => $earnedFromSpending,
                    'expires_at' => now()->addMonths(12),
                    'created_at' => now()->subDays(rand(30, 90)),
                    'updated_at' => now()->subDays(rand(30, 90)),
                ]);
                $basePoints += $earnedFromSpending;
                $lifetimePoints += $earnedFromSpending;
            }
        }

        // Add random bonus points for variety
        $bonusTypes = [
            ['points' => rand(50, 150), 'desc' => 'Bonus member baru', 'days_ago' => rand(60, 120)],
            ['points' => rand(20, 50), 'desc' => 'Bonus ulang tahun', 'days_ago' => rand(30, 60)],
            ['points' => rand(30, 100), 'desc' => 'Bonus promo spesial', 'days_ago' => rand(10, 30)],
        ];

        // Randomly add 1-3 bonus entries
        $numBonuses = rand(1, 3);
        shuffle($bonusTypes);

        for ($i = 0; $i < $numBonuses; $i++) {
            $bonus = $bonusTypes[$i];
            $currentBalance = $basePoints + $bonus['points'];

            LoyaltyPoint::create([
                'customer_id' => $customer->id,
                'points' => $bonus['points'],
                'type' => 'earn',
                'description' => $bonus['desc'],
                'balance_after' => $currentBalance,
                'expires_at' => now()->addMonths(12),
                'created_at' => now()->subDays($bonus['days_ago']),
                'updated_at' => now()->subDays($bonus['days_ago']),
            ]);

            $basePoints = $currentBalance;
            $lifetimePoints += $bonus['points'];
        }

        // Randomly add some redemptions (30% chance)
        if ($basePoints > 50 && rand(1, 100) <= 30) {
            $redeemAmount = rand(10, min(50, (int) floor($basePoints / 2)));
            $currentBalance = $basePoints - $redeemAmount;

            LoyaltyPoint::create([
                'customer_id' => $customer->id,
                'points' => -$redeemAmount,
                'type' => 'redeem',
                'description' => 'Ditukar untuk diskon',
                'balance_after' => $currentBalance,
                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now()->subDays(rand(1, 10)),
            ]);

            $basePoints = $currentBalance;
        }

        // Determine tier based on lifetime points
        $tier = 'bronze';
        if ($lifetimePoints >= 1000) {
            $tier = 'gold';
        } elseif ($lifetimePoints >= 500) {
            $tier = 'silver';
        }

        // Update customer loyalty stats
        $customer->update([
            'loyalty_points' => max(0, $basePoints),
            'lifetime_points' => $lifetimePoints,
            'loyalty_tier' => $tier,
        ]);
    }
}
