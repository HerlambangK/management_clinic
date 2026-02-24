@extends('layouts.portal')

@section('title', __('portal.loyalty_points'))
@section('page-title', __('portal.loyalty_points'))

@section('content')
<div class="space-y-6">
    <!-- Loyalty Status Card -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-5 max-sm:p-4 text-white">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="text-primary-100 text-sm max-sm:text-xs">{{ __('portal.your_points') }}</p>
                <p class="text-3xl max-sm:text-2xl font-bold mt-1">{{ format_number($customer->loyalty_points) }}</p>
                <p class="text-primary-100 mt-2 text-sm max-sm:text-xs">{{ __('portal.lifetime_earned', ['points' => format_number($customer->lifetime_points ?? 0)]) }}</p>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="inline-flex items-center px-3 py-1.5 max-sm:px-2 max-sm:py-1 rounded-full text-xs font-semibold bg-white/20">
                    {{ $customer->loyalty_tier_label }}
                </span>
            </div>
        </div>

        <!-- Point value info -->
        @if($customer->loyalty_points > 0)
        <div class="mt-4 pt-4 border-t border-white/20">
            <p class="text-xs text-primary-100">
                {{ __('portal.points_value_info', ['value' => format_currency($customer->loyalty_points * config('loyalty.points_value', 100))]) }}
            </p>
        </div>
        @endif
    </div>

    <!-- How to Use Points -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('portal.how_to_use_points') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('portal.how_to_use_points_desc') }}</p>
            </div>
        </div>
    </div>

    <!-- Points History -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.points_history') }}</h3>
        </div>
        @if($pointHistory->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($pointHistory as $point)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $point->description ?? __('portal.point_type_' . $point->type) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ format_datetime($point->created_at) }}
                                </p>
                            </div>
                            <span class="text-lg font-semibold {{ $point->points >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $point->points >= 0 ? '+' : '' }}{{ format_number($point->points) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $pointHistory->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('portal.no_points_history') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('portal.points_history_desc') }}</p>
            </div>
        @endif
    </div>

    <!-- How to Earn Points -->
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 max-sm:p-4">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.how_to_earn') }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('portal.earn_by_spending') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.earn_by_spending_desc') }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('portal.earn_by_referral') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.earn_by_referral_desc') }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('portal.earn_bonus') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.earn_bonus_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Code Section -->
    @if($customer->referral_code)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 max-sm:p-4">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">{{ __('portal.your_referral_code') }}</h3>
        <div class="flex items-center gap-3 max-sm:gap-2">
            <div class="flex-1 p-3 max-sm:p-2 bg-gray-100 dark:bg-gray-700 rounded-lg font-mono text-base max-sm:text-sm text-center font-semibold text-gray-900 dark:text-white truncate">
                {{ $customer->referral_code }}
            </div>
            <button type="button" onclick="copyReferralCode('{{ $customer->referral_code }}')" class="px-3 py-2.5 max-sm:px-2.5 max-sm:py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition flex-shrink-0">
                <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                </svg>
            </button>
        </div>
        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">{{ __('portal.referral_code_desc') }}</p>
    </div>
    @endif
</div>

<script>
function copyReferralCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert('{{ __('portal.referral_code_copied') }}');
    });
}
</script>
@endsection
