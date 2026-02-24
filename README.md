# GlowUp - Platform Manajemen Multi-Bisnis (Clinic, Salon, Barbershop, Gym)

<p align="center">
  <strong>Aplikasi manajemen operasional all-in-one</strong><br>
  Klinik Kecantikan &bull; Salon &bull; Barbershop &bull; Gym/Fitness
</p>

## Tentang GlowUp

GlowUp adalah aplikasi web manajemen bisnis multi-tipe yang mendukung **Klinik Kecantikan**, **Salon**, **Barbershop**, dan **Gym/Fitness**. Dibangun dengan Laravel 12, aplikasi ini menyediakan fitur lengkap mulai dari manajemen pelanggan, appointment, POS, loyalty program, **QR attendance gym berbasis lokasi**, customer portal, hingga mobile API.

Setiap owner memilih **satu jenis bisnis** untuk menjaga data dan fitur tetap konsisten (tidak tercampur antar tipe).

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.3, Laravel 12 |
| Frontend | Tailwind CSS 4, Alpine.js |
| Build Tool | Vite 7 |
| API Auth | Laravel Sanctum |
| PDF | barryvdh/laravel-dompdf |
| Excel/CSV | Maatwebsite Excel |
| Testing | PHPUnit 11 |
| Code Style | Laravel Pint |

## Tipe Bisnis yang Didukung

| Tipe | Tema | Fitur Khusus |
|------|------|-------------|
| **Klinik Kecantikan** | Pink | Treatment records, analisis kulit, paket, produk, loyalty, booking online, customer portal |
| **Salon** | Purple | Paket layanan, produk, loyalty, booking online, customer portal, walk-in queue |
| **Barbershop** | Blue | Produk (pomade, wax), loyalty, booking online, walk-in queue |
| **Gym/Fitness** | Green | Paket membership, QR attendance + geolocation, attendance history, customer portal |

Setiap tipe bisnis memiliki konfigurasi fitur (feature toggle), profil pelanggan, label, dan sample data yang berbeda.

## Fitur Lengkap

### 1. Setup Wizard
- Wizard 3 langkah saat pertama kali install
- Pilih tipe bisnis (klinik/salon/barbershop/gym)
- Input detail bisnis (nama, telepon, alamat)
- Buat akun owner
- Auto-generate kategori dan layanan sample sesuai tipe bisnis

### 2. Landing Page
- Halaman publik untuk bisnis
- Tema adaptif sesuai tipe bisnis yang dipilih

### 3. Authentication & User Roles
- **3 Role**: Owner, Admin, Beautician/Stylist/Barber
- Role-based access control via middleware
- Owner-only: manajemen staff, settings
- Owner/Admin: settings, import data
- Semua role: akses operasional sehari-hari

### 4. Dashboard
- Revenue hari ini dan bulan ini
- Jumlah appointment hari ini (pending & completed)
- Customer baru minggu ini & total customer
- Grafik revenue 7 hari terakhir
- Layanan terpopuler
- Daftar appointment hari ini
- Transaksi terbaru

### 5. Manajemen Layanan (Service)
- **Kategori Layanan**: CRUD, reorder (drag & drop), icon
- **Layanan**: CRUD, toggle aktif/nonaktif, harga, durasi (menit)

### 6. Manajemen Produk (Feature Toggle)
- **Kategori Produk**: CRUD, reorder
- **Produk**: CRUD, toggle aktif/nonaktif, tracking stok
- Penyesuaian stok (stock adjustment)

### 7. Manajemen Pelanggan (Customer)
- CRUD dengan pencarian (nama, telepon, email)
- Profil dinamis sesuai tipe bisnis:
  - Klinik: tipe kulit, masalah kulit, alergi
  - Salon: tipe rambut, masalah rambut
  - Barbershop: tipe rambut, preferensi & masalah
- Statistik pelanggan (total kunjungan, total spending)
- Referral code otomatis
- Loyalty points & tier tracking
- Soft delete
- Riwayat appointment, treatment, paket, transaksi

### 8. Sistem Appointment
- CRUD lengkap
- Tampilan kalender dengan events API
- Slot waktu tersedia berdasarkan jam operasional
- Status management: Pending, Confirmed, In Progress, Completed, Cancelled, No Show
- Assign staff/beautician
- Durasi otomatis berdasarkan layanan
- Integrasi dengan treatment records

### 9. Treatment Records (Khusus Klinik)
- CRUD catatan treatment
- Upload multi-foto (before/after)
- Hapus foto individual
- Export PDF per treatment
- Export PDF riwayat treatment per customer
- Terhubung dengan appointment dan layanan

