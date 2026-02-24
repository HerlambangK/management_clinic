<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SetupController extends Controller
{
    /**
     * Show the setup wizard - step 1: choose business type
     */
    public function index(): View|RedirectResponse
    {
        if (is_setup_completed()) {
            return redirect()->route('dashboard');
        }

        $businessTypes = collect(config('business.types'))->map(function ($config, $key) {
            return [
                'key' => $key,
                'name' => $config['name'],
                'name_en' => $config['name_en'],
                'description' => $config['description'],
                'description_en' => $config['description_en'],
                'icon' => $config['icon'],
                'theme' => $config['theme'],
            ];
        });

        return view('setup.index', compact('businessTypes'));
    }

    /**
     * Step 2: Business details form
     */
    public function details(Request $request): View|RedirectResponse
    {
        if (is_setup_completed()) {
            return redirect()->route('dashboard');
        }

        $businessType = $request->query('type');

        if (! $businessType || ! array_key_exists($businessType, config('business.types'))) {
            return redirect()->route('setup.index');
        }

        $businessConfig = config("business.types.{$businessType}");

        return view('setup.details', [
            'businessType' => $businessType,
            'businessConfig' => $businessConfig,
        ]);
    }

    /**
     * Step 3: Create owner account form
     */
    public function account(Request $request): View|RedirectResponse
    {
        if (is_setup_completed()) {
            return redirect()->route('dashboard');
        }

        // Check if we have business type and name in session
        if (! $request->session()->has('setup.business_type') || ! $request->session()->has('setup.business_name')) {
            return redirect()->route('setup.index');
        }

        $businessType = $request->session()->get('setup.business_type');
        $businessConfig = config("business.types.{$businessType}");

        return view('setup.account', compact('businessType', 'businessConfig'));
    }

    /**
     * Store business details to session
     */
    public function storeDetails(Request $request): RedirectResponse
    {
        $businessType = $request->input('business_type');

        $validated = $request->validate([
            'business_type' => 'required|string|in:'.implode(',', array_keys(config('business.types'))),
            'business_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string|max:500',
        ], [], [
            'business_type' => __('setup.business_type'),
            'business_name' => __('setup.business_name'),
            'business_phone' => __('setup.phone_number'),
            'business_address' => __('setup.address'),
        ]);

        // Store in session for next step
        $request->session()->put('setup.business_type', $validated['business_type']);
        $request->session()->put('setup.business_name', $validated['business_name']);
        $request->session()->put('setup.business_phone', $validated['business_phone'] ?? null);
        $request->session()->put('setup.business_address', $validated['business_address'] ?? null);

        return redirect()->route('setup.account');
    }

    /**
     * Complete setup: create owner account and save settings
     */
    public function complete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get business details from session
        $businessType = $request->session()->get('setup.business_type');
        $businessName = $request->session()->get('setup.business_name');
        $businessPhone = $request->session()->get('setup.business_phone');
        $businessAddress = $request->session()->get('setup.business_address');

        if (! $businessType || ! $businessName) {
            return redirect()->route('setup.index');
        }

        // Save business settings
        Setting::set('business_type', $businessType, 'string');
        Setting::set('business_name', $businessName, 'string');
        Setting::set('clinic_name', $businessName, 'string'); // For backward compatibility
        Setting::set('clinic_phone', $businessPhone, 'string');
        Setting::set('clinic_address', $businessAddress, 'string');

        // Create owner account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'owner',
            'business_type' => $businessType,
            'is_active' => true,
        ]);

        $store = Store::create([
            'name' => $businessName,
            'business_type' => $businessType,
            'owner_id' => $user->id,
            'is_active' => true,
            'is_approved' => true,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        $request->session()->put('store_id', $store->id);

        // Create sample service categories and services
        $this->createSampleData($businessType);

        // Mark setup as completed
        Setting::set('setup_completed', true, 'boolean');
        Setting::set('setup_completed_at', now()->toISOString(), 'string');

        // Clear business cache so new settings take effect
        clear_business_cache();

        // Clear setup session data
        $request->session()->forget('setup');

        // Login the user
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', __('Setup completed successfully! Welcome to GlowUp.'));
    }

    /**
     * Create sample service categories and services based on business type
     */
    private function createSampleData(string $businessType): void
    {
        $categories = config("business.types.{$businessType}.sample_categories", []);
        $services = config("business.types.{$businessType}.sample_services", []);

        $categoryMap = [];

        // Create categories
        foreach ($categories as $index => $category) {
            $created = ServiceCategory::create([
                'name' => $category['name'],
                'icon' => $category['icon'] ?? null,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
            $categoryMap[$category['name']] = $created->id;
        }

        // Create services for each category
        foreach ($services as $categoryName => $categoryServices) {
            $categoryId = $categoryMap[$categoryName] ?? null;

            if (! $categoryId) {
                continue;
            }

            foreach ($categoryServices as $service) {
                Service::create([
                    'category_id' => $categoryId,
                    'name' => $service['name'],
                    'description' => $service['description'] ?? null,
                    'price' => $service['price'],
                    'duration_minutes' => $service['duration'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
