<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\LoyaltyRewardController;
use App\Http\Controllers\MemberAttendanceController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\CustomerPortalController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TreatmentRecordController;
use Illuminate\Support\Facades\Route;

// Setup Wizard routes (only accessible when setup not completed)
Route::middleware('setup.required')->prefix('setup')->name('setup.')->group(function () {
    Route::get('/', [SetupController::class, 'index'])->name('index');
    Route::get('/details', [SetupController::class, 'details'])->name('details');
    Route::post('/details', [SetupController::class, 'storeDetails'])->name('storeDetails');
    Route::get('/account', [SetupController::class, 'account'])->name('account');
    Route::post('/complete', [SetupController::class, 'complete'])->name('complete');
});

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/attendance/qr/{token}', [MemberAttendanceController::class, 'publicQr'])->name('attendance.public-qr');

// Public Booking routes (requires setup to be completed)
Route::middleware('setup.completed')->prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/slots', [BookingController::class, 'slots'])->name('slots');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    Route::get('/confirmation/{appointment}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::get('/status', [BookingController::class, 'checkStatus'])->name('status');
    Route::post('/cancel/{appointment}', [BookingController::class, 'cancel'])->name('cancel');
});

// Guest routes (requires setup to be completed)
Route::middleware(['guest', 'setup.completed'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes (requires setup to be completed)
Route::middleware(['auth', 'setup.completed', 'store.access'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Service Categories
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
    Route::post('service-categories/reorder', [ServiceCategoryController::class, 'reorder'])->name('service-categories.reorder');

    // Services
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::patch('services/{service}/toggle-active', [ServiceController::class, 'toggleActive'])->name('services.toggle-active');

    // Product Categories & Products (feature: products)
    Route::middleware('feature:products')->group(function () {
        Route::resource('product-categories', ProductCategoryController::class)->except(['show']);
        Route::post('product-categories/reorder', [ProductCategoryController::class, 'reorder'])->name('product-categories.reorder');
        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
        Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    });

    // Customers
    Route::resource('customers', CustomerController::class);

    // Member Attendance (feature: attendance)
    Route::middleware(['feature:attendance', 'role:owner,admin'])->prefix('member-attendance')->name('member-attendance.')->group(function () {
        Route::get('/', [MemberAttendanceController::class, 'index'])->name('index');
        Route::get('/qr', [MemberAttendanceController::class, 'qr'])->name('qr');
        Route::post('/qr/regenerate', [MemberAttendanceController::class, 'regenerateQr'])->name('qr.regenerate');
    });

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::get('appointments-slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.slots');
    Route::get('appointments-events', [AppointmentController::class, 'calendarEvents'])->name('appointments.events');

    // Treatment Records (feature: treatment_records - only for clinic)
    Route::middleware('feature:treatment_records')->group(function () {
        Route::resource('treatment-records', TreatmentRecordController::class);
        Route::delete('treatment-records/{treatment_record}/photo', [TreatmentRecordController::class, 'deletePhoto'])->name('treatment-records.delete-photo');
        Route::get('treatment-records/{treatment_record}/pdf', [TreatmentRecordController::class, 'exportPdf'])->name('treatment-records.pdf');
        Route::get('treatment-records-customer-pdf', [TreatmentRecordController::class, 'exportCustomerPdf'])->name('treatment-records.customer-pdf');
    });

    // Packages (feature: packages)
    Route::middleware('feature:packages')->group(function () {
        Route::resource('packages', PackageController::class);
        Route::patch('packages/{package}/toggle-active', [PackageController::class, 'toggleActive'])->name('packages.toggle-active');
    });

    // Customer Packages (feature: customer_packages)
    Route::middleware('feature:customer_packages')->group(function () {
        Route::get('customer-packages', [CustomerPackageController::class, 'index'])->name('customer-packages.index');
        Route::get('customer-packages/create', [CustomerPackageController::class, 'create'])->name('customer-packages.create');
        Route::post('customer-packages', [CustomerPackageController::class, 'store'])->name('customer-packages.store');
        Route::get('customer-packages/{customer_package}', [CustomerPackageController::class, 'show'])->name('customer-packages.show');
        Route::post('customer-packages/{customer_package}/use-session', [CustomerPackageController::class, 'useSession'])->name('customer-packages.use-session');
        Route::post('customer-packages/{customer_package}/cancel', [CustomerPackageController::class, 'cancel'])->name('customer-packages.cancel');
        Route::get('api/packages/{package}', [CustomerPackageController::class, 'getPackageDetails'])->name('web.api.packages.show');
        Route::get('api/customers/{customer}/packages', [CustomerPackageController::class, 'getCustomerPackages'])->name('web.api.customers.packages');
        Route::get('api/customers/{customer}/points', [CustomerController::class, 'getCustomerPoints'])->name('web.api.customers.points');
    });

    // Transactions
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('transactions/{transaction}/pay', [TransactionController::class, 'pay'])->name('transactions.pay');
    Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::get('transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
    Route::get('api/services/{service}/price', [TransactionController::class, 'getServicePrice'])->name('api.services.price');
    Route::get('api/packages/{package}/price', [TransactionController::class, 'getPackagePrice'])->name('api.packages.price');
    Route::get('api/products/{product}/price', [TransactionController::class, 'getProductPrice'])->name('api.products.price');

    // Staff (owner only)
    Route::middleware('role:owner')->group(function () {
        Route::resource('staff', StaffController::class)->except(['show']);
        Route::post('staff/{user}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset-password');
    });

    // Stores (owner/admin)
    Route::middleware('role:owner,admin')->group(function () {
        Route::resource('stores', StoreController::class)->except(['show', 'destroy']);
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/services', [ReportController::class, 'services'])->name('services');
        Route::get('/appointments', [ReportController::class, 'appointments'])->name('appointments');
        Route::get('/staff', [ReportController::class, 'staff'])->name('staff');
        Route::get('/loyalty', [ReportController::class, 'loyalty'])->name('loyalty');
        Route::get('/products', [ReportController::class, 'products'])->name('products');
        Route::get('/export/revenue', [ReportController::class, 'exportRevenue'])->name('export.revenue');
        Route::get('/export/customers', [ReportController::class, 'exportCustomers'])->name('export.customers');
    });

    // Settings (owner/admin)
    Route::middleware('role:owner,admin')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('/clinic', [SettingController::class, 'clinic'])->name('clinic');
        Route::post('/clinic', [SettingController::class, 'updateClinic'])->name('clinic.update');
        Route::get('/store', [SettingController::class, 'storeSettings'])->name('store');
        Route::post('/store', [SettingController::class, 'updateStoreSettings'])->name('store.update');
        Route::get('/hours', [SettingController::class, 'hours'])->name('hours');
        Route::post('/hours', [SettingController::class, 'updateHours'])->name('hours.update');
        Route::get('/branding', [SettingController::class, 'branding'])->name('branding');
        Route::post('/branding', [SettingController::class, 'updateBranding'])->name('branding.update');
        Route::post('/branding/remove-logo', [SettingController::class, 'removeLogo'])->name('branding.remove-logo');
    });

    // Import Data (owner/admin)
    Route::middleware('role:owner,admin')->prefix('imports')->name('imports.')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('index');
        Route::get('/{entity}/create', [ImportController::class, 'create'])->name('create');
        Route::post('/{entity}/upload', [ImportController::class, 'upload'])->name('upload');
        Route::get('/{entity}/preview', [ImportController::class, 'preview'])->name('preview');
        Route::post('/{entity}/process', [ImportController::class, 'process'])->name('process');
        Route::get('/{entity}/template', [ImportController::class, 'template'])->name('template');
        Route::get('/log/{import}', [ImportController::class, 'show'])->name('show');
        Route::delete('/log/{import}', [ImportController::class, 'destroy'])->name('destroy');
    });

    // Loyalty Program (feature: loyalty)
    Route::middleware('feature:loyalty')->prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [LoyaltyController::class, 'index'])->name('index');
        Route::get('/customers', [LoyaltyController::class, 'customers'])->name('customers');
        Route::get('/customers/{customer}/history', [LoyaltyController::class, 'customerHistory'])->name('customer-history');
        Route::post('/customers/{customer}/redeem', [LoyaltyController::class, 'redeem'])->name('redeem');
        Route::post('/customers/{customer}/adjust', [LoyaltyController::class, 'adjustPoints'])->name('adjust-points');
        Route::get('/redemptions', [LoyaltyController::class, 'redemptions'])->name('redemptions');
        Route::post('/redemptions/use', [LoyaltyController::class, 'useRedemption'])->name('use-redemption');
        Route::post('/redemptions/{redemption}/cancel', [LoyaltyController::class, 'cancelRedemption'])->name('cancel-redemption');
        Route::get('/check-code', [LoyaltyController::class, 'checkCode'])->name('check-code');
        Route::get('/referrals', [LoyaltyController::class, 'referrals'])->name('referrals');

        // Rewards (owner/admin)
        Route::middleware('role:owner,admin')->group(function () {
            Route::resource('rewards', LoyaltyRewardController::class);
            Route::patch('rewards/{reward}/toggle-active', [LoyaltyRewardController::class, 'toggleActive'])->name('rewards.toggle-active');
        });
    });
});

