<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyRedemption;
use App\Models\LoyaltyReward;
use App\Models\ReferralLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoyaltyController extends Controller
{
    public function index(Request $request): View
    {
        $query = LoyaltyPoint::with(['customer', 'transaction']);

        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $points = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_earned' => LoyaltyPoint::earned()->sum('points'),
            'total_redeemed' => abs(LoyaltyPoint::redeemed()->sum('points')),
            'active_customers' => Customer::where('loyalty_points', '>', 0)->count(),
        ];

        return view('loyalty.index', compact('points', 'stats'));
    }

    public function customers(Request $request): View
    {
        $query = Customer::withCount('loyaltyRedemptions')
            ->orderByDesc('lifetime_points');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('tier')) {
            $query->where('loyalty_tier', $request->tier);
        }

        $customers = $query->paginate(20)->withQueryString();

        $tierStats = [
            'bronze' => Customer::where('loyalty_tier', 'bronze')->orWhereNull('loyalty_tier')->count(),
            'silver' => Customer::where('loyalty_tier', 'silver')->count(),
            'gold' => Customer::where('loyalty_tier', 'gold')->count(),
            'platinum' => Customer::where('loyalty_tier', 'platinum')->count(),
        ];

        return view('loyalty.customers', compact('customers', 'tierStats'));
    }

    public function customerHistory(Customer $customer): View
    {
        $points = $customer->loyaltyPoints()
            ->with('transaction')
            ->latest()
            ->paginate(20);

        $redemptions = $customer->loyaltyRedemptions()
            ->with('reward')
            ->latest()
            ->get();

        $availableRewards = LoyaltyReward::available()
            ->ordered()
            ->get()
            ->filter(fn ($reward) => $reward->canBeRedeemedBy($customer));

        return view('loyalty.customer-history', compact('customer', 'points', 'redemptions', 'availableRewards'));
    }

    public function redeem(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'reward_id' => ['required', 'exists:loyalty_rewards,id'],
        ]);

        $reward = LoyaltyReward::findOrFail($request->reward_id);

        if (! $reward->canBeRedeemedBy($customer)) {
            return back()->with('error', __('loyalty.cannot_redeem'));
        }

        // Deduct points
        $loyaltyPoint = $customer->deductLoyaltyPoints(
            $reward->points_required,
            'redeem',
            null,
            __('loyalty.redeemed_for', ['reward' => $reward->name])
        );

        if (! $loyaltyPoint) {
            return back()->with('error', __('loyalty.insufficient_points'));
        }

        // Calculate validity
        $validUntil = Carbon::now()->addDays(config('loyalty.redemption_validity_days', 30));

        // Create redemption
        $redemption = LoyaltyRedemption::create([
            'customer_id' => $customer->id,
            'loyalty_reward_id' => $reward->id,
            'loyalty_point_id' => $loyaltyPoint->id,
            'points_used' => $reward->points_required,
            'status' => 'pending',
            'valid_until' => $validUntil,
        ]);

        // Decrease stock if applicable
        if ($reward->stock !== null) {
            $reward->decrement('stock');
        }

        return back()->with('success', __('loyalty.redeem_success', [
            'reward' => $reward->name,
            'code' => $redemption->code,
        ]));
    }

    public function adjustPoints(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'points' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $points = (int) $request->points;

        if ($points > 0) {
            $customer->addLoyaltyPoints($points, 'adjust', null, $request->description);
        } else {
            $result = $customer->deductLoyaltyPoints(abs($points), 'adjust', null, $request->description);
            if (! $result) {
                return back()->with('error', __('loyalty.insufficient_points'));
            }
        }

        return back()->with('success', __('loyalty.points_adjusted'));
    }

    public function redemptions(Request $request): View
    {
        $query = LoyaltyRedemption::with(['customer', 'reward']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->search.'%')
                    ->orWhereHas('customer', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $redemptions = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'pending' => LoyaltyRedemption::pending()->count(),
            'used' => LoyaltyRedemption::used()->count(),
            'total' => LoyaltyRedemption::count(),
        ];

        return view('loyalty.redemptions', compact('redemptions', 'stats'));
    }

    public function useRedemption(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $redemption = LoyaltyRedemption::where('code', $request->code)->first();

        if (! $redemption) {
            return back()->with('error', __('loyalty.code_not_found'));
        }

        if (! $redemption->is_valid) {
            $message = match ($redemption->status) {
                'used' => __('loyalty.code_already_used'),
                'expired' => __('loyalty.code_expired'),
                'cancelled' => __('loyalty.code_cancelled'),
                default => __('loyalty.code_invalid'),
            };

            return back()->with('error', $message);
        }

        $redemption->markAsUsed();

        return back()->with('success', __('loyalty.code_used_success', [
            'reward' => $redemption->reward->name,
            'customer' => $redemption->customer->name,
        ]));
    }

    public function cancelRedemption(LoyaltyRedemption $redemption): RedirectResponse
    {
        if ($redemption->status !== 'pending') {
            return back()->with('error', __('loyalty.cannot_cancel_redemption'));
        }

        $redemption->cancel();

        return back()->with('success', __('loyalty.redemption_cancelled'));
    }

    public function checkCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $redemption = LoyaltyRedemption::with(['customer', 'reward'])
            ->where('code', $request->code)
            ->first();

        if (! $redemption) {
            return response()->json([
                'valid' => false,
                'message' => __('loyalty.code_not_found'),
            ]);
        }

        return response()->json([
            'valid' => $redemption->is_valid,
            'status' => $redemption->status,
            'status_label' => $redemption->status_label,
            'customer' => $redemption->customer->name,
            'reward' => $redemption->reward->name,
            'reward_type' => $redemption->reward->reward_type,
            'reward_value' => $redemption->reward->reward_value,
            'valid_until' => $redemption->valid_until?->format('d M Y'),
            'message' => $redemption->is_valid ? __('loyalty.code_valid') : __('loyalty.code_invalid'),
        ]);
    }

    public function referrals(Request $request): View
    {
        $query = ReferralLog::with(['referrer', 'referee', 'transaction']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('referrer', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('phone', 'like', '%'.$request->search.'%');
                })->orWhereHas('referee', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('phone', 'like', '%'.$request->search.'%');
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $referrals = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => ReferralLog::count(),
            'pending' => ReferralLog::pending()->count(),
            'rewarded' => ReferralLog::rewarded()->count(),
            'total_referrer_points' => ReferralLog::rewarded()->sum('referrer_points'),
            'total_referee_points' => ReferralLog::rewarded()->sum('referee_points'),
        ];

        return view('loyalty.referrals', compact('referrals', 'stats'));
    }
}
