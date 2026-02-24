@extends('layouts.dashboard')

@section('title', __('appointment.title'))
@section('page-title', __('appointment.title'))

@php
    // Use $tc from BusinessServiceProvider for theme consistency
    $buttonClass = $tc->button ?? 'bg-rose-500 hover:bg-rose-600';
    $linkClass = $tc->linkDark ?? 'text-rose-600 hover:text-rose-700';
    $accentClass = $tc->accent ?? 'text-rose-500';
    $ringClass = $tc->ring ?? 'focus:ring-rose-500/20 focus:border-rose-400';
@endphp

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('appointment.subtitle') }}</p>
        <div class="flex flex-wrap items-center gap-2">
            @if(business_type() === 'gym' && $currentStore)
                <a href="{{ route('attendance.public-qr', $currentStore->attendance_token) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm max-sm:text-xs font-medium rounded-lg transition hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 8h2v2h-2v-2zm4 0h2v6h-6v-2h4v-4zm-6 6h2v2h-2v-2zm4 4h4v-4h-2v2h-2v2z" />
                    </svg>
                    {{ __('attendance.qr_title') }}
                </a>
            @endif
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $buttonClass }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('appointment.new_booking') }}
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Today Stats -->
    <div class="grid grid-cols-5 max-lg:grid-cols-5 max-sm:grid-cols-2 gap-3 max-sm:gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100">{{ $todayStats['total'] }}</p>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('appointment.total') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <p class="text-2xl max-sm:text-lg font-bold text-gray-500 dark:text-gray-400">{{ $todayStats['pending'] }}</p>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('appointment.pending') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <p class="text-2xl max-sm:text-lg font-bold text-yellow-500 dark:text-yellow-400">{{ $todayStats['confirmed'] }}</p>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('appointment.confirmed') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
            <p class="text-2xl max-sm:text-lg font-bold text-blue-500 dark:text-blue-400">{{ $todayStats['in_progress'] }}</p>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('appointment.progress') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3 max-sm:col-span-2">
            <p class="text-2xl max-sm:text-lg font-bold text-green-500 dark:text-green-400">{{ $todayStats['completed'] }}</p>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('appointment.completed') }}</p>
        </div>
    </div>

    <!-- Date Navigation & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('appointments.index') }}" method="GET" class="flex flex-row max-sm:flex-col items-center max-sm:items-stretch gap-3 max-sm:gap-2">
            <!-- Date Navigation -->
            <div class="flex items-center justify-center max-sm:justify-between gap-2 max-sm:w-full">
                <a href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->format('Y-m-d'), 'staff_id' => $staffId]) }}" class="p-2 max-sm:p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <input
                    type="date"
                    name="date"
                    value="{{ $date->format('Y-m-d') }}"
                    class="flex-1 max-sm:flex-initial px-3 max-sm:px-2 py-2 max-sm:py-1.5 text-sm max-sm:text-xs border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $ringClass }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    onchange="this.form.submit()"
                >
                <a href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->format('Y-m-d'), 'staff_id' => $staffId]) }}" class="p-2 max-sm:p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="{{ route('appointments.index', ['staff_id' => $staffId]) }}" class="px-3 max-sm:px-2 py-1.5 text-sm max-sm:text-xs font-medium {{ $linkClass }} transition whitespace-nowrap">
                    {{ __('appointment.today') }}
                </a>
            </div>

            <div class="flex-1 max-sm:hidden"></div>

            <!-- Staff Filter -->
            <div class="relative max-sm:w-full">
                <select
                    name="staff_id"
                    class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm max-sm:text-xs border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $ringClass }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    onchange="this.form.submit()"
                >
                    <option value="">{{ $allStaffLabel }}</option>
                    @foreach($beauticians as $beautician)
                        <option value="{{ $beautician->id }}" {{ $staffId == $beautician->id ? 'selected' : '' }}>
                            {{ $beautician->name }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Date Display -->
    <div class="text-center">
        <h2 class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100">
            {{ $date->translatedFormat('l, d F Y') }}
        </h2>
        @if($date->isToday())
            <p class="text-sm max-sm:text-xs {{ $accentClass }} font-medium">{{ __('appointment.today') }}</p>
        @endif
    </div>

    <!-- Appointments List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        @if($appointments->isEmpty())
            <div class="text-center py-10 max-sm:py-8">
                <svg class="mx-auto h-10 w-10 max-sm:h-8 max-sm:w-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('appointment.no_appointments') }}</p>
                <a href="{{ route('appointments.create', ['date' => $date->format('Y-m-d')]) }}" class="mt-3 inline-flex items-center text-sm max-sm:text-xs {{ $linkClass }} font-medium">
                    {{ __('appointment.create_new') }}
                    <svg class="ml-1 w-4 h-4 max-sm:w-3 max-sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($appointments as $appointment)
                    @php
                        $statusColors = [
                            'pending' => 'bg-gray-400',
                            'confirmed' => 'bg-yellow-400',
                            'in_progress' => 'bg-blue-400',
                            'completed' => 'bg-green-400',
                            'cancelled' => 'bg-red-400',
                            'no_show' => 'bg-red-400',
                        ];
                        $statusBadgeColors = [
                            'pending' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                            'confirmed' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                            'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                            'no_show' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                        ];
                    @endphp
                    <a href="{{ route('appointments.show', $appointment) }}" class="block p-4 max-sm:p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700 transition">
                        <!-- Desktop Layout -->
                        <div class="hidden sm:flex items-center gap-4">
                            <!-- Time -->
                            <div class="text-center flex-shrink-0 w-20">
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ format_time($appointment->start_time) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ format_time($appointment->end_time) }}</p>
                            </div>

                            <!-- Status Indicator -->
                            <div class="w-1 h-12 rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-400' }}"></div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $appointment->customer?->name ?? '-' }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeColors[$appointment->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $appointment->status_label }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $appointment->service?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->customer?->phone ?? '-' }}</p>
                            </div>

                            <!-- Staff -->
                            @if($appointment->staff)
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $appointment->staff->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $staffLabel }}</p>
                                </div>
                            @else
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">{{ __('customer.not_specified') }}</p>
                                </div>
                            @endif

                            <!-- Arrow -->
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <!-- Mobile Layout -->
                        <div class="sm:hidden">
                            <div class="flex items-start gap-2">
                                <!-- Status Indicator -->
                                <div class="w-1 self-stretch rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-400' }} flex-shrink-0"></div>

                                <!-- Time -->
                                <div class="flex-shrink-0 w-12">
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ format_time($appointment->start_time) }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ format_time($appointment->end_time) }}</p>
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $appointment->customer?->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 truncate">{{ $appointment->service?->name ?? '-' }}</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $appointment->staff?->name ?? __('customer.not_specified') }}</p>
                                </div>

                                <!-- Status Badge & Arrow -->
                                <div class="flex flex-col items-end justify-between flex-shrink-0 self-stretch">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $statusBadgeColors[$appointment->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $appointment->status_label }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-3 max-sm:p-2">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 max-sm:gap-x-3 max-sm:gap-y-1 text-sm max-sm:text-[10px] text-gray-600 dark:text-gray-400">
            <span class="font-medium">{{ __('appointment.status') }}:</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-400"></span> {{ __('appointment.pending') }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> {{ __('appointment.confirmed') }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-400"></span> {{ __('appointment.progress') }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400"></span> {{ __('appointment.completed') }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-400"></span> {{ __('appointment.status_cancelled') }}</span>
        </div>
    </div>
</div>
@endsection
