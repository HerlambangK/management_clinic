@extends('layouts.portal')

@section('title', __('portal.dashboard'))
@section('page-title', __('portal.dashboard'))

@section('content')
@if(business_type() === 'gym')
@php
    $lastAttendance = $attendanceHistory->first();
@endphp
<div class="space-y-6">
    <div class="rounded-2xl p-6 text-white" style="background-color: var(--brand-primary);">
        <h2 class="text-2xl font-bold">{{ __('portal.welcome_greeting', ['name' => $customer->name]) }}</h2>
        <p class="mt-2 text-primary-100">{{ __('attendance.scan_desc') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('attendance.last_checkin') }}</p>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                {{ $lastAttendance?->checked_in_at?->format('d M Y H:i') ?? '-' }}
            </p>
            @if($lastAttendance?->location_name)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ __('attendance.location_name_label') }}: {{ $lastAttendance->location_name }}
                </p>
            @elseif($lastAttendance?->latitude && $lastAttendance?->longitude)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ __('attendance.location_name_label') }}: {{ $lastAttendance->latitude }}, {{ $lastAttendance->longitude }}
                </p>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('attendance.session_status') }}</p>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                {{ $activeAttendance ? __('attendance.session_active') : __('attendance.session_inactive') }}
            </p>
            @if($activeAttendance)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ __('attendance.session_since') }} {{ $activeAttendance->checked_in_at?->format('d M Y H:i') }}
                </p>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.total_visits') }}</p>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">{{ $customer->total_visits ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6" x-data="dashboardAttendance(@js($activeAttendance?->checked_in_at?->toIso8601String()), @js($hasCompletedToday), @js(route('portal.attendance.scan')))">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('attendance.scan_title') }}</h3>
                @if($activeAttendance)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.session_timer_desc') }}</p>
                    <div class="mt-3 text-2xl font-bold text-gray-900 dark:text-white" x-text="timerDisplay"></div>
                @else
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.scan_desc') }}</p>
                @endif
            </div>
            <div class="flex flex-wrap gap-3">
                @if($activeAttendance)
                    <form x-ref="form" method="POST" action="{{ route('portal.attendance.store') }}" class="w-full">
                        @csrf
                        <input type="hidden" name="scan_mode" value="location">
                        <input type="hidden" name="latitude" x-model="latitude">
                        <input type="hidden" name="longitude" x-model="longitude">
                        <input type="hidden" name="accuracy" x-model="accuracy">
                        <input type="hidden" name="location_name" x-model="locationName">
                        <button type="button" class="inline-flex items-center px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition" @click="endWorkout" :disabled="loading">
                            <span x-show="!loading">{{ __('attendance.end_session_button') }}</span>
                            <span x-show="loading" x-cloak>{{ __('attendance.checkin_loading') }}</span>
                        </button>
                    </form>
                    <p class="text-xs text-red-600 dark:text-red-400" x-show="error" x-text="error" x-cloak></p>
                @else
                    <button type="button" class="inline-flex items-center px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition" @click="goToScan('qr')">
                        {{ __('attendance.scan_qr_button') }}
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition hover:bg-gray-50 dark:hover:bg-gray-700" @click="goToScan('location')">
                        {{ __('attendance.checkin_button') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('attendance.history_title') }}</h3>
        </div>
        <div class="p-6">
            @if($attendanceHistory->count() > 0)
                <div class="space-y-3">
                    @foreach($attendanceHistory as $attendance)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $attendance->checked_in_at?->format('d M Y H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $attendance->checked_out_at ? __('attendance.session_finished') : __('attendance.session_active') }}
                                </p>
                                @if($attendance->location_name)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('attendance.location_name_label') }}: {{ $attendance->location_name }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $attendance->checked_out_at?->format('H:i') ?? '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.no_data') }}</p>
            @endif
        </div>
    </div>
