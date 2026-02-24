<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyRewardRequest;
use App\Models\LoyaltyReward;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoyaltyRewardController extends Controller
{
    public function index(Request $request): View
    {
        $query = LoyaltyReward::withCount(['redemptions', 'redemptions as used_count' => function ($q) {
            $q->where('status', 'used');
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('type')) {
            $query->where('reward_type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $rewards = $query->ordered()->paginate(10)->withQueryString();

        $stats = [
            'total' => LoyaltyReward::count(),
            'active' => LoyaltyReward::active()->count(),
            'total_redemptions' => \App\Models\LoyaltyRedemption::count(),
        ];

        return view('loyalty.rewards.index', compact('rewards', 'stats'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();
        $rewardTypes = LoyaltyReward::REWARD_TYPES;

        return view('loyalty.rewards.create', compact('services', 'products', 'rewardTypes'));
    }

    public function store(LoyaltyRewardRequest $request): RedirectResponse
    {
        LoyaltyReward::create($request->validated());

        return redirect()->route('loyalty.rewards.index')
            ->with('success', __('loyalty.reward_created'));
    }

    public function show(LoyaltyReward $reward): View
    {
        $reward->load(['service', 'product']);
        $redemptions = $reward->redemptions()
            ->with('customer')
            ->latest()
            ->paginate(10);

        return view('loyalty.rewards.show', compact('reward', 'redemptions'));
    }

    public function edit(LoyaltyReward $reward): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();
        $rewardTypes = LoyaltyReward::REWARD_TYPES;

        return view('loyalty.rewards.edit', compact('reward', 'services', 'products', 'rewardTypes'));
    }

    public function update(LoyaltyRewardRequest $request, LoyaltyReward $reward): RedirectResponse
    {
        $reward->update($request->validated());

        return redirect()->route('loyalty.rewards.index')
            ->with('success', __('loyalty.reward_updated'));
    }

    public function destroy(LoyaltyReward $reward): RedirectResponse
    {
        if ($reward->redemptions()->whereIn('status', ['pending', 'used'])->count() > 0) {
            return back()->with('error', __('loyalty.reward_has_redemptions'));
        }

        $reward->delete();

        return redirect()->route('loyalty.rewards.index')
            ->with('success', __('loyalty.reward_deleted'));
    }

    public function toggleActive(LoyaltyReward $reward): RedirectResponse
    {
        $reward->update(['is_active' => ! $reward->is_active]);

        $message = $reward->is_active ? __('loyalty.reward_activated') : __('loyalty.reward_deactivated');

        return back()->with('success', $message);
    }
}
