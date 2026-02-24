# Phase 3: Master Data

## Overview
Setup master data untuk services, categories, staff, dan clinic settings.

---

## 3.1 Service Categories

### Tasks
- [ ] Create ServiceCategory model & migration
- [ ] CRUD service categories
- [ ] Drag & drop untuk sort order
- [ ] Icon selector (emoji atau icon)

### Files to Create
```
app/Models/ServiceCategory.php
app/Http/Controllers/ServiceCategoryController.php
database/migrations/xxxx_create_service_categories_table.php
database/seeders/ServiceCategorySeeder.php
resources/views/services/categories/index.blade.php
resources/views/services/categories/form.blade.php
```

### Fields
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| name | string(255) | Yes | |
| description | text | No | |
| icon | string(50) | No | Emoji atau icon name |
| sort_order | int | Yes | Default 0 |
| is_active | boolean | Yes | Default true |

### Default Categories
```
1. Facial (💆)
2. Body Treatment (🧴)
3. Laser & Light (✨)
4. Injection (💉)
5. Hair & Scalp (💇)
6. Nail & Lash (💅)
```

---

## 3.2 Services

### Tasks
- [ ] Create Service model & migration
- [ ] CRUD services dengan category
- [ ] Image upload untuk service
- [ ] Active/inactive toggle
- [ ] Filter by category

### Files to Create
```
app/Models/Service.php
app/Http/Controllers/ServiceController.php
app/Http/Requests/ServiceRequest.php
database/migrations/xxxx_create_services_table.php
database/seeders/ServiceSeeder.php
resources/views/services/index.blade.php
resources/views/services/create.blade.php
resources/views/services/edit.blade.php
```

### Fields
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| category_id | foreign | No | Nullable |
| name | string(255) | Yes | |
| description | text | No | |
| duration_minutes | int | Yes | Default 60 |
| price | decimal(12,2) | Yes | |
| image | string(255) | No | |
| is_active | boolean | Yes | Default true |

### UI - Service List
```
┌─────────────────────────────────────────────────────────────┐
│ Services                                    [+ Add Service] │
├─────────────────────────────────────────────────────────────┤
│ Filter: [All Categories ▼]  🔍 Search...                   │
├─────────────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 💆 FACIAL                                               ││
│ ├─────────────────────────────────────────────────────────┤│
│ │ Image │ Name           │ Duration │ Price      │ Actions││
│ │ 🖼️    │ Facial Bright  │ 60 min   │ Rp 250.000 │ ✏️ 🗑️  ││
│ │ 🖼️    │ Facial Acne    │ 90 min   │ Rp 350.000 │ ✏️ 🗑️  ││
│ └─────────────────────────────────────────────────────────┘│
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 🧴 BODY TREATMENT                                       ││
│ ├─────────────────────────────────────────────────────────┤│
│ │ 🖼️    │ Body Scrub     │ 60 min   │ Rp 300.000 │ ✏️ 🗑️  ││
│ └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

---

## 3.3 Staff Management

### Tasks
- [ ] User list view (staff only, not self)
- [ ] Create/Edit user form
- [ ] Role assignment
- [ ] Active/inactive toggle
- [ ] Reset password functionality

### Files to Create/Modify
```
app/Http/Controllers/StaffController.php
app/Http/Requests/StaffRequest.php
resources/views/staff/index.blade.php
resources/views/staff/create.blade.php
resources/views/staff/edit.blade.php
```

### Access Control
- **Owner:** Can manage all staff
- **Admin:** Can view staff list only
- **Beautician:** No access

### UI - Staff Form
```
┌─────────────────────────────────────────────────────────────┐
│ Add Staff Member                                     [Save] │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Name *              │  │ Email *             │           │
│ └─────────────────────┘  └─────────────────────┘           │
│                                                             │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Phone               │  │ Role *        [▼]   │           │
│ └─────────────────────┘  └─────────────────────┘           │
│                          ○ Owner                            │
│                          ○ Admin                            │
│                          ○ Beautician                       │
│                                                             │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Password *          │  │ Confirm Password *  │           │
│ └─────────────────────┘  └─────────────────────┘           │
│                                                             │
│ ☐ Active                                                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 3.4 Clinic Settings

### Tasks
- [ ] Create Setting model
- [ ] Settings form untuk clinic profile
- [ ] Logo upload
- [ ] Operating hours management
- [ ] Create OperatingHour model

### Files to Create
```
app/Models/Setting.php
app/Models/OperatingHour.php
app/Http/Controllers/SettingController.php
database/migrations/xxxx_create_settings_table.php
database/migrations/xxxx_create_operating_hours_table.php
database/seeders/SettingSeeder.php
resources/views/settings/index.blade.php
resources/views/settings/clinic.blade.php
resources/views/settings/hours.blade.php
```

### Settings Fields
```
[General]
- clinic_name
- clinic_address
- clinic_phone
- clinic_email
- clinic_logo

[Transaction]
- tax_percentage (0 = no tax)
- invoice_prefix (default: INV)
- currency (default: IDR)

[Appointment]
- slot_duration (default: 30 minutes)
- allow_walk_in (default: true)

[System]
- timezone (default: Asia/Jakarta)
```

### Operating Hours Form
```
┌─────────────────────────────────────────────────────────────┐
│ Operating Hours                                      [Save] │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Day       │ Closed │ Open Time │ Close Time                │
│ ──────────┼────────┼───────────┼────────────               │
│ Sunday    │ ☑️     │ ────────  │ ────────                  │
│ Monday    │ ☐      │ [09:00]   │ [18:00]                   │
│ Tuesday   │ ☐      │ [09:00]   │ [18:00]                   │
│ Wednesday │ ☐      │ [09:00]   │ [18:00]                   │
│ Thursday  │ ☐      │ [09:00]   │ [18:00]                   │
│ Friday    │ ☐      │ [09:00]   │ [18:00]                   │
│ Saturday  │ ☐      │ [09:00]   │ [15:00]                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## Routes

```php
// routes/web.php (add to auth middleware group)

// Service Categories
Route::resource('service-categories', ServiceCategoryController::class);
Route::post('service-categories/reorder', [ServiceCategoryController::class, 'reorder'])->name('service-categories.reorder');

// Services
Route::resource('services', ServiceController::class);
Route::patch('services/{service}/toggle-active', [ServiceController::class, 'toggleActive'])->name('services.toggle-active');

// Staff (owner only)
Route::middleware('role:owner')->group(function () {
    Route::resource('staff', StaffController::class);
    Route::post('staff/{user}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset-password');
});

// Settings (owner/admin)
Route::middleware('role:owner,admin')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::get('/clinic', [SettingController::class, 'clinic'])->name('clinic');
    Route::post('/clinic', [SettingController::class, 'updateClinic'])->name('clinic.update');
    Route::get('/hours', [SettingController::class, 'hours'])->name('hours');
    Route::post('/hours', [SettingController::class, 'updateHours'])->name('hours.update');
});
```

---

## Acceptance Criteria

### Service Categories
- [ ] CRUD categories berfungsi
- [ ] Sort order dapat diubah
- [ ] Category bisa di-soft-delete jika ada services

### Services
- [ ] CRUD services berfungsi
- [ ] Image upload works
- [ ] Filter by category works
- [ ] Search by name works
- [ ] Toggle active/inactive works

### Staff
- [ ] Only owner can manage staff
- [ ] CRUD users berfungsi
- [ ] Role assignment works
- [ ] Reset password works

### Settings
- [ ] Clinic profile dapat di-update
- [ ] Logo upload works
- [ ] Operating hours dapat di-set per hari
