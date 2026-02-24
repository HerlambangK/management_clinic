@extends('layouts.dashboard')

@section('title', $customer->name)
@section('page-title', __('customer.detail'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Back Button & Actions -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('customer.back_to_customers') }}
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                {{ __('common.edit') }}
            </a>
            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('customer.delete_confirm') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('common.delete') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Customer Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex flex-row max-sm:flex-col items-start gap-6 max-sm:gap-4">
            <!-- Avatar -->
            <div class="w-20 h-20 max-sm:w-16 max-sm:h-16 bg-gradient-to-br {{ $tc->gradient ?? 'from-rose-400 to-rose-500' }} rounded-full flex items-center justify-center text-white font-bold text-2xl max-sm:text-xl flex-shrink-0">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl max-sm:text-xl font-bold text-gray-900 dark:text-gray-100">{{ $customer->name }}</h2>

                <div class="mt-3 max-sm:mt-2 flex flex-wrap gap-x-6 max-sm:gap-x-4 gap-y-2 text-sm max-sm:text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $customer->phone }}
                    </div>

                    @if($customer->email)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $customer->email }}
                        </div>
                    @endif

                    @if($customer->birthdate)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                            </svg>
                            {{ format_date($customer->birthdate) }} ({{ $customer->age }} {{ __('customer.years_old') }})
                        </div>
                    @endif

                    @if($customer->gender)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $customer->gender === 'male' ? __('customer.male') : ($customer->gender === 'female' ? __('customer.female') : __('customer.other')) }}
                        </div>
                    @endif
                </div>

                @if($customer->address)
                    <div class="mt-2 flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $customer->address }}
                    </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="flex sm:flex-col gap-4 sm:gap-2 text-right max-sm:text-left">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('customer.member_since') }}</p>
                    <p class="text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">{{ format_date($customer->created_at, 'M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid sm:grid-cols-3 gap-4 max-sm:gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100">{{ $customer->total_visits }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('customer.total_visits') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 truncate">{{ $customer->formatted_total_spent }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('customer.total_spent') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-30 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl max-sm:text-base font-bold text-gray-900 dark:text-gray-100">
                        @if($customer->last_visit)
                            {{ $customer->last_visit->diffForHumans() }}
                        @else
                            -
                        @endif
                    </p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('customer.last_visit') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Code Card -->
    @if(config('referral.enabled', true))
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        @php
            $referralStats = $customer->referral_stats;
        @endphp
        <!-- Desktop Layout -->
        <div class="hidden sm:flex flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('loyalty.referral_code') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.share_referral_code') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2">
                    <code class="text-lg font-mono font-bold text-indigo-600 dark:text-indigo-400" id="referral-code-desktop">{{ $customer->referral_code }}</code>
                    <button type="button" onclick="copyReferralCode('desktop')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" title="{{ __('common.copy') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $referralStats['total_referrals'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.total_referrals') }}</p>
                </div>
            </div>
        </div>

        <!-- Mobile Layout -->
        <div class="sm:hidden space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('loyalty.referral_code') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('loyalty.share_referral_code') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-2 flex-1 min-w-0">
                    <code class="text-sm font-mono font-bold text-indigo-600 dark:text-indigo-400 truncate" id="referral-code-mobile">{{ $customer->referral_code }}</code>
                    <button type="button" onclick="copyReferralCode('mobile')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition flex-shrink-0" title="{{ __('common.copy') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <div class="text-center flex-shrink-0">
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $referralStats['total_referrals'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.total_referrals') }}</p>
                </div>
            </div>
        </div>

        @if(($referralStats['total_referrals'] ?? 0) > 0)
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-4 max-sm:gap-3 text-sm max-sm:text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        <span class="text-gray-600 dark:text-gray-300">{{ __('loyalty.pending') }}: <span class="font-medium">{{ $referralStats['pending_referrals'] ?? 0 }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-gray-600 dark:text-gray-300">{{ __('loyalty.rewarded') }}: <span class="font-medium">{{ $referralStats['rewarded_referrals'] ?? 0 }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 dark:text-gray-300">{{ __('loyalty.total_bonus_points') }}: <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ format_number($referralStats['total_points_earned'] ?? 0) }}</span></span>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif

    <!-- Loyalty Points Card -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-700 dark:to-purple-800 rounded-xl shadow-sm p-6 max-sm:p-4 text-white">
        <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-16 h-16 max-sm:w-14 max-sm:h-14 rounded-full flex items-center justify-center {{ $customer->loyalty_tier_color }} text-lg max-sm:text-base font-bold">
                    {{ strtoupper(substr($customer->loyalty_tier ?? 'B', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-lg max-sm:text-base font-semibold">{{ __('loyalty.loyalty_points') }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/20">
                            {{ $customer->loyalty_tier_label }}
                        </span>
                    </div>
                    <p class="text-sm max-sm:text-xs text-purple-100">{{ __('loyalty.earn_info', ['points' => number_format(config('loyalty.points_per_amount', 10000), 0, ',', '.')]) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6 max-sm:gap-4 max-sm:w-full max-sm:justify-between">
                <div class="text-center">
                    <p class="text-3xl max-sm:text-2xl font-bold">{{ format_number($customer->loyalty_points) }}</p>
                    <p class="text-xs text-purple-100">{{ __('loyalty.current_points') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xl max-sm:text-lg font-semibold text-purple-100">{{ format_number($customer->lifetime_points) }}</p>
                    <p class="text-xs text-purple-200">{{ __('loyalty.lifetime') }}</p>
                </div>
                <a href="{{ route('loyalty.customer-history', $customer) }}" class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white/20 hover:bg-white/30 text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                    {{ __('common.detail') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid lg:grid-cols-2 gap-6 max-sm:gap-4">
        <!-- Profile Section (Dynamic based on business type) -->
        @include('customers.partials.profile-display', ['customer' => $customer])

        <!-- Notes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('customer.notes') }}</h3>

            @if($customer->notes)
                <p class="text-sm max-sm:text-xs text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $customer->notes }}</p>
            @else
                <p class="text-gray-400 dark:text-gray-500 text-sm max-sm:text-xs">{{ __('customer.no_notes') }}</p>
            @endif
        </div>
    </div>

    <!-- Active Packages -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-center justify-between mb-4 max-sm:mb-3">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('customer.active_packages_title') }}</h3>
            <a href="{{ route('customer-packages.create', ['customer_id' => $customer->id]) }}" class="text-sm max-sm:text-xs {{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }} font-medium">
                {{ __('customer.buy_package') }}
            </a>
        </div>

        @php
            $activePackages = $customer->packages->where('status', 'active');
        @endphp

        @if($activePackages->count() > 0)
            <div class="space-y-3 max-sm:space-y-2">
                @foreach($activePackages as $cp)
                    <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4 max-sm:p-3 hover:border-gray-200 dark:hover:border-gray-600 transition">
                        <div class="flex items-start justify-between gap-4 max-sm:gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 max-sm:gap-2 mb-2">
                                    <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm max-sm:text-xs truncate">{{ $cp->package->name }}</p>
                                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ $cp->sessions_remaining }} {{ __('customer.remaining_of') }} {{ $cp->sessions_total }} {{ __('customer.sessions') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 max-sm:gap-2 text-xs">
                                    <div class="flex items-center gap-1">
                                        <div class="w-16 max-sm:w-12 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                            <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $cp->usage_percentage }}%"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400">{{ $cp->usage_percentage }}%</span>
                                    </div>
                                    <span class="text-gray-400 dark:text-gray-500 max-sm:hidden">|</span>
                                    <span class="{{ $cp->days_remaining <= 30 ? 'text-yellow-500' : 'text-gray-500 dark:text-gray-400' }} max-sm:hidden">
                                        {{ __('customer.valid_days', ['days' => $cp->days_remaining]) }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('customer-packages.show', $cp) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0">
                                <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 max-sm:py-4">
                <svg class="mx-auto h-10 w-10 max-sm:h-8 max-sm:w-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="mt-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('customer.no_active_packages') }}</p>
            </div>
        @endif
    </div>

    <!-- Treatment History -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-center justify-between mb-4 max-sm:mb-3">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('customer.treatment_history') }}</h3>
            @if($customer->treatmentRecords->count() > 0)
                <div class="flex items-center gap-3 max-sm:gap-2">
                    <a href="{{ route('treatment-records.customer-pdf', ['customer_id' => $customer->id]) }}" class="inline-flex items-center gap-1 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-medium">
                        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('treatment-records.index', ['customer_id' => $customer->id]) }}" class="text-sm max-sm:text-xs {{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }} font-medium">
                        {{ __('customer.view_all') }}
                    </a>
                </div>
            @endif
        </div>

        @if($customer->treatmentRecords->count() > 0)
            <div class="space-y-4 max-sm:space-y-2">
                @foreach($customer->treatmentRecords as $record)
                    <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4 max-sm:p-3 hover:border-gray-200 dark:hover:border-gray-600 transition">
                        <div class="flex items-start justify-between gap-4 max-sm:gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 max-sm:gap-2 mb-2">
                                    <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 {{ $tc->bgLight ?? 'bg-rose-100' }} dark:bg-opacity-30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 {{ $tc->iconColor ?? 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm max-sm:text-xs truncate">{{ $record->appointment->service->name }}</p>
                                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ format_date($record->appointment->appointment_date) }} &bull; {{ $record->staff->name }}</p>
                                    </div>
                                </div>

                                @if($record->notes)
                                    <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-300 line-clamp-2 mb-2">{{ $record->notes }}</p>
                                @endif

                                @if($record->products_used && count($record->products_used) > 0)
                                    <div class="flex flex-wrap gap-1 max-sm:hidden">
                                        @foreach(array_slice($record->products_used, 0, 3) as $product)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ $product }}
                                            </span>
                                        @endforeach
                                        @if(count($record->products_used) > 3)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('customer.and_more', ['count' => count($record->products_used) - 3]) }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if($record->follow_up_date)
                                    <div class="mt-2 flex items-center gap-1 text-xs">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-500 dark:text-gray-400">{{ __('customer.follow_up') }}:</span>
                                        <span class="{{ $record->follow_up_date->isPast() ? 'text-red-500 dark:text-red-400' : ($record->follow_up_date->isToday() ? 'text-yellow-500 dark:text-yellow-400' : 'text-gray-700 dark:text-gray-300') }}">
                                            {{ format_date($record->follow_up_date) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($record->before_photo || $record->after_photo)
                                    <span class="text-xs text-gray-400 max-sm:hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                @endif
                                <a href="{{ route('treatment-records.show', $record) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 max-sm:py-6">
                <svg class="mx-auto h-12 w-12 max-sm:h-10 max-sm:w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('customer.no_treatment_history') }}</p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ __('customer.history_will_appear') }}</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function copyReferralCode(type) {
    const elementId = type === 'mobile' ? 'referral-code-mobile' : 'referral-code-desktop';
    const code = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(code).then(function() {
        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50';
        toast.textContent = '{{ __("loyalty.code_copied") }}';
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.remove();
        }, 2000);
    });
}
</script>
@endpush
@endsection