// Customer Portal routes
Route::middleware('setup.completed')->prefix('portal')->name('portal.')->group(function () {
    // Guest routes (not logged in as customer)
    Route::middleware('customer.guest')->group(function () {
        Route::get('/login', [PortalAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [PortalAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [PortalAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [PortalAuthController::class, 'register'])->name('register.submit');
    });

    // Authenticated customer routes
    Route::middleware(['customer.auth', 'feature:customer_portal'])->group(function () {
        Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');
        Route::get('/', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerPortalController::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');

        Route::middleware('customer.membership')->group(function () {
            Route::middleware(['feature:online_booking', 'customer.portal.premium:online_booking'])->group(function () {
                Route::get('/appointments', [CustomerPortalController::class, 'appointments'])->name('appointments');
                Route::get('/appointments/{id}', [CustomerPortalController::class, 'appointmentDetail'])->name('appointments.show');
            });

            Route::middleware(['feature:treatment_records', 'customer.portal.premium:treatment_records'])->group(function () {
                Route::get('/treatments', [CustomerPortalController::class, 'treatments'])->name('treatments');
                Route::get('/treatments/{id}', [CustomerPortalController::class, 'treatmentDetail'])->name('treatments.show');
            });

            Route::middleware(['feature:packages', 'customer.portal.premium:packages'])->group(function () {
                Route::get('/packages', [CustomerPortalController::class, 'packages'])->name('packages');
                Route::get('/packages/{id}', [CustomerPortalController::class, 'packageDetail'])->name('packages.show');
            });

            Route::middleware(['feature:loyalty', 'customer.portal.premium:loyalty'])->group(function () {
                Route::get('/loyalty', [CustomerPortalController::class, 'loyalty'])->name('loyalty');
            });

            Route::middleware('customer.portal.premium:transactions')->group(function () {
                Route::get('/transactions', [CustomerPortalController::class, 'transactions'])->name('transactions');
                Route::get('/transactions/{id}', [CustomerPortalController::class, 'transactionDetail'])->name('transactions.show');
            });

            Route::middleware('feature:attendance')->group(function () {
                Route::get('/attendance', [MemberAttendanceController::class, 'scan'])->name('attendance.scan');
                Route::post('/attendance', [MemberAttendanceController::class, 'store'])->name('attendance.store');
            });
        });
    });
});
