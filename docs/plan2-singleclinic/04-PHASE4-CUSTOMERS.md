# Phase 4: Customer Management

## Overview
CRUD customers dengan skin profile, treatment history, dan statistik.

---

## 4.1 Customer List

### Tasks
- [ ] Create Customer model & migration
- [ ] Customer list dengan pagination
- [ ] Real-time search (name/phone)
- [ ] Filter by membership type
- [ ] Sort options

### Files to Create
```
app/Models/Customer.php
app/Http/Controllers/CustomerController.php
app/Http/Requests/CustomerRequest.php
database/migrations/xxxx_create_customers_table.php
database/seeders/CustomerSeeder.php
resources/views/customers/index.blade.php
```

### UI - Customer List
```
┌─────────────────────────────────────────────────────────────┐
│ Customers (156)                            [+ Add Customer] │
├─────────────────────────────────────────────────────────────┤
│ 🔍 Search by name or phone...    Filter: [All ▼] [Active ▼]│
├─────────────────────────────────────────────────────────────┤
│ Photo │ Name          │ Phone        │ Last Visit │ Spent    │
│───────┼───────────────┼──────────────┼────────────┼──────────│
│  👤   │ Rina Wijaya   │ 0812-3456... │ 2 days ago │ Rp 2.5Jt │
│  👤   │ Siti Aminah   │ 0813-9876... │ 1 week ago │ Rp 1.2Jt │
│  👤   │ Dewi Kartika  │ 0815-1234... │ Today      │ Rp 500Rb │
├─────────────────────────────────────────────────────────────┤
│ Showing 1-10 of 156                        < 1 2 3 4 5 >    │
└─────────────────────────────────────────────────────────────┘
```

---

## 4.2 Customer Form

### Tasks
- [ ] Create customer form (create/edit)
- [ ] Phone number validation (unique, format)
- [ ] Skin profile fields
- [ ] Skin concerns multi-select

### Fields
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| name | string(255) | Yes | |
| phone | string(20) | Yes | Unique, format 08xx |
| email | string(255) | No | Valid email |
| birthdate | date | No | Before today |
| gender | enum | No | male/female/other |
| address | text | No | Max 500 |
| skin_type | enum | No | normal/oily/dry/combination/sensitive |
| skin_concerns | json | No | Array |
| allergies | text | No | Max 500 |
| notes | text | No | Max 1000 |

### Skin Concerns Options
```
- Acne (Jerawat)
- Aging (Penuaan)
- Pigmentation (Flek)
- Dull Skin (Kusam)
- Large Pores (Pori Besar)
- Redness (Kemerahan)
- Dehydration (Dehidrasi)
- Oily (Berminyak)
- Sensitive (Sensitif)
- Blackheads (Komedo)
```

