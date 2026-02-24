<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OperatingHourResource;
use App\Models\OperatingHour;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get clinic/business settings
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'clinic' => $this->getClinicInfo(),
                'operating_hours' => $this->getOperatingHours(),
                'appointment' => $this->getAppointmentSettings(),
                'features' => $this->getEnabledFeatures(),
                'business_type' => config('business.type', 'clinic'),
            ],
        ]);
    }

    /**
     * Get clinic info only
     */
    public function clinic(): JsonResponse
    {
        return response()->json([
            'data' => $this->getClinicInfo(),
        ]);
    }

    /**
     * Get operating hours
     */
    public function hours(): JsonResponse
    {
        return response()->json([
            'data' => $this->getOperatingHours(),
        ]);
    }

    /**
     * Get branding info
     */
    public function branding(): JsonResponse
    {
        $logo = Setting::get('clinic_logo');

        return response()->json([
            'data' => [
                'logo' => $logo,
                'logo_url' => $logo ? asset('storage/'.$logo) : null,
                'primary_color' => Setting::get('primary_color', '#f43f5e'),
                'secondary_color' => Setting::get('secondary_color', '#cc4637'),
            ],
        ]);
    }

    /**
     * Get loyalty program settings
     */
    public function loyalty(): JsonResponse
    {
        return response()->json([
            'data' => [
                'enabled' => in_array('loyalty', config('business.features', [])),
                'points_per_amount' => config('loyalty.points_per_amount', 10000),
                'tiers' => config('loyalty.tiers', [
                    'bronze' => 0,
                    'silver' => 1000,
                    'gold' => 5000,
                    'platinum' => 10000,
                ]),
                'redemption_validity_days' => config('loyalty.redemption_validity_days', 30),
            ],
        ]);
    }

    /**
     * Get referral program settings
     */
    public function referral(): JsonResponse
    {
        return response()->json([
            'data' => [
                'enabled' => config('referral.enabled', true),
                'referrer_points' => config('referral.referrer_bonus_points', 100),
                'referee_points' => config('referral.referee_bonus_points', 50),
                'code_prefix' => config('referral.code_prefix', 'REF'),
            ],
        ]);
    }

    /**
     * Get appointment settings
     */
    public function appointment(): JsonResponse
    {
        return response()->json([
            'data' => $this->getAppointmentSettings(),
        ]);
    }

    /**
     * Get payment methods
     */
    public function paymentMethods(): JsonResponse
    {
        $methods = Setting::get('payment_methods', []);

        // Filter only enabled methods
        $enabledMethods = array_filter($methods, fn ($m) => $m['is_enabled'] ?? false);

        return response()->json([
            'data' => array_values($enabledMethods),
        ]);
    }

    /**
     * Get clinic info array
     */
    private function getClinicInfo(): array
    {
        return [
            'name' => Setting::get('clinic_name', config('app.name')),
            'phone' => Setting::get('clinic_phone'),
            'email' => Setting::get('clinic_email'),
            'address' => Setting::get('clinic_address'),
            'city' => Setting::get('clinic_city'),
            'province' => Setting::get('clinic_province'),
            'postal_code' => Setting::get('clinic_postal_code'),
            'description' => Setting::get('clinic_description'),
            'whatsapp' => Setting::get('clinic_whatsapp'),
            'instagram' => Setting::get('clinic_instagram'),
            'facebook' => Setting::get('clinic_facebook'),
            'website' => Setting::get('clinic_website'),
        ];
    }

    /**
     * Get operating hours
     */
    private function getOperatingHours(): array
    {
        $hours = OperatingHour::orderBy('day_of_week')->get();

        return OperatingHourResource::collection($hours)->resolve();
    }

    /**
     * Get appointment settings
     */
    private function getAppointmentSettings(): array
    {
        return [
            'slot_duration' => (int) Setting::get('slot_duration', 30),
            'max_booking_days' => (int) Setting::get('max_booking_days', 90),
            'min_booking_hours' => (int) Setting::get('min_booking_hours', 2),
            'allow_walk_in' => (bool) Setting::get('allow_walk_in', true),
            'require_deposit' => (bool) Setting::get('require_deposit', false),
            'deposit_amount' => (int) Setting::get('deposit_amount', 0),
        ];
    }

    /**
     * Get enabled features
     */
    private function getEnabledFeatures(): array
    {
        $allFeatures = [
            'products',
            'treatment_records',
            'packages',
            'customer_packages',
            'loyalty',
            'online_booking',
            'customer_portal',
            'walk_in_queue',
        ];

        $enabledFeatures = config('business.features', []);

        $result = [];
        foreach ($allFeatures as $feature) {
            $result[$feature] = in_array($feature, $enabledFeatures);
        }

        return $result;
    }
}