### 10. Manajemen Paket (Feature Toggle)
- **Paket Treatment**: CRUD, toggle aktif, multi-layanan
- **Paket Pelanggan**: Penjualan paket ke customer
- Tracking sesi (gunakan sesi, sisa sesi)
- Pembatalan paket
- Detail paket via API internal

### 10b. QR Attendance Gym (Gym Only)
- QR format `randomcode|timestamp` (validasi hanya randomcode)
- QR auto-generate sesuai jam operasional & bisa manual regenerate
- Validasi geolocation radius 100m
- Timer workout saat sesi aktif + tombol **Selesai Latihan**
- Attendance history + filter, search, dan durasi sesi

### 11. POS / Transaksi
- Buat transaksi dengan multiple item (layanan, paket, produk)
- Nomor invoice otomatis (format: INV + tanggal + sequence)
- **6 metode pembayaran**: Tunai, Kartu Debit, Kartu Kredit, Transfer Bank, QRIS, Lainnya
- Partial payment (bayar sebagian)
- Status: Belum Bayar, Bayar Sebagian, Lunas, Dibatalkan, Dikembalikan
- Invoice/struk cetak
- Diskon (nominal tetap atau persentase)
- Integrasi poin loyalty sebagai diskon
- Pajak

### 12. Program Loyalty (Feature Toggle)
- **Earning**: 1 poin per Rp 10.000 belanja (configurable)
- **Expiry**: poin kadaluarsa setelah 12 bulan (configurable)
- **4 Tier** dengan bonus multiplier:
  - Bronze (0 pts) - 1x
  - Silver (1.000 pts) - 1.1x
  - Gold (5.000 pts) - 1.25x
  - Platinum (10.000 pts) - 1.5x
- Manajemen rewards (CRUD, toggle aktif) - owner/admin only
- Redemption dengan kode unik (valid 30 hari)
- Riwayat poin per pelanggan
- Penyesuaian poin manual oleh staff
- Validasi kode redemption
- Gunakan poin sebagai diskon di transaksi
- Nilai tukar: 1 poin = Rp 100 (configurable)

### 13. Sistem Referral
- Kode referral otomatis per pelanggan (format: REF-XXXXXXXX)
- Bonus poin referrer: 100 poin (configurable)
- Bonus poin referee: 50 poin (configurable)
- Trigger setelah transaksi pertama yang dibayar
- Minimum transaksi configurable
- Tracking & log referral
- Statistik referral per pelanggan

### 14. Laporan (Reports)
- **Overview**: Ringkasan hari ini dan bulan ini
- **Revenue**: Harian/bulanan, filter rentang tanggal, grafik
- **Customer**: Analisis pelanggan
- **Service**: Layanan terpopuler, pendapatan per layanan
- **Appointment**: Statistik appointment
- **Staff**: Performa staff/beautician
- **Loyalty**: Statistik program loyalty
- **Product**: Laporan produk
- **Export Excel**: Revenue dan customers

### 15. Pengaturan (Settings) - Owner/Admin
- **Informasi Bisnis**: Nama, telepon, alamat
- **Jam Operasional**: Per hari dalam seminggu
- **Branding**: Upload/hapus logo bisnis
- **Store Settings**: Feature toggle, portal plan (basic/premium), premium feature list, tema & warna, koordinat lokasi gym

### 16. Import Data - Owner/Admin
- **Import Pelanggan** dari CSV
- **Import Layanan** dari CSV
- **Import Paket** dari CSV
- Preview data sebelum import
- Download template CSV
- Deteksi duplikat (update jika sudah ada)
- Laporan error detail per baris
- Riwayat import dengan log lengkap

### 17. Booking Online (Publik)
- Halaman booking publik tanpa login
- Pilih layanan dari kategori
- Pilih staff (opsional)
- Pilih tanggal & slot waktu yang tersedia
- Input informasi pelanggan
- Halaman konfirmasi booking
- Cek status booking
- Batalkan booking
- Auto-detect pelanggan portal yang sedang login

### 18. Customer Portal
- **Registrasi & Login** (auth guard terpisah dari staff)
- Verifikasi OTP via email
- **Dashboard**: Appointment mendatang, treatment terbaru, paket aktif
- **Profil**: Edit informasi pribadi
- **Appointments**: Daftar & detail appointment
- **Treatments**: Daftar & detail treatment
- **Packages**: Daftar & detail paket
- **Loyalty**: Poin & riwayat loyalty
- **Transactions**: Daftar & detail transaksi
- **Gym**: QR attendance, timer workout, riwayat presensi

