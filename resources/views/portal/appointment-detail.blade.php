@extends('layouts.portal')

@section('title', __('portal.appointment_detail'))
@section('page-title', __('portal.appointment_detail'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('portal.appointments') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ __('portal.back_to_appointments') }}
    </a>

    <!-- Appointment Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $appointment->service->name ?? '-' }}
                </h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">{{ $appointment->booking_code }}</p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                    'confirmed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                    'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                    'completed' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                    'no_show' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                {{ __('appointments.status_' . $appointment->status) }}
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.date') }}</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ format_date($appointment->appointment_date) }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.time') }}</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            @if($appointment->staff)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.therapist') }}</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointment->staff->name }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.duration') }}</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $appointment->service?->duration_minutes ?? '-' }} {{ __('portal.minutes') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Details -->
    @if($appointment->service)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.service') }}</h3>
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                    @if($appointment->service->category)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->service->category->name }}</p>
                    @endif
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $appointment->service->duration_minutes }} {{ __('portal.minutes') }}</p>
                </div>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ format_currency($appointment->service->price) }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Notes -->
    @if($appointment->notes)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.notes') }}</h3>
            <div class="prose prose-sm dark:prose-invert max-w-none">
                {!! nl2br(e($appointment->notes)) !!}
            </div>
        </div>
    @endif

    <!-- Actions -->
    @if(in_array($appointment->status, ['pending', 'confirmed']))
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6">
            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                {{ __('portal.cancel_appointment_notice') }}
            </p>
        </div>
    @endif
</div>
@endsection
