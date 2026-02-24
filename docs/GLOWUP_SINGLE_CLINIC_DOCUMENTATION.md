# GlowUp - Beauty Clinic Management System
## Complete Documentation: PRD, FSD, Database Design

**Version:** 1.0  
**Last Updated:** January 2026  
**Author:** JagoFlutter Academy  
**Tech Stack:** Laravel 12 + Alpine.js + Tailwind CSS 4

---

# PART 1: PRODUCT REQUIREMENTS DOCUMENT (PRD)

## 1.1 Executive Summary

### Product Vision
GlowUp adalah sistem manajemen klinik kecantikan yang dirancang untuk membantu owner klinik mengelola operasional bisnis secara efisien. Sistem ini mencakup booking management, customer records, treatment history, POS, dan reporting dalam satu platform terintegrasi.

### Target User
- **Primary:** Owner klinik kecantikan skala kecil-menengah (1-10 staff)
- **Secondary:** Admin/resepsionis, beautician/terapis

### Business Model (Freelance)
| Item | Harga |
|------|-------|
| Setup & Installation | Rp 3.000.000 - 5.000.000 |
| Training (2 sesi) | Included |
| Monthly Maintenance | Rp 300.000 - 500.000 |
| Custom Feature | Rp 500.000 - 1.000.000 / request |
| Hosting (VPS) | Rp 100.000 - 200.000 / bulan |

### Key Differentiators
1. **Treatment Record dengan Foto** - Before/after documentation
2. **Skin Profile Management** - Track jenis kulit, alergi, concern
3. **Package Tracking** - Monitor sisa sesi paket
4. **WhatsApp Integration Ready** - Reminder & notification
5. **Simple & Fast** - Optimized for daily clinic operations

---

## 1.2 Problem Statement

### Current Pain Points (Klinik Kecantikan)

| Problem | Impact | GlowUp Solution |
|---------|--------|-----------------|
| Booking via WA manual | Bentrok jadwal, lupa follow-up | Centralized booking system |
| Catatan treatment di kertas | Hilang, susah dicari, tidak profesional | Digital treatment records |
| Tidak ada foto before/after | Sulit buktikan hasil ke customer | Photo gallery per customer |
| Tidak tahu treatment history | Rekomendasi tidak akurat | Complete customer profile |
| Paket treatment tidak tertrack | Lupa sisa sesi, customer komplain | Package management system |
| Laporan keuangan manual | Tidak tahu profit sebenarnya | Automated reports |
| Tidak tahu customer loyal | Miss opportunity upselling | Customer analytics |

### User Quotes (dari riset)
> "Saya sering lupa customer ini sudah pernah treatment apa, alergi apa. Malu kalau harus tanya ulang."

> "Paket 10x facial, customer bilang masih sisa 3, catatan saya bilang sisa 2. Ribut deh."

> "Kompetitor pakai sistem, keliatan lebih profesional. Customer pindah kesana."

---

## 1.3 User Personas

### Persona 1: Owner - Dr. Sarah (35 tahun)
**Background:**
- Owner klinik kecantikan 3 tahun
- 1 dokter, 3 beautician, 1 admin
- Revenue Rp 50-80 juta/bulan

**Goals:**
- Pantau revenue dan performa bisnis
- Tingkatkan customer retention
- Tampil lebih profesional dari kompetitor

**Frustrations:**
- Tidak punya waktu input data manual
- Sulit track performa staff
- Laporan keuangan berantakan

**Tech Savviness:** Medium (pakai smartphone, familiar dengan apps)

---

### Persona 2: Admin - Rina (25 tahun)
**Background:**
- Resepsionis & admin klinik
- Handle booking, checkout, customer service

**Goals:**
- Proses booking cepat tanpa error
- Cari data customer dengan mudah
- Checkout tanpa ribet

**Frustrations:**
- Buku booking penuh coretan
- Susah cari history customer
- Hitung manual sering salah

**Tech Savviness:** High (Gen-Z, cepat adaptasi)

---

### Persona 3: Beautician - Maya (28 tahun)
**Background:**
- Beautician/terapis senior
- Handle 5-8 customer per hari

**Goals:**
- Tahu jadwal hari ini dengan jelas
- Akses history treatment customer sebelum mulai
- Catat hasil treatment dengan mudah

**Frustrations:**
- Tidak tahu customer datang jam berapa
- Lupa concern customer sebelumnya
- Capek nulis catatan manual

**Tech Savviness:** Medium

---

## 1.4 Product Scope

### In Scope (MVP)
| Module | Priority | Description |
|--------|----------|-------------|
| Authentication | P0 | Login, role-based access |
| Dashboard | P0 | Overview stats & quick actions |
| Customer Management | P0 | CRUD, profile, skin data |
| Service Catalog | P0 | Categories, services, pricing |
| Appointment | P0 | Booking, calendar, status |
| Treatment Records | P0 | Notes, photos, recommendations |
| Package Management | P1 | Create, sell, track usage |
| POS / Checkout | P0 | Payment, receipt |
| Reports | P1 | Revenue, services, customers |
| Settings | P1 | Clinic profile, users, config |

### Out of Scope (Future)
- Online booking portal untuk customer
- Mobile app (Flutter)
- WhatsApp API integration
- Inventory management
- Loyalty points system
- Multi-branch support

---

## 1.5 Success Metrics

### Business Metrics
| Metric | Target | Measurement |
|--------|--------|-------------|
| Time to complete booking | < 1 minute | System log |
| Customer lookup time | < 10 seconds | User testing |
| Daily closing time | < 5 minutes | User feedback |
| Data entry errors | < 1% | Error logs |

### User Satisfaction
| Metric | Target |
|--------|--------|
| NPS Score | > 8 |
| Feature adoption rate | > 80% |
| Daily active usage | > 90% staff |

---

## 1.6 User Stories

### Epic 1: Authentication & Authorization

```
US-1.1: Sebagai Admin, saya ingin login dengan email dan password,
        agar saya bisa mengakses sistem dengan aman.
        
        Acceptance Criteria:
        - Login dengan email + password
        - Remember me option
        - Redirect ke dashboard setelah login
        - Show error jika credentials salah
        
US-1.2: Sebagai Owner, saya ingin mengatur role staff,
        agar setiap orang hanya akses fitur yang relevan.
        
        Acceptance Criteria:
        - Role: Owner, Admin, Beautician
        - Owner bisa manage semua
        - Admin bisa manage booking, customer, transaction
        - Beautician hanya lihat jadwal & input treatment record
```

### Epic 2: Dashboard

```
US-2.1: Sebagai Owner, saya ingin melihat ringkasan bisnis hari ini,
        agar saya tahu kondisi klinik dengan cepat.
        
        Acceptance Criteria:
        - Revenue hari ini
        - Jumlah appointment hari ini
        - Appointment yang sedang berjalan
        - Customer baru minggu ini
        
US-2.2: Sebagai Admin, saya ingin melihat appointment hari ini,
        agar saya bisa prepare untuk customer yang akan datang.
        
        Acceptance Criteria:
        - List appointment hari ini
        - Sorted by time
        - Show status (pending, confirmed, in progress, done)
        - Quick action: confirm, start, complete
```

