# Phase 3: Treatment Records & Package Management

**Priority:** P1 (Important)
**Estimasi:** Week 2-3

---

## Module A: Treatment Records

### A.1 Database

- [ ] **Create treatment_records migration**
  ```bash
  php artisan make:migration create_treatment_records_table
  ```
  Fields:
  - id
  - appointment_id: foreign key
  - customer_id: foreign key
  - staff_id: foreign key (beautician yang handle)
  - notes: text nullable
  - products_used: json nullable (array of product names)
  - before_photo: varchar(255) nullable
  - after_photo: varchar(255) nullable
  - recommendations: text nullable
  - follow_up_date: date nullable
  - timestamps

### A.2 Model

- [ ] **Create TreatmentRecord Model**
  ```bash
  php artisan make:model TreatmentRecord -f
  ```

  ```php
  protected $fillable = [
      'appointment_id', 'customer_id', 'staff_id',
      'notes', 'products_used', 'before_photo', 'after_photo',
      'recommendations', 'follow_up_date'
  ];

  protected function casts(): array
  {
      return [
          'products_used' => 'array',
          'follow_up_date' => 'date',
      ];
  }

  // Relationships
  public function appointment(): BelongsTo
  {
      return $this->belongsTo(Appointment::class);
  }

  public function customer(): BelongsTo
  {
      return $this->belongsTo(Customer::class);
  }

  public function staff(): BelongsTo
  {
      return $this->belongsTo(User::class, 'staff_id');
  }
  ```

### A.3 Controller

- [ ] **Create TreatmentRecordController**
  ```bash
  php artisan make:controller TreatmentRecordController --resource
  ```

  Methods:
  - create(Appointment $appointment) - Form untuk appointment tertentu
  - store(Request) - Simpan treatment record
  - show(TreatmentRecord) - Detail view
  - edit(TreatmentRecord) - Edit form
  - update(Request, TreatmentRecord) - Update
  - customerHistory(Customer) - Timeline semua treatment customer

### A.4 Image Upload Service

- [ ] **Create ImageUploadService**
  ```bash
  php artisan make:class Services/ImageUploadService
  ```

  Methods:
  - uploadTreatmentPhoto(file, type: 'before'|'after'): string
  - deletePhoto(path): bool
  - compressImage(file): file (optional optimization)

  Storage path: `storage/app/public/treatments/{customer_id}/{date}/`

### A.5 Views

- [ ] **Create/Edit Form** (resources/views/treatment-records/form.blade.php)

  Layout:
  ```
  ┌─────────────────────────────────────────────────────┐
  │ Treatment Record                            [Save]  │
  │ Appointment: Rina - Facial | 26 Jan 2026, 10:00    │
  ├─────────────────────────────────────────────────────┤
  │                                                     │
  │ BEFORE & AFTER PHOTOS                               │
  │ ┌──────────────┐    ┌──────────────┐               │
  │ │  📷 Before   │    │  📷 After    │               │
  │ │ [Upload]     │    │ [Upload]     │               │
  │ └──────────────┘    └──────────────┘               │
  │                                                     │
  │ TREATMENT NOTES                                     │
  │ ┌─────────────────────────────────────────────────┐│
  │ │ Textarea dengan rich formatting hints           ││
  │ └─────────────────────────────────────────────────┘│
  │                                                     │
  │ PRODUCTS USED                                       │
  │ [+ Add Product]                                     │
  │ • Product 1  [x]                                    │
  │ • Product 2  [x]                                    │
  │                                                     │
  │ RECOMMENDATIONS                                     │
  │ ┌─────────────────────────────────────────────────┐│
  │ │ Saran untuk customer                            ││
  │ └─────────────────────────────────────────────────┘│
  │                                                     │
  │ FOLLOW UP                                           │
  │ Suggested next visit: [Date Picker]                 │
  └─────────────────────────────────────────────────────┘
  ```

  Alpine.js features:
  - Drag & drop image upload
  - Image preview before upload
  - Dynamic product list (add/remove)
  - Auto-save draft (optional)

- [ ] **Detail View** (resources/views/treatment-records/show.blade.php)
  - Header: Customer name, service, date
  - Photo comparison (side by side)
  - Treatment notes
  - Products used
  - Recommendations
  - Follow up date
  - Actions: Edit, Print PDF

- [ ] **Customer History** (partial in customer show page)
  - Timeline view
  - Each entry: date, service, beautician, thumbnail
  - Click to expand/view detail
  - Filter by date range
  - Filter by service type

---

## Module B: Package Management

### B.1 Database

