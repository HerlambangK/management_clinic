<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ReferralLogResource;
use App\Models\Customer;
use App\Models\ReferralLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReferralController extends Controller
{
    /**
     * Get referral info for a customer
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'data' => [
                'referral_code' => $customer->referral_code,
                'referral_link' => url('/?ref='.$customer->referral_code),
                'stats' => $customer->referral_stats,
                'referrer' => $customer->referrer ? new CustomerResource($customer->referrer) : null,
            ],
        ]);
    }

    /**
     * Get customer's referral history (as referrer)
     */
    public function history(Customer $customer): AnonymousResourceCollection
    {
        $logs = ReferralLog::with(['referee', 'transaction'])
            ->where('referrer_id', $customer->id)
            ->latest()
            ->paginate(20);

        return ReferralLogResource::collection($logs);
    }

    /**
     * Get customers referred by this customer
     */
    public function referrals(Customer $customer): AnonymousResourceCollection
    {
        $referrals = Customer::where('referred_by_id', $customer->id)
            ->latest()
            ->paginate(20);

        return CustomerResource::collection($referrals);
    }

    /**
     * Validate a referral code
     */
    public function validateCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $referrer = Customer::where('referral_code', $request->code)->first();

        if (! $referrer) {
            return response()->json([
                'valid' => false,
                'message' => 'Referral code not found.',
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Referral code is valid.',
            'data' => [
                'referrer_name' => $referrer->name,
                'referrer_points' => config('referral.referrer_bonus_points', 100),
                'referee_points' => config('referral.referee_bonus_points', 50),
            ],
        ]);
    }

    /**
     * Apply referral code to customer (during registration)
     */
    public function apply(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if ($customer->referred_by_id) {
            return response()->json([
                'message' => 'Customer already has a referrer.',
            ], 422);
        }

        $referrer = Customer::where('referral_code', $request->code)->first();

        if (! $referrer) {
            return response()->json([
                'message' => 'Referral code not found.',
            ], 404);
        }

        if ($referrer->id === $customer->id) {
            return response()->json([
                'message' => 'Cannot use your own referral code.',
            ], 422);
        }

        // Link customer to referrer
        $customer->update(['referred_by_id' => $referrer->id]);

        // Create pending referral log
        ReferralLog::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $customer->id,
            'referrer_points' => config('referral.referrer_bonus_points', 100),
            'referee_points' => config('referral.referee_bonus_points', 50),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Referral code applied successfully. Points will be awarded after first purchase.',
            'data' => [
                'referrer' => new CustomerResource($referrer),
            ],
        ]);
    }

    /**
     * Get referral program info
     */
    public function programInfo(): JsonResponse
    {
        return response()->json([
            'data' => [
                'referrer_points' => config('referral.referrer_bonus_points', 100),
                'referee_points' => config('referral.referee_bonus_points', 50),
                'code_prefix' => config('referral.code_prefix', 'REF'),
                'terms' => [
                    'Points awarded after referee completes first transaction',
                    'Referrer receives '.config('referral.referrer_bonus_points', 100).' points',
                    'New customer receives '.config('referral.referee_bonus_points', 50).' points',
                ],
            ],
        ]);
    }
}