### Epic 3: Customer Management

```
US-3.1: Sebagai Admin, saya ingin menambah customer baru,
        agar data customer tersimpan di sistem.
        
        Acceptance Criteria:
        - Input: nama, telepon (required), email, tanggal lahir, gender
        - Phone number unique validation
        - Auto-format phone number
        
US-3.2: Sebagai Beautician, saya ingin melihat profil kulit customer,
        agar saya bisa memberikan treatment yang tepat.
        
        Acceptance Criteria:
        - Skin type (normal, oily, dry, combination, sensitive)
        - Skin concerns (acne, aging, pigmentation, etc)
        - Allergies
        - Special notes
        
US-3.3: Sebagai Admin, saya ingin mencari customer dengan cepat,
        agar tidak membuang waktu customer.
        
        Acceptance Criteria:
        - Search by name or phone
        - Real-time search (as you type)
        - Show recent customers
```

### Epic 4: Service Catalog

```
US-4.1: Sebagai Owner, saya ingin mengelola daftar treatment,
        agar customer tahu layanan yang tersedia.
        
        Acceptance Criteria:
        - CRUD services
        - Fields: name, category, duration, price, description
        - Active/inactive status
        - Sort by category
        
US-4.2: Sebagai Owner, saya ingin membuat kategori treatment,
        agar layanan terorganisir dengan baik.
        
        Acceptance Criteria:
        - CRUD categories
        - Example: Facial, Body, Hair, Nail
        - Custom icon per category
```

### Epic 5: Appointment

```
US-5.1: Sebagai Admin, saya ingin membuat booking baru,
        agar jadwal customer tercatat di sistem.
        
        Acceptance Criteria:
        - Select/create customer
        - Select service
        - Select date & time
        - Assign beautician (optional)
        - Add notes
        - Check availability
        
US-5.2: Sebagai Admin, saya ingin melihat jadwal dalam calendar view,
        agar mudah melihat slot yang tersedia.
        
        Acceptance Criteria:
        - Daily view (default)
        - Weekly view
        - Color coded by status
        - Click to view detail
        - Drag to reschedule (nice to have)
        
US-5.3: Sebagai Admin, saya ingin mengubah status appointment,
        agar tracking progress jelas.
        
        Acceptance Criteria:
        - Status flow: Pending → Confirmed → In Progress → Completed
        - Cancel with reason
        - No-show marking
```

### Epic 6: Treatment Records

```
US-6.1: Sebagai Beautician, saya ingin mencatat hasil treatment,
        agar ada dokumentasi untuk follow-up.
        
        Acceptance Criteria:
        - Linked to appointment
        - Treatment notes
        - Products used
        - Recommendations
        - Follow-up date suggestion
        
US-6.2: Sebagai Beautician, saya ingin upload foto before/after,
        agar customer bisa lihat progress.
        
        Acceptance Criteria:
        - Upload before photo
        - Upload after photo
        - Auto-compress images
        - Gallery view per customer
        
US-6.3: Sebagai Owner, saya ingin melihat history treatment customer,
        agar bisa review kualitas layanan.
        
        Acceptance Criteria:
        - Timeline view
        - Filter by date range
        - Filter by service type
        - Export to PDF (nice to have)
```

### Epic 7: Package Management

```
US-7.1: Sebagai Owner, saya ingin membuat paket treatment,
        agar customer tertarik beli bundle.
        
        Acceptance Criteria:
        - Package name & description
        - Select services included
        - Total sessions
        - Package price (discounted)
        - Validity period (days)
        
US-7.2: Sebagai Admin, saya ingin menjual paket ke customer,
        agar tercatat kepemilikan paketnya.
        
        Acceptance Criteria:
        - Select customer
        - Select package
        - Set purchase date
        - Auto-calculate expiry date
        - Create transaction
        
US-7.3: Sebagai Admin, saya ingin redeem sesi dari paket,
        agar sisa sesi terupdate otomatis.
        
        Acceptance Criteria:
        - Select customer's active package
        - Redeem 1 session
        - Update remaining sessions
        - Link to appointment
        - Alert if package expired or depleted
```

### Epic 8: POS / Checkout

```
US-8.1: Sebagai Admin, saya ingin checkout appointment yang selesai,
        agar pembayaran tercatat.
        
        Acceptance Criteria:
        - Auto-load services from appointment
        - Add additional items
        - Apply discount (amount or percentage)
        - Calculate total
        
US-8.2: Sebagai Admin, saya ingin memproses pembayaran,
        agar transaksi complete.
        
        Acceptance Criteria:
        - Payment methods: Cash, QRIS, Transfer, Card
        - Split payment support
        - Generate invoice number
        - Mark as paid
        
US-8.3: Sebagai Admin, saya ingin mencetak/kirim struk,
        agar customer punya bukti pembayaran.
        
        Acceptance Criteria:
        - Print receipt (thermal printer)
        - Digital receipt (WhatsApp/Email)
        - Receipt includes: clinic info, items, total, payment method
```

### Epic 9: Reports

```
US-9.1: Sebagai Owner, saya ingin melihat laporan revenue,
        agar tahu performa keuangan.
        
        Acceptance Criteria:
        - Daily, weekly, monthly view
        - Revenue chart
        - Compare with previous period
        - Breakdown by payment method
        
US-9.2: Sebagai Owner, saya ingin melihat laporan services,
        agar tahu treatment mana yang populer.
        
        Acceptance Criteria:
        - Most booked services
        - Revenue per service
        - Trend over time
        
US-9.3: Sebagai Owner, saya ingin melihat laporan customer,
        agar tahu pertumbuhan dan retention.
        
        Acceptance Criteria:
        - New customers per period
        - Returning customers
        - Top spenders
        - Inactive customers (>30 days)
```

### Epic 10: Settings

```
US-10.1: Sebagai Owner, saya ingin mengatur profil klinik,
         agar informasi tampil di struk dan laporan.
         
         Acceptance Criteria:
         - Clinic name, address, phone, email
         - Logo upload
         - Operating hours
         
US-10.2: Sebagai Owner, saya ingin mengelola user/staff,
         agar setiap orang punya akun sendiri.
         
         Acceptance Criteria:
         - CRUD users
         - Assign role
         - Active/inactive status
         - Reset password
```

---

## 1.7 Release Plan

### Phase 1: MVP (Week 1-2)
- Authentication
- Dashboard (basic)
- Customer Management
- Service Catalog
- Appointment (basic)

### Phase 2: Core Features (Week 3-4)
- Treatment Records + Photos
- Package Management
- POS / Checkout
- Reports (basic)

### Phase 3: Polish (Week 5)
- Settings
- UI/UX improvements
- Testing & bug fixes
- Documentation

### Phase 4: Deployment
- Server setup
- Deployment
- Training
- Go-live support

---

# PART 2: FUNCTIONAL SPECIFICATION DOCUMENT (FSD)

