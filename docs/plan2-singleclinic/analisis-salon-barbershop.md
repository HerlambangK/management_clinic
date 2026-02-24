# Analisis Ekspansi GlowUp untuk Salon & Barbershop

## Ringkasan Eksekutif

Setelah menganalisis codebase GlowUp secara menyeluruh, **sistem ini SANGAT COCOK** untuk di-expand ke bisnis Salon dan Barbershop dengan modifikasi minimal. Arsitektur yang sudah dibangun sangat generic dan fleksibel.

---

## 1. Analisis Kesesuaian Bisnis

### 1.1 Perbandingan Model Bisnis

| Aspek | Klinik Kecantikan | Salon | Barbershop |
|-------|-------------------|-------|------------|
| **Pelanggan** | Customer dengan profil kulit | Customer dengan profil rambut | Customer dengan profil rambut/jenggot |
| **Layanan** | Treatment kulit, facial | Potong, coloring, treatment | Potong, cukur, styling |
| **Staff** | Beautician | Hairstylist | Barber |
| **Durasi** | 30-120 menit | 15-180 menit | 15-60 menit |
| **Paket** | Paket treatment | Paket coloring/treatment | Paket langganan |
| **Before/After** | Photo kulit | Photo gaya rambut | Photo gaya rambut |
| **Follow-up** | Jadwal kontrol | Jadwal touch-up | Jadwal maintenance |

### 1.2 Fitur yang Sudah Support (Tidak Perlu Diubah)

**100% Compatible:**
- Sistem Appointment/Scheduling
- Transaction & Payment (multi-payment, invoice)
- Package System (session-based)
- Customer Management (basic info)
- Service Categories & Services
- Operating Hours
- Staff Management
- Reports & Dashboard
- Mobile API
- Import Data
- Multi-language (ID/EN)

### 1.3 Fitur yang Perlu Disesuaikan

**Perlu Modifikasi Minor:**
- Customer Profile (skin ’ generic/hair)
- Treatment Records (context-aware)
- Terminology/Labels (beautician ’ stylist/barber)
- Role Names

---

## 2. Gap Analysis Detail

### 2.1 Model Customer

**Current State:**
```php
// Customer.php - Skin-specific fields
'skin_type' => ['normal', 'oily', 'dry', 'combination', 'sensitive']
'skin_concerns' => JSON array (Jerawat, Anti-Aging, Pigmentasi, etc.)
'allergies' => text
```

**Required Changes:**
```php
// Generic profile yang bisa dipakai semua bisnis
'profile_type' => ['skin', 'hair', 'general'] // NEW: tipe profil
'profile_data' => JSON // NEW: dynamic data berdasarkan business type
```

**Atau pendekatan lebih simpel:**
- Rename `skin_type` ’ `profile_type` (tetap pakai untuk hair type)
- Rename `skin_concerns` ’ `concerns` (generic)
- Hair types: normal, oily, dry, damaged, color-treated
- Hair concerns: ketombe, rontok, bercabang, dll

### 2.2 Model User (Staff Roles)

**Current State:**
```php
const ROLES = ['owner', 'admin', 'beautician'];
```

**Required Changes:**
```php
// Option A: Keep generic
const ROLES = ['owner', 'admin', 'staff']; // rename beautician ’ staff

// Option B: Business-specific (configurable via settings)
// Role label configurable: Staff, Beautician, Stylist, Barber
```

### 2.3 Treatment Records

**Current State:**
- `before_photo`, `after_photo` - Generic, bisa dipakai
- `notes` - Generic
- `products_used` - Generic (bisa untuk produk styling)
- `recommendations` - Generic
- `follow_up_date` - Generic

**Assessment:** Model ini sudah cukup generic!

### 2.4 Business Type Configuration

**New Feature Required:**
```php
// Setting: business_type
'business_type' => ['clinic', 'salon', 'barbershop', 'spa']
```

Ini akan menentukan:
- Default terminology
- Profile fields yang ditampilkan
- Sample categories/services saat setup
- Dashboard metrics labels

---

## 3. Perubahan yang Diperlukan

### 3.1 Database Changes

#### Migration 1: Update Customers Table
```php
// Rename columns untuk generalisasi
Schema::table('customers', function (Blueprint $table) {
    // Option A: Rename existing columns
    $table->renameColumn('skin_type', 'profile_type');
    $table->renameColumn('skin_concerns', 'concerns');

    // Option B: Add new generic column, deprecate old
    $table->string('profile_type')->nullable()->after('allergies');
    $table->json('concerns')->nullable()->after('profile_type');
});
```

#### Migration 2: Add Business Type Setting
```php
// Seed default setting
Setting::set('business_type', 'clinic', 'string');
Setting::set('staff_role_label', 'Beautician', 'string');
```

