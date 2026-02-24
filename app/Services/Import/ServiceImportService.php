<?php

namespace App\Services\Import;

use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceImportService extends BaseImportService
{
    public function getRequiredColumns(): array
    {
        return ['name', 'category', 'price', 'duration_minutes'];
    }

    public function getAvailableColumns(): array
    {
        return [
            'name' => 'Nama layanan (wajib)',
            'category' => 'Nama kategori (wajib, akan dibuat otomatis jika tidak ada)',
            'description' => 'Deskripsi layanan',
            'price' => 'Harga (wajib, angka tanpa format)',
            'duration_minutes' => 'Durasi dalam menit (wajib)',
            'is_active' => 'Status aktif (1/0, yes/no, aktif/tidak)',
        ];
    }

    public function getSampleData(): array
    {
        return [
            [
                'name' => 'Facial Basic',
                'category' => 'Facial',
                'description' => 'Perawatan wajah dasar dengan pembersihan dan masker',
                'price' => '150000',
                'duration_minutes' => '60',
                'is_active' => '1',
            ],
            [
                'name' => 'Chemical Peeling',
                'category' => 'Facial',
                'description' => 'Peeling wajah dengan bahan kimia untuk mengangkat sel kulit mati',
                'price' => '350000',
                'duration_minutes' => '45',
                'is_active' => '1',
            ],
            [
                'name' => 'Laser Hair Removal',
                'category' => 'Laser',
                'description' => 'Penghilangan bulu dengan teknologi laser',
                'price' => '500000',
                'duration_minutes' => '30',
                'is_active' => '1',
            ],
        ];
    }

    protected function processRow(array $row, int $rowNumber): array
    {
        $name = $this->cleanValue($row['name'] ?? null);
        $categoryName = $this->cleanValue($row['category'] ?? null);
        $priceRaw = $row['price'] ?? null;
        $durationRaw = $row['duration_minutes'] ?? null;

        // Validate required fields
        if (empty($name)) {
            return ['success' => false, 'message' => 'Nama layanan wajib diisi.'];
        }

        if (empty($categoryName)) {
            return ['success' => false, 'message' => 'Kategori wajib diisi.'];
        }

        $price = $this->parseNumber($priceRaw);
        if ($price === null || $price < 0) {
            return ['success' => false, 'message' => "Harga tidak valid: {$priceRaw}. Masukkan angka positif."];
        }

        $duration = $this->parseNumber($durationRaw);
        if ($duration === null || $duration <= 0) {
            return ['success' => false, 'message' => "Durasi tidak valid: {$durationRaw}. Masukkan angka positif."];
        }

        // Find or create category
        $category = ServiceCategory::where('name', $categoryName)->first();

        if (! $category) {
            $maxSortOrder = ServiceCategory::max('sort_order') ?? 0;
            $category = ServiceCategory::create([
                'name' => $categoryName,
                'description' => null,
                'icon' => null,
                'sort_order' => $maxSortOrder + 1,
                'is_active' => true,
            ]);
        }

        // Check for existing service with same name in same category
        $existingService = Service::withTrashed()
            ->where('name', $name)
            ->where('category_id', $category->id)
            ->first();

        if ($existingService) {
            if ($existingService->trashed()) {
                $existingService->restore();
            }

            // Update existing service
            $existingService->update([
                'description' => $this->cleanValue($row['description'] ?? null) ?? $existingService->description,
                'price' => $price,
                'duration_minutes' => (int) $duration,
                'is_active' => isset($row['is_active']) ? $this->parseBoolean($row['is_active']) : $existingService->is_active,
            ]);

            return [
                'success' => true,
                'skipped' => true,
                'message' => "Layanan '{$name}' sudah ada dalam kategori '{$categoryName}', data diperbarui.",
                'data' => $existingService,
            ];
        }

        // Create new service
        $service = Service::create([
            'category_id' => $category->id,
            'name' => $name,
            'description' => $this->cleanValue($row['description'] ?? null),
            'price' => $price,
            'duration_minutes' => (int) $duration,
            'is_active' => isset($row['is_active']) ? $this->parseBoolean($row['is_active']) : true,
        ]);

        return [
            'success' => true,
            'message' => 'Layanan berhasil ditambahkan.',
            'data' => $service,
        ];
    }
}