## 2.1 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                         │
├─────────────────────────────────────────────────────────────┤
│  Browser (Chrome/Safari/Firefox)                            │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │  Dashboard  │  │   Forms     │  │   Reports   │         │
│  │  (Alpine.js)│  │ (Alpine.js) │  │ (Chart.js)  │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     APPLICATION LAYER                       │
├─────────────────────────────────────────────────────────────┤
│  Laravel 12                                                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Routes (web.php)                                     │  │
│  │  ├── Auth Routes                                      │  │
│  │  ├── Dashboard Routes                                 │  │
│  │  ├── Resource Routes (CRUD)                          │  │
│  │  └── API Routes (AJAX)                               │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Controllers                                          │  │
│  │  ├── AuthController                                   │  │
│  │  ├── DashboardController                              │  │
│  │  ├── CustomerController                               │  │
│  │  ├── ServiceController                                │  │
│  │  ├── AppointmentController                            │  │
│  │  ├── TreatmentRecordController                        │  │
│  │  ├── PackageController                                │  │
│  │  ├── TransactionController                            │  │
│  │  ├── ReportController                                 │  │
│  │  └── SettingController                                │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Services                                             │  │
│  │  ├── AppointmentService                               │  │
│  │  ├── PackageService                                   │  │
│  │  ├── TransactionService                               │  │
│  │  └── ReportService                                    │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Models (Eloquent)                                    │  │
│  │  ├── User                                             │  │
│  │  ├── Customer                                         │  │
│  │  ├── ServiceCategory                                  │  │
│  │  ├── Service                                          │  │
│  │  ├── Appointment                                      │  │
│  │  ├── TreatmentRecord                                  │  │
│  │  ├── Package                                          │  │
│  │  ├── CustomerPackage                                  │  │
│  │  ├── Transaction                                      │  │
│  │  ├── TransactionItem                                  │  │
│  │  └── Setting                                          │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       DATA LAYER                            │
├─────────────────────────────────────────────────────────────┤
│  MySQL 8.0                                                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Database: glowup_clinic                              │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
│  File Storage (Local/S3)                                    │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  /storage/app/public/                                 │  │
│  │  ├── logos/                                           │  │
│  │  ├── services/                                        │  │
│  │  ├── treatments/                                      │  │
│  │  │   ├── before/                                      │  │
│  │  │   └── after/                                       │  │
│  │  └── customers/                                       │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 2.2 Module Specifications

### 2.2.1 Authentication Module

#### Login Page
**URL:** `/login`  
**Method:** GET (form), POST (submit)

**UI Components:**
```
┌────────────────────────────────────────┐
│              GlowUp Logo               │
│                                        │
│  ┌──────────────────────────────────┐  │
│  │ Email                            │  │
│  └──────────────────────────────────┘  │
│  ┌──────────────────────────────────┐  │
│  │ Password                    👁️   │  │
│  └──────────────────────────────────┘  │
│  ☐ Remember me                         │
│                                        │
│  ┌──────────────────────────────────┐  │
│  │           LOGIN                  │  │
│  └──────────────────────────────────┘  │
│                                        │
│         Forgot password?               │
└────────────────────────────────────────┘
```

**Validation Rules:**
- Email: required, valid email format
- Password: required, min 8 characters

**Business Logic:**
1. Validate credentials
2. Check user is_active status
3. Create session
4. Redirect to dashboard based on role

**Error Handling:**
- Invalid credentials: "Email atau password salah"
- Inactive user: "Akun Anda tidak aktif. Hubungi admin."

---

### 2.2.2 Dashboard Module

#### Main Dashboard
**URL:** `/dashboard`  
**Method:** GET  
**Access:** All authenticated users

**UI Layout:**
```
┌─────────────────────────────────────────────────────────────────┐
│ SIDEBAR │                    HEADER                             │
│         │  Search...                      [+ New Booking] 🔔 👤  │
├─────────┼───────────────────────────────────────────────────────┤
│ □ Dash  │                                                       │
│ □ Appt  │   ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐    │
│ □ Cust  │   │Revenue  │ │Appt     │ │New Cust │ │Completed│    │
│ □ Serv  │   │Today    │ │Today    │ │This Week│ │Today    │    │
│ □ Pkg   │   │Rp 3.2Jt │ │12       │ │28       │ │4        │    │
│ □ Trans │   └─────────┘ └─────────┘ └─────────┘ └─────────┘    │
│ □ Staff │                                                       │
│ □ Report│   ┌───────────────────────┐ ┌─────────────────────┐  │
│ ─────── │   │                       │ │ Popular Services    │  │
│ □ Setting   │   Revenue Chart       │ │ 1. Facial    Rp8.5Jt│  │
│         │   │   (Bar Chart)         │ │ 2. Laser    Rp12.4Jt│  │
│         │   │                       │ │ 3. Botox     Rp9.8Jt│  │
│         │   └───────────────────────┘ └─────────────────────┘  │
│         │                                                       │
│         │   ┌───────────────────────────────────────────────┐  │
│         │   │ Today's Appointments                          │  │
│         │   │ Time    │ Customer │ Service │ Staff │ Status │  │
│         │   │ 09:00   │ Rina     │ Facial  │ Maya  │ Done   │  │
│         │   │ 10:30   │ Siti     │ Laser   │ Dr.S  │ InProg │  │
│         │   └───────────────────────────────────────────────┘  │
└─────────┴───────────────────────────────────────────────────────┘
```

**Data Requirements:**
```php
// DashboardController@index
return view('dashboard.index', [
    'revenueToday' => $this->getRevenueToday(),
    'appointmentsToday' => $this->getAppointmentsToday(),
    'newCustomersThisWeek' => $this->getNewCustomersThisWeek(),
    'completedToday' => $this->getCompletedToday(),
    'revenueChart' => $this->getRevenueChart(), // last 12 months
    'popularServices' => $this->getPopularServices(5),
    'todayAppointments' => $this->getTodayAppointments(),
]);
```

---

### 2.2.3 Customer Module

#### Customer List
**URL:** `/customers`  
**Method:** GET

**UI Components:**
```
┌─────────────────────────────────────────────────────────────┐
│ Customers                                    [+ Add Customer]│
├─────────────────────────────────────────────────────────────┤
│ 🔍 Search by name or phone...          Filter: [All ▼]      │
├─────────────────────────────────────────────────────────────┤
│ Photo │ Name          │ Phone        │ Last Visit │ Actions │
│ ───────────────────────────────────────────────────────────│
│  👤   │ Rina Wijaya   │ 0812-3456... │ 2 days ago │ 👁️ ✏️ 🗑️│
│  👤   │ Siti Aminah   │ 0813-9876... │ 1 week ago │ 👁️ ✏️ 🗑️│
│  👤   │ Dewi Kartika  │ 0815-1234... │ Today      │ 👁️ ✏️ 🗑️│
├─────────────────────────────────────────────────────────────┤
│ Showing 1-10 of 156 customers              < 1 2 3 4 5 >    │
└─────────────────────────────────────────────────────────────┘
```

**Features:**
- Real-time search (debounced 300ms)
- Pagination (10 per page)
- Sort by: name, created_at, last_visit
- Filter by: membership type

---

#### Customer Form (Create/Edit)
**URL:** `/customers/create`, `/customers/{id}/edit`