### 3.2 Code Changes Summary

| File | Change | Priority |
|------|--------|----------|
| `config/business.php` | NEW: Business type config | High |
| `Customer.php` | Rename/generalize profile fields | High |
| `User.php` | Make role labels configurable | Medium |
| `CustomerController.php` | Update profile form fields | Medium |
| `views/customers/*` | Dynamic profile labels | Medium |
| `lang/*.json` | Add salon/barbershop terms | Low |
| `seeders/*` | Add sample data per business type | Low |

### 3.3 UI/UX Adjustments

**Form Customer:**
- Profile section labels dari settings/config
- Dropdown options dari config berdasarkan business_type

**Dashboard:**
- "Total Treatments" ’ "Total Services Completed"
- "Top Beautician" ’ "Top Staff" atau sesuai config

---

## 4. Implementation Plan

### Phase A: Foundation (Core Changes)

**A1. Business Type Configuration**
- [ ] Create `config/business.php` untuk business type definitions
- [ ] Add `business_type` setting
- [ ] Create helper `business()` untuk akses config

**A2. Database Generalization**
- [ ] Create migration untuk rename/add customer profile columns
- [ ] Update Customer model
- [ ] Update Customer factory & seeder

**A3. Terminology Abstraction**
- [ ] Add configurable labels ke settings
- [ ] Create Blade directive untuk dynamic labels
- [ ] Update language files

### Phase B: UI Updates

**B1. Customer Profile Form**
- [ ] Dynamic profile fields berdasarkan business type
- [ ] Validation rules per business type
- [ ] Import template per business type

**B2. Staff/Role Labels**
- [ ] Update sidebar menu labels
- [ ] Update dashboard cards
- [ ] Update reports

**B3. Landing Page**
- [ ] Business-type specific hero text
- [ ] Sample services per type

### Phase C: Data & Templates

**C1. Sample Data**
- [ ] Seeder untuk Salon (categories & services)
- [ ] Seeder untuk Barbershop (categories & services)
- [ ] Sample packages per type

**C2. Import Templates**
- [ ] CSV template salon services
- [ ] CSV template barbershop services

### Phase D: Testing & Polish

**D1. Testing**
- [ ] Test business type switching
- [ ] Test customer profile per type
- [ ] Test all CRUD operations

**D2. Documentation**
- [ ] Update user guide
- [ ] Add setup wizard documentation

---

## 5. Detailed Checklist

### 5.1 Phase A: Foundation

#### A1. Config Business Type
```
[ ] Create config/business.php
    [ ] Define business types: clinic, salon, barbershop
    [ ] Define profile fields per type
    [ ] Define role labels per type
    [ ] Define sample categories per type

[ ] Add Settings
    [ ] business_type (default: clinic)
    [ ] staff_role_label (default: Beautician)
    [ ] profile_section_label (default: Profil Kulit)

[ ] Create Helper
    [ ] app/Helpers/business.php
    [ ] business_type() - get current type
    [ ] business_config($key) - get config for current type
    [ ] business_label($key) - get label for current type
```

#### A2. Database Migration
```
[ ] Migration: update_customers_table_generalize_profile
    [ ] Add column: profile_type (nullable, string)
    [ ] Add column: concerns (nullable, json)
    [ ] Keep existing skin_type & skin_concerns for backward compatibility
    [ ] OR rename columns (breaking change)

[ ] Update Customer Model
    [ ] Add profile_type casts
    [ ] Add concerns accessor/mutator
    [ ] Update fillable
    [ ] Add helper method: getProfileFields()

[ ] Update CustomerFactory
    [ ] Dynamic profile generation based on type
```

#### A3. Language Files
```
[ ] Update lang/id.json
    [ ] Add: "Hairstylist", "Barber", "Stylist"
    [ ] Add: "Profil Rambut", "Tipe Rambut", "Kondisi Rambut"
    [ ] Add hair types: "Normal", "Berminyak", "Kering", "Rusak", "Diwarnai"
    [ ] Add hair concerns: "Ketombe", "Rontok", "Bercabang", "Kusam"

[ ] Update lang/en.json
    [ ] Add corresponding English translations
```

### 5.2 Phase B: UI Updates

#### B1. Customer Profile Form
```
[ ] Update views/customers/create.blade.php
    [ ] Dynamic profile section title
    [ ] Dynamic profile fields based on business type
    [ ] Show/hide skin vs hair fields

[ ] Update views/customers/edit.blade.php
    [ ] Same changes as create

[ ] Update views/customers/show.blade.php
    [ ] Dynamic labels for profile display

[ ] Update CustomerController
    [ ] Dynamic validation rules
    [ ] Handle both skin and hair profiles

[ ] Update CustomerRequest (if exists)
    [ ] Dynamic validation based on business type
```

