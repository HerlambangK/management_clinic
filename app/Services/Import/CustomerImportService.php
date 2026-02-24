<?php

namespace App\Services\Import;

use App\Models\Customer;

class CustomerImportService extends BaseImportService
{
    public function getRequiredColumns(): array
    {
        return ['name', 'phone'];
    }

    public function getAvailableColumns(): array
    {
        $businessType = business_type() ?? 'clinic';

        // Dynamic profile field descriptions based on business type
        $profileTypeDesc = match ($businessType) {
            'salon', 'barbershop' => 'Tipe rambut (normal/dry/oily/damaged/curly/straight/wavy)',
            'gym' => 'Level kebugaran (beginner/intermediate/advanced)',
            default => 'Tipe kulit (normal/dry/oily/combination/sensitive)',
        };

        $profileConcernsDesc = match ($businessType) {
            'salon', 'barbershop' => 'Masalah rambut (pisahkan dengan koma)',
            'gym' => 'Tujuan latihan (pisahkan dengan koma)',
            default => 'Masalah kulit (pisahkan dengan koma)',
        };

        return [
            'name' => 'Nama pelanggan (wajib)',
            'phone' => 'Nomor telepon (wajib, format: 08xxxxxxxxxx)',
            'email' => 'Email',
            'birthdate' => 'Tanggal lahir (format: YYYY-MM-DD atau DD/MM/YYYY)',
            'gender' => 'Jenis kelamin (male/female)',
            'address' => 'Alamat',
            'skin_type' => $profileTypeDesc,
            'skin_concerns' => $profileConcernsDesc,
            'allergies' => 'Alergi',
            'notes' => 'Catatan',
        ];
    }

    public function getSampleData(): array
    {
        $businessType = business_type() ?? 'clinic';

        if (in_array($businessType, ['salon', 'barbershop'])) {
            return [
                [
                    'name' => 'Siti Aminah',
                    'phone' => '081234567890',
                    'email' => 'siti@email.com',
                    'birthdate' => '1990-05-15',
                    'gender' => 'female',
                    'address' => 'Jl. Sudirman No. 123, Jakarta',
                    'skin_type' => 'wavy',
                    'skin_concerns' => 'dry,frizzy',
                    'allergies' => 'Tidak ada',
                    'notes' => 'Pelanggan reguler',
                ],
                [
                    'name' => 'Budi Santoso',
                    'phone' => '082345678901',
                    'email' => 'budi@email.com',
                    'birthdate' => '1985-08-20',
                    'gender' => 'male',
                    'address' => 'Jl. Gatot Subroto No. 45',
                    'skin_type' => 'oily',
                    'skin_concerns' => 'dandruff',
                    'allergies' => 'Tidak ada',
                    'notes' => '',
                ],
            ];
        }
        if ($businessType === 'gym') {
            return [
                [
                    'name' => 'Rian Pratama',
                    'phone' => '081234567890',
                    'email' => 'rian@email.com',
                    'birthdate' => '1992-06-12',
                    'gender' => 'male',
                    'address' => 'Jl. Magelang No. 10',
                    'skin_type' => 'beginner',
                    'skin_concerns' => 'weight_loss,endurance',
                    'allergies' => 'Tidak ada',
                    'notes' => 'Member baru',
                ],
                [
                    'name' => 'Dewi Lestari',
                    'phone' => '082345678901',
                    'email' => 'dewi@email.com',
                    'birthdate' => '1995-09-22',
                    'gender' => 'female',
                    'address' => 'Jl. Kaliurang No. 45',
                    'skin_type' => 'intermediate',
                    'skin_concerns' => 'muscle_gain,flexibility',
                    'allergies' => 'Tidak ada',
                    'notes' => '',
                ],
            ];
        }

        // Default: clinic (skin profile)
        return [
            [
                'name' => 'Siti Aminah',
                'phone' => '081234567890',
                'email' => 'siti@email.com',
                'birthdate' => '1990-05-15',
                'gender' => 'female',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'skin_type' => 'combination',
                'skin_concerns' => 'acne,dark_spots',
                'allergies' => 'Tidak ada',
                'notes' => 'Pelanggan reguler',
            ],
            [
                'name' => 'Budi Santoso',
                'phone' => '082345678901',
                'email' => 'budi@email.com',
                'birthdate' => '1985-08-20',
                'gender' => 'male',
                'address' => 'Jl. Gatot Subroto No. 45',
                'skin_type' => 'oily',
                'skin_concerns' => 'acne',
                'allergies' => 'Parfum',
                'notes' => '',
            ],
        ];
    }

