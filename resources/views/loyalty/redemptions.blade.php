@extends('layouts.dashboard')

@section('title', __('loyalty.redemptions_title'))
@section('page-title', __('loyalty.redemptions_title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('loyalty.redemptions_subtitle') }}</p>
        <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.pending_redemptions') }}</p>
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.used_redemptions') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-green-600 dark:text-green-400 mt-1">{{ format_number($stats['used']) }}</p>
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.total_redemptions') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-gray-900 dark:text-white mt-1">{{ format_number($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Use Redemption Code -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('loyalty.use-redemption') }}" method="POST" class="flex flex-row max-sm:flex-col gap-3">
            @csrf
            <div class="flex-1">
                <input
                    type="text"
                    name="code"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition font-mono uppercase"
                    placeholder="{{ __('loyalty.enter_code') }}"
                    required
                >
            </div>
            <button type="submit" class="px-4 py-2 max-sm:py-1.5 {{ $themeButton }} text-white text-sm font-medium rounded-lg transition">
                {{ __('loyalty.use_code') }}
            </button>
        </form>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('loyalty.redemptions') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    placeholder="{{ __('loyalty.search_code_or_customer') }}"
                >
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select
                        name="status"
                        class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('loyalty.all_status') }}</option>
                        @foreach(\App\Models\LoyaltyRedemption::STATUSES as $value => $label)
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
                    <a href="{{ route('loyalty.redemptions') }}" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Redemptions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.code') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.customer') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.reward') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.points_used') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.valid_until') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($redemptions as $redemption)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $redemption->code }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('loyalty.customer-history', $redemption->customer) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $redemption->customer?->name ?? '-' }}
                                </a>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $redemption->reward?->name ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ format_number($redemption->points_used) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'used' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$redemption->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $redemption->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ format_date($redemption->valid_until) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if($redemption->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('loyalty.use-redemption') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ $redemption->code }}">
                                            <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-700 text-sm font-medium">
                                                {{ __('loyalty.use') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('loyalty.cancel-redemption', $redemption) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('loyalty.confirm_cancel') }}')">
                                            @csrf
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 text-sm font-medium">
                                                {{ __('common.cancel') }}
                                            </button>
                                        </form>
                                    </div>
                                @elseif($redemption->status === 'used')
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ format_datetime($redemption->used_at) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_redemptions') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($redemptions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
