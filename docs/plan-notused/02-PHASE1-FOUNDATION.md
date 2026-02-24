# Phase 1: Foundation, Landing Page & Authentication

**Priority:** P0 (Must Have)
**Estimasi:** Week 1

---

## Checklist

### 1.0 Landing Page (Public)

Convert `docs/landing-page.html` ke Blade template.

- [ ] **Create Landing Layout** (resources/views/layouts/landing.blade.php)
  - Standalone layout untuk public pages
  - Include Tailwind, Alpine.js, Google Fonts
  - Decorative background elements (blobs)

- [ ] **Create LandingController**
  ```bash
  php artisan make:controller LandingController
  ```

- [ ] **Create Landing Page View** (resources/views/landing/index.blade.php)

  Sections:
  - [ ] **Navigation** - Logo, menu links, Masuk/Coba Gratis buttons
  - [ ] **Hero Section** - Headline, subheadline, CTA buttons, dashboard preview
  - [ ] **Features Section** - 6 feature cards dengan icons
  - [ ] **How It Works** - 3 steps process
  - [ ] **Pricing Section** - 3 pricing tiers (Basic, Professional, Enterprise)
  - [ ] **Testimonials** - Customer reviews carousel/grid
  - [ ] **FAQ Section** - Accordion dengan Alpine.js
  - [ ] **CTA Section** - Final call to action
  - [ ] **Footer** - Links, contact info, social media

- [ ] **Setup Route** (routes/web.php)
  ```php
  // Public routes
  Route::get('/', [LandingController::class, 'index'])->name('home');
  ```

- [ ] **Components untuk Landing**
  - [ ] `resources/views/components/landing/navbar.blade.php`
  - [ ] `resources/views/components/landing/feature-card.blade.php`
  - [ ] `resources/views/components/landing/pricing-card.blade.php`
  - [ ] `resources/views/components/landing/testimonial-card.blade.php`
  - [ ] `resources/views/components/landing/faq-item.blade.php`
  - [ ] `resources/views/components/landing/footer.blade.php`

- [ ] **Mobile Responsive**
  - Mobile menu dengan Alpine.js toggle
  - Responsive grid untuk features & pricing
  - Stack hero content di mobile

- [ ] **Animations** (CSS)
  ```css
  @keyframes float { ... }
  @keyframes fadeInUp { ... }
  .animate-float { animation: float 6s ease-in-out infinite; }
  .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
  ```

---

### 1.1 Project Setup

- [ ] **Laravel Fresh Install**
  ```bash
  composer create-project laravel/laravel glowup-clinic
  cd glowup-clinic
  ```

- [ ] **Configure Database** (.env)
  ```env
  DB_CONNECTION=mysql
  DB_DATABASE=glowup_clinic
  DB_USERNAME=root
  DB_PASSWORD=
  ```

- [ ] **Install Frontend Dependencies**
  ```bash
  npm install
  npm install -D tailwindcss@latest
  npm install alpinejs
  ```

- [ ] **Configure Tailwind** (vite.config.js, tailwind.config.js)
  - Setup custom colors (rose, primary, cream, peach)
  - Setup font families (Playfair Display, DM Sans)

- [ ] **Setup Alpine.js** (resources/js/app.js)
  ```javascript
  import Alpine from 'alpinejs';
  window.Alpine = Alpine;
  Alpine.start();
  ```

---

### 1.2 Database Migrations

- [ ] **Modify users table**
  ```bash
  php artisan make:migration add_role_to_users_table --table=users
  ```
  Fields tambahan:
  - role: enum('owner', 'admin', 'beautician')
  - phone: varchar(20)
  - avatar: varchar(255)
  - is_active: boolean default true

- [ ] **Create settings table**
  ```bash
  php artisan make:migration create_settings_table
  ```
  Fields:
  - key: varchar(100) unique
  - value: text
  - group: varchar(50)

- [ ] **Create operating_hours table**
  ```bash
  php artisan make:migration create_operating_hours_table
  ```
  Fields:
  - day_of_week: tinyint (0-6)
  - open_time: time
  - close_time: time
  - is_closed: boolean

- [ ] **Run Migrations**
  ```bash
  php artisan migrate
  ```

---

### 1.3 Models

- [ ] **Update User Model** (app/Models/User.php)
  ```php
  protected function casts(): array
  {
      return [
          'email_verified_at' => 'datetime',
          'password' => 'hashed',
          'is_active' => 'boolean',
      ];
  }

  public function isOwner(): bool
  {
      return $this->role === 'owner';
  }

  public function isAdmin(): bool
  {
      return $this->role === 'admin';
  }

  public function isBeautician(): bool
  {
      return $this->role === 'beautician';
  }
  ```

- [ ] **Create Setting Model**
  ```bash
  php artisan make:model Setting
  ```

- [ ] **Create OperatingHour Model**
  ```bash
  php artisan make:model OperatingHour
  ```

---

### 1.4 Seeders

- [ ] **Create UserSeeder**
  ```bash
  php artisan make:seeder UserSeeder
  ```
  Default users:
  - Owner: owner@glowup.com / password
  - Admin: admin@glowup.com / password
  - Beautician: maya@glowup.com / password

- [ ] **Create SettingSeeder**
  ```bash
  php artisan make:seeder SettingSeeder
  ```
  Default settings:
  - clinic_name: Glow Aesthetic Clinic
  - clinic_address: Jl. Sudirman No. 123, Jakarta
  - clinic_phone: 021-1234-5678
  - clinic_email: hello@glowclinic.com

