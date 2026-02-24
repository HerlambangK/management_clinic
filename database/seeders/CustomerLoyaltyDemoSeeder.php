<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\LoyaltyPoint;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TreatmentRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerLoyaltyDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create the customer
        $customer = Customer::where('email', 'saiful.bahri.tl@gmail.com')->first();

        if (! $customer) {
            $customer = Customer::create([
                'name' => 'Saiful Bahri',
                'email' => 'saiful.bahri.tl@gmail.com',
                'phone' => '+6281234567890',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'birthdate' => '1990-05-15',
                'loyalty_points' => 0,
                'lifetime_points' => 0,
                'loyalty_tier' => 'bronze',
                'total_visits' => 0,
                'total_spent' => 0,
            ]);
        }

        // Get services and staff
        $services = Service::where('is_active', true)->take(5)->get();
        $staff = User::where('role', 'beautician')->first() ?? User::first();

        if ($services->isEmpty()) {
            $this->command->warn('No active services found. Please seed services first.');

            return;
        }

        // Create past appointments and transactions
        $transactionDates = [
            now()->subDays(45),
            now()->subDays(30),
            now()->subDays(14),
            now()->subDays(7),
            now()->subDays(2),
        ];

        $totalSpent = 0;
        $totalVisits = 0;

        foreach ($transactionDates as $index => $date) {
            $service = $services[$index % $services->count()];

            // Create appointment
            $appointment = Appointment::create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'staff_id' => $staff->id,
                'appointment_date' => $date->toDateString(),
                'start_time' => '10:00',
                'end_time' => $date->copy()->addMinutes($service->duration_minutes ?? 60)->format('H:i'),
                'status' => 'completed',
                'source' => 'online',
                'notes' => null,
            ]);

            // Create treatment record
            TreatmentRecord::create([
                'appointment_id' => $appointment->id,
                'customer_id' => $customer->id,
                'staff_id' => $staff->id,
                'notes' => 'Perawatan berjalan dengan baik. Pelanggan puas dengan hasil.',
                'recommendations' => 'Disarankan untuk melakukan perawatan rutin setiap 2 minggu.',
                'follow_up_date' => $date->copy()->addDays(14)->toDateString(),
            ]);

            // Create transaction
            $subtotal = $service->price;
            $discount = $index === 2 ? 50000 : 0; // Give discount on 3rd visit
            $total = $subtotal - $discount;

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'appointment_id' => $appointment->id,
                'cashier_id' => $staff->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'discount_type' => $discount > 0 ? 'fixed' : null,
                'tax_amount' => 0,
                'total_amount' => $total,
                'status' => 'paid',
                'notes' => null,
            ]);

            // Create transaction item
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'item_type' => 'service',
                'service_id' => $service->id,
                'item_name' => $service->name,
                'quantity' => 1,
                'unit_price' => $service->price,
                'discount' => $discount,
                'total_price' => $total,
            ]);

            // Add loyalty points (1 point per 10,000)
            $pointsEarned = (int) floor($total / 10000);
            if ($pointsEarned > 0) {
                LoyaltyPoint::create([
                    'customer_id' => $customer->id,
                    'transaction_id' => $transaction->id,
                    'points' => $pointsEarned,
                    'type' => 'earn',
                    'description' => 'Poin dari transaksi #'.$transaction->invoice_number,
                    'expires_at' => $date->copy()->addMonths(12),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            $totalSpent += $total;
            $totalVisits++;
        }

        // Add bonus points for referral (simulated)
        LoyaltyPoint::create([
            'customer_id' => $customer->id,
            'points' => 100,
            'type' => 'earn',
            'description' => 'Bonus referral - Anda mengajak Dewi Lestari',
            'expires_at' => now()->addMonths(12),
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays(20),
        ]);

        // Add bonus points for birthday
        LoyaltyPoint::create([
            'customer_id' => $customer->id,
            'points' => 50,
            'type' => 'earn',
            'description' => 'Bonus ulang tahun - Selamat ulang tahun!',
            'expires_at' => now()->addMonths(12),
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        // Add a point redemption
        LoyaltyPoint::create([
            'customer_id' => $customer->id,
            'points' => -50,
            'type' => 'redeem',
            'description' => 'Ditukar untuk diskon transaksi',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        // Calculate total points
        $totalPoints = LoyaltyPoint::where('customer_id', $customer->id)->sum('points');
        $lifetimePoints = LoyaltyPoint::where('customer_id', $customer->id)
            ->where('points', '>', 0)
            ->sum('points');

        // Determine tier based on lifetime points
        $tier = 'bronze';
        if ($lifetimePoints >= 1000) {
            $tier = 'gold';
        } elseif ($lifetimePoints >= 500) {
            $tier = 'silver';
        }

        // Update customer stats
        $customer->update([
            'loyalty_points' => max(0, $totalPoints),
            'lifetime_points' => $lifetimePoints,
            'loyalty_tier' => $tier,
            'total_visits' => $totalVisits,
            'total_spent' => $totalSpent,
            'last_visit' => now()->subDays(2),
        ]);

        // Create upcoming appointment
        Appointment::create([
            'customer_id' => $customer->id,
            'service_id' => $services->first()->id,
            'staff_id' => $staff->id,
            'appointment_date' => now()->addDays(3)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'confirmed',
            'source' => 'online',
            'notes' => 'Perawatan rutin bulanan',
        ]);

        $this->command->info("Customer loyalty demo data created for: {$customer->email}");
        $this->command->info("Total Points: {$customer->loyalty_points}");
        $this->command->info("Lifetime Points: {$customer->lifetime_points}");
        $this->command->info("Tier: {$customer->loyalty_tier}");
        $this->command->info("Total Visits: {$totalVisits}");
        $this->command->info('Total Spent: Rp '.number_format($totalSpent, 0, ',', '.'));
    }
}