### UI - Customer Form
```
┌─────────────────────────────────────────────────────────────┐
│ Add New Customer                                     [Save] │
├─────────────────────────────────────────────────────────────┤
│ BASIC INFORMATION                                           │
│ ┌───────────────────────┐  ┌───────────────────────┐       │
│ │ Name *                │  │ Phone *               │       │
│ │ [                   ] │  │ [08                 ] │       │
│ └───────────────────────┘  └───────────────────────┘       │
│                                                             │
│ ┌───────────────────────┐  ┌───────────────────────┐       │
│ │ Email                 │  │ Birthdate             │       │
│ │ [                   ] │  │ [📅 Select date     ] │       │
│ └───────────────────────┘  └───────────────────────┘       │
│                                                             │
│ ┌───────────────────────┐                                  │
│ │ Gender                │  ○ Pria ○ Wanita ○ Lainnya      │
│ └───────────────────────┘                                  │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Address                                                 ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ ─────────────────────────────────────────────────────────  │
│ SKIN PROFILE                                                │
│                                                             │
│ Skin Type:                                                  │
│ ○ Normal ○ Oily ○ Dry ○ Combination ○ Sensitive           │
│                                                             │
│ Skin Concerns (pilih semua yang sesuai):                   │
│ ☐ Acne      ☐ Aging      ☐ Pigmentation   ☐ Dull          │
│ ☐ Pores     ☐ Redness    ☐ Dehydration    ☐ Oily          │
│ ☐ Sensitive ☐ Blackheads                                   │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Allergies                                               ││
│ │ [Contoh: AHA, Retinol, Parfum                         ] ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Notes                                                   ││
│ │ [Catatan tambahan tentang customer                    ] ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 4.3 Customer Detail

### Tasks
- [ ] Customer detail view
- [ ] Tabs: Overview, Treatment History, Packages, Photos
- [ ] Statistics widget
- [ ] Active packages display
- [ ] Recent treatments timeline

### Files to Create
```
resources/views/customers/show.blade.php
resources/views/customers/partials/overview.blade.php
resources/views/customers/partials/treatments.blade.php
resources/views/customers/partials/packages.blade.php
resources/views/customers/partials/photos.blade.php
```

### UI - Customer Detail
```
┌─────────────────────────────────────────────────────────────┐
│ ← Back to Customers                            [Edit] [🗑️] │
├─────────────────────────────────────────────────────────────┤
│ ┌─────────┐                                                 │
│ │  Photo  │  RINA WIJAYA                 [+ New Booking]   │
│ │   👤    │  📱 0812-3456-7890  ✉️ rina@email.com          │
│ └─────────┘  🎂 15 Maret 1990 (34 tahun)                    │
│              📍 Jl. Sudirman No. 123, Jakarta               │
├─────────────────────────────────────────────────────────────┤
│ [Overview] [Treatment History] [Packages] [Photos]          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ SKIN PROFILE        │  │ STATISTICS          │           │
│ │                     │  │                     │           │
│ │ Type: Combination   │  │ Total Visits: 24    │           │
│ │                     │  │                     │           │
│ │ Concerns:           │  │ Total Spent:        │           │
│ │ • Acne             │  │ Rp 12.500.000       │           │
│ │ • Large Pores      │  │                     │           │
│ │                     │  │ Last Visit:         │           │
│ │ Allergies:          │  │ 2 days ago          │           │
│ │ AHA, Parfum        │  │                     │           │
│ │                     │  │ Member Since:       │           │
│ │                     │  │ Jan 2024            │           │
│ └─────────────────────┘  └─────────────────────┘           │
│                                                             │
│ ACTIVE PACKAGES                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 🎁 Facial Glow Package                                  ││
│ │    Remaining: 4/10 sessions                             ││
│ │    Expires: 15 Mar 2026                      [Redeem]   ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ RECENT TREATMENTS                                           │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 📅 24 Jan 2026 - Facial Brightening                    ││
│ │    By: Maya | Status: Completed                         ││
│ │    Notes: Kulit respond well, lanjut minggu depan      ││
│ │                                          [View Detail]  ││
│ └─────────────────────────────────────────────────────────┘│
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 📅 17 Jan 2026 - Facial Deep Cleansing                 ││
│ │    By: Maya | Status: Completed                         ││
│ │                                          [View Detail]  ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 4.4 API Endpoints (AJAX)

```php
// For real-time search
GET /api/customers?search=keyword&page=1&per_page=10
Response: {
    data: [...],
    meta: { total, per_page, current_page, last_page }
}

// Quick lookup (for appointment form)
GET /api/customers/search?q=keyword&limit=5
Response: {
    data: [{ id, name, phone, last_visit }]
}

// Get customer stats
GET /api/customers/{id}/stats
Response: {
    total_visits, total_spent, last_visit, member_since
}

// Get customer treatment history
GET /api/customers/{id}/treatments?page=1
Response: {
    data: [...],
    meta: {...}
}
```

---

## Routes

```php
// routes/web.php (add to auth middleware group)

// Customers
Route::resource('customers', CustomerController::class);

// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('customers', [CustomerApiController::class, 'index']);
    Route::get('customers/search', [CustomerApiController::class, 'search']);
    Route::get('customers/{customer}/stats', [CustomerApiController::class, 'stats']);
    Route::get('customers/{customer}/treatments', [CustomerApiController::class, 'treatments']);
});
```

---

## Acceptance Criteria

- [ ] Customer list dengan pagination
- [ ] Real-time search berfungsi
- [ ] Filter by membership works
- [ ] Create customer dengan validasi phone unique
- [ ] Edit customer works
- [ ] Delete customer (soft delete)
- [ ] Customer detail menampilkan semua info
- [ ] Statistics dihitung dengan benar
- [ ] Active packages ditampilkan
- [ ] Treatment history dengan timeline
