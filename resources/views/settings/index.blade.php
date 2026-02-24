@extends('layouts.dashboard')

@section('title', __('setting.title'))
@section('page-title', __('setting.title'))

@section('content')
@php
    $activeStoreName = $currentStore?->name ?? __('store.all_stores');
    $activeBusinessType = $currentStore?->business_type ?? $businessType;
    $activeBusinessLabel = config("business.types.{$activeBusinessType}.name", ucfirst($activeBusinessType));
@endphp
<div class="max-w-5xl space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row gap-6 lg:items-center">
            <div class="flex items-start gap-4 flex-1">
                <div class="w-12 h-12 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-30 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100 4m0-4a2 2 0 110 4m12-4a2 2 0 100 4m0-4a2 2 0 110 4m-6-6v6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('setting.title') }}</p>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.subtitle') }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('setting.clinic_profile_desc') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('store.title') }}</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activeStoreName }}</p>
                </div>
                <div class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('setting.business_type') }}</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activeBusinessLabel }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <a href="{{ route('settings.clinic') }}" class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:{{ $tc->borderHover ?? 'border-rose-200' }} hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-20 rounded-xl flex items-center justify-center group-hover:{{ $tc->bgMedium ?? 'bg-rose-200' }} dark:group-hover:bg-opacity-30 transition">
                        <svg class="w-5 h-5 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.clinic_profile') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('setting.clinic_profile_desc') }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:{{ $tc->iconColor ?? 'text-rose-600' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('settings.hours') }}" class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:{{ $tc->borderHover ?? 'border-rose-200' }} hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-20 rounded-xl flex items-center justify-center group-hover:{{ $tc->bgMedium ?? 'bg-rose-200' }} dark:group-hover:bg-opacity-30 transition">
                        <svg class="w-5 h-5 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.operating_hours') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('setting.operating_hours_desc') }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:{{ $tc->iconColor ?? 'text-rose-600' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('settings.branding') }}" class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:{{ $tc->borderHover ?? 'border-rose-200' }} hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-20 rounded-xl flex items-center justify-center group-hover:{{ $tc->bgMedium ?? 'bg-rose-200' }} dark:group-hover:bg-opacity-30 transition">
                        <svg class="w-5 h-5 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.branding') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('setting.branding_desc') }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:{{ $tc->iconColor ?? 'text-rose-600' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        @if(auth()->user()->hasRole(['owner', 'admin']))
            <a href="{{ route('settings.store') }}" class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:{{ $tc->borderHover ?? 'border-rose-200' }} hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-20 rounded-xl flex items-center justify-center group-hover:{{ $tc->bgMedium ?? 'bg-rose-200' }} dark:group-hover:bg-opacity-30 transition">
                            <svg class="w-5 h-5 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3-1.343-3-3 0-1.1.6-2.06 1.5-2.58M12 8c1.657 0 3-1.343 3-3 0-1.1-.6-2.06-1.5-2.58M12 8v12m-6-6h12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.store_settings') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('setting.store_settings_desc_short') }}</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:{{ $tc->iconColor ?? 'text-rose-600' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('stores.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:{{ $tc->borderHover ?? 'border-rose-200' }} hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-20 rounded-xl flex items-center justify-center group-hover:{{ $tc->bgMedium ?? 'bg-rose-200' }} dark:group-hover:bg-opacity-30 transition">
                            <svg class="w-5 h-5 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 11h6M9 15h6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('store.title') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('store.subtitle') }}</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 group-hover:{{ $tc->iconColor ?? 'text-rose-600' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        @endif
    </div>
</div>
@endsection
