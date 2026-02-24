<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::query()->get();

        if ($stores->isEmpty()) {
            return;
        }

        foreach ($stores as $store) {
            $servicesByCategory = config("business.types.{$store->business_type}.sample_services", []);

            if (! $servicesByCategory) {
                continue;
            }

            $categories = ServiceCategory::query()
                ->where('store_id', $store->id)
                ->get()
                ->keyBy('name');

            foreach ($servicesByCategory as $categoryName => $services) {
                $categoryId = $categories->get($categoryName)?->id;

                foreach ($services as $service) {
                    Service::updateOrCreate(
                        [
                            'store_id' => $store->id,
                            'name' => $service['name'],
                        ],
                        [
                            'store_id' => $store->id,
                            'category_id' => $categoryId,
                            'name' => $service['name'],
                            'description' => $service['description'] ?? "Layanan {$service['name']}",
                            'duration_minutes' => $service['duration'] ?? 60,
                            'price' => $service['price'] ?? 0,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