    protected function processRow(array $row, int $rowNumber): array
    {
        $name = $this->cleanValue($row['name'] ?? null);
        $phone = $this->cleanValue($row['phone'] ?? null);

        // Validate required fields
        if (empty($name)) {
            return ['success' => false, 'message' => 'Nama wajib diisi.'];
        }

        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor telepon wajib diisi.'];
        }

        // Normalize phone number
        $phone = $this->normalizePhone($phone);

        // Validate phone format
        if (! preg_match('/^08[0-9]{8,13}$/', $phone)) {
            return ['success' => false, 'message' => "Format nomor telepon tidak valid: {$phone}. Gunakan format 08xxxxxxxxxx."];
        }

        // Check for duplicate phone
        $existingCustomer = Customer::withTrashed()->where('phone', $phone)->first();

        if ($existingCustomer) {
            if ($existingCustomer->trashed()) {
                // Restore if soft deleted
                $existingCustomer->restore();
                $this->updateCustomer($existingCustomer, $row);

                return [
                    'success' => true,
                    'message' => 'Pelanggan dipulihkan dan diperbarui.',
                    'data' => $existingCustomer,
                ];
            }

            // Update existing customer
            $this->updateCustomer($existingCustomer, $row);

            return [
                'success' => true,
                'skipped' => true,
                'message' => "Pelanggan dengan telepon {$phone} sudah ada, data diperbarui.",
                'data' => $existingCustomer,
            ];
        }

