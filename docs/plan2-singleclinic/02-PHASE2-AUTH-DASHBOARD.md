# Phase 2: Authentication & Dashboard

## Overview
Setup authentication system dan dashboard layout sebagai foundation untuk semua modul.

---

## 2.1 Authentication

### Tasks
- [ ] Setup Laravel built-in authentication
- [ ] Create login page dengan design system
- [ ] Implement remember me functionality
- [ ] Add role field to users table (owner, admin, beautician)
- [ ] Create RoleMiddleware untuk access control
- [ ] Redirect berdasarkan role setelah login
- [ ] Logout functionality

### Files to Create/Modify
```
app/Http/Controllers/Auth/AuthController.php
app/Http/Middleware/RoleMiddleware.php
app/Http/Requests/Auth/LoginRequest.php
resources/views/auth/login.blade.php
routes/web.php
bootstrap/app.php (middleware registration)
database/migrations/xxxx_add_role_to_users_table.php
```

### Login Page Design
```
┌────────────────────────────────────────────────────────────┐
│                                                            │
│                     ┌─────────────────────┐               │
│                     │     GlowUp Logo     │               │
│                     └─────────────────────┘               │
│                                                            │
│                     Masuk ke Dashboard                     │
│                                                            │
│     ┌────────────────────────────────────────────┐        │
│     │ Email                                      │        │
│     └────────────────────────────────────────────┘        │
│                                                            │
│     ┌────────────────────────────────────────────┐        │
│     │ Password                              👁️   │        │
│     └────────────────────────────────────────────┘        │
│                                                            │
│     ☑️ Ingat saya                                          │
│                                                            │
│     ┌────────────────────────────────────────────┐        │
│     │              MASUK                         │        │
│     └────────────────────────────────────────────┘        │
│                                                            │
│                   Lupa password?                           │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### User Roles & Permissions
| Role | Permissions |
|------|------------|
| owner | Full access ke semua modul |
| admin | CRUD customers, appointments, transactions. View reports |
| beautician | View jadwal sendiri, input treatment records |

---

## 2.2 Dashboard Layout

### Tasks
- [ ] Create dashboard layout (sidebar + main content)
- [ ] Create sidebar component dengan navigation
- [ ] Create header component dengan search, notifications, profile
- [ ] Mobile responsive (collapsible sidebar)
- [ ] Active state untuk current page

### Files to Create
```
resources/views/layouts/dashboard.blade.php
resources/views/components/sidebar.blade.php
resources/views/components/header.blade.php
resources/views/components/mobile-menu.blade.php
```

### Layout Structure
```html
<!-- layouts/dashboard.blade.php -->
<div class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="lg:pl-64">
        <!-- Header -->
        @include('components.header')

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</div>
```

### Sidebar Navigation Items
```
Dashboard          (icon: home)
─────────────────
Appointments       (icon: calendar)
Customers          (icon: users)
Services           (icon: sparkles)
Packages           (icon: gift)
─────────────────
Transactions       (icon: credit-card)
Reports            (icon: chart-bar)
─────────────────
Staff              (icon: user-group) [owner only]
Settings           (icon: cog) [owner/admin]
```

---

## 2.3 Dashboard Overview

### Tasks
- [ ] Create DashboardController
- [ ] Dashboard view dengan stats cards
- [ ] Revenue chart (last 7 days atau 30 days)
- [ ] Today's appointments list
- [ ] Popular services widget
- [ ] Quick actions (new booking, new customer)

### Files to Create
```
app/Http/Controllers/DashboardController.php
resources/views/dashboard/index.blade.php
```

### Dashboard Widgets

#### Stats Cards (Row 1)
```
┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ Revenue     │ │ Appointments│ │ New         │ │ Completed   │
│ Today       │ │ Today       │ │ Customers   │ │ Today       │
│ Rp 3.2 Jt   │ │ 12          │ │ 5 (week)    │ │ 8           │
│ +15% ▲      │ │ 2 pending   │ │ +20% ▲      │ │             │
└─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘
```

#### Revenue Chart (Row 2 - Left)
- Bar/Line chart revenue 7 hari terakhir
- Library: Chart.js
- Show trend comparison

#### Popular Services (Row 2 - Right)
- Top 5 services by revenue
- Show service name & total revenue

#### Today's Appointments (Row 3)
- Table: Time | Customer | Service | Staff | Status | Actions
- Quick actions: Confirm, Start, Complete
- Color coded by status

### Data Requirements
```php
// DashboardController@index
return view('dashboard.index', [
    'stats' => [
        'revenue_today' => Transaction::whereDate('created_at', today())->sum('total'),
        'appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
        'pending_appointments' => Appointment::whereDate('appointment_date', today())
            ->where('status', 'pending')->count(),
        'new_customers_week' => Customer::where('created_at', '>=', now()->subDays(7))->count(),
        'completed_today' => Appointment::whereDate('appointment_date', today())
            ->where('status', 'completed')->count(),
    ],
    'revenue_chart' => $this->getRevenueChart(7), // last 7 days
    'popular_services' => $this->getPopularServices(5),
    'today_appointments' => Appointment::with(['customer', 'service', 'staff'])
        ->whereDate('appointment_date', today())
        ->orderBy('start_time')
        ->get(),
]);
```

---

## 2.4 Database Migrations

### Users Table Modification
```php
// add_role_to_users_table.php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['owner', 'admin', 'beautician'])->default('admin')->after('password');
    $table->string('phone', 20)->nullable()->after('role');
    $table->string('avatar')->nullable()->after('phone');
    $table->boolean('is_active')->default(true)->after('avatar');
});
```

### Default User Seeder
```php
// UserSeeder.php
User::create([
    'name' => 'Owner',
    'email' => 'owner@glowup.test',
    'password' => bcrypt('password'),
    'role' => 'owner',
    'is_active' => true,
]);

User::create([
    'name' => 'Admin',
    'email' => 'admin@glowup.test',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'is_active' => true,
]);
```

---

## 2.5 Routes

```php
// routes/web.php

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // More routes will be added in next phases...
});
```

---

## Acceptance Criteria

### Authentication
- [x] User bisa login dengan email & password
- [x] Error message jelas jika credentials salah
- [x] Remember me functionality works
- [x] Redirect ke dashboard setelah login berhasil
- [x] Logout works dan redirect ke login

### Dashboard
- [x] Sidebar navigation responsive
- [x] Stats cards menampilkan data real-time
- [x] Revenue chart menampilkan 7 hari terakhir
- [x] Today's appointments list dengan status
- [x] Quick actions berfungsi
