<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\LoyaltyController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ReferralController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\ServiceCategoryController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\StaffController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\TreatmentRecordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| All routes are prefixed with /api/v1
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Public Settings (for app initialization)
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/settings/clinic', [SettingController::class, 'clinic']);
Route::get('/settings/hours', [SettingController::class, 'hours']);
Route::get('/settings/branding', [SettingController::class, 'branding']);
Route::get('/settings/loyalty', [SettingController::class, 'loyalty']);
Route::get('/settings/referral', [SettingController::class, 'referral']);
Route::get('/settings/appointment', [SettingController::class, 'appointment']);
Route::get('/settings/payment-methods', [SettingController::class, 'paymentMethods']);

// Referral validation (public for registration)
Route::post('/referral/validate', [ReferralController::class, 'validateCode']);
Route::get('/referral/program-info', [ReferralController::class, 'programInfo']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

    // Service Categories
    Route::get('/service-categories', [ServiceCategoryController::class, 'index']);
    Route::get('/service-categories/{serviceCategory}', [ServiceCategoryController::class, 'show']);

    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{service}', [ServiceController::class, 'show']);

    // Products
    Route::get('/product-categories', [ProductController::class, 'categories']);
    Route::get('/product-categories/{productCategory}', [ProductController::class, 'showCategory']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Staff
    Route::get('/staff', [StaffController::class, 'index']);
    Route::get('/staff/beauticians', [StaffController::class, 'beauticians']);
    Route::get('/staff/{user}', [StaffController::class, 'show']);

    // Customers
    Route::apiResource('customers', CustomerController::class)->names('api.customers');
    Route::get('/customers/{customer}/stats', [CustomerController::class, 'stats'])->name('api.customers.stats');
    Route::get('/customers/{customer}/treatments', [CustomerController::class, 'treatments'])->name('api.customers.treatments');
    Route::get('/customers/{customer}/packages', [CustomerController::class, 'packages'])->name('api.customers.packages');
    Route::get('/customers/{customer}/appointments', [CustomerController::class, 'appointments'])->name('api.customers.appointments');

    // Customer Loyalty
    Route::get('/customers/{customer}/loyalty', [LoyaltyController::class, 'customerSummary'])->name('api.customers.loyalty');
    Route::get('/customers/{customer}/loyalty/points', [LoyaltyController::class, 'customerPoints'])->name('api.customers.loyalty.points');
    Route::post('/customers/{customer}/loyalty/redeem', [LoyaltyController::class, 'redeem'])->name('api.customers.loyalty.redeem');
    Route::get('/customers/{customer}/loyalty/redemptions', [LoyaltyController::class, 'customerRedemptions'])->name('api.customers.loyalty.redemptions');
    Route::post('/customers/{customer}/loyalty/adjust', [LoyaltyController::class, 'adjustPoints'])->name('api.customers.loyalty.adjust');

    // Customer Referral
    Route::get('/customers/{customer}/referral', [ReferralController::class, 'show'])->name('api.customers.referral');
    Route::get('/customers/{customer}/referral/history', [ReferralController::class, 'history'])->name('api.customers.referral.history');
    Route::get('/customers/{customer}/referral/referrals', [ReferralController::class, 'referrals'])->name('api.customers.referral.referrals');
    Route::post('/customers/{customer}/referral/apply', [ReferralController::class, 'apply'])->name('api.customers.referral.apply');

    // Loyalty Rewards
    Route::get('/loyalty/rewards', [LoyaltyController::class, 'rewards'])->name('api.loyalty.rewards');
    Route::get('/loyalty/rewards/{reward}', [LoyaltyController::class, 'showReward'])->name('api.loyalty.rewards.show');
    Route::post('/loyalty/check-code', [LoyaltyController::class, 'checkCode'])->name('api.loyalty.check-code');
    Route::post('/loyalty/use-code', [LoyaltyController::class, 'useCode'])->name('api.loyalty.use-code');
    Route::post('/loyalty/redemptions/{redemption}/cancel', [LoyaltyController::class, 'cancelRedemption'])->name('api.loyalty.redemptions.cancel');

    // Appointments
    Route::apiResource('appointments', AppointmentController::class)->names('api.appointments');
    Route::get('/appointments-available-slots', [AppointmentController::class, 'availableSlots'])->name('api.appointments.available-slots');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('api.appointments.update-status');
    Route::get('/appointments-today', [AppointmentController::class, 'today'])->name('api.appointments.today');
    Route::get('/appointments-calendar', [AppointmentController::class, 'calendar'])->name('api.appointments.calendar');

    // Treatment Records
    Route::apiResource('treatments', TreatmentRecordController::class)->names('api.treatments');

    // Packages
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{package}', [PackageController::class, 'show']);
    Route::get('/customer-packages', [PackageController::class, 'customerPackages']);
    Route::get('/customer-packages/usable', [PackageController::class, 'usablePackages']);
    Route::post('/customer-packages', [PackageController::class, 'storeCustomerPackage']);
    Route::get('/customer-packages/{customerPackage}', [PackageController::class, 'showCustomerPackage']);
    Route::post('/customer-packages/{customerPackage}/use', [PackageController::class, 'useSession']);

    // Transactions
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'show', 'store'])->names('api.transactions');
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])->name('api.transactions.pay');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('api.transactions.receipt');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('api.reports');
    Route::get('/reports/summary', [ReportController::class, 'summary'])->name('api.reports.summary');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('api.reports.revenue');
    Route::get('/reports/services', [ReportController::class, 'services'])->name('api.reports.services');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('api.reports.customers');
    Route::get('/reports/staff', [ReportController::class, 'staff'])->name('api.reports.staff');
    Route::get('/reports/packages', [ReportController::class, 'packages'])->name('api.reports.packages');
});
