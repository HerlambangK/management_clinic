@extends('layouts.dashboard')

@section('title', __('dashboard.title'))
@section('page-title', __('dashboard.title'))

@php
    // Theme-based classes
    $accentColor = $tc->primary ?? 'primary';
    $linkClass = $tc->link ?? 'text-primary-600 hover:text-primary-700';
    $badgeBgClass = $tc->badgeBg ?? 'bg-primary-100';
    $badgeTextClass = $tc->badgeText ?? 'text-primary-700';
@endphp

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Stats Cards -->
    <div class="grid grid-cols-4 max-lg:grid-cols-2 gap-4 max-sm:gap-2">
        <!-- Revenue Today -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('dashboard.revenue') }}</p>
                    <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5 truncate">{{ format_currency($stats['revenue_today']) }}</p>
                </div>
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Transactions Today -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('dashboard.transactions') }}</p>
                    <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5">{{ $stats['transactions_today'] }}</p>
                </div>
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 {{ $badgeBgClass }} rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 {{ $badgeTextClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Appointments Today -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('dashboard.appointments') }}</p>
                    <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5">{{ $stats['appointments_today'] }}</p>
                </div>
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('dashboard.customers') }}</p>
                    <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5">{{ $stats['total_customers'] }}</p>
                </div>
                <div class="w-9 h-9 max-sm:w-8 max-sm:h-8 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Widgets Row -->
    <div class="grid grid-cols-3 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Revenue Chart -->
        <div class="col-span-2 max-lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4 max-sm:mb-3">
                <h2 class="text-base max-sm:text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('dashboard.revenue_7_days') }}</h2>
                <a href="{{ route('reports.revenue') }}" class="text-xs {{ $linkClass }} font-medium">{{ __('dashboard.view_report') }}</a>
            </div>
            <div class="h-56 max-sm:h-48 -mx-2">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Popular Services -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col max-h-80 max-sm:max-h-64">
            <div class="flex items-center justify-between mb-3 flex-shrink-0">
                <h2 class="text-base max-sm:text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('dashboard.popular_services') }}</h2>
                <a href="{{ route('reports.services') }}" class="text-xs {{ $linkClass }} font-medium">{{ __('common.detail') }}</a>
            </div>
            <div class="space-y-3 overflow-y-auto flex-1 scrollbar-thin">
                @forelse($popularServices as $index => $service)
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 {{ $badgeBgClass }} rounded-md flex items-center justify-center {{ $badgeTextClass }} font-semibold text-xs flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100 truncate">{{ $service->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->appointments_count }} {{ __('dashboard.bookings') }}</p>
                        </div>
                        <span class="text-xs font-medium text-gray-900 dark:text-gray-100 flex-shrink-0">{{ $service->formatted_price }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">{{ __('service.no_services') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-5 max-sm:p-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
            <div class="flex items-center justify-between">
                <h2 class="text-base max-sm:text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('dashboard.recent_transactions') }}</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs {{ $linkClass }} font-medium">{{ __('dashboard.view_all') }}</a>
            </div>
        </div>
        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto max-h-80 overflow-y-auto scrollbar-thin">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.invoice') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.customer') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('transaction.total') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentTransactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('transactions.show', $transaction) }}" class="font-mono text-sm font-medium {{ $linkClass }}">
                                    {{ $transaction->invoice_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->customer?->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->formatted_total_amount }}</p>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                                        'partial' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400',
                                        'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                        'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'refunded' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ format_datetime($transaction->created_at) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_transactions') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Mobile Cards -->
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto">
            @forelse($recentTransactions as $transaction)
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
                        <span class="font-mono text-xs font-medium {{ $badgeTextClass }}">{{ $transaction->invoice_number }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $transaction->status_label }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->customer?->name ?? '-' }}</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->formatted_total_amount }}</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ format_datetime($transaction->created_at) }}</p>
                </a>
            @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_transactions') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-5 max-sm:p-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
            <div class="flex items-center justify-between">
                <h2 class="text-base max-sm:text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('dashboard.today_appointments') }}</h2>
                <a href="{{ route('appointments.index') }}" class="text-xs {{ $linkClass }} font-medium">{{ __('dashboard.view_all') }}</a>
            </div>
        </div>
        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto max-h-80 overflow-y-auto scrollbar-thin">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.time') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('appointment.customer') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('appointment.service') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('staff.title') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($todayAppointments as $appointment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ format_time($appointment->start_time) }}</span>
                                <span class="text-sm text-gray-400">-</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ format_time($appointment->end_time) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                                        {{ substr($appointment->customer?->name ?? '-', 0, 2) }}
                                    </div>
                                    <div class="ml-2.5">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $appointment->customer?->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->customer?->phone ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $appointment->service?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->service?->duration_minutes ?? 0 }} {{ __('common.minutes') }}</p>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $appointment->staff?->name ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'confirmed' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                                        'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                        'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                        'no_show' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $appointment->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($appointment->status === 'pending')
                                        <button class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-xs font-medium">{{ __('appointment.confirm_action') }}</button>
                                    @elseif($appointment->status === 'confirmed')
                                        <button class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-xs font-medium">{{ __('dashboard.start') }}</button>
                                    @elseif($appointment->status === 'in_progress')
                                        <button class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-xs font-medium">{{ __('dashboard.complete') }}</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_appointments_today') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Mobile Cards -->
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto">
            @forelse($todayAppointments as $appointment)
                @php
                    $statusColors = [
                        'pending' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                        'confirmed' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                        'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                        'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                        'no_show' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                    ];
                @endphp
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                                {{ substr($appointment->customer?->name ?? '-', 0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $appointment->customer?->name ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $appointment->status_label }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                        <span>{{ format_time($appointment->start_time) }} - {{ format_time($appointment->end_time) }}</span>
                        <span>{{ $appointment->service?->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Staff: {{ $appointment->staff?->name ?? '-' }}</span>
                        @if($appointment->status === 'pending')
                            <button class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-xs font-medium">{{ __('appointment.confirm_action') }}</button>
                        @elseif($appointment->status === 'confirmed')
                            <button class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-xs font-medium">{{ __('dashboard.start') }}</button>
                        @elseif($appointment->status === 'in_progress')
                            <button class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-xs font-medium">{{ __('dashboard.complete') }}</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_appointments_today') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const isMobile = window.innerWidth < 640;

    // Theme colors based on business type
    const chartColors = {
        'clinic': { bg: 'rgba(244, 63, 94, 0.8)', border: 'rgba(244, 63, 94, 1)' },
        'salon': { bg: 'rgba(168, 85, 247, 0.8)', border: 'rgba(168, 85, 247, 1)' },
        'barbershop': { bg: 'rgba(59, 130, 246, 0.8)', border: 'rgba(59, 130, 246, 1)' },
        'gym': { bg: 'rgba(16, 185, 129, 0.8)', border: 'rgba(16, 185, 129, 1)' }
    };
    const businessType = '{{ $businessType ?? "clinic" }}';
    const colors = chartColors[businessType] || chartColors['clinic'];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($revenueChart['labels']),
            datasets: [{
                label: '{{ __("dashboard.revenue") }}',
                data: @json($revenueChart['data']),
                backgroundColor: colors.bg,
                borderColor: colors.border,
                borderWidth: 1,
                borderRadius: isMobile ? 4 : 6,
                barThickness: isMobile ? 24 : undefined,
                maxBarThickness: isMobile ? 32 : 50,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 0,
                    right: isMobile ? 8 : 16,
                    top: 0,
                    bottom: 0
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 10 : 12
                        },
                        maxTicksLimit: isMobile ? 5 : 8,
                        callback: function(value) {
                            if (isMobile) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                }
                                return 'Rp ' + value;
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 10 : 12
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