#### B2. Staff & Dashboard Labels
```
[ ] Update views/layouts/sidebar.blade.php
    [ ] Dynamic "Beautician" ’ business_label('staff')

[ ] Update views/dashboard/index.blade.php
    [ ] "Top Beautician" ’ dynamic label
    [ ] "Treatment" ’ "Layanan" (generic)

[ ] Update views/staff/*
    [ ] Page title dynamic
    [ ] Role labels dynamic
```

#### B3. Other Views
```
[ ] Update views/appointments/*
    [ ] "Beautician" field label ’ dynamic

[ ] Update views/treatment-records/*
    [ ] Keep generic (already okay)

[ ] Update views/reports/*
    [ ] Dynamic terminology
```

### 5.3 Phase C: Data & Templates

#### C1. Sample Data Seeders
```
[ ] Create database/seeders/SalonServiceSeeder.php
    Categories:
    - Potong Rambut (Haircut)
    - Coloring & Highlight
    - Treatment Rambut
    - Styling & Blow Dry
    - Creambath & Spa

[ ] Create database/seeders/BarbershopServiceSeeder.php
    Categories:
    - Potong Rambut (Haircut)
    - Cukur & Shaving
    - Treatment
    - Paket Combo

[ ] Create database/seeders/SalonPackageSeeder.php
    - Paket Coloring 3x (hemat 15%)
    - Paket Treatment 5x (hemat 20%)
    - Paket Creambath 10x (hemat 25%)

[ ] Create database/seeders/BarbershopPackageSeeder.php
    - Paket Member Bulanan (4x potong)
    - Paket Grooming 5x
```

#### C2. Import Templates
```
[ ] Create storage/app/templates/import-services-salon.csv
[ ] Create storage/app/templates/import-services-barbershop.csv
[ ] Update ImportController untuk template berdasarkan business type
```

### 5.4 Phase D: Testing

```
[ ] Feature Tests
    [ ] test_business_type_config_loads_correctly
    [ ] test_customer_profile_fields_per_business_type
    [ ] test_customer_create_with_hair_profile
    [ ] test_staff_label_changes_based_on_config
    [ ] test_dashboard_shows_correct_labels

[ ] Manual Testing Checklist
    [ ] Switch business_type to 'salon'
    [ ] Create new customer - verify hair profile fields
    [ ] View customer - verify labels correct
    [ ] Check dashboard terminology
    [ ] Check sidebar labels
    [ ] Run seeders for salon
    [ ] Verify services created correctly
```

---