**Form Fields:**
```
┌─────────────────────────────────────────────────────────────┐
│ Add New Customer                                     [Save] │
├─────────────────────────────────────────────────────────────┤
│ BASIC INFORMATION                                           │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Name *              │  │ Phone *             │           │
│ └─────────────────────┘  └─────────────────────┘           │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Email               │  │ Birthdate           │           │
│ └─────────────────────┘  └─────────────────────┘           │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Gender [Select ▼]   │  │ Address             │           │
│ └─────────────────────┘  └─────────────────────┘           │
│                                                             │
│ SKIN PROFILE                                                │
│ ┌─────────────────────┐                                    │
│ │ Skin Type [Select ▼]│  ○ Normal ○ Oily ○ Dry            │
│ └─────────────────────┘  ○ Combination ○ Sensitive         │
│                                                             │
│ Skin Concerns (multiple select):                            │
│ ☐ Acne  ☐ Aging  ☐ Pigmentation  ☐ Dull                   │
│ ☐ Large Pores  ☐ Redness  ☐ Dehydration                   │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Allergies                                               ││
│ │ e.g., AHA, retinol, certain fragrances                  ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Notes                                                   ││
│ │ Additional information about this customer              ││
│ └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

**Validation:**
```php
[
    'name' => 'required|string|max:255',
    'phone' => 'required|string|max:20|unique:customers,phone',
    'email' => 'nullable|email|max:255',
    'birthdate' => 'nullable|date|before:today',
    'gender' => 'nullable|in:male,female,other',
    'address' => 'nullable|string|max:500',
    'skin_type' => 'nullable|in:normal,oily,dry,combination,sensitive',
    'skin_concerns' => 'nullable|array',
    'allergies' => 'nullable|string|max:500',
    'notes' => 'nullable|string|max:1000',
]
```

---

#### Customer Detail
**URL:** `/customers/{id}`

**UI Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│ ← Back to Customers                            [Edit] [🗑️] │
├─────────────────────────────────────────────────────────────┤
│ ┌─────────┐                                                 │
│ │  Photo  │  RINA WIJAYA                                   │
│ │   👤    │  📱 0812-3456-7890  ✉️ rina@email.com          │
│ └─────────┘  🎂 15 Maret 1990 (34 tahun)                    │
│              📍 Jl. Sudirman No. 123, Jakarta               │
├─────────────────────────────────────────────────────────────┤
│ [Overview] [Treatment History] [Packages] [Photos]          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ SKIN PROFILE                    STATISTICS                  │
│ ┌───────────────────┐          ┌───────────────────┐       │
│ │ Type: Combination │          │ Total Visits: 24  │       │
│ │ Concerns:         │          │ Total Spent:      │       │
│ │ • Acne           │          │ Rp 12.500.000     │       │
│ │ • Large Pores    │          │ Last Visit:       │       │
│ │ Allergies: AHA   │          │ 2 days ago        │       │
│ └───────────────────┘          │ Member Since:     │       │
│                                │ Jan 2024          │       │
│                                └───────────────────┘       │
│                                                             │
│ ACTIVE PACKAGES                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Facial Glow Package          Remaining: 4/10 sessions   ││
│ │ Expires: 15 Mar 2026                         [Redeem]   ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ RECENT TREATMENTS                                           │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 24 Jan 2026 - Facial Brightening                       ││
│ │ By: Maya | Products: Serum C, Moisturizer             ││
│ │ Notes: Kulit respond well, lanjut minggu depan        ││
│ │                                           [View Detail]││
│ └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

---

### 2.2.4 Appointment Module

#### Calendar View
**URL:** `/appointments`

**UI Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│ Appointments                                 [+ New Booking] │
├─────────────────────────────────────────────────────────────┤
│ [Day] [Week] [Month]           < January 26, 2026 >         │
├─────────────────────────────────────────────────────────────┤
│ Time    │ Maya            │ Dr. Sarah       │ Beautician 3  │
│─────────┼─────────────────┼─────────────────┼───────────────│
│ 08:00   │                 │                 │               │
│ 09:00   │ ████████████    │                 │ ███████████   │
│         │ Rina - Facial   │                 │ Dewi - Facial │
│ 10:00   │ ████████████    │ ████████████    │               │
│         │ (continued)     │ Siti - Laser    │               │
│ 11:00   │                 │ ████████████    │               │
│         │                 │ (continued)     │               │
│ 12:00   │ ─ BREAK ─       │ ─ BREAK ─       │ ─ BREAK ─     │
│ 13:00   │ ████████████    │                 │ ███████████   │
│         │ Anisa - Peeling │                 │ Lisa - Facial │
│ 14:00   │                 │ ████████████    │               │
│         │                 │ Wati - Botox    │               │
└─────────┴─────────────────┴─────────────────┴───────────────┘

Legend: 
🟢 Completed  🔵 In Progress  🟡 Confirmed  ⚪ Pending  🔴 Cancelled
```

---

#### Appointment Form
**URL:** `/appointments/create`

**Form Flow:**
```
Step 1: Select Customer
┌─────────────────────────────────────────────────────────────┐
│ 🔍 Search customer...                                       │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Recent Customers:                                       ││
│ │ • Rina Wijaya (0812-3456-7890)                         ││
│ │ • Siti Aminah (0813-9876-5432)                         ││
│ │ • Dewi Kartika (0815-1234-5678)                        ││
│ └─────────────────────────────────────────────────────────┘│
│ Or: [+ Add New Customer]                                    │
└─────────────────────────────────────────────────────────────┘

Step 2: Select Service
┌─────────────────────────────────────────────────────────────┐
│ Select Service                                              │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ FACIAL                                                  ││
│ │ ○ Facial Brightening      60 min    Rp 250.000         ││
│ │ ○ Facial Acne Treatment   90 min    Rp 350.000         ││
│ │ ○ Facial Anti Aging       75 min    Rp 400.000         ││
│ │                                                         ││
│ │ BODY                                                    ││
│ │ ○ Body Scrub              60 min    Rp 300.000         ││
│ │ ○ Body Massage            90 min    Rp 400.000         ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Or redeem from package:                                     │
│ ☐ Use Facial Glow Package (6 sessions remaining)           │
└─────────────────────────────────────────────────────────────┘

Step 3: Select Date & Time
┌─────────────────────────────────────────────────────────────┐
│ Select Date & Time                                          │
│                                                             │
│ Date: [📅 January 27, 2026        ▼]                       │
│                                                             │
│ Available Slots:                                            │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ MORNING                                                 ││
│ │ ○ 09:00  ○ 09:30  ● 10:00  ○ 10:30  ○ 11:00           ││
│ │                                                         ││
│ │ AFTERNOON                                               ││
│ │ ○ 13:00  ○ 13:30  ○ 14:00  ○ 14:30  ○ 15:00           ││
│ │ ○ 15:30  ○ 16:00  ○ 16:30                              ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Beautician: [Maya ▼] (Optional)                            │
└─────────────────────────────────────────────────────────────┘

Step 4: Confirmation
┌─────────────────────────────────────────────────────────────┐
│ Confirm Booking                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Customer:  Rina Wijaya                                  ││
│ │ Service:   Facial Brightening                           ││
│ │ Date:      27 January 2026                              ││
│ │ Time:      10:00 - 11:00                                ││
│ │ Beautician: Maya                                        ││
│ │ Price:     Rp 250.000                                   ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Notes:                                                      │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ e.g., Customer request extra masker                    ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│                              [Cancel]  [Confirm Booking]    │
└─────────────────────────────────────────────────────────────┘
```

