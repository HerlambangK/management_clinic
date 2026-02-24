<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Service;
use App\Models\Store;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::query()->get();

        if ($stores->isEmpty()) {
            return;
        }

        $packagesByType = [
            'gym' => [
                [
                    'name' => 'Membership Bulanan',
                    'service' => 'Monthly Membership',
                    'description' => 'Paket membership 30 hari untuk akses gym.',
                    'total_sessions' => 30,
                    'original_price' => 300000,
                    'package_price' => 275000,
                    'validity_days' => 30,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Membership Triwulan',
                    'service' => 'Quarterly Membership',
                    'description' => 'Paket membership 90 hari dengan harga hemat.',
                    'total_sessions' => 90,
                    'original_price' => 900000,
                    'package_price' => 800000,
                    'validity_days' => 90,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Membership Tahunan',
                    'service' => 'Quarterly Membership',
                    'description' => 'Paket membership 12 bulan untuk member aktif.',
                    'total_sessions' => 365,
                    'original_price' => 3600000,
                    'package_price' => 3200000,
                    'validity_days' => 365,
                    'sort_order' => 3,
                ],
            ],
        ];

        foreach ($stores as $store) {
            $features = config("business.types.{$store->business_type}.features", []);

            if (! ($features['packages'] ?? false)) {
                continue;
            }

            $services = Service::query()
                ->where('store_id', $store->id)
                ->get()
                ->keyBy('name');

            $packages = $packagesByType[$store->business_type] ?? [];

            foreach ($packages as $package) {
                $serviceId = $services->get($package['service'])?->id;

                Package::updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'name' => $package['name'],
                    ],
                    [
                        'store_id' => $store->id,
                        'name' => $package['name'],
                        'description' => $package['description'] ?? null,
                        'service_id' => $serviceId,
                        'total_sessions' => $package['total_sessions'],
                        'original_price' => $package['original_price'],
                        'package_price' => $package['package_price'],
                        'validity_days' => $package['validity_days'],
                        'is_active' => true,
                        'sort_order' => $package['sort_order'] ?? 0,
                    ]
                );
            }
        }
    }
}