- [ ] **Create packages migration**
  ```bash
  php artisan make:migration create_packages_table
  ```
  Fields:
  - id
  - name: varchar(255)
  - description: text nullable
  - services: json (array of service_ids)
  - total_sessions: int
  - price: decimal(12,2)
  - validity_days: int default 365
  - is_active: boolean default true
  - timestamps

- [ ] **Create customer_packages migration**
  ```bash
  php artisan make:migration create_customer_packages_table
  ```
  Fields:
  - id
  - customer_id: foreign key
  - package_id: foreign key
  - sessions_total: int
  - sessions_used: int default 0
  - sessions_remaining: int
  - purchased_at: timestamp
  - expires_at: timestamp
  - status: enum('active', 'expired', 'depleted')
  - transaction_id: foreign key nullable
  - timestamps

### B.2 Models

- [ ] **Create Package Model**
  ```bash
  php artisan make:model Package -f
  ```

  ```php
  protected $fillable = [
      'name', 'description', 'services',
      'total_sessions', 'price', 'validity_days', 'is_active'
  ];

  protected function casts(): array
  {
      return [
          'services' => 'array',
          'price' => 'decimal:2',
          'is_active' => 'boolean',
      ];
  }

  // Get service objects
  public function getServicesAttribute(): Collection
  {
      return Service::whereIn('id', $this->services ?? [])->get();
  }
  ```

- [ ] **Create CustomerPackage Model**
  ```bash
  php artisan make:model CustomerPackage -f
  ```

  ```php
  protected $fillable = [
      'customer_id', 'package_id', 'sessions_total',
      'sessions_used', 'sessions_remaining',
      'purchased_at', 'expires_at', 'status', 'transaction_id'
  ];

  protected function casts(): array
  {
      return [
          'purchased_at' => 'datetime',
          'expires_at' => 'datetime',
      ];
  }

  // Scopes
  public function scopeActive($query)
  {
      return $query->where('status', 'active')
                   ->where('expires_at', '>', now());
  }

  // Methods
  public function redeem(): bool
  {
      if ($this->sessions_remaining <= 0) {
          return false;
      }

      $this->sessions_used++;
      $this->sessions_remaining--;

      if ($this->sessions_remaining === 0) {
          $this->status = 'depleted';
      }

      return $this->save();
  }

  public function isExpired(): bool
  {
      return $this->expires_at < now();
  }
  ```

### B.3 Service Layer

- [ ] **Create PackageService**
  ```bash
  php artisan make:class Services/PackageService
  ```

  Methods:
  - sellPackageToCustomer(Customer, Package, ?Transaction): CustomerPackage
  - redeemSession(CustomerPackage, Appointment): bool
  - checkExpiredPackages(): void (untuk scheduled job)
  - getCustomerActivePackages(Customer): Collection

### B.4 Controllers

- [ ] **Create PackageController** (Admin - manage package templates)
  ```bash
  php artisan make:controller PackageController --resource
  ```

