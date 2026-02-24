<?php

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\ImportLog;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyRedemption;
use App\Models\LoyaltyReward;
use App\Models\MemberAttendance;
use App\Models\OperatingHour;
use App\Models\Package;
use App\Models\PackageUsage;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ReferralLog;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TreatmentRecord;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('customer_packages', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('import_logs', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('loyalty_rewards', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('loyalty_redemptions', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('member_attendances', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('operating_hours', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('package_usages', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('referral_logs', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('service_categories', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        Schema::table('treatment_records', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
        });

        $store = $this->resolveDefaultStore();

        if (! $store) {
            return;
        }

        Appointment::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        Customer::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        CustomerPackage::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        ImportLog::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        LoyaltyPoint::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        LoyaltyReward::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        LoyaltyRedemption::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        MemberAttendance::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        OperatingHour::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        Package::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        PackageUsage::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        Payment::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        ProductCategory::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        Product::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        ReferralLog::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        Service::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        ServiceCategory::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        Transaction::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
        TransactionItem::query()->whereNull('store_id')->update(['store_id' => $store->id]);
        TreatmentRecord::withTrashed()->whereNull('store_id')->update(['store_id' => $store->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('referral_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('package_usages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('operating_hours', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('member_attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('loyalty_redemptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('loyalty_rewards', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('import_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('customer_packages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });
    }

    private function resolveDefaultStore(): ?Store
    {
        $store = Store::query()->first();

        if ($store) {
            return $store;
        }

        $businessType = 'clinic';
        $businessName = 'Main Store';

        try {
            $businessType = Setting::get('business_type', $businessType);
            $businessName = Setting::get('business_name', $businessName);
        } catch (\Exception $e) {
            //
        }

        $owner = User::query()->where('role', 'owner')->orderBy('id')->first();

        $storeId = DB::table('stores')->insertGetId([
            'name' => $businessName ?: 'Main Store',
            'business_type' => $businessType ?: 'clinic',
            'owner_id' => $owner?->id,
            'is_active' => true,
            'is_approved' => true,
            'approved_by' => $owner?->id,
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Store::query()->find($storeId);
    }
};
