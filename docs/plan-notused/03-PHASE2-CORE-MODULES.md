# Phase 2: Core Modules

**Priority:** P0 (Must Have)
**Estimasi:** Week 1-2

---

## Module A: Customer Management

### A.1 Database

- [ ] **Create customers migration**
  ```bash
  php artisan make:migration create_customers_table
  ```
  Fields:
  - id
  - name: varchar(255)
  - phone: varchar(20) unique
  - email: varchar(255) nullable
  - birthdate: date nullable
  - gender: enum('male', 'female', 'other') nullable
  - address: text nullable
  - skin_type: enum('normal', 'oily', 'dry', 'combination', 'sensitive') nullable
  - skin_concerns: json nullable
  - allergies: text nullable
  - notes: text nullable
  - membership_type: enum('regular', 'silver', 'gold', 'platinum') default 'regular'
  - total_spent: decimal(14,2) default 0
  - total_visits: int default 0
  - last_visit_at: timestamp nullable
  - timestamps

### A.2 Model & Factory

- [ ] **Create Customer Model**
  ```bash
  php artisan make:model Customer -f
  ```

- [ ] **Setup Model**
  ```php
  protected $fillable = [
      'name', 'phone', 'email', 'birthdate', 'gender',
      'address', 'skin_type', 'skin_concerns', 'allergies',
      'notes', 'membership_type'
  ];

  protected function casts(): array
  {
      return [
          'birthdate' => 'date',
          'skin_concerns' => 'array',
          'total_spent' => 'decimal:2',
          'last_visit_at' => 'datetime',
      ];
  }
  ```

- [ ] **Setup Factory**
  ```php
  return [
      'name' => fake()->name(),
      'phone' => fake()->unique()->phoneNumber(),
      'email' => fake()->unique()->safeEmail(),
      'birthdate' => fake()->dateTimeBetween('-50 years', '-18 years'),
      'gender' => fake()->randomElement(['male', 'female']),
      'skin_type' => fake()->randomElement(['normal', 'oily', 'dry', 'combination', 'sensitive']),
  ];
  ```

### A.3 Controller & Requests

- [ ] **Create CustomerController**
  ```bash
  php artisan make:controller CustomerController --resource
  ```

- [ ] **Create Form Requests**
  ```bash
  php artisan make:request StoreCustomerRequest
  php artisan make:request UpdateCustomerRequest
  ```

  Validation rules:
  - name: required, string, max:255
  - phone: required, string, max:20, unique:customers
  - email: nullable, email, max:255
  - birthdate: nullable, date, before:today
  - gender: nullable, in:male,female,other
  - skin_type: nullable, in:normal,oily,dry,combination,sensitive
  - skin_concerns: nullable, array

### A.4 Routes

```php
Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('customers.search');
});
```

### A.5 Views

- [ ] **Index** (resources/views/customers/index.blade.php)
  - Header dengan title + Add Customer button
  - Search bar (real-time dengan Alpine.js)
  - Filter dropdown (membership type)
  - Table dengan kolom: Photo, Name, Phone, Last Visit, Actions
  - Pagination
  - Empty state

- [ ] **Create/Edit Form** (resources/views/customers/form.blade.php)
  - Basic Information section
    - Name (required)
    - Phone (required)
    - Email
    - Birthdate
    - Gender (radio buttons)
    - Address
  - Skin Profile section
    - Skin Type (radio buttons)
    - Skin Concerns (checkboxes)
    - Allergies (textarea)
    - Notes (textarea)
  - Form validation dengan Alpine.js

- [ ] **Show/Detail** (resources/views/customers/show.blade.php)
  - Customer header (photo, name, contact info, age)
  - Tab navigation: Overview, Treatment History, Packages, Photos
  - Skin Profile card
  - Statistics card (total visits, total spent, member since)
  - Active Packages list
  - Recent Treatments timeline

---

## Module B: Service Catalog

### B.1 Database