- [ ] **Create CustomerPackageController** (Customer's owned packages)
  ```bash
  php artisan make:controller CustomerPackageController
  ```

  Methods:
  - index(Customer) - List packages owned by customer
  - store(Customer, Package) - Sell package to customer
  - redeem(CustomerPackage, Appointment) - Redeem 1 session

### B.5 Seeders

- [ ] **Create PackageSeeder**
  ```php
  $packages = [
      [
          'name' => 'Facial Glow Package',
          'description' => 'Paket 10x facial brightening untuk kulit lebih cerah',
          'services' => [1, 4], // Facial Brightening, Hydrating
          'total_sessions' => 10,
          'price' => 2000000,
          'validity_days' => 180,
      ],
      [
          'name' => 'Anti Aging Package',
          'description' => 'Paket 6x treatment anti aging',
          'services' => [3, 10], // Facial Anti Aging, Laser Toning
          'total_sessions' => 6,
          'price' => 4500000,
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

### B.6 Views

- [ ] **Packages Index** (Admin) - resources/views/packages/index.blade.php
  - Grid cards
  - Each card: name, services included, sessions, price
  - Status badge (active/inactive)
  - Actions: edit, duplicate, toggle active

- [ ] **Package Form** - resources/views/packages/form.blade.php
  - Package name
  - Description
  - Services selection (checkboxes from service list)
  - Total sessions
  - Price
  - Validity period (days)
  - Preview: services included, normal price, savings

- [ ] **Customer Packages** (in Customer detail page)
  ```
  ┌─────────────────────────────────────────────────────┐
  │ Active Packages                     [+ Sell Package]│
  ├─────────────────────────────────────────────────────┤
  │ ┌─────────────────────────────────────────────────┐ │
  │ │ 🎁 Facial Glow Package                          │ │
  │ │ Remaining: 4/10 sessions                        │ │
  │ │ ████████░░ 60%                                  │ │
  │ │ Expires: 15 Mar 2026 (48 days left)            │ │
  │ │                                      [Redeem]   │ │
  │ └─────────────────────────────────────────────────┘ │
  │                                                     │
  │ ┌─────────────────────────────────────────────────┐ │
  │ │ 🎁 Anti Aging Package                           │ │
  │ │ Remaining: 2/6 sessions                         │ │
  │ │ ██████████████░░░░ 67%                          │ │
  │ │ Expires: 1 Feb 2026 (6 days left) ⚠️           │ │
  │ │                                      [Redeem]   │ │
  │ └─────────────────────────────────────────────────┘ │
  └─────────────────────────────────────────────────────┘
  ```

- [ ] **Sell Package Modal**
  - Search/select customer (if not in customer page)
  - Select package
  - Show: sessions, price, expiry date
  - Confirm & create transaction

- [ ] **Redeem Session Flow**
  Integration dengan Appointment creation:
  - Saat pilih service, tampilkan opsi "Use from Package"
  - Show available packages yang include service tersebut
  - Select package untuk redeem
  - Price menjadi Rp 0 (dari paket)

---

## Integration Points

### Appointment ↔ Treatment Record

```php
// Saat appointment completed, tampilkan button "Create Treatment Record"
// Di AppointmentController@complete
public function complete(Appointment $appointment)
{
    $appointment->update(['status' => 'completed']);

    // Redirect ke create treatment record
    return redirect()->route('treatment-records.create', $appointment);
}
```

### Appointment ↔ Package Redemption

```php
// Di appointment form, check customer packages
$customerPackages = $customer->packages()
    ->active()
    ->whereJsonContains('services', $serviceId)
    ->get();

// Saat create appointment dengan package
$appointment = Appointment::create([
    // ...
    'customer_package_id' => $customerPackageId,
]);

// Redeem session
$customerPackage->redeem();
```

### Customer ↔ Stats Update

```php
// Observer atau Event listener
// Saat appointment completed
$customer->increment('total_visits');
$customer->update(['last_visit_at' => now()]);

// Saat transaction paid
$customer->increment('total_spent', $transaction->total);
```

---

## Scheduled Tasks

- [ ] **Check Expired Packages** (daily)
  ```php
  // app/Console/Kernel.php atau routes/console.php
  Schedule::call(function () {
      CustomerPackage::where('status', 'active')
          ->where('expires_at', '<', now())
          ->update(['status' => 'expired']);
  })->daily();
  ```

- [ ] **Follow Up Reminder** (optional - future)
  Send notification untuk treatment yang punya follow_up_date = today

---

## Testing Checklist

### Treatment Records
- [ ] Create treatment record dari completed appointment
- [ ] Upload before photo
- [ ] Upload after photo
- [ ] Add multiple products used
- [ ] Set follow up date
- [ ] View customer treatment history
- [ ] Photos tersimpan dengan benar di storage

### Packages
- [ ] Create package dengan multiple services
- [ ] Sell package ke customer
- [ ] Check expiry date calculated correctly
- [ ] Redeem session dari package
- [ ] Sessions remaining updated after redeem
- [ ] Status becomes 'depleted' when sessions = 0
- [ ] Status becomes 'expired' after expiry date
- [ ] Cannot redeem from expired package
- [ ] Cannot redeem from depleted package

### Integration
- [ ] Appointment dengan package redemption
- [ ] Price = 0 untuk redeemed appointment
- [ ] Customer stats updated setelah visit

---

## Files Summary

```
app/
├── Http/Controllers/
│   ├── TreatmentRecordController.php
│   ├── PackageController.php
│   └── CustomerPackageController.php
├── Http/Requests/
│   ├── StoreTreatmentRecordRequest.php
│   ├── StorePackageRequest.php
│   └── SellPackageRequest.php
├── Models/
│   ├── TreatmentRecord.php
│   ├── Package.php
│   └── CustomerPackage.php
├── Services/
│   ├── ImageUploadService.php
│   └── PackageService.php
└── Observers/
    └── AppointmentObserver.php (optional)

database/
├── migrations/
│   ├── xxxx_create_treatment_records_table.php
│   ├── xxxx_create_packages_table.php
│   └── xxxx_create_customer_packages_table.php
├── factories/
│   ├── TreatmentRecordFactory.php
│   ├── PackageFactory.php
│   └── CustomerPackageFactory.php
└── seeders/
    └── PackageSeeder.php

resources/views/
├── treatment-records/
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── packages/
    ├── index.blade.php
    └── form.blade.php
```