## 6. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|--------|--------|----------|
| Breaking change pada existing data | High | Gunakan pendekatan additive (add columns, don't rename) |
| UI inconsistency | Medium | Thorough testing pada semua views |
| Performance (config lookup) | Low | Cache business config |
| User confusion | Medium | Clear documentation & setup wizard |

---

## 7. Rekomendasi Implementasi

### Pendekatan yang Disarankan: Additive, Non-Breaking

1. **Jangan rename** column `skin_type` dan `skin_concerns`
2. **Tambah** column baru `profile_type` dan `concerns` (generic)
3. **Buat helper** yang membaca dari column yang sesuai berdasarkan business type
4. **Migrasi bertahap** - existing data tetap berfungsi

### Prioritas Implementasi

**Must Have (Launch):**
- Business type config
- Dynamic labels/terminology
- Customer profile abstraction

**Should Have (Post-Launch):**
- Sample seeders per business type
- Import templates per type
- Setup wizard

**Nice to Have (Future):**
- Multi-business support (satu owner punya clinic + salon)
- Business-specific reports
- Business-specific landing page themes

---

## 8. Kesimpulan

**Verdict: HIGHLY FEASIBLE**

Sistem GlowUp sudah memiliki arsitektur yang sangat baik dan modular. Ekspansi ke Salon dan Barbershop memerlukan:

- **~5-7 file** perlu dimodifikasi
- **~3-5 file** perlu ditambahkan (config, seeders, migrations)
- **0 perubahan** pada core business logic (appointment, transaction, package)
- **Backward compatible** - tidak break existing clinic users

**Effort Estimate:**
- Phase A (Foundation): 1-2 hari
- Phase B (UI): 1-2 hari
- Phase C (Data): 0.5-1 hari
- Phase D (Testing): 0.5-1 hari

**Total: ~3-6 hari development**

---

## 9. Quick Start Checklist (MVP)

Untuk launch cepat dengan fitur minimal:

### Day 1: Config & Database
- [ ] Buat `config/business.php`
- [ ] Add setting `business_type`
- [ ] Create migration add `profile_type`, `concerns` ke customers
- [ ] Update Customer model

### Day 2: UI Labels
- [ ] Update customer form (dynamic fields)
- [ ] Update sidebar labels
- [ ] Update dashboard cards
- [ ] Update lang files

### Day 3: Data & Test
- [ ] Create salon/barbershop seeders
- [ ] Run full test suite
- [ ] Manual testing semua flow

### Day 4: Polish
- [ ] Fix any bugs found
- [ ] Update documentation
- [ ] Deploy!

---

## Appendix A: Config Structure

```php
// config/business.php
return [
    'types' => [
        'clinic' => [
            'name' => 'Klinik Kecantikan',
            'staff_label' => 'Beautician',
            'profile_label' => 'Profil Kulit',
            'profile_fields' => [
                'type' => [
                    'label' => 'Tipe Kulit',
                    'options' => ['normal', 'oily', 'dry', 'combination', 'sensitive'],
                ],
                'concerns' => [
                    'label' => 'Masalah Kulit',
                    'options' => ['acne', 'aging', 'pigmentation', 'dull', 'large_pores', 'redness', 'dehydration', 'oily', 'sensitive', 'blackheads'],
                ],
            ],
        ],
        'salon' => [
            'name' => 'Salon',
            'staff_label' => 'Hairstylist',
            'profile_label' => 'Profil Rambut',
            'profile_fields' => [
                'type' => [
                    'label' => 'Tipe Rambut',
                    'options' => ['normal', 'oily', 'dry', 'damaged', 'color_treated', 'curly', 'straight', 'wavy'],
                ],
                'concerns' => [
                    'label' => 'Masalah Rambut',
                    'options' => ['dandruff', 'hair_loss', 'split_ends', 'dull', 'frizzy', 'oily_scalp', 'dry_scalp', 'thinning'],
                ],
            ],
        ],
        'barbershop' => [
            'name' => 'Barbershop',
            'staff_label' => 'Barber',
            'profile_label' => 'Profil',
            'profile_fields' => [
                'type' => [
                    'label' => 'Tipe Rambut',
                    'options' => ['normal', 'oily', 'dry', 'thick', 'thin', 'curly', 'straight'],
                ],
                'concerns' => [
                    'label' => 'Preferensi',
                    'options' => ['dandruff', 'hair_loss', 'oily_scalp', 'beard_care', 'sensitive_skin'],
                ],
            ],
        ],
    ],
];
```

---

## Appendix B: Sample Salon Categories & Services

| Category | Service | Duration | Price (Rp) |
|----------|---------|----------|------------|
| Potong Rambut | Potong Rambut Wanita | 60 min | 100,000 |
| Potong Rambut | Potong Rambut Pria | 30 min | 50,000 |
| Potong Rambut | Potong Anak | 30 min | 40,000 |
| Coloring | Coloring Full | 120 min | 300,000 |
| Coloring | Highlight | 90 min | 250,000 |
| Coloring | Balayage | 150 min | 500,000 |
| Coloring | Touch Up Roots | 60 min | 150,000 |
| Treatment | Hair Mask | 45 min | 150,000 |
| Treatment | Keratin Treatment | 120 min | 800,000 |
| Treatment | Hair Spa | 60 min | 200,000 |
| Styling | Blow Dry | 30 min | 75,000 |
| Styling | Curling/Straightening | 45 min | 100,000 |
| Styling | Updo/Sanggul | 60 min | 200,000 |
| Creambath | Creambath Regular | 60 min | 100,000 |
| Creambath | Creambath Premium | 75 min | 150,000 |

---

## Appendix C: Sample Barbershop Categories & Services

| Category | Service | Duration | Price (Rp) |
|----------|---------|----------|------------|
| Potong Rambut | Potong Reguler | 30 min | 40,000 |
| Potong Rambut | Potong Premium | 45 min | 60,000 |
| Potong Rambut | Potong Anak | 20 min | 30,000 |
| Potong Rambut | Buzz Cut | 20 min | 35,000 |
| Potong Rambut | Fade Cut | 35 min | 50,000 |
| Cukur | Cukur Jenggot | 15 min | 25,000 |
| Cukur | Cukur Kumis | 10 min | 15,000 |
| Cukur | Hot Towel Shave | 30 min | 50,000 |
| Cukur | Beard Trim & Shape | 20 min | 35,000 |
| Treatment | Hair Wash | 15 min | 20,000 |
| Treatment | Head Massage | 15 min | 30,000 |
| Treatment | Scalp Treatment | 30 min | 75,000 |
| Treatment | Hair Tonic | 10 min | 25,000 |
| Styling | Pomade Styling | 10 min | 15,000 |
| Styling | Hair Color | 60 min | 150,000 |
| Paket | Combo Potong + Cukur | 45 min | 60,000 |
| Paket | Premium Package | 60 min | 100,000 |
| Paket | Grooming Complete | 75 min | 120,000 |

---

*Document Version: 1.0*
*Created: February 2026*
*Author: GlowUp Development Team*
