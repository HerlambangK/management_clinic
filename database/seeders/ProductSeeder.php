<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::query()->get();

        if ($stores->isEmpty()) {
            return;
        }

        $productsByType = [
            'clinic' => [
                [
                    'category' => 'Skincare',
                    'name' => 'Brightening Serum',
                    'sku' => 'CLC-001',
                    'description' => 'Serum pencerah dengan Vitamin C dan Niacinamide',
                    'price' => 185000,
                    'cost_price' => 85000,
                    'stock' => 50,
                    'min_stock' => 10,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Skincare',
                    'name' => 'Acne Spot Treatment',
                    'sku' => 'CLC-002',
                    'description' => 'Gel untuk mengatasi jerawat dengan Salicylic Acid',
                    'price' => 95000,
                    'cost_price' => 40000,
                    'stock' => 30,
                    'min_stock' => 5,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Body Care',
                    'name' => 'Body Lotion Whitening',
                    'sku' => 'CLC-101',
                    'description' => 'Lotion pencerah kulit tubuh',
                    'price' => 125000,
                    'cost_price' => 55000,
                    'stock' => 35,
                    'min_stock' => 10,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Makeup',
                    'name' => 'BB Cream Natural',
                    'sku' => 'CLC-201',
                    'description' => 'BB Cream dengan coverage natural',
                    'price' => 175000,
                    'cost_price' => 75000,
                    'stock' => 30,
                    'min_stock' => 8,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Suplemen',
                    'name' => 'Collagen Drink',
                    'sku' => 'CLC-301',
                    'description' => 'Minuman kolagen untuk kulit kenyal (1 box isi 14)',
                    'price' => 350000,
                    'cost_price' => 180000,
                    'stock' => 20,
                    'min_stock' => 5,
                    'unit' => 'box',
                    'track_stock' => true,
                ],
            ],
            'salon' => [
                [
                    'category' => 'Hair Care',
                    'name' => 'Shampoo Repair',
                    'sku' => 'SAL-001',
                    'description' => 'Shampoo untuk rambut rusak',
                    'price' => 85000,
                    'cost_price' => 35000,
                    'stock' => 40,
                    'min_stock' => 8,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Hair Care',
                    'name' => 'Conditioner Smooth',
                    'sku' => 'SAL-002',
                    'description' => 'Conditioner untuk rambut halus dan lembut',
                    'price' => 90000,
                    'cost_price' => 38000,
                    'stock' => 35,
                    'min_stock' => 8,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Styling',
                    'name' => 'Hair Serum Shine',
                    'sku' => 'SAL-101',
                    'description' => 'Serum untuk kilau rambut',
                    'price' => 110000,
                    'cost_price' => 45000,
                    'stock' => 25,
                    'min_stock' => 5,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Treatment',
                    'name' => 'Hair Mask Protein',
                    'sku' => 'SAL-201',
                    'description' => 'Masker rambut dengan protein',
                    'price' => 120000,
                    'cost_price' => 52000,
                    'stock' => 20,
                    'min_stock' => 5,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
            ],
            'barbershop' => [
                [
                    'category' => 'Pomade & Wax',
                    'name' => 'Pomade Strong Hold',
                    'sku' => 'BRB-001',
                    'description' => 'Pomade dengan daya tahan kuat',
                    'price' => 90000,
                    'cost_price' => 38000,
                    'stock' => 40,
                    'min_stock' => 10,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Beard Care',
                    'name' => 'Beard Oil',
                    'sku' => 'BRB-101',
                    'description' => 'Minyak jenggot untuk perawatan harian',
                    'price' => 75000,
                    'cost_price' => 30000,
                    'stock' => 30,
                    'min_stock' => 8,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Grooming',
                    'name' => 'Face Wash Men',
                    'sku' => 'BRB-201',
                    'description' => 'Pembersih wajah khusus pria',
                    'price' => 65000,
                    'cost_price' => 28000,
                    'stock' => 30,
                    'min_stock' => 8,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
            ],
            'gym' => [
                [
                    'category' => 'Supplements',
                    'name' => 'Whey Protein 1kg',
                    'sku' => 'GYM-001',
                    'description' => 'Protein whey untuk pemulihan otot',
                    'price' => 450000,
                    'cost_price' => 300000,
                    'stock' => 25,
                    'min_stock' => 5,
                    'unit' => 'jar',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Supplements',
                    'name' => 'BCAA 300g',
                    'sku' => 'GYM-002',
                    'description' => 'BCAA untuk daya tahan latihan',
                    'price' => 250000,
                    'cost_price' => 160000,
                    'stock' => 30,
                    'min_stock' => 6,
                    'unit' => 'jar',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Apparel',
                    'name' => 'Training T-Shirt',
                    'sku' => 'GYM-101',
                    'description' => 'Kaos latihan breathable',
                    'price' => 120000,
                    'cost_price' => 70000,
                    'stock' => 40,
                    'min_stock' => 10,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Accessories',
                    'name' => 'Gym Gloves',
                    'sku' => 'GYM-201',
                    'description' => 'Sarung tangan untuk angkat beban',
                    'price' => 85000,
                    'cost_price' => 45000,
                    'stock' => 35,
                    'min_stock' => 8,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
                [
                    'category' => 'Recovery',
                    'name' => 'Foam Roller',
                    'sku' => 'GYM-301',
                    'description' => 'Foam roller untuk recovery',
                    'price' => 150000,
                    'cost_price' => 90000,
                    'stock' => 20,
                    'min_stock' => 5,
                    'unit' => 'pcs',
                    'track_stock' => true,
                ],
            ],
        ];

        foreach ($stores as $store) {
            $products = $productsByType[$store->business_type] ?? $productsByType['clinic'];
            $categories = ProductCategory::query()
                ->where('store_id', $store->id)
                ->get()
                ->keyBy('name');

            foreach ($products as $product) {
                $categoryId = $categories->get($product['category'])?->id;

                if (! $categoryId) {
                    continue;
                }

                $baseSku = $product['sku'];
                $sku = "{$baseSku}-S{$store->id}";

                Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'store_id' => $store->id,
                        'category_id' => $categoryId,
                        'name' => $product['name'],
                        'sku' => $sku,
                        'description' => $product['description'] ?? null,
                        'price' => $product['price'] ?? 0,
                        'cost_price' => $product['cost_price'] ?? null,
                        'stock' => $product['stock'] ?? 0,
                        'min_stock' => $product['min_stock'] ?? 0,
                        'unit' => $product['unit'] ?? 'pcs',
                        'is_active' => true,
                        'track_stock' => $product['track_stock'] ?? true,
                    ]
                );
            }
        }
    }
}