</div>
@else
<div class="space-y-6">
    @php
        $premiumBadgeLabel = __('portal.premium_badge');
        $onlineBookingLocked = customer_portal_feature_locked('online_booking');
        $treatmentLocked = customer_portal_feature_locked('treatment_records');
        $packagesLocked = customer_portal_feature_locked('packages');
        $loyaltyLocked = customer_portal_feature_locked('loyalty');
    @endphp
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-6 text-white">
        <h2 class="text-2xl font-bold">{{ __('portal.welcome_greeting', ['name' => $customer->name]) }}</h2>
        <p class="mt-2 text-primary-100">{{ __('portal.welcome_message') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 max-sm:gap-2">
        <!-- Loyalty Points -->
        @if(has_feature('loyalty'))
            <div class="relative bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="{{ $loyaltyLocked ? 'blur-sm opacity-60' : '' }}">
                    <div class="flex items-center gap-3 max-sm:gap-2">
                        <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.loyalty_points') }}</p>
                            <p class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ format_number($customer->loyalty_points) }}</p>
                        </div>
                    </div>
                    <div class="mt-3 max-sm:mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $customer->loyalty_tier_color }}">
                            {{ $customer->loyalty_tier_label }}
                        </span>
                    </div>
                </div>
                @if($loyaltyLocked)
                    <div class="absolute top-3 right-3 z-20">
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                            </svg>
                            <span>{{ $premiumBadgeLabel }}</span>
                        </span>
                    </div>
                    <div class="absolute inset-0 z-10 cursor-not-allowed"></div>
                @endif
            </div>
        @endif

        <!-- Total Visits -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3 max-sm:gap-2">
                <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.total_visits') }}</p>
                    <p class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $customer->total_visits ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Packages -->
        @if(has_feature('packages'))
            <div class="relative bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="{{ $packagesLocked ? 'blur-sm opacity-60' : '' }}">
                    <div class="flex items-center gap-3 max-sm:gap-2">
                        <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.active_packages') }}</p>
                            <p class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $activePackages->count() }}</p>
                        </div>
                    </div>
                </div>
                @if($packagesLocked)
                    <div class="absolute top-3 right-3 z-20">
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                            </svg>
                            <span>{{ $premiumBadgeLabel }}</span>
                        </span>
                    </div>
                    <div class="absolute inset-0 z-10 cursor-not-allowed"></div>
                @endif
            </div>
        @endif

        <!-- Total Spent -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 max-sm:p-3 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3 max-sm:gap-2">
                <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.total_spent') }}</p>
                    <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-white truncate">{{ $customer->formatted_total_spent }}</p>
                </div>
            </div>
        </div>
    </div>

        @if(has_feature('attendance'))
            @php
            $qrImageUrl = $attendanceQrPayload ? 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data='.urlencode($attendanceQrPayload) : null;
            @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('attendance.qr_title') }}</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.scan_desc') }}</p>
                    <a href="{{ route('portal.attendance.scan') }}" class="mt-4 inline-flex items-center px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition">
                        {{ __('attendance.checkin_button') }}
                    </a>
                </div>
                <div class="relative p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="absolute inset-0 rounded-xl ring-2 ring-primary-200/60 animate-pulse-soft pointer-events-none"></div>
                    @if($qrImageUrl)
                        <img src="{{ $qrImageUrl }}" alt="QR Attendance" class="relative w-40 h-40">
                    @else
                        <div class="w-40 h-40 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">
                            {{ $attendanceIsOpen ? __('attendance.qr_missing') : __('attendance.qr_closed') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 max-sm:gap-3">
        <!-- Upcoming Appointments -->
        @if(has_feature('online_booking'))
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="{{ $onlineBookingLocked ? 'blur-sm opacity-60' : '' }}">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.upcoming_appointments') }}</h3>
                            @if($onlineBookingLocked)
                                <span class="text-sm text-gray-400">{{ __('portal.view_all') }}</span>
                            @else
                                <a href="{{ route('portal.appointments') }}" class="text-sm text-primary-600 hover:text-primary-700">{{ __('portal.view_all') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($upcomingAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingAppointments as $appointment)
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center">
                                            <span class="text-sm font-bold text-primary-700 dark:text-primary-300">{{ $appointment->appointment_date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $appointment->service->name ?? '-' }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ format_date($appointment->appointment_date) }} - {{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('H:i') : '-' }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                            {{ __('appointments.status_' . $appointment->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400">{{ __('portal.no_upcoming_appointments') }}</p>
                                @if($onlineBookingLocked)
                                    <span class="mt-4 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-400 rounded-lg text-sm font-medium">
                                        {{ __('portal.book_now') }}
                                    </span>
                                @else
                                    <a href="{{ route('booking.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        {{ __('portal.book_now') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @if($onlineBookingLocked)
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                            </svg>
                            <span>{{ $premiumBadgeLabel }}</span>
                        </span>
                    </div>
                    <div class="absolute inset-0 z-10 cursor-not-allowed"></div>
                @endif
            </div>
        @endif

        <!-- Recent Treatments -->
        @if(has_feature('treatment_records'))
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="{{ $treatmentLocked ? 'blur-sm opacity-60' : '' }}">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.recent_treatments') }}</h3>
                            @if($treatmentLocked)
                                <span class="text-sm text-gray-400">{{ __('portal.view_all') }}</span>
                            @else
                                <a href="{{ route('portal.treatments') }}" class="text-sm text-primary-600 hover:text-primary-700">{{ __('portal.view_all') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentTreatments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentTreatments as $treatment)
                                    @if($treatmentLocked)
                                        <div class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $treatment->appointment?->service?->name ?? '-' }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ format_date($treatment->created_at) }}
                                                    </p>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('portal.treatments.show', $treatment) }}" class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $treatment->appointment?->service?->name ?? '-' }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ format_date($treatment->created_at) }}
                                                    </p>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400">{{ __('portal.no_treatments_yet') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($treatmentLocked)
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                            </svg>
                            <span>{{ $premiumBadgeLabel }}</span>
                        </span>
                    </div>
                    <div class="absolute inset-0 z-10 cursor-not-allowed"></div>
                @endif
            </div>
        @endif
    </div>

    <!-- Active Packages -->
    @if(has_feature('packages') && $activePackages->count() > 0)
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="{{ $packagesLocked ? 'blur-sm opacity-60' : '' }}">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.active_packages') }}</h3>
                        @if($packagesLocked)
                            <span class="text-sm text-gray-400">{{ __('portal.view_all') }}</span>
                        @else
                            <a href="{{ route('portal.packages') }}" class="text-sm text-primary-600 hover:text-primary-700">{{ __('portal.view_all') }}</a>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($activePackages as $customerPackage)
                            @if($packagesLocked)
                                <div class="block p-4 border border-gray-200 dark:border-gray-700 rounded-xl">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $customerPackage->package?->name }}</h4>
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('portal.remaining_sessions') }}</span>
                                            <span class="font-semibold text-primary-600">{{ $customerPackage->remaining_sessions }} / {{ $customerPackage->total_sessions }}</span>
                                        </div>
                                        <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $customerPackage->total_sessions > 0 ? ($customerPackage->remaining_sessions / $customerPackage->total_sessions) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    @if($customerPackage->expires_at)
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('portal.expires') }}: {{ format_date($customerPackage->expires_at) }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <a href="{{ route('portal.packages.show', $customerPackage) }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-primary-500 transition-colors">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $customerPackage->package?->name }}</h4>
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('portal.remaining_sessions') }}</span>
                                            <span class="font-semibold text-primary-600">{{ $customerPackage->remaining_sessions }} / {{ $customerPackage->total_sessions }}</span>
                                        </div>
                                        <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $customerPackage->total_sessions > 0 ? ($customerPackage->remaining_sessions / $customerPackage->total_sessions) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    @if($customerPackage->expires_at)
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('portal.expires') }}: {{ format_date($customerPackage->expires_at) }}
                                        </p>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @if($packagesLocked)
                <div class="absolute top-4 right-4 z-20">
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                        </svg>
                        <span>{{ $premiumBadgeLabel }}</span>
                    </span>
                </div>
                <div class="absolute inset-0 z-10 cursor-not-allowed"></div>
            @endif
        </div>
    @endif