### 19. Mobile API (REST)
- Token-based auth via Laravel Sanctum
- API versioning dengan prefix `/api/v1`
- Semua response menggunakan Eloquent API Resources
- **Endpoint tersedia**:
  - Auth (login, logout, profile)
  - Customers (CRUD, stats, treatments, packages, appointments)
  - Service Categories & Services
  - Appointments (CRUD, status, today, calendar, available slots)
  - Treatment Records (CRUD)
  - Packages & Customer Packages
  - Transactions (list, detail, receipt)

### 20. Multi-Bahasa (i18n)
- **Bahasa Indonesia** dan **English**
- Language switcher di UI
- File terjemahan lengkap untuk semua modul

### 21. Dark Mode
- Dukungan dark mode
- Pencegahan flash putih saat navigasi halaman di mobile

### 22. UI/UX
- Desktop-first responsive design (Tailwind `max-*` breakpoints)
- Tema dinamis berdasarkan tipe bisnis
- Komponen form reusable (input, select, textarea, button, currency input)
- Ikon SVG custom
- Alpine.js untuk interaktivitas

## Instalasi

```bash
# Clone repository
git clone <repository-url>
cd clinic-glowup-web-feature-multi-business-type

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Migrasi database
php artisan migrate

# (Opsional) Seed data contoh
php artisan db:seed

# Build assets
npm run build

# Atau jalankan development server
composer run dev
```

## Development

```bash
# Jalankan semua service sekaligus (server, queue, logs, vite)
composer run dev

# Atau jalankan terpisah
php artisan serve    # Backend server
npm run dev          # Vite dev server

# Jalankan test
php artisan test --compact

# Format code
vendor/bin/pint --dirty
```

## Struktur Direktori Utama

```
app/
├── Exports/              # Excel exports (Revenue, Customers)
├── Helpers/              # Helper functions (business, branding, format)
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/       # Mobile API controllers
│   │   ├── Auth/         # Staff authentication
│   │   └── Portal/       # Customer portal controllers
│   ├── Middleware/        # Role, Feature, Setup, Customer auth, Locale
│   └── Requests/         # Form request validation
├── Models/               # Eloquent models (20 models)
└── Services/
    ├── AppointmentService.php
    └── Import/           # CSV import services

resources/views/
├── appointments/         # Manajemen appointment
├── auth/                 # Login staff
├── booking/              # Booking online publik
├── components/           # Reusable Blade components
├── customer-packages/    # Paket pelanggan
├── customers/            # Manajemen pelanggan
├── dashboard/            # Dashboard utama
├── imports/              # Import data
├── landing/              # Landing page publik
├── layouts/              # Layout templates (dashboard, landing, portal)
├── loyalty/              # Program loyalty & rewards
├── packages/             # Manajemen paket
├── portal/               # Customer portal
├── product-categories/   # Kategori produk
├── products/             # Manajemen produk
├── reports/              # Laporan (8 jenis)
├── service-categories/   # Kategori layanan
├── services/             # Manajemen layanan
├── settings/             # Pengaturan bisnis
├── setup/                # Setup wizard
├── staff/                # Manajemen staff
├── transactions/         # POS & transaksi
└── treatment-records/    # Catatan treatment (PDF export)

routes/
├── web.php               # Web routes (staff & public)
└── api.php               # Mobile API routes (v1)

config/
├── business.php          # Konfigurasi tipe bisnis & features
├── loyalty.php           # Konfigurasi program loyalty
└── referral.php          # Konfigurasi sistem referral
```

## Models

| Model | Deskripsi |
|-------|-----------|
| User | Staff (Owner, Admin, Beautician) |
| Customer | Pelanggan (dengan auth terpisah untuk portal) |
| ServiceCategory | Kategori layanan |
| Service | Layanan/treatment |
| ProductCategory | Kategori produk |
| Product | Produk (dengan tracking stok) |
| Appointment | Janji temu/booking |
| TreatmentRecord | Catatan treatment (foto before/after) |
| Package | Paket treatment/layanan |
| CustomerPackage | Paket yang dibeli pelanggan |
| PackageUsage | Penggunaan sesi paket |
| Transaction | Transaksi POS |
| TransactionItem | Item dalam transaksi |
| Payment | Pembayaran transaksi |
| LoyaltyPoint | Riwayat poin loyalty |
| LoyaltyReward | Hadiah/reward loyalty |
| LoyaltyRedemption | Penukaran reward |
| ReferralLog | Log referral |
| Setting | Pengaturan aplikasi (key-value) |
| OperatingHour | Jam operasional per hari |
| ImportLog | Log import data |

## License

Copyright (c) 2026 Bembie.  
All rights reserved. This project is proprietary software.

## Author

Bembie
