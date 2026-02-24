@extends('layouts.dashboard')

@section('title', __('loyalty.referrals_title'))
@section('page-title', __('loyalty.referrals_title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('loyalty.referrals_subtitle') }}</p>
        <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 max-lg:grid-cols-2 max-sm:grid-cols-1 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.total_referrals') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-gray-900 dark:text-white mt-1">{{ format_number($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.pending_referrals') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ format_number($stats['pending']) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.rewarded_referrals') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-green-600 dark:text-green-400 mt-1">{{ format_number($stats['rewarded']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.total_points_given') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ format_number($stats['total_referrer_points'] + $stats['total_referee_points']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('loyalty.referrals') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    placeholder="{{ __('loyalty.search_referral') }}"
                >
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select
                        name="status"
                        class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('loyalty.all_status') }}</option>
                        @foreach(\App\Models\ReferralLog::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-800 dark:bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-900 dark:hover:bg-gray-500 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('loyalty.referrals') }}" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Referrals Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.referrer') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.referee') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.referrer_points') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.referee_points') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($referrals as $referral)
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rewarded' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('customers.show', $referral->referrer) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $referral->referrer?->name ?? '-' }}
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $referral->referrer?->phone ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('customers.show', $referral->referee) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $referral->referee?->name ?? '-' }}
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $referral->referee?->phone ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium {{ $referral->status === 'rewarded' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                                    +{{ format_number($referral->referrer_points) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium {{ $referral->status === 'rewarded' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                                    +{{ format_number($referral->referee_points) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$referral->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $referral->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $referral->rewarded_at ? format_datetime($referral->rewarded_at) : format_datetime($referral->created_at) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_referrals') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($referrals as $referral)
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'rewarded' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                    ];
                @endphp
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $referral->referrer?->name }}</span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $referral->referee?->name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $referral->rewarded_at ? format_datetime($referral->rewarded_at) : format_datetime($referral->created_at) }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$referral->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $referral->status_label }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">{{ __('loyalty.referrer') }}:</span>
                            <span class="font-medium {{ $referral->status === 'rewarded' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">+{{ format_number($referral->referrer_points) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">{{ __('loyalty.referee') }}:</span>
                            <span class="font-medium {{ $referral->status === 'rewarded' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">+{{ format_number($referral->referee_points) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_referrals') }}</p>
                </div>
            @endforelse
        </div>

        @if($referrals->hasPages())
            <div class="px-5 py-4 max-sm:px-4 max-sm:py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