---

### 2.2.5 Treatment Record Module

#### Create Treatment Record
**URL:** `/appointments/{id}/treatment-record`

**Form Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│ Treatment Record                                     [Save] │
│ Appointment: Rina Wijaya - Facial Brightening               │
│ Date: 26 Jan 2026 | Time: 10:00                            │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ BEFORE & AFTER PHOTOS                                       │
│ ┌──────────────────┐    ┌──────────────────┐               │
│ │                  │    │                  │               │
│ │  📷 Before       │    │  📷 After        │               │
│ │  [Upload Photo]  │    │  [Upload Photo]  │               │
│ │                  │    │                  │               │
│ └──────────────────┘    └──────────────────┘               │
│                                                             │
│ TREATMENT NOTES                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Kondisi kulit saat datang:                              ││
│ │ - Ada beberapa jerawat kecil di area dagu               ││
│ │ - Kulit cenderung berminyak di T-zone                   ││
│ │                                                         ││
│ │ Treatment yang dilakukan:                               ││
│ │ - Double cleansing                                      ││
│ │ - Eksfoliasi ringan                                     ││
│ │ - Extraction comedone                                   ││
│ │ - Serum Vitamin C                                       ││
│ │ - Masker brightening                                    ││
│ │ - Moisturizer + Sunscreen                               ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ PRODUCTS USED                                               │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ + Add Product                                           ││
│ │ • Cleanser Oil - Brand A                               ││
│ │ • Cleanser Foam - Brand A                              ││
│ │ • Serum Vitamin C 15% - Brand B                        ││
│ │ • Sheet Mask Brightening - Brand C                     ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ RECOMMENDATIONS                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ - Gunakan sunscreen setiap hari                        ││
│ │ - Hindari produk dengan alkohol                        ││
│ │ - Lanjutkan treatment 2 minggu lagi                    ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ FOLLOW UP                                                   │
│ Suggested next visit: [📅 9 Feb 2026          ]            │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

### 2.2.6 POS / Checkout Module

#### Checkout Flow
**URL:** `/transactions/create?appointment_id={id}`

**UI Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│ Checkout                                                    │
├─────────────────────────────────────────────────────────────┤
│ Customer: Rina Wijaya                                       │
│ Appointment: 26 Jan 2026, 10:00                            │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ITEMS                                                       │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Item                      Qty    Price        Subtotal  ││
│ │─────────────────────────────────────────────────────────││
│ │ Facial Brightening        1      Rp 250.000  Rp 250.000 ││
│ │                                              [Remove]   ││
│ │─────────────────────────────────────────────────────────││
│ │ [+ Add Service]  [+ Add Product]  [+ Redeem Package]   ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ DISCOUNT                                                    │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ ○ Percentage  ○ Fixed Amount                           ││
│ │ Value: [10    ] %                     = Rp 25.000      ││
│ │ Reason: [Member discount           ]                   ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ SUMMARY                                                     │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Subtotal:                              Rp 250.000       ││
│ │ Discount (10%):                       -Rp  25.000       ││
│ │ ─────────────────────────────────────────────────       ││
│ │ TOTAL:                                 Rp 225.000       ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ PAYMENT                                                     │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Method: ○ Cash  ○ QRIS  ○ Transfer  ○ Card            ││
│ │                                                         ││
│ │ [Cash Selected]                                        ││
│ │ Amount Received: [Rp 300.000    ]                      ││
│ │ Change:          Rp 75.000                             ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│                    [Cancel]  [Complete & Print Receipt]     │
└─────────────────────────────────────────────────────────────┘
```

---

#### Receipt Template
```
┌─────────────────────────────────────────┐
│           GLOW AESTHETIC CLINIC         │
│      Jl. Sudirman No. 123, Jakarta      │
│          Tel: 021-1234-5678             │
├─────────────────────────────────────────┤
│ Invoice: INV-20260126-001               │
│ Date: 26 Jan 2026 10:45                 │
│ Cashier: Admin                          │
│ Customer: Rina Wijaya                   │
├─────────────────────────────────────────┤
│ Item                         Subtotal   │
│─────────────────────────────────────────│
│ Facial Brightening                      │
│ 1 x Rp 250.000              Rp 250.000  │
├─────────────────────────────────────────┤
│ Subtotal                    Rp 250.000  │
│ Discount (10%)             -Rp  25.000  │
│─────────────────────────────────────────│
│ TOTAL                       Rp 225.000  │
├─────────────────────────────────────────┤
│ Payment: Cash               Rp 300.000  │
│ Change                      Rp  75.000  │
├─────────────────────────────────────────┤
│                                         │
│      Terima kasih atas kunjungan        │
│           Anda. Sampai jumpa!           │
│                                         │
└─────────────────────────────────────────┘
```

---

### 2.2.7 Reports Module

#### Revenue Report
**URL:** `/reports/revenue`

**UI Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│ Revenue Report                              [Export PDF/Excel]│
├─────────────────────────────────────────────────────────────┤
│ Period: [This Month ▼]  From: [01/01/2026] To: [31/01/2026]│
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ SUMMARY                                                     │
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐            │
│ │ Total       │ │ Transactions│ │ Avg/Trans   │            │
│ │ Revenue     │ │ Count       │ │             │            │
│ │ Rp 47.5 Jt  │ │ 156         │ │ Rp 304.487  │            │
│ │ +23% ▲      │ │ +12% ▲      │ │ +8% ▲       │            │
│ └─────────────┘ └─────────────┘ └─────────────┘            │
│                                                             │
│ REVENUE TREND                                               │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ [Line Chart - Daily Revenue for Selected Period]        ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ BY PAYMENT METHOD                  BY SERVICE CATEGORY      │
│ ┌───────────────────────┐        ┌───────────────────────┐ │
│ │ Cash      Rp 20.3 Jt │        │ Facial    Rp 25.1 Jt │ │
│ │ QRIS      Rp 15.2 Jt │        │ Body      Rp 12.4 Jt │ │
│ │ Transfer  Rp 10.5 Jt │        │ Laser     Rp  8.2 Jt │ │
│ │ Card      Rp  1.5 Jt │        │ Other     Rp  1.8 Jt │ │
│ └───────────────────────┘        └───────────────────────┘ │
│                                                             │
│ DAILY BREAKDOWN                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Date       │ Transactions │ Revenue      │ vs Prev Day ││
│ │────────────┼──────────────┼──────────────┼─────────────││
│ │ 26 Jan     │ 8            │ Rp 2.450.000 │ +15% ▲      ││
│ │ 25 Jan     │ 6            │ Rp 2.130.000 │ -8% ▼       ││
│ │ 24 Jan     │ 7            │ Rp 2.320.000 │ +5% ▲       ││
│ └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

---

## 2.3 API Specifications (Internal AJAX)

### Customer APIs

```
GET    /api/customers
       Query: ?search=keyword&page=1&per_page=10
       Response: { data: [...], meta: { total, per_page, current_page } }

GET    /api/customers/{id}
       Response: { data: { id, name, phone, ... } }