        // Validate email if provided
        $email = $this->cleanValue($row['email'] ?? null);
        if ($email && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => "Format email tidak valid: {$email}."];
        }

        // Check duplicate email if provided
        if ($email) {
            $existingEmail = Customer::where('email', $email)->exists();
            if ($existingEmail) {
                return ['success' => false, 'message' => "Email {$email} sudah digunakan pelanggan lain."];
            }
        }

        // Parse and validate gender
        $gender = $this->parseGender($row['gender'] ?? null);

        // Parse and validate skin type
        $skinType = $this->parseSkinType($row['skin_type'] ?? null);

        // Parse skin concerns
        $skinConcerns = $this->parseSkinConcerns($row['skin_concerns'] ?? null);

        // Create customer
        $customer = Customer::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'birthdate' => $this->parseDate($row['birthdate'] ?? null),
            'gender' => $gender,
            'address' => $this->cleanValue($row['address'] ?? null),
            'skin_type' => $skinType,
            'skin_concerns' => $skinConcerns,
            'allergies' => $this->cleanValue($row['allergies'] ?? null),
            'notes' => $this->cleanValue($row['notes'] ?? null),
            'total_visits' => 0,
            'total_spent' => 0,
        ]);

        return [
            'success' => true,
            'message' => 'Pelanggan berhasil ditambahkan.',
            'data' => $customer,
        ];
    }

    private function normalizePhone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert +62 or 62 prefix to 0
        if (str_starts_with($phone, '62')) {
            $phone = '0'.substr($phone, 2);
        }

        return $phone;
    }

    private function parseGender(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = strtolower(trim($value));
        $map = [
            'male' => 'male',
            'female' => 'female',
            'laki-laki' => 'male',
            'perempuan' => 'female',
            'pria' => 'male',
            'wanita' => 'female',
            'l' => 'male',
            'p' => 'female',
            'm' => 'male',
            'f' => 'female',
        ];

        return $map[$value] ?? null;
    }

    private function parseSkinType(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = strtolower(trim($value));
        $businessType = business_type() ?? 'clinic';

        if (in_array($businessType, ['salon', 'barbershop'])) {
            // Hair types for salon/barbershop
            $validTypes = ['normal', 'dry', 'oily', 'damaged', 'curly', 'straight', 'wavy'];

            $map = [
                'kering' => 'dry',
                'berminyak' => 'oily',
                'rusak' => 'damaged',
                'keriting' => 'curly',
                'lurus' => 'straight',
                'bergelombang' => 'wavy',
            ];
        } elseif ($businessType === 'gym') {
            // Fitness levels for gym
            $validTypes = ['beginner', 'intermediate', 'advanced'];

            $map = [
                'pemula' => 'beginner',
                'menengah' => 'intermediate',
                'lanjutan' => 'advanced',
            ];
        } else {
            // Skin types for clinic
            $validTypes = ['normal', 'dry', 'oily', 'combination', 'sensitive'];

            $map = [
                'kering' => 'dry',
                'berminyak' => 'oily',
                'kombinasi' => 'combination',
                'sensitif' => 'sensitive',
            ];
        }

        if (in_array($value, $validTypes)) {
            return $value;
        }

        return $map[$value] ?? null;
    }

    private function parseSkinConcerns(?string $value): ?array
    {
        if (empty($value)) {
            return null;
        }

        $concerns = array_map('trim', explode(',', $value));
        $businessType = business_type() ?? 'clinic';

        if (in_array($businessType, ['salon', 'barbershop'])) {
            // Hair concerns for salon/barbershop
            $validConcerns = ['dry', 'oily', 'dandruff', 'hair_loss', 'split_ends', 'frizzy', 'thin', 'gray'];

            $map = [
                'kering' => 'dry',
                'berminyak' => 'oily',
                'ketombe' => 'dandruff',
                'rontok' => 'hair_loss',
                'bercabang' => 'split_ends',
                'mengembang' => 'frizzy',
                'tipis' => 'thin',
                'beruban' => 'gray',
            ];
        } elseif ($businessType === 'gym') {
            // Training goals for gym
            $validConcerns = ['weight_loss', 'muscle_gain', 'endurance', 'flexibility', 'rehab'];

            $map = [
                'turun berat badan' => 'weight_loss',
                'weight loss' => 'weight_loss',
                'massa otot' => 'muscle_gain',
                'muscle gain' => 'muscle_gain',
                'daya tahan' => 'endurance',
                'endurance' => 'endurance',
                'fleksibilitas' => 'flexibility',
                'flexibility' => 'flexibility',
                'pemulihan' => 'rehab',
                'rehab' => 'rehab',
            ];
        } else {
            // Skin concerns for clinic
            $validConcerns = ['acne', 'aging', 'dark_spots', 'dull_skin', 'large_pores', 'redness', 'wrinkles', 'dehydration'];

            $map = [
                'jerawat' => 'acne',
                'penuaan' => 'aging',
                'flek' => 'dark_spots',
                'flek hitam' => 'dark_spots',
                'kusam' => 'dull_skin',
                'pori besar' => 'large_pores',
                'kemerahan' => 'redness',
                'keriput' => 'wrinkles',
                'dehidrasi' => 'dehydration',
            ];
        }

        $result = [];
        foreach ($concerns as $concern) {
            $concern = strtolower($concern);
            if (in_array($concern, $validConcerns)) {
                $result[] = $concern;
            } elseif (isset($map[$concern])) {
                $result[] = $map[$concern];
            }
        }

        return ! empty($result) ? array_unique($result) : null;
    }

    private function updateCustomer(Customer $customer, array $row): void
    {
        $updates = [];

        $email = $this->cleanValue($row['email'] ?? null);
        if ($email && $email !== $customer->email) {
            $existingEmail = Customer::where('email', $email)->where('id', '!=', $customer->id)->exists();
            if (! $existingEmail) {
                $updates['email'] = $email;
            }
        }

        if (! empty($row['name'])) {
            $updates['name'] = $this->cleanValue($row['name']);
        }

        if (! empty($row['birthdate'])) {
            $updates['birthdate'] = $this->parseDate($row['birthdate']);
        }

        if (! empty($row['gender'])) {
            $updates['gender'] = $this->parseGender($row['gender']);
        }

        if (! empty($row['address'])) {
            $updates['address'] = $this->cleanValue($row['address']);
        }

        if (! empty($row['skin_type'])) {
            $updates['skin_type'] = $this->parseSkinType($row['skin_type']);
        }

        if (! empty($row['skin_concerns'])) {
            $updates['skin_concerns'] = $this->parseSkinConcerns($row['skin_concerns']);
        }

        if (! empty($row['allergies'])) {
            $updates['allergies'] = $this->cleanValue($row['allergies']);
        }

        if (! empty($row['notes'])) {
            $updates['notes'] = $this->cleanValue($row['notes']);
        }

        if (! empty($updates)) {
            $customer->update($updates);
        }
    }
}
