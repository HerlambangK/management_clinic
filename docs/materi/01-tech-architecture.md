# Dokumentasi Teknis & Arsitektur GlowUp Clinic

## Daftar Isi
1. [Gambaran Umum](#1-gambaran-umum)
2. [Tech Stack](#2-tech-stack)
3. [Arsitektur Aplikasi](#3-arsitektur-aplikasi)
4. [Database Schema](#4-database-schema)
5. [Fitur-Fitur Utama](#5-fitur-fitur-utama)
6. [API Mobile](#6-api-mobile)
7. [Sistem Autentikasi](#7-sistem-autentikasi)
8. [Konfigurasi Bisnis](#8-konfigurasi-bisnis)

---

## 1. Gambaran Umum

**GlowUp Clinic** adalah sistem manajemen klinik kecantikan berbasis web yang dibangun dengan Laravel 12. Aplikasi ini menyediakan solusi lengkap untuk mengelola operasional klinik kecantikan termasuk:

- Manajemen pelanggan dengan profil kulit
- Sistem appointment dengan kalender
- Rekam treatment dengan foto before/after
- Manajemen paket layanan
- Point of Sale (POS) dengan multi-pembayaran
- Program loyalitas dengan poin dan tier
- Program referral pelanggan
- Laporan bisnis komprehensif
- Mobile API untuk aplikasi mobile
- Portal pelanggan self-service

---

## 2. Tech Stack

### Backend
| Komponen | Versi | Keterangan |
|----------|-------|------------|
| PHP | 8.3.22 | Runtime utama |
| Laravel | 12.x | Framework PHP |
| Laravel Sanctum | - | API Authentication |
| Laravel Pint | 1.x | Code Formatter |
| PHPUnit | 11.x | Testing Framework |

### Frontend
| Komponen | Keterangan |
|----------|------------|
| Blade Templates | Template engine Laravel |
| Tailwind CSS | CSS framework dengan pendekatan desktop-first |
| Alpine.js | JavaScript framework untuk interaktivitas |
| FullCalendar | Library kalender untuk appointment |
| Chart.js | Visualisasi data untuk laporan |

### Database
| Komponen | Keterangan |
|----------|------------|
| MySQL/MariaDB | Database utama (production) |
| SQLite | Database untuk testing |

### External Packages
| Package | Fungsi |
|---------|--------|
| Maatwebsite/Excel | Export laporan ke Excel |
| Intervention/Image | Manipulasi gambar |
| Laravel MCP | Model Context Protocol |

---

## 3. Arsitektur Aplikasi

### 3.1 Struktur Direktori

```
clinic-glowup-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/          # API Controllers
│   │   │   ├── Auth/            # Web Authentication
│   │   │   ├── Portal/          # Customer Portal
│   │   │   └── *.php            # Feature Controllers
│   │   ├── Requests/            # Form Request Validation
│   │   ├── Resources/           # API Resources (JSON)
│   │   └── Middleware/
│   ├── Models/                  # Eloquent Models
│   ├── Services/                # Business Logic Services
│   │   └── Import/              # CSV Import Services
│   └── Policies/                # Authorization Policies
├── config/
│   ├── business.php             # Business Type Configuration
│   ├── loyalty.php              # Loyalty Program Config
│   └── referral.php             # Referral Program Config
├── database/
│   ├── migrations/              # Database Migrations
│   ├── seeders/                 # Data Seeders
│   └── factories/               # Model Factories
├── resources/
│   └── views/
│       ├── layouts/             # Layout Templates
│       ├── components/          # Blade Components
│       ├── dashboard/           # Dashboard Views
│       ├── customers/           # Customer Management
│       ├── appointments/        # Appointment Views
│       ├── treatment-records/   # Treatment Records
│       ├── transactions/        # POS & Transactions
│       ├── packages/            # Package Management
│       ├── loyalty/             # Loyalty Program
│       ├── reports/             # Reports Views
│       ├── settings/            # Settings Views
│       ├── portal/              # Customer Portal
│       └── booking/             # Public Booking
├── routes/
│   ├── web.php                  # Web Routes
│   └── api.php                  # API Routes
└── tests/
    ├── Feature/                 # Feature Tests
    │   └── Api/                 # API Tests
    └── Unit/                    # Unit Tests
```

### 3.2 Layer Arsitektur

```
┌─────────────────────────────────────────────────────────────┐
│                      PRESENTATION LAYER                      │
├─────────────────────────────────────────────────────────────┤
│  Web UI (Blade)    │  Mobile API (REST)  │  Customer Portal │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                      CONTROLLER LAYER                        │
├─────────────────────────────────────────────────────────────┤
│  Web Controllers   │  API Controllers    │  Portal Controllers│
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                      BUSINESS LAYER                          │
├─────────────────────────────────────────────────────────────┤
│  Services          │  Policies           │  Form Requests    │
│  (AppointmentSvc)  │  (Authorization)    │  (Validation)     │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                              │
├─────────────────────────────────────────────────────────────┤
│  Eloquent Models   │  API Resources      │  Migrations       │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                          │
├─────────────────────────────────────────────────────────────┤
│                    MySQL / MariaDB                           │
└─────────────────────────────────────────────────────────────┘
```

### 3.3 Flow Diagram

```
                    ┌──────────────────┐
                    │   Web Browser    │
                    └────────┬─────────┘
                             │
         ┌───────────────────┼───────────────────┐
         │                   │                   │
         ▼                   ▼                   ▼
┌────────────────┐  ┌────────────────┐  ┌────────────────┐
│   Public Web   │  │  Staff Panel   │  │ Customer Portal│
│   (Booking)    │  │  (Dashboard)   │  │ (Self-Service) │
└────────┬───────┘  └────────┬───────┘  └────────┬───────┘
         │                   │                   │
         └───────────────────┼───────────────────┘
                             │
                    ┌────────▼────────┐
                    │  Laravel Router │
                    └────────┬────────┘
                             │
              ┌──────────────┼──────────────┐
              ▼              ▼              ▼
       ┌────────────┐ ┌────────────┐ ┌────────────┐
       │ Middleware │ │ Middleware │ │ Middleware │
       │   (Auth)   │ │  (Role)    │ │ (Feature)  │
       └─────┬──────┘ └─────┬──────┘ └─────┬──────┘
             └──────────────┼──────────────┘
                            ▼
                    ┌────────────────┐
                    │   Controller   │
                    └────────┬───────┘
                             │
              ┌──────────────┼──────────────┐
              ▼              ▼              ▼
       ┌────────────┐ ┌────────────┐ ┌────────────┐
       │  Service   │ │   Model    │ │  Resource  │
       └────────────┘ └────────────┘ └────────────┘
```

---

## 4. Database Schema

### 4.1 Entity Relationship Diagram

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    users     │     │  customers   │     │   services   │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id           │     │ id           │     │ id           │
│ name         │     │ name         │     │ category_id  │◄───┐
│ email        │     │ phone        │     │ name         │    │
│ role         │     │ email        │     │ price        │    │
│ is_active    │     │ loyalty_pts  │     │ duration     │    │
└──────────────┘     │ referred_by  │◄────│ is_active    │    │
       │             └──────────────┘     └──────────────┘    │
       │                    │                    │             │
       │                    │                    │      ┌──────┴───────┐
       │                    │                    │      │service_      │
       │                    │                    │      │categories    │
       │                    │                    │      ├──────────────┤
       ▼                    ▼                    ▼      │ id           │
┌──────────────┐     ┌──────────────┐     ┌──────────────┤ name         │
│ appointments │◄────┤              │────►│              │ sort_order   │
├──────────────┤     │              │     │              └──────────────┘
│ id           │     │              │     │
│ customer_id  │────►│   customer   │     │   service
│ service_id   │────►│              │     │
│ staff_id     │────►│   (users)    │     │
│ date/time    │     │              │     │
│ status       │     └──────────────┘     └──────────────┐
└──────────────┘                                         │
       │                                                 │
       ▼                                                 │
┌──────────────┐     ┌──────────────┐     ┌──────────────┤
│ treatment_   │     │ transactions │     │   packages   │
│ records      │     ├──────────────┤     ├──────────────┤
├──────────────┤     │ id           │     │ id           │
│ id           │     │ invoice_no   │     │ service_id   │◄──┘
│ appointment  │────►│ customer_id  │────►│ total_sess   │
│ customer_id  │────►│ subtotal     │     │ price        │
│ staff_id     │────►│ discount     │     │ validity     │
│ notes        │     │ total_amount │     └──────────────┘
│ before_photos│     │ status       │            │
│ after_photos │     └──────────────┘            ▼
└──────────────┘            │            ┌──────────────┐
                            │            │ customer_    │
                            │            │ packages     │
                            ▼            ├──────────────┤
                    ┌──────────────┐     │ id           │
                    │ transaction_ │     │ customer_id  │────►
                    │ items        │     │ package_id   │────►
                    ├──────────────┤     │ sessions_used│
                    │ id           │     │ expires_at   │
                    │ transaction  │────►│ status       │
                    │ item_type    │     └──────────────┘
                    │ service_id   │            │
                    │ package_id   │            ▼
                    │ product_id   │     ┌──────────────┐
                    │ quantity     │     │ package_     │
                    │ price        │     │ usages       │
                    └──────────────┘     ├──────────────┤
                            │            │ id           │
                            ▼            │ cust_package │
                    ┌──────────────┐     │ appointment  │
                    │   payments   │     │ used_at      │
                    ├──────────────┤     └──────────────┘
                    │ id           │
                    │ transaction  │
                    │ method       │
                    │ amount       │
                    └──────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│ loyalty_     │     │ loyalty_     │     │ loyalty_     │
│ points       │     │ rewards      │     │ redemptions  │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id           │     │ id           │     │ id           │
│ customer_id  │────►│ name         │     │ customer_id  │────►
│ type (earn/  │     │ points_req   │     │ reward_id    │────►
│       redeem)│     │ reward_type  │     │ points_used  │
│ points       │     │ reward_value │     │ code         │
│ expires_at   │     │ is_active    │     │ status       │
└──────────────┘     └──────────────┘     └──────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│ referral_    │     │   products   │     │ product_     │
│ logs         │     ├──────────────┤     │ categories   │
├──────────────┤     │ id           │     ├──────────────┤
│ id           │     │ category_id  │────►│ id           │
│ referrer_id  │────►│ name         │     │ name         │
│ referee_id   │────►│ sku          │     │ sort_order   │
│ ref_points   │     │ price        │     └──────────────┘
│ ref_points   │     │ stock        │
│ status       │     │ is_active    │
└──────────────┘     └──────────────┘

┌──────────────┐     ┌──────────────┐
│   settings   │     │ operating_   │
├──────────────┤     │ hours        │
│ key          │     ├──────────────┤
│ value        │     │ day_of_week  │
│ type         │     │ open_time    │
└──────────────┘     │ close_time   │
                     │ is_closed    │
                     └──────────────┘
```

### 4.2 Tabel Utama

| Tabel | Deskripsi | Relasi |
|-------|-----------|--------|
| `users` | Staff/karyawan klinik | hasMany appointments, treatments |
| `customers` | Data pelanggan | hasMany appointments, treatments, packages, transactions |
| `services` | Layanan/treatment | belongsTo category, hasMany appointments |
| `service_categories` | Kategori layanan | hasMany services |
| `appointments` | Jadwal appointment | belongsTo customer, service, staff |
| `treatment_records` | Rekam treatment | belongsTo appointment, customer |
| `transactions` | Transaksi penjualan | belongsTo customer, hasMany items |
| `transaction_items` | Item transaksi | belongsTo transaction, service/package/product |
| `payments` | Pembayaran | belongsTo transaction |
| `packages` | Paket layanan | belongsTo service |
| `customer_packages` | Paket pelanggan | belongsTo customer, package |
| `package_usages` | Penggunaan sesi | belongsTo customer_package |
| `products` | Produk retail | belongsTo category |
| `loyalty_points` | Riwayat poin | belongsTo customer |
| `loyalty_rewards` | Hadiah loyalty | hasMany redemptions |
| `loyalty_redemptions` | Penukaran hadiah | belongsTo customer, reward |
| `referral_logs` | Log referral | belongsTo referrer, referee |
| `settings` | Pengaturan sistem | key-value storage |
| `operating_hours` | Jam operasional | per hari |

---

## 5. Fitur-Fitur Utama

### 5.1 Manajemen Pelanggan

**Komponen:**
- Model: `Customer`
- Controller: `CustomerController`
- Views: `resources/views/customers/`

**Fitur:**
- CRUD pelanggan lengkap
- Profil kulit (tipe kulit, masalah kulit, alergi)
- Riwayat kunjungan dan transaksi
- Statistik pelanggan (total kunjungan, total spending)
- Referral code unik per pelanggan
- Loyalty points dan tier

**Tipe Kulit:**
- Normal, Oily, Dry, Combination, Sensitive

**Masalah Kulit:**
- Acne, Aging, Pigmentation, Dull, Large Pores, Redness, Dehydration, Blackheads

### 5.2 Sistem Appointment

**Komponen:**
- Model: `Appointment`
- Controller: `AppointmentController`
- Service: `AppointmentService`
- Views: `resources/views/appointments/`

**Fitur:**
- Kalender visual dengan FullCalendar
- Cek ketersediaan slot otomatis
- Multi-sumber booking (walk-in, phone, whatsapp, online)
- Status tracking (pending, confirmed, in_progress, completed, cancelled, no_show)
- Assign ke beautician tertentu

**Status Flow:**
```
pending → confirmed → in_progress → completed
    ↓         ↓            ↓
cancelled  cancelled   cancelled
    ↓         ↓            ↓
no_show    no_show     no_show
```

### 5.3 Rekam Treatment

**Komponen:**
- Model: `TreatmentRecord`
- Controller: `TreatmentRecordController`
- Views: `resources/views/treatment-records/`

**Fitur:**
- Foto before/after (multiple photos)
- Catatan treatment
- Rekomendasi follow-up
- Jadwal follow-up
- Export PDF per treatment
- Export PDF riwayat pelanggan

### 5.4 Manajemen Paket

**Komponen:**
- Model: `Package`, `CustomerPackage`, `PackageUsage`
- Controller: `PackageController`, `CustomerPackageController`

**Fitur:**
- Paket berbasis sesi (misal: 10 sesi facial)
- Harga paket vs harga satuan
- Masa berlaku paket
- Tracking penggunaan sesi
- Status: active, completed, expired, cancelled

### 5.5 Transaksi & POS

**Komponen:**
- Model: `Transaction`, `TransactionItem`, `Payment`
- Controller: `TransactionController`
- Views: `resources/views/transactions/`

**Fitur:**
- Multi-item per transaksi (services, packages, products)
- Multiple payment methods (cash, debit, credit, transfer, QRIS)
- Partial payment support
- Diskon (persentase atau nominal)
- Penggunaan poin loyalty
- Auto-generate invoice number
- Cetak invoice

**Tipe Item:**
- Service (layanan satuan)
- Package (pembelian paket)
- Product (produk retail)
- Other (lain-lain)

### 5.6 Program Loyalty

**Komponen:**
- Model: `LoyaltyPoint`, `LoyaltyReward`, `LoyaltyRedemption`
- Controller: `LoyaltyController`, `LoyaltyRewardController`

**Fitur:**
- Earning poin (1 poin per Rp 10.000)
- Tier system (Bronze, Silver, Gold, Platinum)
- Multiplier bonus per tier
- Katalog reward
- Redemption dengan kode unik
- Expiry poin (12 bulan)

**Tier & Multiplier:**
| Tier | Lifetime Points | Multiplier |
|------|-----------------|------------|
| Bronze | 0 - 499 | 1.0x |
| Silver | 500 - 1,999 | 1.2x |
| Gold | 2,000 - 4,999 | 1.5x |
| Platinum | 5,000+ | 2.0x |

### 5.7 Program Referral

**Komponen:**
- Model: `ReferralLog`
- Terkait: `Customer` (referral_code, referred_by_id)

**Fitur:**
- Kode referral unik per pelanggan (REF-XXXXXXXX)
- Bonus poin untuk referrer
- Bonus poin untuk referee
- Minimum transaksi untuk aktivasi
- Tracking status (pending, rewarded, cancelled)

### 5.8 Laporan

**Komponen:**
- Controller: `ReportController`
- Views: `resources/views/reports/`

**Jenis Laporan:**
1. **Revenue Report** - Pendapatan harian/bulanan, metode pembayaran
2. **Customer Report** - Pelanggan baru, top customer, growth
3. **Service Report** - Layanan populer, revenue per layanan
4. **Appointment Report** - Status, source, peak hours
5. **Staff Report** - Performa beautician
6. **Loyalty Report** - Poin earned/redeemed, tier distribution
7. **Product Report** - Sales, stock, low stock alert

**Export:**
- Excel export untuk Revenue dan Customer report

### 5.9 Settings

**Komponen:**
- Model: `Setting`, `OperatingHour`
- Controller: `SettingController`

**Pengaturan:**
- **Clinic**: Nama, alamat, telepon, email
- **Operating Hours**: Jam buka per hari
- **Branding**: Logo, tema warna
- **Loyalty**: Points ratio, expiry, tiers
- **Referral**: Bonus points, minimum transaction

---

## 6. API Mobile

### 6.1 Autentikasi

**Base URL:** `/api/v1`

**Login:**
```http
POST /api/v1/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password",
  "device_name": "iPhone 15"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {...},
    "token": "1|abc123..."
  }
}
```

### 6.2 Endpoint Categories

| Category | Endpoints | Auth Required |
|----------|-----------|---------------|
| Auth | login, logout, profile | Partial |
| Settings | clinic, hours, branding, loyalty, referral | No |
| Customers | CRUD, stats, treatments, packages, appointments | Yes |
| Services | list, detail, categories | Yes |
| Appointments | CRUD, available slots, today, calendar | Yes |
| Treatments | CRUD with photo upload | Yes |
| Packages | list, detail, customer packages | Yes |
| Transactions | CRUD, pay, receipt | Yes |
| Loyalty | points, rewards, redeem | Yes |
| Referral | validate, apply, history | Yes |
| Reports | summary, revenue, services, customers | Yes |

### 6.3 Response Format

**Success:**
```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

**Paginated:**
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

---

## 7. Sistem Autentikasi

### 7.1 Staff Authentication

**Guard:** `web` (default)
**Model:** `User`
**Method:** Email + Password

**Roles:**
| Role | Akses |
|------|-------|
| Owner | Full access |
| Admin | Semua kecuali staff management |
| Beautician | Limited (appointments, treatments) |

### 7.2 Customer Authentication (Portal)

**Guard:** `customer`
**Model:** `Customer`
**Method:** OTP (One-Time Password)

**Flow:**
1. Customer input email/phone
2. System generate OTP (6 digit, expired 10 menit)
3. OTP dikirim via email/SMS
4. Customer verify OTP
5. Session created

### 7.3 API Authentication

**Method:** Laravel Sanctum (Token-based)

**Flow:**
1. POST `/api/v1/login` dengan credentials
2. System return `plainTextToken`
3. Client store token
4. Request dengan header `Authorization: Bearer {token}`

---

## 8. Konfigurasi Bisnis

### 8.1 Business Types

File: `config/business.php`

```php
'types' => [
    'clinic' => [
        'name' => 'Klinik Kecantikan',
        'staff_label' => 'Beautician',
        'service_label' => 'Treatment',
        'features' => [
            'treatment_records' => true,
            'packages' => true,
            'customer_packages' => true,
            'products' => true,
            'loyalty' => true,
            'online_booking' => true,
            'customer_portal' => true,
        ],
        'theme' => [
            'primary_color' => 'pink',
            'primary_hex' => '#ec4899',
        ],
    ],
    // salon, barbershop (planned)
]
```

### 8.2 Feature Flags

Fitur dapat diaktifkan/nonaktifkan per tipe bisnis:

| Feature | Description |
|---------|-------------|
| treatment_records | Rekam treatment dengan foto |
| packages | Manajemen paket layanan |
| customer_packages | Penjualan paket ke customer |
| products | Inventory produk retail |
| loyalty | Program loyalty points |
| online_booking | Booking online publik |
| customer_portal | Portal self-service |

### 8.3 Settings Storage

**Model:** `Setting`
**Cache:** Auto-cached, clear on update

**Key Settings:**
```
business_name      : Nama bisnis
business_address   : Alamat
business_phone     : Telepon
business_email     : Email
clinic_logo        : Path logo
tax_percentage     : Persentase pajak
invoice_prefix     : Prefix invoice
slot_duration      : Durasi slot (menit)
points_per_amount  : Nominal per 1 poin
points_expiry_months : Masa berlaku poin
```

---

## Kesimpulan

GlowUp Clinic adalah aplikasi manajemen klinik kecantikan yang komprehensif dengan arsitektur modern berbasis Laravel 12. Aplikasi ini menerapkan:

- **Clean Architecture** dengan pemisahan layer yang jelas
- **RESTful API** untuk integrasi mobile
- **Role-Based Access Control** untuk keamanan
- **Feature Flags** untuk fleksibilitas konfigurasi
- **Responsive Design** dengan pendekatan desktop-first

Dokumentasi ini mencakup aspek teknis utama yang diperlukan untuk memahami dan mengembangkan aplikasi lebih lanjut.