POST   /api/customers
       Body: { name, phone, email, ... }
       Response: { data: { id, ... }, message: "Customer created" }

PUT    /api/customers/{id}
       Body: { name, phone, email, ... }
       Response: { data: { id, ... }, message: "Customer updated" }

DELETE /api/customers/{id}
       Response: { message: "Customer deleted" }

GET    /api/customers/{id}/treatment-history
       Response: { data: [...] }

GET    /api/customers/{id}/packages
       Response: { data: [...] }
```

### Appointment APIs

```
GET    /api/appointments
       Query: ?date=2026-01-26&staff_id=1&status=confirmed
       Response: { data: [...] }

GET    /api/appointments/calendar
       Query: ?start_date=2026-01-01&end_date=2026-01-31
       Response: { data: [...] }

GET    /api/appointments/available-slots
       Query: ?date=2026-01-26&service_id=1&staff_id=1
       Response: { data: ["09:00", "09:30", "10:00", ...] }

POST   /api/appointments
       Body: { customer_id, service_id, date, start_time, staff_id, notes }
       Response: { data: { id, ... }, message: "Appointment created" }

PUT    /api/appointments/{id}/status
       Body: { status: "confirmed|in_progress|completed|cancelled" }
       Response: { data: { id, status, ... } }
```

### Transaction APIs

```
POST   /api/transactions
       Body: { 
           customer_id, 
           appointment_id,
           items: [{ type, item_id, quantity, price }],
           discount_type,
           discount_value,
           payment_method,
           amount_paid,
           notes
       }
       Response: { data: { id, invoice_number, ... } }

GET    /api/transactions/{id}/receipt
       Response: { data: { receipt_html } }
```

### Dashboard APIs

```
GET    /api/dashboard/stats
       Query: ?date=2026-01-26
       Response: { 
           revenue_today, 
           appointments_today, 
           new_customers_week,
           completed_today 
       }

GET    /api/dashboard/revenue-chart
       Query: ?period=year|month|week
       Response: { labels: [...], data: [...] }

GET    /api/dashboard/popular-services
       Query: ?limit=5&period=month
       Response: { data: [...] }
```

---

# PART 3: DATABASE DESIGN

## 3.1 Entity Relationship Diagram (ERD)

```
┌─────────────┐       ┌─────────────────┐       ┌─────────────┐
│   users     │       │   customers     │       │  services   │
├─────────────┤       ├─────────────────┤       ├─────────────┤
│ id          │       │ id              │       │ id          │
│ name        │       │ name            │       │ category_id │──┐
│ email       │       │ phone           │       │ name        │  │
│ password    │       │ email           │       │ duration    │  │
│ role        │       │ birthdate       │       │ price       │  │
│ phone       │       │ gender          │       │ description │  │
│ avatar      │       │ address         │       │ image       │  │
│ is_active   │       │ skin_type       │       │ is_active   │  │
└─────────────┘       │ skin_concerns   │       └─────────────┘  │
      │               │ allergies       │             │          │
      │               │ notes           │             │          │
      │               │ membership_type │             │          │
      │               │ total_spent     │             │    ┌─────┴──────────┐
      │               │ total_visits    │             │    │service_categories
      │               └─────────────────┘             │    ├────────────────┤
      │                      │                        │    │ id             │
      │                      │                        │    │ name           │
      │                      │                        │    │ description    │
      │         ┌────────────┴────────────┐          │    │ icon           │
      │         │                         │          │    │ sort_order     │
      │         ▼                         ▼          │    └────────────────┘
      │   ┌───────────────┐      ┌────────────────┐  │
      │   │ appointments  │      │customer_packages│  │
      │   ├───────────────┤      ├────────────────┤  │
      │   │ id            │      │ id             │  │
      └──▶│ staff_id      │      │ customer_id    │◀─┼───────────┐
          │ customer_id   │◀─────│ package_id     │  │           │
          │ service_id    │◀─────│ sessions_total │  │           │
          │ date          │      │ sessions_used  │  │           │
          │ start_time    │      │ sessions_remain│  │     ┌─────┴─────┐
          │ end_time      │      │ purchased_at   │  │     │ packages  │
          │ status        │      │ expires_at     │  │     ├───────────┤
          │ source        │      │ status         │  │     │ id        │
          │ notes         │      └────────────────┘  │     │ name      │
          └───────────────┘                          │     │ description
                │                                    │     │ services  │
                │                                    │     │ sessions  │
                ▼                                    │     │ price     │
      ┌──────────────────┐                          │     │ validity  │
      │treatment_records │                          │     │ is_active │
      ├──────────────────┤                          │     └───────────┘
      │ id               │                          │
      │ appointment_id   │◀─────────────────────────┘
      │ customer_id      │
      │ staff_id         │
      │ notes            │
      │ products_used    │
      │ before_photo     │
      │ after_photo      │
      │ recommendations  │
      │ follow_up_date   │
      └──────────────────┘
                │
                │
                ▼
      ┌──────────────────┐       ┌───────────────────┐
      │  transactions    │       │ transaction_items │
      ├──────────────────┤       ├───────────────────┤
      │ id               │       │ id                │
      │ customer_id      │       │ transaction_id    │◀──┐
      │ appointment_id   │──────▶│ item_type         │   │
      │ invoice_number   │       │ item_id           │   │
      │ subtotal         │       │ item_name         │   │
      │ discount         │       │ quantity          │   │
      │ discount_type    │       │ unit_price        │   │
      │ tax              │       │ subtotal          │   │
      │ total            │       └───────────────────┘   │
      │ payment_method   │                               │
      │ payment_status   │                               │
      │ amount_paid      │                               │
      │ change           │                               │
      │ paid_at          │                               │
      │ notes            │                               │
      │ created_by       │───────────────────────────────┘
      └──────────────────┘

      ┌──────────────────┐       ┌───────────────────┐
      │    settings      │       │  operating_hours  │
      ├──────────────────┤       ├───────────────────┤
      │ id               │       │ id                │
      │ key              │       │ day_of_week       │
      │ value            │       │ open_time         │
      │ group            │       │ close_time        │
      └──────────────────┘       │ is_closed         │
                                 └───────────────────┘
