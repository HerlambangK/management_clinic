# Sistem Referral GlowUp

Dokumentasi lengkap tentang sistem referral dan loyalty points di GlowUp.

## Daftar Isi

1. [Overview](#overview)
2. [Konfigurasi](#konfigurasi)
3. [Flow Referral](#flow-referral)
4. [Jalur Input Kode Referral](#jalur-input-kode-referral)
5. [Trigger Reward](#trigger-reward)
6. [Penggunaan Poin](#penggunaan-poin)
7. [Database Schema](#database-schema)
8. [API Endpoints](#api-endpoints)
9. [Testing](#testing)

---

## Overview

Sistem referral memungkinkan pelanggan untuk mengajak pelanggan baru dan mendapatkan bonus poin loyalty. Kedua pihak (pengajak dan yang diajak) mendapatkan bonus saat pelanggan baru melakukan transaksi pertama.

### Alur Singkat

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  Customer A     │     │  Customer B     │     │   Transaksi     │
│  (Referrer)     │────▶│  (Referee)      │────▶│   Pertama       │
│  Punya Kode     │     │  Pakai Kode     │     │   Dibayar       │
└─────────────────┘     └─────────────────┘     └────────┬────────┘
                                                         │
                                                         ▼
                                               ┌─────────────────┐
                                               │  Bonus Points   │
                                               │  A: +100 poin   │
                                               │  B: +50 poin    │
                                               └─────────────────┘
```

---

## Konfigurasi

### File: `config/referral.php`

```php
return [
    // Aktifkan/nonaktifkan sistem referral
    'enabled' => env('REFERRAL_ENABLED', true),

    // Prefix untuk kode referral (format: REF-XXXXXXXX)
    'code_prefix' => 'REF',

    // Bonus poin untuk referrer (yang mengajak)
    'referrer_bonus_points' => env('REFERRAL_REFERRER_POINTS', 100),

    // Bonus poin untuk referee (yang diajak)
    'referee_bonus_points' => env('REFERRAL_REFEREE_POINTS', 50),

    // Minimal transaksi untuk trigger reward (0 = tanpa minimal)
    'min_transaction_amount' => env('REFERRAL_MIN_TRANSACTION', 0),
];
```

### File: `config/loyalty.php` (untuk redemption)

```php
return [
    // Nilai 1 poin dalam Rupiah (untuk diskon)
    'points_value' => env('LOYALTY_POINTS_VALUE', 100),

    // Minimal poin untuk ditukar
    'min_points_redeem' => env('LOYALTY_MIN_POINTS_REDEEM', 10),

    // ... konfigurasi lainnya
];
```

### Environment Variables

```env
REFERRAL_ENABLED=true
REFERRAL_REFERRER_POINTS=100
REFERRAL_REFEREE_POINTS=50
REFERRAL_MIN_TRANSACTION=0
LOYALTY_POINTS_VALUE=100
LOYALTY_MIN_POINTS_REDEEM=10
```

---

## Flow Referral

### 1. Customer Mendapatkan Kode Referral

Setiap customer **otomatis** mendapatkan kode referral unik saat akun dibuat.

- **Format**: `REF-XXXXXXXX` (8 karakter alfanumerik uppercase)
- **Lokasi Kode**:
  - Halaman detail customer (`/customers/{id}`)
  - Portal customer (`/portal`)
  - Menu Loyalty > Referrals (`/loyalty/referrals`)

**Generate kode untuk customer lama:**
```bash
php artisan referral:generate-codes
```

### 2. Customer Baru Menggunakan Kode

Customer baru bisa memasukkan kode referral melalui 3 jalur (lihat bagian berikutnya).

### 3. Customer Baru Melakukan Transaksi Pertama

Saat customer baru membayar transaksi pertama:
- Sistem mengecek apakah customer punya `referred_by_id`
- Sistem mengecek apakah `referral_rewarded_at` masih null
- Jika ya, bonus diberikan ke kedua pihak

### 4. Bonus Points Diberikan

- **Referrer**: +100 poin (default)
- **Referee**: +50 poin (default)
- Status referral berubah dari `pending` ke `rewarded`
- Timestamp `referral_rewarded_at` diisi

---

## Jalur Input Kode Referral

### Jalur 1: Portal Registration

**URL**: `/portal/register`

Pelanggan mendaftar sendiri melalui portal customer.

```
┌──────────────────────────────────────┐
│         Daftar Akun Baru             │
├──────────────────────────────────────┤
│  Nama: [___________________]         │
│  No. HP: [___________________]       │
│  Email: [___________________]        │
│  Password: [___________________]     │
│                                      │
│  Kode Referral (Opsional):           │
│  [REF-XXXXXXXX___________]           │
│                                      │
│  [        DAFTAR        ]            │
└──────────────────────────────────────┘
```

**Pre-fill kode via URL:**
```
/portal/register?ref=REF-ABC12345
```

**File terkait:**
- View: `resources/views/portal/auth/register.blade.php`
- Controller: `app/Http/Controllers/Portal/AuthController.php`

---

### Jalur 2: Public Booking

**URL**: `/booking`

Customer baru booking tanpa login, kode referral diinput di step pertama.

```
┌──────────────────────────────────────┐
│         Booking Online               │
├──────────────────────────────────────┤
│  Step 1: Data Diri                   │
│                                      │
│  Nama: [___________________]         │
│  No. HP: [___________________]       │
│  Email: [___________________]        │
│                                      │
│  Kode Referral (Opsional):           │
│  [REF-XXXXXXXX___________]           │
│                                      │
│  [      LANJUTKAN       ]            │
└──────────────────────────────────────┘
```

**File terkait:**
- View: `resources/views/booking/index.blade.php`
- Controller: `app/Http/Controllers/BookingController.php`

---

### Jalur 3: Admin Create Customer

**URL**: `/customers/create`

Admin/staff membuat customer baru dari dashboard.

```
┌──────────────────────────────────────┐
│         Tambah Pelanggan             │
├──────────────────────────────────────┤
│  Nama: [___________________]         │
│  No. HP: [___________________]       │
│  Email: [___________________]        │
│  Tanggal Lahir: [___________]        │
│  ...                                 │
│                                      │
│  Kode Referral (Opsional):           │
│  [REF-XXXXXXXX___________]           │
│  Masukkan kode referral jika         │
│  pelanggan diajak oleh pelanggan     │
│  lain                                │
│                                      │
│  [    SIMPAN PELANGGAN    ]          │
└──────────────────────────────────────┘
```

**File terkait:**
- View: `resources/views/customers/create.blade.php`
- Controller: `app/Http/Controllers/CustomerController.php`

---

## Trigger Reward

### Kapan Reward Diberikan?

Reward diberikan saat **transaksi pertama dibayar** oleh customer yang menggunakan kode referral.

### Kondisi yang Harus Terpenuhi:

1. `config('referral.enabled')` = `true`
2. Customer memiliki `referred_by_id` (di-refer oleh seseorang)
3. `referral_rewarded_at` masih `null` (belum pernah dapat reward)
4. Total transaksi >= `min_transaction_amount` (default: 0)
5. Ini adalah transaksi pertama yang dibayar

### Proses di TransactionController

```php
// File: app/Http/Controllers/TransactionController.php
// Method: pay()

private function processReferralReward(Transaction $transaction): void
{
    // Cek apakah referral enabled
    if (!config('referral.enabled', true)) {
        return;
    }

    $customer = $transaction->customer;

    // Cek apakah customer punya referrer dan belum dapat reward
    if (!$customer->hasUnrewardedReferral()) {
        return;
    }

    // Cek minimal transaksi
    $minAmount = config('referral.min_transaction_amount', 0);
    if ($transaction->total_amount < $minAmount) {
        return;
    }

    // Cek apakah ini transaksi pertama yang dibayar
    $hasPreviousPaidTransaction = Transaction::where('customer_id', $customer->id)
        ->where('id', '!=', $transaction->id)
        ->where('status', 'paid')
        ->exists();

    if ($hasPreviousPaidTransaction) {
        return;
    }

    // Berikan reward ke kedua pihak
    $referrer = $customer->referrer;
    $referrerPoints = config('referral.referrer_bonus_points', 100);
    $refereePoints = config('referral.referee_bonus_points', 50);

    // Bonus untuk referrer
    $referrer->addLoyaltyPoints(
        $referrerPoints,
        'bonus',
        "Bonus referral - mengajak {$customer->name}"
    );

    // Bonus untuk referee
    $customer->addLoyaltyPoints(
        $refereePoints,
        'bonus',
        "Bonus referral - diajak oleh {$referrer->name}"
    );

    // Catat di referral log
    ReferralLog::create([
        'referrer_id' => $referrer->id,
        'referee_id' => $customer->id,
        'transaction_id' => $transaction->id,
        'referrer_points' => $referrerPoints,
        'referee_points' => $refereePoints,
        'status' => 'rewarded',
        'rewarded_at' => now(),
    ]);

    // Tandai referral sudah di-reward
    $customer->update(['referral_rewarded_at' => now()]);
}
```

---

## Penggunaan Poin

### Konversi Poin ke Rupiah

```
1 poin = Rp 100 (default)

Contoh:
- 50 poin = Rp 5.000
- 100 poin = Rp 10.000
- 500 poin = Rp 50.000
```

### Cara Pakai Poin di Transaksi

**URL**: `/transactions/create`

```
┌──────────────────────────────────────┐
│            Ringkasan                 │
├──────────────────────────────────────┤
│  Subtotal              Rp 500.000    │
│  Diskon Tambahan       Rp 0          │
│                                      │
│  ┌────────────────────────────────┐  │
│  │ 🪙 Gunakan Poin                │  │
│  │    Tersedia: 150 poin          │  │
│  │                                │  │
│  │ [✓] ──────────────────         │  │
│  │                                │  │
│  │ Jumlah: [100_______] poin      │  │
│  │                                │  │
│  │ 100 poin = Rp 10.000           │  │
│  │              [Pakai Maksimal]  │  │
│  └────────────────────────────────┘  │
│                                      │
│  Diskon Poin           -Rp 10.000    │
│  Pajak                 Rp 0          │
│  ────────────────────────────────    │
│  TOTAL                 Rp 490.000    │
│                                      │
│  [      BUAT TRANSAKSI      ]        │
└──────────────────────────────────────┘
```

### Validasi Poin:

1. Poin minimal: 10 (konfigurasi)
2. Poin maksimal: minimum dari:
   - Saldo poin customer
   - (Subtotal - Diskon) / Points Value
3. Diskon poin tidak bisa melebihi total transaksi

### Proses di TransactionController

```php
// Saat transaksi dibuat
if ($request->points_used > 0) {
    $pointsValue = config('loyalty.points_value', 100);
    $pointsDiscount = $pointsUsed * $pointsValue;

    // Potong poin dari customer
    $customer->addLoyaltyPoints(
        -$pointsUsed,
        'redeem',
        "Digunakan untuk transaksi {$invoice}"
    );
}

// Jika transaksi dibatalkan, poin dikembalikan
if ($transaction->points_used > 0) {
    $customer->addLoyaltyPoints(
        $transaction->points_used,
        'refund',
        "Pengembalian poin dari transaksi {$invoice}"
    );
}
```

---

## Database Schema

### Tabel: customers

```sql
-- Kolom terkait referral
referral_code VARCHAR(20) UNIQUE    -- Kode referral customer ini
referred_by_id BIGINT UNSIGNED      -- FK ke customers.id (siapa yang refer)
referral_rewarded_at TIMESTAMP      -- Kapan reward diberikan

-- Kolom terkait loyalty
loyalty_points INT DEFAULT 0        -- Saldo poin saat ini
lifetime_points INT DEFAULT 0       -- Total poin yang pernah didapat
```

### Tabel: referral_logs

```sql
CREATE TABLE referral_logs (
    id BIGINT PRIMARY KEY,
    referrer_id BIGINT UNSIGNED,     -- Customer yang mengajak
    referee_id BIGINT UNSIGNED,      -- Customer yang diajak
    transaction_id BIGINT UNSIGNED,  -- Transaksi yang trigger reward
    referrer_points INT,             -- Poin untuk referrer
    referee_points INT,              -- Poin untuk referee
    status ENUM('pending', 'rewarded', 'cancelled'),
    rewarded_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    UNIQUE(referrer_id, referee_id)
);
```

### Tabel: transactions

```sql
-- Kolom terkait poin
points_used INT DEFAULT 0           -- Jumlah poin yang dipakai
points_discount DECIMAL(12,2)       -- Nilai diskon dari poin
```

### Tabel: loyalty_points

```sql
CREATE TABLE loyalty_points (
    id BIGINT PRIMARY KEY,
    customer_id BIGINT UNSIGNED,
    points INT,                      -- Jumlah poin (+/-)
    type ENUM('earn', 'redeem', 'bonus', 'adjust', 'refund', 'expire'),
    description TEXT,
    reference_type VARCHAR(255),     -- Polymorphic
    reference_id BIGINT UNSIGNED,
    balance_after INT,               -- Saldo setelah transaksi
    created_at TIMESTAMP
);
```

---

## API Endpoints

### Internal API (Web)

```
GET /api/customers/{id}/points
Response: { points: 150, lifetime_points: 500, tier: "silver" }

GET /api/customers/{id}/packages
Response: [{ id: 1, package: {...}, sessions_remaining: 5 }, ...]
```

### Mobile API (Sanctum)

```
GET /api/v1/customers/{id}/stats
Response: { loyalty_points: 150, tier: "silver", ... }
```

---

## Testing

### Menjalankan Test Referral

```bash
php artisan test --filter=ReferralTest
```

### Test Cases

1. **test_customer_gets_referral_code_on_creation**
   - Customer baru otomatis dapat kode referral

2. **test_referral_code_is_unique**
   - Setiap kode referral unik

3. **test_booking_with_referral_code_links_customers**
   - Booking dengan kode referral menghubungkan customer

4. **test_referral_reward_given_on_first_paid_transaction**
   - Reward diberikan saat transaksi pertama dibayar

5. **test_referral_reward_not_given_twice**
   - Reward tidak diberikan dua kali

6. **test_referral_reward_not_given_when_disabled**
   - Reward tidak diberikan jika fitur disabled

7. **test_portal_registration_with_referral_code**
   - Registrasi portal dengan kode referral

### Manual Testing Flow

```
1. Buka /customers/2 → Copy kode referral (REF-XXXXXXXX)

2. Buat customer baru:
   - Via /customers/create, atau
   - Via /portal/register, atau
   - Via /booking
   - Masukkan kode referral

3. Buat transaksi untuk customer baru:
   - /transactions/create
   - Pilih customer baru
   - Tambah item
   - Buat transaksi

4. Bayar transaksi:
   - /transactions/{id}
   - Klik "Bayar"
   - Masukkan jumlah pembayaran

5. Cek bonus poin:
   - Referrer: /customers/{referrer_id} → Cek loyalty points (+100)
   - Referee: /customers/{referee_id} → Cek loyalty points (+50)

6. Test pakai poin:
   - Buat transaksi baru untuk customer dengan poin
   - Toggle "Gunakan Poin"
   - Masukkan jumlah poin
   - Lihat diskon di total
```

---

## Troubleshooting

### Kode Referral Tidak Muncul

```bash
# Generate kode untuk customer yang belum punya
php artisan referral:generate-codes
```

### Reward Tidak Diberikan

Cek kondisi berikut:
1. `config('referral.enabled')` = true?
2. Customer punya `referred_by_id`?
3. `referral_rewarded_at` masih null?
4. Ini transaksi pertama yang dibayar?

### Poin Tidak Bisa Dipakai

Cek:
1. Customer punya cukup poin?
2. Poin >= `min_points_redeem` (10)?
3. Subtotal transaksi > 0?

---

## File Terkait

### Models
- `app/Models/Customer.php`
- `app/Models/ReferralLog.php`
- `app/Models/LoyaltyPoint.php`
- `app/Models/Transaction.php`

### Controllers
- `app/Http/Controllers/CustomerController.php`
- `app/Http/Controllers/TransactionController.php`
- `app/Http/Controllers/LoyaltyController.php`
- `app/Http/Controllers/Portal/AuthController.php`
- `app/Http/Controllers/BookingController.php`

### Views
- `resources/views/customers/create.blade.php`
- `resources/views/customers/show.blade.php`
- `resources/views/transactions/create.blade.php`
- `resources/views/transactions/show.blade.php`
- `resources/views/portal/auth/register.blade.php`
- `resources/views/booking/index.blade.php`
- `resources/views/loyalty/referrals.blade.php`

### Config
- `config/referral.php`
- `config/loyalty.php`

### Migrations
- `database/migrations/2026_02_05_064701_add_referral_fields_to_customers_table.php`
- `database/migrations/2026_02_05_064721_create_referral_logs_table.php`
- `database/migrations/2026_02_05_101852_add_points_fields_to_transactions_table.php`

### Commands
- `app/Console/Commands/GenerateReferralCodes.php`

### Tests
- `tests/Feature/ReferralTest.php`
