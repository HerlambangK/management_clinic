<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use App\Models\Product;
use App\Models\ReferralLog;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Transaction::query()->with(['customer', 'cashier']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $perPage = $request->integer('per_page', 15);
        $transactions = $query->latest()->paginate($perPage);

        return TransactionResource::collection($transactions);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['customer', 'appointment.service', 'cashier', 'items', 'payments']);

        return response()->json([
            'data' => new TransactionResource($transaction),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:service,package,product,other'],
            'items.*.service_id' => ['nullable', 'exists:services,id'],
            'items.*.package_id' => ['nullable', 'exists:packages,id'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.customer_package_id' => ['nullable', 'exists:customer_packages,id'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'string', 'max:50'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'points_used' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'customer_id.required' => 'Customer harus dipilih.',
            'items.required' => 'Minimal 1 item harus ditambahkan.',
            'items.min' => 'Minimal 1 item harus ditambahkan.',
            'items.*.item_name.required' => 'Nama item harus diisi.',
            'items.*.quantity.required' => 'Jumlah harus diisi.',
            'items.*.unit_price.required' => 'Harga harus diisi.',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $totalPrice = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
                $subtotal += $totalPrice;

                $itemsData[] = [
                    'item_type' => $item['item_type'],
                    'service_id' => $item['service_id'] ?? null,
                    'package_id' => $item['package_id'] ?? null,
                    'product_id' => $item['product_id'] ?? null,
                    'customer_package_id' => $item['customer_package_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'] ?? null,
                ];

                // Decrease product stock if it's a product item
                if ($item['item_type'] === 'product' && ! empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->track_stock) {
                        $product->decreaseStock($item['quantity']);
                    }
                }
            }

            $discountAmount = $request->discount_amount ?? 0;
            $taxAmount = $request->tax_amount ?? 0;

            // Handle loyalty points redemption
            $pointsUsed = 0;
            $pointsDiscount = 0;
            $customer = Customer::find($request->customer_id);

            if ($request->filled('points_used') && $request->points_used > 0 && $customer) {
                $requestedPoints = (int) $request->points_used;
                $availablePoints = $customer->loyalty_points;
                $pointsValue = config('loyalty.points_value', 100);
                $minPoints = config('loyalty.min_points_redeem', 10);

                if ($requestedPoints >= $minPoints && $requestedPoints <= $availablePoints) {
                    $maxPointsDiscount = $subtotal - $discountAmount;
                    $requestedDiscount = $requestedPoints * $pointsValue;

                    if ($requestedDiscount > $maxPointsDiscount) {
                        $pointsUsed = (int) floor($maxPointsDiscount / $pointsValue);
                        $pointsDiscount = $pointsUsed * $pointsValue;
                    } else {
                        $pointsUsed = $requestedPoints;
                        $pointsDiscount = $requestedDiscount;
                    }
                }
            }

            $totalAmount = $subtotal - $discountAmount - $pointsDiscount + $taxAmount;

            $transaction = Transaction::create([
                'customer_id' => $request->customer_id,
                'appointment_id' => $request->appointment_id,
                'cashier_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'discount_type' => $request->discount_type,
                'points_used' => $pointsUsed,
                'points_discount' => $pointsDiscount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Deduct loyalty points immediately when transaction is created
            if ($pointsUsed > 0 && $customer) {
                $customer->addLoyaltyPoints(
                    -$pointsUsed,
                    'redeem',
                    $transaction,
                    __('loyalty.points_used_transaction', ['invoice' => $transaction->invoice_number])
                );
            }

            foreach ($itemsData as $item) {
                $transaction->items()->create($item);
            }

            // If transaction is created from an appointment, mark it as completed
            if ($request->appointment_id) {
                $appointment = Appointment::find($request->appointment_id);
                if ($appointment && $appointment->status === 'in_progress') {
                    $appointment->update(['status' => 'completed']);
                    $appointment->customer->increment('total_visits');
                    $appointment->customer->update(['last_visit' => today()]);
                }
            }

            DB::commit();

            $transaction->load(['customer', 'appointment.service', 'cashier', 'items', 'payments']);

            return response()->json([
                'message' => 'Transaksi berhasil dibuat.',
                'data' => new TransactionResource($transaction),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal membuat transaksi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function pay(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->status === 'paid') {
            return response()->json([
                'message' => 'Transaksi sudah lunas.',
            ], 422);
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'in:cash,debit_card,credit_card,transfer,qris,other'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transaction->addPayment(
            amount: $request->amount,
            method: $request->payment_method,
            reference: $request->reference_number,
            notes: $request->notes
        );

        // Update customer total spent and add loyalty points if fully paid
        if ($transaction->status === 'paid') {
            $customer = $transaction->customer;
            $customer->increment('total_spent', $transaction->total_amount);

            // Add loyalty points
            $basePoints = $customer->calculatePointsFromAmount($transaction->total_amount);
            if ($basePoints > 0) {
                $multiplier = config('loyalty.tier_multipliers.'.$customer->loyalty_tier, 1.0);
                $earnedPoints = (int) round($basePoints * $multiplier);

                $expiryMonths = config('loyalty.points_expiry_months');
                $expiresAt = $expiryMonths ? now()->addMonths($expiryMonths)->toDateString() : null;

                $customer->addLoyaltyPoints(
                    $earnedPoints,
                    'earn',
                    $transaction,
                    __('loyalty.points_from_transaction', ['invoice' => $transaction->invoice_number]),
                    $expiresAt
                );
            }

            // Process referral reward if applicable
            $this->processReferralReward($customer, $transaction);

            // Create CustomerPackage for package items in transaction
            $this->createCustomerPackagesFromTransaction($transaction);
        }

        $transaction->load(['customer', 'appointment.service', 'cashier', 'items', 'payments']);

        return response()->json([
            'message' => __('transaction.payment_recorded'),
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * Process referral reward for customer's first paid transaction.
     */
    private function processReferralReward(Customer $customer, Transaction $transaction): void
    {
        if (! config('referral.enabled', true)) {
            return;
        }

        if (! $customer->hasUnrewardedReferral()) {
            return;
        }

        $minAmount = config('referral.min_transaction_amount', 0);
        if ($minAmount > 0 && $transaction->total_amount < $minAmount) {
            return;
        }

        $paidTransactionsCount = $customer->transactions()
            ->paid()
            ->count();

        if ($paidTransactionsCount > 1) {
            return;
        }

        $referrer = $customer->referrer;
        if (! $referrer) {
            return;
        }

        $referrerPoints = (int) config('referral.referrer_bonus_points', 100);
        $refereePoints = (int) config('referral.referee_bonus_points', 50);

        if ($referrerPoints > 0) {
            $referrer->addLoyaltyPoints(
                $referrerPoints,
                'earn',
                null,
                __('loyalty.referral_bonus_referrer', ['name' => $customer->name])
            );
        }

        if ($refereePoints > 0) {
            $customer->addLoyaltyPoints(
                $refereePoints,
                'earn',
                null,
                __('loyalty.referral_bonus_referee', ['name' => $referrer->name])
            );
        }

        ReferralLog::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $customer->id,
            'referrer_points' => $referrerPoints,
            'referee_points' => $refereePoints,
            'transaction_id' => $transaction->id,
            'status' => 'rewarded',
            'rewarded_at' => now(),
        ]);

        $customer->update(['referral_rewarded_at' => now()]);
    }

    /**
     * Create CustomerPackage records for package items in a paid transaction.
     */
    private function createCustomerPackagesFromTransaction(Transaction $transaction): void
    {
        $packageItems = $transaction->items()->where('item_type', 'package')->whereNotNull('package_id')->get();

        foreach ($packageItems as $item) {
            $package = Package::find($item->package_id);
            if (! $package) {
                continue;
            }

            for ($i = 0; $i < $item->quantity; $i++) {
                CustomerPackage::create([
                    'customer_id' => $transaction->customer_id,
                    'package_id' => $package->id,
                    'sold_by' => $transaction->cashier_id,
                    'price_paid' => $item->unit_price,
                    'sessions_total' => $package->total_sessions,
                    'sessions_used' => 0,
                    'purchased_at' => $transaction->created_at->toDateString(),
                    'expires_at' => $transaction->created_at->copy()->addDays($package->validity_days)->toDateString(),
                    'status' => 'active',
                    'notes' => __('package.purchased_via_transaction', ['invoice' => $transaction->invoice_number]),
                ]);
            }
        }
    }

    public function receipt(Transaction $transaction): JsonResponse
    {
        $transaction->load(['customer', 'items', 'payments']);

        $clinicName = Setting::where('key', 'clinic_name')->value('value') ?? 'GlowUp Clinic';
        $clinicAddress = Setting::where('key', 'clinic_address')->value('value') ?? '';
        $clinicPhone = Setting::where('key', 'clinic_phone')->value('value') ?? '';

        return response()->json([
            'data' => [
                'clinic' => [
                    'name' => $clinicName,
                    'address' => $clinicAddress,
                    'phone' => $clinicPhone,
                ],
                'transaction' => new TransactionResource($transaction),
            ],
        ]);
    }
}
