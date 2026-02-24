<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoyaltyPointResource;
use App\Http\Resources\LoyaltyRedemptionResource;
use App\Http\Resources\LoyaltyRewardResource;
use App\Models\Customer;
use App\Models\LoyaltyRedemption;
use App\Models\LoyaltyReward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LoyaltyController extends Controller
{
    /**
     * Get available rewards list
     */
    public function rewards(Request $request): AnonymousResourceCollection
    {
        $rewards = LoyaltyReward::query()
            ->available()
            ->ordered()
            ->with(['service', 'product'])
            ->get();

        return LoyaltyRewardResource::collection($rewards);
    }

    /**
     * Get single reward detail
     */
    public function showReward(LoyaltyReward $reward): LoyaltyRewardResource
    {
        $reward->load(['service', 'product']);

        return new LoyaltyRewardResource($reward);
    }

    /**
     * Get customer loyalty points history
     */
    public function customerPoints(Customer $customer): AnonymousResourceCollection
    {
        $points = $customer->loyaltyPoints()
            ->with('transaction')
            ->latest()
            ->paginate(20);

        return LoyaltyPointResource::collection($points);
    }

    /**
     * Get customer loyalty summary
     */
    public function customerSummary(Customer $customer): JsonResponse
    {
        return response()->json([
            'data' => [
                'current_points' => $customer->loyalty_points,
                'lifetime_points' => $customer->lifetime_points,
                'tier' => $customer->loyalty_tier,
                'tier_label' => $customer->loyalty_tier_label,
                'total_earned' => $customer->loyaltyPoints()->earned()->sum('points'),
                'total_redeemed' => abs($customer->loyaltyPoints()->redeemed()->sum('points')),
                'pending_redemptions' => $customer->pendingRedemptions()->count(),
            ],
        ]);
    }

    /**
     * Redeem a reward for customer
     */
    public function redeem(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'reward_id' => 'required|exists:loyalty_rewards,id',
        ]);

        $reward = LoyaltyReward::findOrFail($request->reward_id);

        if (! $reward->canBeRedeemedBy($customer)) {
            return response()->json([
                'message' => 'Cannot redeem this reward. Check points balance, availability, or redemption limit.',
            ], 422);
        }

        // Deduct points
        $loyaltyPoint = $customer->deductLoyaltyPoints(
            $reward->points_required,
            'redeem',
            null,
            __('loyalty.points_redeemed', ['reward' => $reward->name])
        );

        if (! $loyaltyPoint) {
            return response()->json([
                'message' => 'Insufficient points.',
            ], 422);
        }

        // Create redemption
        $redemption = LoyaltyRedemption::create([
            'customer_id' => $customer->id,
            'loyalty_reward_id' => $reward->id,
            'loyalty_point_id' => $loyaltyPoint->id,
            'points_used' => $reward->points_required,
            'status' => 'pending',
            'valid_until' => now()->addDays(config('loyalty.redemption_validity_days', 30)),
        ]);

        // Decrease reward stock if tracked
        if ($reward->stock !== null) {
            $reward->decrement('stock');
        }

        $redemption->load(['reward', 'customer']);

        return response()->json([
            'message' => 'Reward redeemed successfully.',
            'data' => new LoyaltyRedemptionResource($redemption),
        ], 201);
    }

    /**
     * Get customer redemptions
     */
    public function customerRedemptions(Customer $customer, Request $request): AnonymousResourceCollection
    {
        $query = $customer->loyaltyRedemptions()
            ->with(['reward', 'transaction']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $redemptions = $query->latest()->paginate(20);

        return LoyaltyRedemptionResource::collection($redemptions);
    }

    /**
     * Check redemption code validity
     */
    public function checkCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $redemption = LoyaltyRedemption::with(['reward', 'customer'])
            ->where('code', $request->code)
            ->first();

        if (! $redemption) {
            return response()->json([
                'valid' => false,
                'message' => 'Redemption code not found.',
            ], 404);
        }

        return response()->json([
            'valid' => $redemption->is_valid,
            'status' => $redemption->status,
            'message' => $redemption->is_valid ? 'Code is valid.' : 'Code is expired or already used.',
            'data' => new LoyaltyRedemptionResource($redemption),
        ]);
    }

    /**
     * Use/apply redemption code
     */
    public function useCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'transaction_id' => 'nullable|exists:transactions,id',
        ]);

        $redemption = LoyaltyRedemption::with(['reward', 'customer'])
            ->where('code', $request->code)
            ->first();

        if (! $redemption) {
            return response()->json([
                'message' => 'Redemption code not found.',
            ], 404);
        }

        if (! $redemption->is_valid) {
            return response()->json([
                'message' => 'Code is expired or already used.',
            ], 422);
        }

        $transaction = $request->transaction_id
            ? \App\Models\Transaction::find($request->transaction_id)
            : null;

        $redemption->markAsUsed($transaction);

        return response()->json([
            'message' => 'Redemption code applied successfully.',
            'data' => new LoyaltyRedemptionResource($redemption->fresh(['reward', 'customer', 'transaction'])),
        ]);
    }

    /**
     * Cancel pending redemption
     */
    public function cancelRedemption(LoyaltyRedemption $redemption): JsonResponse
    {
        if ($redemption->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending redemptions can be cancelled.',
            ], 422);
        }

        $redemption->cancel();

        return response()->json([
            'message' => 'Redemption cancelled and points refunded.',
            'data' => new LoyaltyRedemptionResource($redemption->fresh(['reward', 'customer'])),
        ]);
    }

    /**
     * Adjust customer points (admin only)
     */
    public function adjustPoints(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'points' => 'required|integer',
            'description' => 'required|string|max:255',
        ]);

        $points = $request->points;

        if ($points > 0) {
            $loyaltyPoint = $customer->addLoyaltyPoints(
                $points,
                'adjust',
                null,
                $request->description
            );
        } else {
            $loyaltyPoint = $customer->deductLoyaltyPoints(
                abs($points),
                'adjust',
                null,
                $request->description
            );

            if (! $loyaltyPoint) {
                return response()->json([
                    'message' => 'Insufficient points for deduction.',
                ], 422);
            }
        }

        return response()->json([
            'message' => 'Points adjusted successfully.',
            'data' => [
                'adjustment' => new LoyaltyPointResource($loyaltyPoint),
                'new_balance' => $customer->fresh()->loyalty_points,
            ],
        ]);
    }
}