```

---

## 3.2 Table Definitions

### 3.2.1 users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('owner', 'admin', 'beautician') NOT NULL DEFAULT 'admin',
    phone VARCHAR(20) NULL,
    avatar VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_users_email (email),
    INDEX idx_users_role (role),
    INDEX idx_users_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.2 customers
```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NULL,
    birthdate DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    address TEXT NULL,
    skin_type ENUM('normal', 'oily', 'dry', 'combination', 'sensitive') NULL,
    skin_concerns JSON NULL COMMENT 'Array of concerns: acne, aging, pigmentation, etc',
    allergies TEXT NULL,
    notes TEXT NULL,
    membership_type ENUM('regular', 'silver', 'gold', 'platinum') NOT NULL DEFAULT 'regular',
    total_spent DECIMAL(14,2) NOT NULL DEFAULT 0,
    total_visits INT UNSIGNED NOT NULL DEFAULT 0,
    last_visit_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE INDEX idx_customers_phone (phone),
    INDEX idx_customers_name (name),
    INDEX idx_customers_membership (membership_type),
    INDEX idx_customers_last_visit (last_visit_at),
    FULLTEXT INDEX ft_customers_search (name, phone, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.3 service_categories
```sql
CREATE TABLE service_categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    icon VARCHAR(50) NULL COMMENT 'Icon name or emoji',
    sort_order INT NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_categories_sort (sort_order),
    INDEX idx_categories_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.4 services
```sql
CREATE TABLE services (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    duration_minutes INT UNSIGNED NOT NULL DEFAULT 60,
    price DECIMAL(12,2) NOT NULL,
    image VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL,
    INDEX idx_services_category (category_id),
    INDEX idx_services_active (is_active),
    INDEX idx_services_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.5 appointments
```sql
CREATE TABLE appointments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    staff_id BIGINT UNSIGNED NULL,
    customer_package_id BIGINT UNSIGNED NULL COMMENT 'If redeemed from package',
    appointment_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') NOT NULL DEFAULT 'pending',
    source ENUM('walk_in', 'phone', 'whatsapp', 'online') NOT NULL DEFAULT 'walk_in',
    notes TEXT NULL,
    cancelled_at TIMESTAMP NULL,
    cancelled_reason TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_package_id) REFERENCES customer_packages(id) ON DELETE SET NULL,
    
    INDEX idx_appointments_date (appointment_date),
    INDEX idx_appointments_customer (customer_id),
    INDEX idx_appointments_staff (staff_id),
    INDEX idx_appointments_status (status),
    INDEX idx_appointments_datetime (appointment_date, start_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.6 treatment_records
```sql
CREATE TABLE treatment_records (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    appointment_id BIGINT UNSIGNED NOT NULL,
    customer_id BIGINT UNSIGNED NOT NULL,
    staff_id BIGINT UNSIGNED NOT NULL,
    notes TEXT NULL,
    products_used JSON NULL COMMENT 'Array of product names/IDs used',
    before_photo VARCHAR(255) NULL,
    after_photo VARCHAR(255) NULL,
    recommendations TEXT NULL,
    follow_up_date DATE NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_treatment_appointment (appointment_id),
    INDEX idx_treatment_customer (customer_id),
    INDEX idx_treatment_followup (follow_up_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.7 packages
```sql
CREATE TABLE packages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    services JSON NOT NULL COMMENT 'Array of service_ids included',
    total_sessions INT UNSIGNED NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    validity_days INT UNSIGNED NOT NULL DEFAULT 365,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_packages_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.8 customer_packages
```sql
CREATE TABLE customer_packages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT UNSIGNED NOT NULL,
    package_id BIGINT UNSIGNED NOT NULL,
    sessions_total INT UNSIGNED NOT NULL,
    sessions_used INT UNSIGNED NOT NULL DEFAULT 0,
    sessions_remaining INT UNSIGNED NOT NULL,
    purchased_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    status ENUM('active', 'expired', 'depleted') NOT NULL DEFAULT 'active',
    transaction_id BIGINT UNSIGNED NULL COMMENT 'Link to purchase transaction',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    
    INDEX idx_custpkg_customer (customer_id),
    INDEX idx_custpkg_status (status),
    INDEX idx_custpkg_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.9 transactions
```sql
CREATE TABLE transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(50) NOT NULL,
    customer_id BIGINT UNSIGNED NOT NULL,
    appointment_id BIGINT UNSIGNED NULL,
    subtotal DECIMAL(14,2) NOT NULL,
    discount_type ENUM('percentage', 'fixed') NULL,
    discount_value DECIMAL(12,2) NULL DEFAULT 0,
    discount_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
    tax_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
    total DECIMAL(14,2) NOT NULL,
    payment_method ENUM('cash', 'qris', 'transfer', 'card', 'other') NOT NULL DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'partial', 'refunded') NOT NULL DEFAULT 'pending',
    amount_paid DECIMAL(14,2) NULL,
    change_amount DECIMAL(14,2) NULL,
    paid_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    
    UNIQUE INDEX idx_transactions_invoice (invoice_number),
    INDEX idx_transactions_customer (customer_id),
    INDEX idx_transactions_date (created_at),
    INDEX idx_transactions_status (payment_status),
    INDEX idx_transactions_method (payment_method)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.10 transaction_items
```sql
CREATE TABLE transaction_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    transaction_id BIGINT UNSIGNED NOT NULL,
    item_type ENUM('service', 'product', 'package') NOT NULL,
    item_id BIGINT UNSIGNED NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    INDEX idx_trxitems_transaction (transaction_id),
    INDEX idx_trxitems_type (item_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2.11 settings
```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL,
    value TEXT NULL,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE INDEX idx_settings_key (`key`),
    INDEX idx_settings_group (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default settings
INSERT INTO settings (`key`, value, `group`) VALUES
('clinic_name', 'Glow Aesthetic Clinic', 'general'),
('clinic_address', 'Jl. Sudirman No. 123, Jakarta', 'general'),
('clinic_phone', '021-1234-5678', 'general'),
('clinic_email', 'hello@glowclinic.com', 'general'),
('clinic_logo', NULL, 'general'),
('tax_percentage', '0', 'transaction'),
('invoice_prefix', 'INV', 'transaction'),
('currency', 'IDR', 'general'),
('timezone', 'Asia/Jakarta', 'general'),
('slot_duration', '30', 'appointment'),
('allow_walk_in', 'true', 'appointment');
```

### 3.2.12 operating_hours
```sql
CREATE TABLE operating_hours (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    day_of_week TINYINT UNSIGNED NOT NULL COMMENT '0=Sunday, 6=Saturday',
    open_time TIME NULL,
    close_time TIME NULL,
    is_closed BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE INDEX idx_ophours_day (day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default operating hours
INSERT INTO operating_hours (day_of_week, open_time, close_time, is_closed) VALUES
(0, NULL, NULL, TRUE),      -- Sunday closed
(1, '09:00', '18:00', FALSE), -- Monday
(2, '09:00', '18:00', FALSE), -- Tuesday
(3, '09:00', '18:00', FALSE), -- Wednesday
(4, '09:00', '18:00', FALSE), -- Thursday
(5, '09:00', '18:00', FALSE), -- Friday
(6, '09:00', '15:00', FALSE); -- Saturday (half day)
```

---

## 3.3 Sample Data / Seeders

### Service Categories Seeder
```php
$categories = [
    ['name' => 'Facial', 'icon' => '💆', 'sort_order' => 1],
    ['name' => 'Body Treatment', 'icon' => '🧴', 'sort_order' => 2],
    ['name' => 'Laser & Light', 'icon' => '✨', 'sort_order' => 3],
    ['name' => 'Injection', 'icon' => '💉', 'sort_order' => 4],
    ['name' => 'Hair & Scalp', 'icon' => '💇', 'sort_order' => 5],
    ['name' => 'Nail & Lash', 'icon' => '💅', 'sort_order' => 6],
];
```

### Services Seeder
```php
$services = [
    // Facial
    ['category' => 'Facial', 'name' => 'Facial Brightening', 'duration' => 60, 'price' => 250000],
    ['category' => 'Facial', 'name' => 'Facial Acne Treatment', 'duration' => 90, 'price' => 350000],
    ['category' => 'Facial', 'name' => 'Facial Anti Aging', 'duration' => 75, 'price' => 400000],
    ['category' => 'Facial', 'name' => 'Facial Hydrating', 'duration' => 60, 'price' => 280000],
    ['category' => 'Facial', 'name' => 'Facial Deep Cleansing', 'duration' => 60, 'price' => 200000],
    
    // Body Treatment
    ['category' => 'Body Treatment', 'name' => 'Body Scrub', 'duration' => 60, 'price' => 300000],
    ['category' => 'Body Treatment', 'name' => 'Body Massage', 'duration' => 90, 'price' => 400000],
    ['category' => 'Body Treatment', 'name' => 'Body Whitening', 'duration' => 60, 'price' => 350000],
    ['category' => 'Body Treatment', 'name' => 'Slimming Treatment', 'duration' => 60, 'price' => 500000],
    
    // Laser
    ['category' => 'Laser & Light', 'name' => 'Laser Toning', 'duration' => 30, 'price' => 500000],
    ['category' => 'Laser & Light', 'name' => 'IPL Rejuvenation', 'duration' => 45, 'price' => 750000],
    ['category' => 'Laser & Light', 'name' => 'Laser Hair Removal', 'duration' => 30, 'price' => 400000],
    
    // Injection
    ['category' => 'Injection', 'name' => 'Botox', 'duration' => 30, 'price' => 1500000],
    ['category' => 'Injection', 'name' => 'Filler', 'duration' => 45, 'price' => 2000000],
    ['category' => 'Injection', 'name' => 'Vitamin Injection', 'duration' => 15, 'price' => 300000],
];
```

### Packages Seeder
```php
$packages = [
    [
        'name' => 'Facial Glow Package',
        'description' => 'Paket 10x facial brightening untuk kulit lebih cerah dan sehat',
        'services' => [1, 4], // Facial Brightening, Hydrating
        'total_sessions' => 10,
        'price' => 2000000, // Normal: 2.5jt, save 500rb
        'validity_days' => 180,
    ],
    [
        'name' => 'Anti Aging Package',
        'description' => 'Paket 6x treatment anti aging untuk kulit awet muda',
        'services' => [3, 10], // Facial Anti Aging, Laser Toning
        'total_sessions' => 6,
        'price' => 4500000, // Normal: 5.4jt
        'validity_days' => 120,
    ],
    [
        'name' => 'Acne Clear Package',
        'description' => 'Paket 8x treatment untuk kulit berjerawat',
        'services' => [2, 5], // Facial Acne, Deep Cleansing
        'total_sessions' => 8,
        'price' => 2400000,
        'validity_days' => 90,
    ],
];
```

---

## 3.4 Database Indexes & Optimization

### Recommended Indexes
```sql
-- For dashboard queries
CREATE INDEX idx_transactions_daily ON transactions (DATE(created_at), payment_status);

-- For appointment calendar
CREATE INDEX idx_appointments_calendar ON appointments (appointment_date, status, staff_id);

-- For customer search
CREATE FULLTEXT INDEX ft_customers_search ON customers (name, phone, email);

-- For reports
CREATE INDEX idx_transactions_report ON transactions (created_at, payment_method, total);
```

### Query Optimization Notes
1. **Dashboard Stats**: Use single query with CASE WHEN for multiple counts
2. **Calendar View**: Index on (date, staff_id) for daily view per beautician
3. **Customer Search**: Use FULLTEXT index for real-time search
4. **Reports**: Consider denormalized summary tables for monthly reports

---

## 3.5 Backup Strategy

### Daily Backup Script
```bash
#!/bin/bash
# /scripts/backup.sh

DB_NAME="glowup_clinic"
BACKUP_DIR="/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup
mysqldump -u root -p$MYSQL_PASSWORD $DB_NAME | gzip > $BACKUP_DIR/${DB_NAME}_${DATE}.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

# Upload to cloud (optional)
# aws s3 cp $BACKUP_DIR/${DB_NAME}_${DATE}.sql.gz s3://backup-bucket/
```

---

# APPENDIX

## A. Glossary

| Term | Definition |
|------|------------|
| Appointment | Jadwal booking customer untuk treatment |
| Beautician | Staff yang melakukan treatment |
| Customer Package | Paket yang sudah dibeli customer |
| POS | Point of Sale, sistem kasir |
| Treatment Record | Catatan hasil treatment |
| Walk-in | Customer datang tanpa booking |

## B. File Structure

```
glowup-clinic/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── ServiceCategoryController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── TreatmentRecordController.php
│   │   │   ├── PackageController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── ReportController.php
│   │   │   └── SettingController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── CustomerRequest.php
│   │       ├── AppointmentRequest.php
│   │       └── TransactionRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Customer.php
│   │   ├── ServiceCategory.php
│   │   ├── Service.php
│   │   ├── Appointment.php
│   │   ├── TreatmentRecord.php
│   │   ├── Package.php
│   │   ├── CustomerPackage.php
│   │   ├── Transaction.php
│   │   ├── TransactionItem.php
│   │   └── Setting.php
│   └── Services/
│       ├── AppointmentService.php
│       ├── PackageService.php
│       ├── TransactionService.php
│       └── ReportService.php
├── database/
│   ├── migrations/
│   │   ├── 001_create_users_table.php
│   │   ├── 002_create_customers_table.php
│   │   ├── 003_create_service_categories_table.php
│   │   ├── 004_create_services_table.php
│   │   ├── 005_create_appointments_table.php
│   │   ├── 006_create_treatment_records_table.php
│   │   ├── 007_create_packages_table.php
│   │   ├── 008_create_customer_packages_table.php
│   │   ├── 009_create_transactions_table.php
│   │   ├── 010_create_transaction_items_table.php
│   │   ├── 011_create_settings_table.php
│   │   └── 012_create_operating_hours_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── ServiceCategorySeeder.php
│       ├── ServiceSeeder.php
│       ├── PackageSeeder.php
│       └── SettingSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── guest.blade.php
│       ├── components/
│       │   ├── sidebar.blade.php
│       │   ├── header.blade.php
│       │   ├── modal.blade.php
│       │   └── alert.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── customers/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── services/
│       ├── appointments/
│       ├── treatment-records/
│       ├── packages/
│       ├── transactions/
│       ├── reports/
│       └── settings/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── storage/
│   └── app/
│       └── public/
│           ├── logos/
│           ├── services/
│           └── treatments/
└── config/
```

## C. Deployment Checklist

- [ ] Server Requirements: PHP 8.2+, MySQL 8.0+, Nginx/Apache
- [ ] Install dependencies: `composer install --optimize-autoloader --no-dev`
- [ ] Configure .env (database, app URL, mail)
- [ ] Generate app key: `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeders: `php artisan db:seed --force`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Set permissions: `chmod -R 775 storage bootstrap/cache`
- [ ] Configure cron for scheduler
- [ ] Setup SSL certificate
- [ ] Configure backup script
- [ ] Test all features

---

**Document Version:** 1.0  
**Status:** Final  
**Prepared for:** JagoFlutter Academy AFC/FIC 2026
