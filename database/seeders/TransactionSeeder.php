<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $cashiers = User::whereIn('role', ['owner', 'admin'])->get();
        $stores = \App\Models\Store::query()->get();

        if ($cashiers->isEmpty() || $stores->isEmpty()) {
            return;
        }

        $paymentMethods = ['cash', 'cash', 'cash', 'debit_card', 'transfer', 'qris'];

        foreach ($stores as $store) {
            $customers = Customer::query()->where('store_id', $store->id)->get();
            $services = Service::query()->where('store_id', $store->id)->get();
            $packages = Package::query()->where('store_id', $store->id)->get();
            $completedAppointments = Appointment::query()
                ->where('store_id', $store->id)
                ->where('status', 'completed')
                ->get();

            if ($customers->isEmpty() || $services->isEmpty()) {
                continue;
            }

            // Create transactions for completed appointments (service sales)
            foreach ($completedAppointments as $appointment) {
                $cashier = $cashiers->random();
                $service = $appointment->service;
                $customer = $appointment->customer;

                if (! $service || ! $customer) {
                    continue;
                }

                $discount = rand(0, 5) === 0 ? rand(1, 5) * 10000 : 0;
                $subtotal = $service->price;
                $total = $subtotal - $discount;

                $transaction = Transaction::create([
                    'store_id' => $store->id,
                    'customer_id' => $customer->id,
                    'appointment_id' => $appointment->id,
                    'cashier_id' => $cashier->id,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'discount_type' => $discount > 0 ? 'Promo Bulan Ini' : null,
                    'tax_amount' => 0,
                    'total_amount' => $total,
                    'paid_amount' => $total,
                    'status' => 'paid',
                    'paid_at' => $appointment->appointment_date->copy()->setTime(rand(10, 18), rand(0, 59)),
                    'created_at' => $appointment->appointment_date->copy()->setTime(rand(9, 17), rand(0, 59)),
                    'updated_at' => $appointment->appointment_date->copy()->setTime(rand(10, 18), rand(0, 59)),
                ]);

                $transaction->items()->create([
                    'store_id' => $store->id,
                    'item_type' => 'service',
                    'service_id' => $service->id,
                    'item_name' => $service->name,
                    'quantity' => 1,
                    'unit_price' => $service->price,
                    'discount' => 0,
                    'total_price' => $service->price,
                ]);

                $transaction->payments()->create([
                    'store_id' => $store->id,
                    'amount' => $total,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'received_by' => $cashier->id,
                    'paid_at' => $transaction->paid_at,
                ]);

                $customer->increment('total_visits');
                $customer->increment('total_spent', $total);
                $customer->update(['last_visit' => $appointment->appointment_date]);
            }

            // Create package sales (CustomerPackage)
            if ($packages->isNotEmpty()) {
                foreach ($customers->shuffle()->take(4) as $customer) {
                    $package = $packages->random();
                    $cashier = $cashiers->random();
                    $purchaseDate = Carbon::today()->subDays(rand(5, 60));

                    CustomerPackage::create([
                        'store_id' => $store->id,
                        'customer_id' => $customer->id,
                        'package_id' => $package->id,
                        'sold_by' => $cashier->id,
                        'sessions_total' => $package->total_sessions,
                        'sessions_used' => rand(0, min(3, $package->total_sessions)),
                        'price_paid' => $package->package_price,
                        'purchased_at' => $purchaseDate,
                        'expires_at' => $purchaseDate->copy()->addDays($package->validity_days),
                        'status' => 'active',
                    ]);

                    $transaction = Transaction::create([
                        'store_id' => $store->id,
                        'customer_id' => $customer->id,
                        'cashier_id' => $cashier->id,
                        'subtotal' => $package->package_price,
                        'discount_amount' => 0,
                        'tax_amount' => 0,
                        'total_amount' => $package->package_price,
                        'paid_amount' => $package->package_price,
                        'status' => 'paid',
                        'paid_at' => $purchaseDate->copy()->setTime(rand(10, 17), rand(0, 59)),
                        'created_at' => $purchaseDate->copy()->setTime(rand(9, 16), rand(0, 59)),
                        'updated_at' => $purchaseDate->copy()->setTime(rand(10, 17), rand(0, 59)),
                    ]);

                    $transaction->items()->create([
                        'store_id' => $store->id,
                        'item_type' => 'package',
                        'package_id' => $package->id,
                        'item_name' => $package->name,
                        'quantity' => 1,
                        'unit_price' => $package->package_price,
                        'discount' => 0,
                        'total_price' => $package->package_price,
                    ]);

                    $transaction->payments()->create([
                        'store_id' => $store->id,
                        'amount' => $package->package_price,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'received_by' => $cashier->id,
                        'paid_at' => $transaction->paid_at,
                    ]);

                    $customer->increment('total_spent', $package->package_price);
                }
            }

            // Create completed PAID transactions for TODAY (for dashboard testing)
            for ($i = 0; $i < 3; $i++) {
                $customer = $customers->random();
                $service = $services->random();
                $cashier = $cashiers->random();

                $discount = rand(0, 3) === 0 ? rand(1, 3) * 10000 : 0;
                $subtotal = $service->price;
                $total = $subtotal - $discount;
                $transactionTime = now()->setTime(rand(9, 13), rand(0, 59));

                $transaction = Transaction::create([
                    'store_id' => $store->id,
                    'customer_id' => $customer->id,
                    'cashier_id' => $cashier->id,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'discount_type' => $discount > 0 ? 'Promo Hari Ini' : null,
                    'tax_amount' => 0,
                    'total_amount' => $total,
                    'paid_amount' => $total,
                    'status' => 'paid',
                    'paid_at' => $transactionTime,
                    'created_at' => $transactionTime->copy()->subMinutes(rand(5, 30)),
                    'updated_at' => $transactionTime,
                ]);

                $transaction->items()->create([
                    'store_id' => $store->id,
                    'item_type' => 'service',
                    'service_id' => $service->id,
                    'item_name' => $service->name,
                    'quantity' => 1,
                    'unit_price' => $service->price,
                    'discount' => 0,
                    'total_price' => $service->price,
                ]);

                $transaction->payments()->create([
                    'store_id' => $store->id,
                    'amount' => $total,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'received_by' => $cashier->id,
                    'paid_at' => $transactionTime,
                ]);

                $customer->increment('total_visits');
                $customer->increment('total_spent', $total);
                $customer->update(['last_visit' => now()]);
            }

            // Create some pending transactions (today)
            for ($i = 0; $i < 2; $i++) {
                $customer = $customers->random();
                $service = $services->random();
                $cashier = $cashiers->random();

                $transaction = Transaction::create([
                    'store_id' => $store->id,
                    'customer_id' => $customer->id,
                    'cashier_id' => $cashier->id,
                    'subtotal' => $service->price,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'total_amount' => $service->price,
                    'paid_amount' => 0,
                    'status' => 'pending',
                    'created_at' => now()->setTime(rand(9, 12), rand(0, 59)),
                ]);

                $transaction->items()->create([
                    'store_id' => $store->id,
                    'item_type' => 'service',
                    'service_id' => $service->id,
                    'item_name' => $service->name,
                    'quantity' => 1,
                    'unit_price' => $service->price,
                    'discount' => 0,
                    'total_price' => $service->price,
                ]);
            }

            // Create one partial payment transaction
            $customer = $customers->random();
            $service = $services->where('price', '>=', 400000)->first() ?? $services->first();
            $cashier = $cashiers->random();

            $transaction = Transaction::create([
                'store_id' => $store->id,
                'customer_id' => $customer->id,
                'cashier_id' => $cashier->id,
                'subtotal' => $service->price,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $service->price,
                'paid_amount' => 200000,
                'status' => 'partial',
                'created_at' => now()->subDay()->setTime(15, 30),
            ]);

            $transaction->items()->create([
                'store_id' => $store->id,
                'item_type' => 'service',
                'service_id' => $service->id,
                'item_name' => $service->name,
                'quantity' => 1,
                'unit_price' => $service->price,
                'discount' => 0,
                'total_price' => $service->price,
            ]);

            $transaction->payments()->create([
                'store_id' => $store->id,
                'amount' => 200000,
                'payment_method' => 'cash',
                'received_by' => $cashier->id,
                'paid_at' => now()->subDay()->setTime(15, 35),
                'notes' => 'DP pembayaran',
            ]);
        }
    }
}
