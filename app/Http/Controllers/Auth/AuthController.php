<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        $this->storeBusinessTypeFilter(request());

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            $businessType = $this->resolveBusinessType($request);

            if ($user?->isOwner()) {
                if ($user->business_type) {
                    $businessType = $user->business_type;
                } elseif ($businessType) {
                    $user->update(['business_type' => $businessType]);
                }
            }

            if ($businessType) {
                $request->session()->put('business_type_filter', $businessType);

                $storeQuery = Store::query()
                    ->where('business_type', $businessType)
                    ->orderBy('id');

                if ($user?->isOwner()) {
                    $storeQuery->where('owner_id', $user->id);
                }

                $store = $storeQuery->first();

                if ($store) {
                    $request->session()->put('store_id', $store->id);
                }
            }

            $intendedUrl = $request->session()->get('url.intended');
            if ($intendedUrl) {
                $path = parse_url($intendedUrl, PHP_URL_PATH) ?? '';

                if (str_starts_with($path, '/portal') || $path === '/login') {
                    $request->session()->forget('url.intended');
                }
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function resolveBusinessType(Request $request, ?string $default = null): ?string
    {
        $businessTypes = array_keys(config('business.types', []));
        $input = $request->input('business_type')
            ?? $request->input('type')
            ?? $request->query('business_type')
            ?? $request->query('type')
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
