<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::query()->get();

        if ($stores->isEmpty()) {
            return;
        }

        $baseCustomers = [
            ['name' => 'Rina Wijaya', 'gender' => 'female'],
            ['name' => 'Siti Aminah', 'gender' => 'female'],
            ['name' => 'Dewi Kartika', 'gender' => 'female'],
            ['name' => 'Anisa Putri', 'gender' => 'female'],
            ['name' => 'Maya Sari', 'gender' => 'female'],
            ['name' => 'Budi Santoso', 'gender' => 'male'],
            ['name' => 'Reza Pratama', 'gender' => 'male'],
            ['name' => 'Nadia Lestari', 'gender' => 'female'],
        ];

        foreach ($stores as $store) {
            $typeOptions = array_keys(config("business.types.{$store->business_type}.profile_fields.type.options", []));
            $concernOptions = array_keys(config("business.types.{$store->business_type}.profile_fields.concerns.options", []));

            foreach ($baseCustomers as $index => $baseCustomer) {
                $skinType = $typeOptions[$index % max(count($typeOptions), 1)] ?? null;
                $skinConcerns = [];

                if (! empty($concernOptions)) {
                    $start = $index % count($concernOptions);
                    $skinConcerns = array_slice(array_merge($concernOptions, $concernOptions), $start, 2);
                }

                $phone = sprintf('08%09d', ($store->id * 100) + $index + 1);
                $email = Str::slug($baseCustomer['name'])."+{$store->id}@jagoflutter.com";

                Customer::updateOrCreate(
                    ['email' => $email],
                    [
                        'store_id' => $store->id,
                        'name' => $baseCustomer['name'],
                        'phone' => $phone,
                        'email' => $email,
                        'birthdate' => now()->subYears(20 + ($index % 10))->subMonths($index)->toDateString(),
                        'gender' => $baseCustomer['gender'],
                        'address' => 'Jl. Contoh Alamat No. '.(10 + $index),
                        'skin_type' => $skinType,
                        'skin_concerns' => $skinConcerns,
                        'allergies' => $index % 3 === 0 ? 'AHA, Parfum' : null,
                    ]
                );
            }
        }
    }
}
