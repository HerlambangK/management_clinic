<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use App\Models\Product;
use App\Models\ReferralLog;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['customer', 'cashier', 'items'])
            ->withCount('items');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', function ($q2) use ($request) {
                        $q2->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $todayStats = [
            'total' => Transaction::today()->count(),
            'paid' => Transaction::today()->paid()->count(),
            'revenue' => Transaction::today()->paid()->sum('total_amount'),
        ];

        return view('transactions.index', compact('transactions', 'todayStats'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $packages = Package::active()->ordered()->get();
        $products = Product::active()->inStock()->with('category')->orderBy('name')->get();

        $selectedCustomerId = $request->get('customer_id');
        $appointmentId = $request->get('appointment_id');
        $appointment = null;

        if ($appointmentId) {
            $appointment = Appointment::with(['customer', 'service'])->find($appointmentId);
        }

        return view('transactions.create', compact('customers', 'services', 'packages', 'products', 'selectedCustomerId', 'appointment'));
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
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

                // Validate points
                if ($requestedPoints >= $minPoints && $requestedPoints <= $availablePoints) {
                    $maxPointsDiscount = $subtotal - $discountAmount; // Can't exceed subtotal after discount
                    $requestedDiscount = $requestedPoints * $pointsValue;

                    if ($requestedDiscount > $maxPointsDiscount) {
                        // Adjust points to not exceed max discount
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
                    // Update customer stats
                    $appointment->customer->increment('total_visits');
                    $appointment->customer->update(['last_visit' => today()]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil dibuat.'.($request->appointment_id ? ' Appointment telah diselesaikan.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Gagal membuat transaksi: '.$e->getMessage());
        }
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['customer', 'appointment.service', 'cashier', 'items', 'payments.receiver']);

        return view('transactions.show', compact('transaction'));
    }

    public function pay(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->status === 'paid') {
            return back()->with('error', 'Transaksi sudah lunas.');
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
                // Apply tier multiplier
                $multiplier = config('loyalty.tier_multipliers.'.$customer->loyalty_tier, 1.0);
                $earnedPoints = (int) round($basePoints * $multiplier);

                // Calculate expiry
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

        return back()->with('success', __('transaction.payment_recorded'));
    }

    /**
     * Process referral reward for customer's first paid transaction
     */
    private function processReferralReward(Customer $customer, Transaction $transaction): void
    {
        // Check if referral program is enabled
        if (! config('referral.enabled', true)) {
            return;
        }

        // Check if customer was referred and reward not yet given
        if (! $customer->hasUnrewardedReferral()) {
            return;
        }

        // Check minimum transaction amount
        $minAmount = config('referral.min_transaction_amount', 0);
        if ($minAmount > 0 && $transaction->total_amount < $minAmount) {
            return;
        }

        // Check if this is the first paid transaction for this customer
        $paidTransactionsCount = $customer->transactions()
            ->paid()
            ->count();

        if ($paidTransactionsCount > 1) {
            return; // Not the first paid transaction
        }

        $referrer = $customer->referrer;
        if (! $referrer) {
            return;
        }

        $referrerPoints = (int) config('referral.referrer_bonus_points', 100);
        $refereePoints = (int) config('referral.referee_bonus_points', 50);

        // Give points to referrer
        if ($referrerPoints > 0) {
            $referrer->addLoyaltyPoints(
                $referrerPoints,
                'earn',
                null,
                __('loyalty.referral_bonus_referrer', ['name' => $customer->name])
            );
        }

        // Give points to referee (the new customer)
        if ($refereePoints > 0) {
            $customer->addLoyaltyPoints(
                $refereePoints,
                'earn',
                null,
                __('loyalty.referral_bonus_referee', ['name' => $referrer->name])
            );
        }

        // Create referral log
        $referralLog = ReferralLog::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $customer->id,
            'referrer_points' => $referrerPoints,
            'referee_points' => $refereePoints,
            'transaction_id' => $transaction->id,
            'status' => 'rewarded',
            'rewarded_at' => now(),
        ]);

        // Mark customer's referral as rewarded
        $customer->update(['referral_rewarded_at' => now()]);
    }

    /**
     * Create CustomerPackage records for package items in a paid transaction
     */
    private function createCustomerPackagesFromTransaction(Transaction $transaction): void
    {
        $packageItems = $transaction->items()->where('item_type', 'package')->whereNotNull('package_id')->get();

        foreach ($packageItems as $item) {
            $package = Package::find($item->package_id);
            if (! $package) {
                continue;
            }

            // Create CustomerPackage for each quantity
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

    public function cancel(Transaction $transaction): RedirectResponse
    {
        if (! in_array($transaction->status, ['pending', 'partial'])) {
            return back()->with('error', 'Transaksi tidak dapat dibatalkan.');
        }

        // Refund loyalty points if any were used
        if ($transaction->points_used > 0 && $transaction->customer) {
            $transaction->customer->addLoyaltyPoints(
                $transaction->points_used,
                'refund',
                __('loyalty.points_refunded_transaction', ['invoice' => $transaction->invoice_number])
            );
        }

        $transaction->update(['status' => 'cancelled']);

        return back()->with('success', 'Transaksi berhasil dibatalkan.');
    }

    public function invoice(Transaction $transaction): View
    {
        $transaction->load(['customer', 'items', 'payments']);

        return view('transactions.invoice', compact('transaction'));
    }

    public function getServicePrice(Service $service)
    {
        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'price' => $service->price,
            'formatted_price' => $service->formatted_price,
        ]);
    }

    public function getPackagePrice(Package $package)
    {
        return response()->json([
            'id' => $package->id,
            'name' => $package->name,
            'price' => $package->package_price,
            'formatted_price' => $package->formatted_package_price,
        ]);
    }

    public function getProductPrice(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'formatted_price' => $product->formatted_price,
            'stock' => $product->stock,
            'track_stock' => $product->track_stock,
        ]);
    }
}
