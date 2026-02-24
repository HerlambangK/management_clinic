@extends('layouts.dashboard')

@section('title', __('setting.store_settings'))
@section('page-title', __('setting.store_settings'))

@section('content')
@php
    $businessLabel = config("business.types.{$store->business_type}.name", ucfirst($store->business_type));
@endphp
<div class="max-w-6xl space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-30 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100 4m0-4a2 2 0 110 4m12-4a2 2 0 100 4m0-4a2 2 0 110 4m-6-6v6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('setting.store_settings') }}</p>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $store->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('setting.store_settings_desc', ['store' => $store->name]) }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('setting.business_type') }}</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $businessLabel }}</p>
                </div>
                <div class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('setting.portal_menu_title') }}</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 capitalize">{{ $portalPlan }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('settings.store.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.portal_menu_title') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('setting.portal_menu_desc') }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $portalPlan === 'premium' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $portalPlan === 'premium' ? __('setting.portal_menu_premium') : __('setting.portal_menu_basic') }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                        <label class="relative cursor-pointer">
                            <input
                                type="radio"
                                name="customer_portal_plan"
                                value="basic"
                                class="peer absolute right-4 top-4 z-10 h-5 w-5 appearance-none rounded-full border border-gray-300 bg-white shadow-sm transition checked:border-primary-500 checked:bg-primary-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30"
                                {{ $portalPlan === 'basic' ? 'checked' : '' }}
                            >
                            <div class="p-4 pr-12 rounded-2xl border-2 transition-all border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-500 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-primary-500/25 peer-checked:dark:border-primary-400 peer-checked:dark:bg-primary-900/20 peer-checked:dark:ring-primary-400/30">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('setting.portal_menu_basic') }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.portal_menu_basic_desc') }}</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input
                                type="radio"
                                name="customer_portal_plan"
                                value="premium"
                                class="peer absolute right-4 top-4 z-10 h-5 w-5 appearance-none rounded-full border border-gray-300 bg-white shadow-sm transition checked:border-primary-500 checked:bg-primary-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30"
                                {{ $portalPlan === 'premium' ? 'checked' : '' }}
                            >
                            <div class="p-4 pr-12 rounded-2xl border-2 transition-all border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-500 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-primary-500/25 peer-checked:dark:border-primary-400 peer-checked:dark:bg-primary-900/20 peer-checked:dark:ring-primary-400/30">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('setting.portal_menu_premium') }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.portal_menu_premium_desc') }}</p>
                            </div>
                        </label>
                    </div>
                    @error('customer_portal_plan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.feature_toggles') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('setting.feature_default_on') }} / {{ __('setting.feature_default_off') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        @foreach($features as $feature)
                            <div class="relative">
                                <input type="hidden" name="features[{{ $feature['key'] }}]" value="0">
                                <input
                                    id="feature-{{ $feature['key'] }}"
                                    type="checkbox"
                                    name="features[{{ $feature['key'] }}]"
                                    value="1"
                                    {{ $feature['enabled'] ? 'checked' : '' }}
                                    class="peer absolute right-4 top-1/2 z-10 h-6 w-11 -translate-y-1/2 appearance-none rounded-full border border-gray-200 bg-gray-200 transition before:absolute before:left-0.5 before:top-0.5 before:h-5 before:w-5 before:rounded-full before:bg-white before:shadow-sm before:transition checked:border-primary-500 checked:bg-primary-500 checked:before:translate-x-5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30"
                                >
                                <label
                                    for="feature-{{ $feature['key'] }}"
                                    class="relative z-0 flex items-center justify-between gap-4 p-4 pr-16 border border-gray-200 dark:border-gray-700 rounded-xl transition-colors cursor-pointer select-none hover:bg-gray-50 dark:hover:bg-gray-700/60 peer-checked:bg-primary-50 peer-checked:border-primary-500 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-primary-500/25 peer-checked:dark:bg-primary-900/20 peer-checked:dark:border-primary-400/70 peer-checked:dark:ring-primary-400/30"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $feature['label'] }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[11px] font-semibold {{ $feature['default'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $feature['default'] ? __('setting.feature_default_on') : __('setting.feature_default_off') }}
                                        </span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                @php
                    $featureLabelMap = collect($features)->mapWithKeys(fn ($feature) => [$feature['key'] => $feature['label']]);
                    $premiumFeatureKeys = collect($premiumEligible)->values();
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.premium_feature_toggles') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('setting.premium_feature_desc') }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-100 px-3 py-1 rounded-full">
                            {{ __('portal.premium_badge') }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        @foreach($premiumFeatureKeys as $featureKey)
                            @php
                                $label = $featureLabelMap[$featureKey] ?? \Illuminate\Support\Str::headline($featureKey);
                                $isSelected = in_array($featureKey, $premiumSelected, true);
                            @endphp
                            <div class="relative">
                                <input type="hidden" name="premium_features[{{ $featureKey }}]" value="0">
                                <input
                                    id="premium-feature-{{ $featureKey }}"
                                    type="checkbox"
                                    name="premium_features[{{ $featureKey }}]"
                                    value="1"
                                    {{ $isSelected ? 'checked' : '' }}
                                    class="peer absolute right-4 top-1/2 z-10 h-6 w-11 -translate-y-1/2 appearance-none rounded-full border border-gray-200 bg-gray-200 transition before:absolute before:left-0.5 before:top-0.5 before:h-5 before:w-5 before:rounded-full before:bg-white before:shadow-sm before:transition checked:border-primary-500 checked:bg-primary-500 checked:before:translate-x-5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30"
                                >
                                <label
                                    for="premium-feature-{{ $featureKey }}"
                                    class="relative z-0 flex items-center justify-between gap-4 p-4 pr-16 border border-gray-200 dark:border-gray-700 rounded-xl transition-colors cursor-pointer select-none hover:bg-gray-50 dark:hover:bg-gray-700/60 peer-checked:bg-primary-50 peer-checked:border-primary-500 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-primary-500/25 peer-checked:dark:bg-primary-900/20 peer-checked:dark:border-primary-400/70 peer-checked:dark:ring-primary-400/30"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $label }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('setting.premium_feature_hint') }}</p>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('attendance.location_title') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('attendance.location_setting_desc') }}</p>
                    <div class="space-y-4">
                        <div>
                            <label for="store_latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('attendance.location_latitude') }}</label>
                            <input
                                type="text"
                                id="store_latitude"
                                name="store_latitude"
                                value="{{ old('store_latitude', $store->latitude) }}"
                                placeholder="-6.2000000"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('store_latitude') border-red-400 @enderror"
                            >
                            @error('store_latitude')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="store_longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('attendance.location_longitude') }}</label>
                            <input
                                type="text"
                                id="store_longitude"
                                name="store_longitude"
                                value="{{ old('store_longitude', $store->longitude) }}"
                                placeholder="106.8166660"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('store_longitude') border-red-400 @enderror"
                            >
                            @error('store_longitude')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <button
                            type="button"
                            id="use-current-location"
                            class="px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition"
                        >
                            {{ __('attendance.location_use_current') }}
                        </button>
                        <span id="location-status" class="text-xs text-gray-500 dark:text-gray-400"></span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">{{ __('attendance.location_radius_hint', ['radius' => 100]) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('setting.theme_colors') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('setting.theme_colors_desc') }}</p>

                    @php
                        $palettes = brand_palettes();
                        $selectedPalette = old('theme_palette', $themePalette);
                    @endphp

                    <div class="space-y-3">
                        <label class="relative cursor-pointer block">
                            <input type="radio" name="theme_palette" value="" class="peer sr-only" {{ empty($selectedPalette) ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 transition-all border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-500 peer-checked:border-gray-900 peer-checked:bg-gray-50 peer-checked:dark:border-gray-200 peer-checked:dark:bg-gray-700/40 peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('setting.theme_palette_default') }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.theme_palette_default_desc') }}</p>
                            </div>
                        </label>

                        @foreach($palettes as $key => $palette)
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="theme_palette" value="{{ $key }}" class="peer sr-only" {{ $selectedPalette === $key ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 transition-all border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-500 peer-checked:border-gray-900 peer-checked:bg-gray-50 peer-checked:dark:border-gray-200 peer-checked:dark:bg-gray-700/40 peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $palette['label'] }}</p>
                                        <div class="flex items-center gap-1">
                                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $palette['colors']['400'] }};"></span>
                                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $palette['colors']['500'] }};"></span>
                                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $palette['colors']['600'] }};"></span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('theme_palette')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.custom_colors') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="theme_custom_primary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.custom_primary') }}</label>
                            <input
                                type="text"
                                id="theme_custom_primary"
                                name="theme_custom_primary"
                                value="{{ old('theme_custom_primary', $themeCustom['primary'] ?? '') }}"
                                placeholder="#f43f5e"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('theme_custom_primary') border-red-400 @enderror"
                            >
                            @error('theme_custom_primary')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="theme_custom_primary_hover" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.custom_primary_hover') }}</label>
                            <input
                                type="text"
                                id="theme_custom_primary_hover"
                                name="theme_custom_primary_hover"
                                value="{{ old('theme_custom_primary_hover', $themeCustom['primary_hover'] ?? '') }}"
                                placeholder="#e11d48"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('theme_custom_primary_hover') border-red-400 @enderror"
                            >
                            @error('theme_custom_primary_hover')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="theme_custom_primary_light" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.custom_primary_light') }}</label>
                            <input
                                type="text"
                                id="theme_custom_primary_light"
                                name="theme_custom_primary_light"
                                value="{{ old('theme_custom_primary_light', $themeCustom['primary_light'] ?? '') }}"
                                placeholder="#fff1f2"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('theme_custom_primary_light') border-red-400 @enderror"
                            >
                            @error('theme_custom_primary_light')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">{{ __('setting.custom_colors_hint') }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="px-5 py-2.5 {{ $tc->button }} text-white text-sm font-semibold rounded-xl transition">
                {{ __('setting.save_settings') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('use-current-location')?.addEventListener('click', () => {
        const status = document.getElementById('location-status');
        const latInput = document.getElementById('store_latitude');
        const lngInput = document.getElementById('store_longitude');

        if (! navigator.geolocation) {
            if (status) {
                status.textContent = '{{ __('attendance.location_not_supported') }}';
            }
            return;
        }

        if (status) {
            status.textContent = '{{ __('attendance.location_fetching') }}';
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                if (latInput) {
                    latInput.value = position.coords.latitude.toFixed(7);
                }
                if (lngInput) {
                    lngInput.value = position.coords.longitude.toFixed(7);
                }
                if (status) {
                    status.textContent = '{{ __('attendance.location_filled') }}';
                }
            },
            (error) => {
                if (! status) {
                    return;
                }
                if (error?.code === error.PERMISSION_DENIED) {
                    status.textContent = '{{ __('attendance.location_permission_denied') }}';
                    return;
                }
                if (error?.code === error.POSITION_UNAVAILABLE) {
                    status.textContent = '{{ __('attendance.location_unavailable') }}';
                    return;
                }
                if (error?.code === error.TIMEOUT) {
                    status.textContent = '{{ __('attendance.location_timeout') }}';
                    return;
                }
                status.textContent = '{{ __('attendance.location_denied') }}';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
            }
        );
    });
</script>
@endpush
