<?php

namespace App\Services\Import;

use App\Models\Package;
use App\Models\Service;

class PackageImportService extends BaseImportService
{
    public function getRequiredColumns(): array
    {
        return ['name', 'service', 'total_sessions', 'package_price'];
    }

    public function getAvailableColumns(): array
    {
        return [
            'name' => 'Nama paket (wajib)',
            'service' => 'Nama layanan (wajib, harus sudah terdaftar)',
            'description' => 'Deskripsi paket',
            'total_sessions' => 'Total sesi (wajib)',
            'original_price' => 'Harga normal (opsional, default: harga layanan x total sesi)',
            'package_price' => 'Harga paket (wajib)',
            'validity_days' => 'Masa berlaku dalam hari (default: 365)',
            'is_active' => 'Status aktif (1/0, yes/no)',
        ];
    }

    public function getSampleData(): array
    {
        return [
            [
                'name' => 'Paket Facial Basic 5 Sesi',
                'service' => 'Facial Basic',
                'description' => 'Hemat 15% untuk 5 kali treatment facial basic',
                'total_sessions' => '5',
                'original_price' => '750000',
                'package_price' => '637500',
                'validity_days' => '180',
                'is_active' => '1',
            ],
            [
                'name' => 'Paket Chemical Peeling 3 Sesi',
                'service' => 'Chemical Peeling',
                'description' => 'Paket perawatan peeling untuk hasil maksimal',
                'total_sessions' => '3',
                'original_price' => '1050000',
                'package_price' => '900000',
                'validity_days' => '90',
                'is_active' => '1',
            ],
        ];
    }

    protected function processRow(array $row, int $rowNumber): array
    {
        $name = $this->cleanValue($row['name'] ?? null);
        $serviceName = $this->cleanValue($row['service'] ?? null);
        $totalSessionsRaw = $row['total_sessions'] ?? null;
        $packagePriceRaw = $row['package_price'] ?? null;

        // Validate required fields
        if (empty($name)) {
            return ['success' => false, 'message' => 'Nama paket wajib diisi.'];
        }

        if (empty($serviceName)) {
            return ['success' => false, 'message' => 'Nama layanan wajib diisi.'];
        }

        // Find service
        $service = Service::where('name', $serviceName)->first();

        if (! $service) {
            return ['success' => false, 'message' => "Layanan '{$serviceName}' tidak ditemukan. Pastikan layanan sudah terdaftar."];
        }

        $totalSessions = $this->parseNumber($totalSessionsRaw);
        if ($totalSessions === null || $totalSessions <= 0) {
            return ['success' => false, 'message' => "Total sesi tidak valid: {$totalSessionsRaw}. Masukkan angka positif."];
        }

        $packagePrice = $this->parseNumber($packagePriceRaw);
        if ($packagePrice === null || $packagePrice < 0) {
            return ['success' => false, 'message' => "Harga paket tidak valid: {$packagePriceRaw}. Masukkan angka positif."];
        }

        // Calculate or validate original price
        $originalPriceRaw = $row['original_price'] ?? null;
        $originalPrice = $this->parseNumber($originalPriceRaw);

        if ($originalPrice === null) {
            // Default: service price * total sessions
            $originalPrice = $service->price * $totalSessions;
        }

        if ($packagePrice > $originalPrice) {
            return ['success' => false, 'message' => "Harga paket ({$packagePrice}) tidak boleh lebih besar dari harga normal ({$originalPrice})."];
        }

        // Parse validity days
        $validityDaysRaw = $row['validity_days'] ?? null;
        $validityDays = $this->parseNumber($validityDaysRaw);
        if ($validityDays === null || $validityDays <= 0) {
            $validityDays = 365; // Default 1 year
        }

        // Check for existing package with same name
        $existingPackage = Package::withTrashed()
            ->where('name', $name)
            ->where('service_id', $service->id)
            ->first();

        if ($existingPackage) {
            if ($existingPackage->trashed()) {
                $existingPackage->restore();
            }

            // Update existing package
            $existingPackage->update([
                'description' => $this->cleanValue($row['description'] ?? null) ?? $existingPackage->description,
                'total_sessions' => (int) $totalSessions,
                'original_price' => $originalPrice,
                'package_price' => $packagePrice,
                'validity_days' => (int) $validityDays,
                'is_active' => isset($row['is_active']) ? $this->parseBoolean($row['is_active']) : $existingPackage->is_active,
            ]);

            return [
                'success' => true,
                'skipped' => true,
                'message' => "Paket '{$name}' sudah ada, data diperbarui.",
                'data' => $existingPackage,
            ];
        }

        // Get max sort order
        $maxSortOrder = Package::max('sort_order') ?? 0;

        // Create new package
        $package = Package::create([
            'name' => $name,
            'description' => $this->cleanValue($row['description'] ?? null),
            'service_id' => $service->id,
            'total_sessions' => (int) $totalSessions,
            'original_price' => $originalPrice,
            'package_price' => $packagePrice,
            'validity_days' => (int) $validityDays,
            'is_active' => isset($row['is_active']) ? $this->parseBoolean($row['is_active']) : true,
            'sort_order' => $maxSortOrder + 1,
        ]);

        return [
            'success' => true,
            'message' => 'Paket berhasil ditambahkan.',
            'data' => $package,
        ];
    }
}
