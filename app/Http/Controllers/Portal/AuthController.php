<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        $this->storeBusinessTypeFilter(request());

        return view('portal.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (! $customer || ! $customer->password) {
            return back()->withErrors([
                'email' => __('portal.invalid_credentials'),
            ])->withInput();
        }

        if (! Hash::check($request->password, $customer->password)) {
            return back()->withErrors([
                'email' => __('portal.invalid_credentials'),
            ])->withInput();
        }

        $businessType = $this->resolveBusinessType($request);

        $customerStore = $customer->store_id
            ? Store::query()->find($customer->store_id)
            : null;

        if ($customerStore) {
            $businessType = $customerStore->business_type ?? $businessType;
        }

        if (! $customer->store_id) {
            $store = $this->resolvePortalStore($businessType);

            if ($store) {
                $customer->update(['store_id' => $store->id]);
                $businessType = $store->business_type;
            }
        }

        Auth::guard('customer')->login($customer, $request->boolean('remember'));

        $request->session()->regenerate();
        $request->session()->put('store_id', $customer->store_id);

        if ($businessType) {
            $request->session()->put('business_type_filter', $businessType);
        }

        return redirect()->intended(route('portal.dashboard'))
            ->with('success', __('portal.login_success'));
    }

    public function showRegister(): View
    {
        $this->storeBusinessTypeFilter(request());

        return view('portal.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ]);

        $referrerId = null;

        if ($request->filled('referral_code') && config('referral.enabled', true)) {
            $referrer = Customer::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        $businessType = $this->resolveBusinessType($request);
        $store = $this->resolvePortalStore($businessType);

        if ($store) {
            $businessType = $store->business_type;
        }

        $customer = Customer::create([
            'store_id' => $store?->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referred_by_id' => $referrerId,
        ]);

        Auth::guard('customer')->login($customer);

        $request->session()->regenerate();
        $request->session()->put('store_id', $customer->store_id);

        if ($businessType) {
            $request->session()->put('business_type_filter', $businessType);
        }

        return redirect()->route('portal.dashboard')
            ->with('success', __('portal.register_success'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login')
            ->with('success', __('portal.logout_success'));
    }

    private function resolvePortalStore(?string $businessType = null): ?Store
    {
        $store = current_store();

        if ($store && (! $businessType || $store->business_type === $businessType)) {
            return $store;
        }

        $query = Store::query()
            ->active()
            ->approved()
            ->orderBy('id');

        if ($businessType) {
            $query->where('business_type', $businessType);
        }

        return $query->first();
    }

    private function resolveBusinessType(Request $request, ?string $default = null): ?string
    {
        $businessTypes = array_keys(config('business.types', []));
        $input = $request->input('business_type')
            ?? $request->input('type')
            ?? $request->query('business_type')
            ?? $request->query('type')
            ?? $request->session()->get('business_type_filter')
            ?? $default;

        if ($input && in_array($input, $businessTypes, true)) {
            return $input;
        }

        return $default;
    }

    private function storeBusinessTypeFilter(Request $request): void
    {
        $businessType = $this->resolveBusinessType($request);

        if ($businessType) {
            $request->session()->put('business_type_filter', $businessType);
        }
    }
}
