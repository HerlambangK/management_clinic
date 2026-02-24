@extends('layouts.dashboard')

@section('title', __('transaction.title'))
@section('page-title', __('transaction.title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header & Stats -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('transaction.subtitle') }}</p>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $themeButton }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('transaction.add') }}
        </a>
    </div>

    <!-- Today Stats -->
    <div class="grid grid-cols-3 max-sm:grid-cols-3 gap-3 max-sm:gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <div class="flex items-center gap-3 max-sm:flex-col max-sm:items-start max-sm:gap-2">
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.today') }}</p>
                    <p class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ $todayStats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <div class="flex items-center gap-3 max-sm:flex-col max-sm:items-start max-sm:gap-2">
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.paid') }}</p>
                    <p class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ $todayStats['paid'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <div class="flex items-center gap-3 max-sm:flex-col max-sm:items-start max-sm:gap-2">
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 {{ $themeBadgeBg }} rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 {{ $themeBadgeText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.revenue') }}</p>
                    <p class="text-lg max-sm:text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ format_currency($todayStats['revenue']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('transaction.search_placeholder') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
            <div class="flex gap-2 max-sm:flex-wrap">
                <div class="relative">
                    <select name="status" class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">{{ __('transaction.status') }}</option>
                        @foreach(\App\Models\Transaction::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search', 'status', 'date']))
                    <a href="{{ route('transactions.index') }}" class="px-3 py-2 max-sm:py-1.5 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-gray-200 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Transactions List -->
    @if($transactions->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.invoice') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.customer') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.items') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.total') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.status') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.date') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($transactions as $transaction)
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                                    'partial' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400',
                                    'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                    'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    'refunded' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="font-mono text-sm font-medium {{ $themeLink }}">
                                        {{ $transaction->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    @if($transaction->customer)
                                        <a href="{{ route('customers.show', $transaction->customer) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:{{ $themeAccent }}">
                                            {{ $transaction->customer->name }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->customer->phone }}</p>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $transaction->items_count }} item
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->formatted_total_amount }}</p>
                                    @if($transaction->status === 'partial')
                                        <p class="text-xs text-orange-500 dark:text-orange-400">{{ __('transaction.paid') }}: {{ $transaction->formatted_paid_amount }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $transaction->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ format_date($transaction->created_at) }}
                                    <p class="text-xs">{{ format_time($transaction->created_at) }}</p>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="{{ __('common.detail') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($transaction->status === 'paid')
                                            <a href="{{ route('transactions.invoice', $transaction) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="{{ __('transaction.invoice') }}" target="_blank">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($transactions as $transaction)
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                            'partial' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400',
                            'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                            'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                            'refunded' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                        ];
                    @endphp
                    <a href="{{ route('transactions.show', $transaction) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-medium {{ $themeAccent }}">{{ $transaction->invoice_number }}</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $transaction->status_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->customer?->name ?? '-' }}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->formatted_total_amount }}</p>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $transaction->items_count }} item</span>
                            <span>{{ format_datetime($transaction->created_at) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-10 max-sm:p-6 text-center">
            <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-base max-sm:text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ __('transaction.no_transactions') }}</h3>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-4">{{ __('transaction.add_first') }}</p>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $themeButton }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('transaction.add') }}
            </a>
        </div>
    @endif
</div>
@endsection