- [ ] **Create service_categories migration**
  ```bash
  php artisan make:migration create_service_categories_table
  ```
  Fields:
  - id
  - name: varchar(255)
  - description: text nullable
  - icon: varchar(50) nullable (emoji atau icon name)
  - sort_order: int default 0
  - is_active: boolean default true
  - timestamps

- [ ] **Create services migration**
  ```bash
  php artisan make:migration create_services_table
  ```
  Fields:
  - id
  - category_id: foreign key nullable
  - name: varchar(255)
  - description: text nullable
  - duration_minutes: int default 60
  - price: decimal(12,2)
  - image: varchar(255) nullable
  - is_active: boolean default true
  - timestamps

### B.2 Models & Factories

- [ ] **Create ServiceCategory Model**
  ```bash
  php artisan make:model ServiceCategory -f
  ```
  Relationships:
  - hasMany services

- [ ] **Create Service Model**
  ```bash
  php artisan make:model Service -f
  ```
  Relationships:
  - belongsTo category

### B.3 Seeders

- [ ] **Create ServiceCategorySeeder**
  Categories:
  - Facial (icon: 💆)
  - Body Treatment (icon: 🧴)
  - Laser & Light (icon: ✨)
  - Injection (icon: 💉)
  - Hair & Scalp (icon: 💇)
  - Nail & Lash (icon: 💅)

- [ ] **Create ServiceSeeder**
  Services per category dari dokumentasi

### B.4 Controllers

- [ ] **Create ServiceCategoryController**
  ```bash
  php artisan make:controller ServiceCategoryController --resource
  ```

- [ ] **Create ServiceController**
  ```bash
  php artisan make:controller ServiceController --resource
  ```

### B.5 Views

- [ ] **Categories Index** (resources/views/service-categories/index.blade.php)
  - Grid layout dengan cards
  - Each card: icon, name, service count
  - Add/Edit/Delete actions
  - Drag to reorder (nice to have)

- [ ] **Services Index** (resources/views/services/index.blade.php)
  - Filter by category
  - Grid atau list view toggle
  - Service card: image, name, duration, price, status badge
  - Quick actions: edit, toggle active

- [ ] **Service Form** (resources/views/services/form.blade.php)
  - Category dropdown
  - Name
  - Description
  - Duration (minutes)
  - Price (currency input)
  - Image upload
  - Is Active toggle

---

## Module C: Appointment

### C.1 Database

- [ ] **Create appointments migration**
  ```bash
  php artisan make:migration create_appointments_table
  ```
  Fields:
  - id
  - customer_id: foreign key
  - service_id: foreign key
  - staff_id: foreign key nullable (user with role beautician)
  - customer_package_id: foreign key nullable
  - appointment_date: date
  - start_time: time
  - end_time: time
  - status: enum('pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show')
  - source: enum('walk_in', 'phone', 'whatsapp', 'online')
  - notes: text nullable
  - cancelled_at: timestamp nullable
  - cancelled_reason: text nullable
  - timestamps

### C.2 Model

- [ ] **Create Appointment Model**
  ```bash
  php artisan make:model Appointment -f
  ```
  Relationships:
  - belongsTo customer
  - belongsTo service
  - belongsTo staff (User)
  - belongsTo customerPackage (nullable)
  - hasOne treatmentRecord

  Scopes:
  - scopeToday()
  - scopeUpcoming()
  - scopeByStatus()

### C.3 Service Layer

- [ ] **Create AppointmentService**
  ```bash
  php artisan make:class Services/AppointmentService
  ```
  Methods:
  - getAvailableSlots(date, serviceId, staffId)
  - createAppointment(data)
  - updateStatus(appointment, status)
  - calculateEndTime(startTime, serviceDuration)

### C.4 Controller

- [ ] **Create AppointmentController**
  ```bash
  php artisan make:controller AppointmentController --resource
  ```

### C.5 Views