</div>
@endif
@endsection

@push('scripts')
<script>
    function dashboardAttendance(activeStartedAt, hasCompletedToday, scanUrl) {
        return {
            activeStartedAt: activeStartedAt,
            hasCompletedToday: hasCompletedToday,
            timerTick: 0,
            timerInterval: null,
            loading: false,
            error: null,
            latitude: null,
            longitude: null,
            accuracy: null,
            locationName: '',
            init() {
                this.setupTimer();
            },
            get timerDisplay() {
                if (! this.activeStartedAt) {
                    return '00:00:00';
                }
                void this.timerTick;
                const started = new Date(this.activeStartedAt).getTime();
                const diffSeconds = Math.max(0, Math.floor((Date.now() - started) / 1000));
                const hours = String(Math.floor(diffSeconds / 3600)).padStart(2, '0');
                const minutes = String(Math.floor((diffSeconds % 3600) / 60)).padStart(2, '0');
                const seconds = String(diffSeconds % 60).padStart(2, '0');
                return `${hours}:${minutes}:${seconds}`;
            },
            setupTimer() {
                if (! this.activeStartedAt) {
                    return;
                }
                if (this.timerInterval) {
                    clearInterval(this.timerInterval);
                }
                this.timerInterval = setInterval(() => {
                    this.timerTick += 1;
                }, 1000);
            },
            goToScan(mode) {
                if (this.hasCompletedToday) {
                    const confirmed = window.confirm('{{ __('attendance.restart_confirm') }}');
                    if (! confirmed) {
                        return;
                    }
                }
                window.location.href = `${scanUrl}?mode=${mode}`;
            },
            endWorkout() {
                this.error = null;
                if (! navigator.geolocation) {
                    this.error = '{{ __('attendance.location_not_supported') }}';
                    return;
                }
                this.loading = true;
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.accuracy = Math.round(position.coords.accuracy);
                        this.resolveLocationName().finally(() => {
                            this.$refs.form.submit();
                        });
                    },
                    (error) => {
                        this.loading = false;
                        if (error?.code === error.PERMISSION_DENIED) {
                            this.error = '{{ __('attendance.location_permission_denied') }}';
                            return;
                        }
                        if (error?.code === error.POSITION_UNAVAILABLE) {
                            this.error = '{{ __('attendance.location_unavailable') }}';
                            return;
                        }
                        if (error?.code === error.TIMEOUT) {
                            this.error = '{{ __('attendance.location_timeout') }}';
                            return;
                        }
                        this.error = '{{ __('attendance.location_denied') }}';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                    }
                );
            },
            resolveLocationName() {
                if (this.latitude === null || this.longitude === null) {
                    return Promise.resolve();
                }
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${this.latitude}&lon=${this.longitude}`;
                return fetch(url, { headers: { 'Accept-Language': 'id' } })
                    .then((response) => response.ok ? response.json() : null)
                    .then((data) => {
                        if (! data?.address) {
                            return;
                        }
                        const address = data.address;
                        const village = address.village || address.suburb || address.neighbourhood || address.hamlet;
                        const city = address.city || address.town || address.county || address.city_district;
                        const state = address.state || address.region;
                        const parts = [village, city, state].filter(Boolean);
                        if (parts.length > 0) {
                            this.locationName = parts.join(', ');
                        }
                    })
                    .catch(() => {});
            },
        };
    }
</script>
@endpush