- [ ] **Create OperatingHourSeeder**
  ```bash
  php artisan make:seeder OperatingHourSeeder
  ```
  Default: Senin-Sabtu 09:00-18:00, Minggu tutup

- [ ] **Run Seeders**
  ```bash
  php artisan db:seed
  ```

---

### 1.5 Authentication

- [ ] **Create Auth Controllers**
  ```bash
  php artisan make:controller Auth/LoginController
  php artisan make:controller Auth/LogoutController
  ```

- [ ] **Create Login Form Request**
  ```bash
  php artisan make:request Auth/LoginRequest
  ```
  Validation:
  - email: required, email
  - password: required, min:8

- [ ] **Setup Routes** (routes/web.php)
  ```php
  // Public routes
  Route::get('/', [LandingController::class, 'index'])->name('home');

  // Guest routes (not logged in)
  Route::middleware('guest')->group(function () {
      Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
      Route::post('/login', [LoginController::class, 'login']);
  });

  // Authenticated routes
  Route::middleware('auth')->group(function () {
      Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
      Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  });
  ```

- [ ] **Create RoleMiddleware**
  ```bash
  php artisan make:middleware RoleMiddleware
  ```
  Register di bootstrap/app.php

---

### 1.6 Layouts & Views

- [ ] **Create Guest Layout** (resources/views/layouts/guest.blade.php)
  - Clean layout untuk login page
  - Logo centered
  - Gradient background subtle

- [ ] **Create App Layout** (resources/views/layouts/app.blade.php)
  - Sidebar (collapsible)
  - Header dengan search, notifications, profile dropdown
  - Main content area
  - Alpine.js untuk interactivity

- [ ] **Create Login View** (resources/views/auth/login.blade.php)
  - Logo GlowUp
  - Email input
  - Password input dengan show/hide toggle
  - Remember me checkbox
  - Login button dengan gradient
  - Error handling

- [ ] **Create Sidebar Component** (resources/views/components/sidebar.blade.php)
  - Logo
  - Navigation items dengan icons
  - Active state (rose-50 background, rose-600 text)
  - Collapse toggle

- [ ] **Create Header Component** (resources/views/components/header.blade.php)
  - Mobile menu toggle
  - Search bar
  - New Booking button
  - Notification bell
  - Profile dropdown

---

### 1.7 Dashboard

- [ ] **Create DashboardController**
  ```bash
  php artisan make:controller DashboardController
  ```

- [ ] **Create Dashboard View** (resources/views/dashboard/index.blade.php)
  - Page header (Welcome back!)
  - 4 Stats cards:
    - Revenue Today
    - Appointments Today
    - New Customers (this week)
    - Completed Today
  - Revenue chart (Chart.js - bar chart)
  - Popular Services list
  - Today's Appointments table

- [ ] **Dummy Data untuk Dashboard**
  Sementara pakai data static, nanti akan diganti dengan real data

---

### 1.8 Assets & Build

- [ ] **Add Google Fonts** (resources/views/layouts/app.blade.php)
  ```html
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  ```

- [ ] **Add Chart.js**
  ```bash
  npm install chart.js
  ```

- [ ] **Build Assets**
  ```bash
  npm run build
  ```

- [ ] **Storage Link**
  ```bash
  php artisan storage:link
  ```

---

## Testing Checklist

### Landing Page
- [ ] Landing page accessible di `/`
- [ ] Navbar responsive - mobile menu berfungsi
- [ ] Semua section tampil dengan benar
- [ ] FAQ accordion berfungsi
- [ ] Smooth scroll ke section saat klik menu
- [ ] CTA buttons link ke `/login` dan `/register`
- [ ] Responsive di semua device sizes

### Authentication
- [ ] Login dengan credentials valid berhasil redirect ke dashboard
- [ ] Login dengan credentials invalid menampilkan error
- [ ] User inactive tidak bisa login
- [ ] Logout berhasil redirect ke login

### Dashboard
- [ ] Dashboard menampilkan semua stats cards
- [ ] Sidebar collapsible berfungsi
- [ ] Profile dropdown berfungsi
- [ ] Responsive di mobile

---

## Files yang Dibuat

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── LogoutController.php
│   │   ├── DashboardController.php
│   │   └── LandingController.php
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   └── Requests/
│       └── Auth/
│           └── LoginRequest.php
├── Models/
│   ├── User.php (modified)
│   ├── Setting.php
│   └── OperatingHour.php

database/
├── migrations/
│   ├── xxxx_add_role_to_users_table.php
│   ├── xxxx_create_settings_table.php
│   └── xxxx_create_operating_hours_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   ├── SettingSeeder.php
│   └── OperatingHourSeeder.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── guest.blade.php
│   └── landing.blade.php
├── components/
│   ├── sidebar.blade.php
│   ├── header.blade.php
│   └── landing/
│       ├── navbar.blade.php
│       ├── feature-card.blade.php
│       ├── pricing-card.blade.php
│       ├── testimonial-card.blade.php
│       ├── faq-item.blade.php
│       └── footer.blade.php
├── auth/
│   └── login.blade.php
├── dashboard/
│   └── index.blade.php
└── landing/
    └── index.blade.php

routes/
└── web.php

bootstrap/
└── app.php (middleware registration)
```

---

## Acceptance Criteria

1. Landing page tampil dengan baik di `/`
2. User bisa login dengan email dan password
3. Dashboard menampilkan informasi ringkasan
4. Sidebar navigasi berfungsi dengan baik
5. Layout responsive untuk mobile
6. Role-based access sudah disetup
