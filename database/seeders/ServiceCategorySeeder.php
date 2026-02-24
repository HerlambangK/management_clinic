<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::query()->get();

        if ($stores->isEmpty()) {
            return;
        }

        foreach ($stores as $store) {
            $categories = config("business.types.{$store->business_type}.sample_categories", []);

            foreach ($categories as $index => $category) {
                ServiceCategory::updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'name' => $category['name'],
                    ],
                    [
                        'store_id' => $store->id,
                        'name' => $category['name'],
                        'icon' => $category['icon'] ?? null,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