- [ ] **Calendar View** (resources/views/appointments/index.blade.php)
  - View toggle: Day, Week (Month nice to have)
  - Date navigation (prev/next)
  - Staff columns (untuk day view)
  - Appointment blocks dengan color coding by status
  - Click to view detail
  - New Booking button

- [ ] **Create Booking Form** (resources/views/appointments/create.blade.php)
  Multi-step form dengan Alpine.js:

  **Step 1: Select Customer**
  - Search existing customer
  - Recent customers list
  - Or create new customer (modal)

  **Step 2: Select Service**
  - Services grouped by category
  - Show duration & price
  - Option to redeem from package (if available)

  **Step 3: Select Date & Time**
  - Date picker
  - Available time slots grid
  - Staff assignment (optional)

  **Step 4: Confirmation**
  - Summary of booking
  - Add notes
  - Confirm button

- [ ] **Appointment Detail Modal/Page**
  - Customer info
  - Service info
  - Date, time, staff
  - Status with update buttons
  - Notes
  - Actions: Start, Complete, Cancel, Create Treatment Record

---

## API Endpoints (AJAX)

```php
// routes/api.php atau web.php dengan prefix api

// Customers
Route::get('/api/customers', [CustomerController::class, 'apiIndex']);
Route::get('/api/customers/search', [CustomerController::class, 'search']);

// Services
Route::get('/api/services', [ServiceController::class, 'apiIndex']);
Route::get('/api/services/by-category', [ServiceController::class, 'byCategory']);

// Appointments
Route::get('/api/appointments', [AppointmentController::class, 'apiIndex']);
Route::get('/api/appointments/calendar', [AppointmentController::class, 'calendar']);
Route::get('/api/appointments/available-slots', [AppointmentController::class, 'availableSlots']);
Route::put('/api/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
```

---

## Testing Checklist

### Customer
- [ ] Create customer dengan data valid
- [ ] Create customer dengan phone duplicate (error)
- [ ] Update customer data
- [ ] Search customer by name
- [ ] Search customer by phone
- [ ] Delete customer
- [ ] View customer detail dengan history

### Service
- [ ] Create category
- [ ] Create service dalam category
- [ ] Update service price
- [ ] Toggle service active/inactive
- [ ] Filter services by category

### Appointment
- [ ] Create appointment untuk customer existing
- [ ] Create appointment dengan customer baru
- [ ] Available slots menampilkan slot yang benar
- [ ] Tidak bisa double booking di waktu yang sama
- [ ] Update status: pending → confirmed → in_progress → completed
- [ ] Cancel appointment dengan reason
- [ ] View calendar by day
- [ ] View calendar by week

---

## Files Summary

```
app/
├── Http/Controllers/
│   ├── CustomerController.php
│   ├── ServiceCategoryController.php
│   ├── ServiceController.php
│   └── AppointmentController.php
├── Http/Requests/
│   ├── StoreCustomerRequest.php
│   ├── UpdateCustomerRequest.php
│   ├── StoreServiceRequest.php
│   └── StoreAppointmentRequest.php
├── Models/
│   ├── Customer.php
│   ├── ServiceCategory.php
│   ├── Service.php
│   └── Appointment.php
└── Services/
    └── AppointmentService.php

database/
├── migrations/
│   ├── xxxx_create_customers_table.php
│   ├── xxxx_create_service_categories_table.php
│   ├── xxxx_create_services_table.php
│   └── xxxx_create_appointments_table.php
├── factories/
│   ├── CustomerFactory.php
│   ├── ServiceCategoryFactory.php
│   ├── ServiceFactory.php
│   └── AppointmentFactory.php
└── seeders/
    ├── CustomerSeeder.php
    ├── ServiceCategorySeeder.php
    └── ServiceSeeder.php

resources/views/
├── customers/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── service-categories/
│   ├── index.blade.php
│   └── form.blade.php
├── services/
│   ├── index.blade.php
│   └── form.blade.php
└── appointments/
    ├── index.blade.php
    ├── create.blade.php
    └── show.blade.php
```
