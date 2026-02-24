<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::query()->get();

        if ($stores->isEmpty()) {
            return;
        }

        $categoriesByType = [
            'clinic' => [
                ['name' => 'Skincare', 'description' => 'Produk perawatan kulit wajah'],
                ['name' => 'Body Care', 'description' => 'Produk perawatan tubuh'],
                ['name' => 'Makeup', 'description' => 'Produk makeup dan kosmetik'],
                ['name' => 'Suplemen', 'description' => 'Suplemen kecantikan dan kesehatan'],
            ],
            'salon' => [
                ['name' => 'Hair Care', 'description' => 'Shampoo, conditioner, dan perawatan rambut'],
                ['name' => 'Styling', 'description' => 'Produk styling dan finishing'],
                ['name' => 'Treatment', 'description' => 'Masker dan vitamin rambut'],
                ['name' => 'Color Care', 'description' => 'Produk perawatan rambut diwarnai'],
                ['name' => 'Accessories', 'description' => 'Aksesoris rambut dan tools'],
            ],
            'barbershop' => [
                ['name' => 'Pomade & Wax', 'description' => 'Produk styling rambut pria'],
                ['name' => 'Beard Care', 'description' => 'Perawatan jenggot dan kumis'],
                ['name' => 'Grooming', 'description' => 'Produk grooming harian'],
                ['name' => 'Hair Care', 'description' => 'Perawatan rambut pria'],
                ['name' => 'Accessories', 'description' => 'Sisir, cape, dan aksesoris'],
            ],
            'gym' => [
                ['name' => 'Supplements', 'description' => 'Suplemen kebugaran'],
                ['name' => 'Apparel', 'description' => 'Pakaian olahraga'],
                ['name' => 'Accessories', 'description' => 'Aksesoris gym'],
                ['name' => 'Recovery', 'description' => 'Peralatan recovery & wellness'],
            ],
        ];

        foreach ($stores as $store) {
            $categories = $categoriesByType[$store->business_type] ?? $categoriesByType['clinic'];

            foreach ($categories as $index => $category) {
                ProductCategory::firstOrCreate(
                    ['store_id' => $store->id, 'name' => $category['name']],
                    array_merge($category, [
                        'store_id' => $store->id,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ])
                );
            }
        }
    }
}
