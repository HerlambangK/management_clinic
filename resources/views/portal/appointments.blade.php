@extends('layouts.portal')

@section('title', __('portal.my_appointments'))
@section('page-title', __('portal.my_appointments'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('portal.appointments_subtitle') }}</p>
        </div>
        <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors text-sm max-sm:text-xs whitespace-nowrap flex-shrink-0">
            <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="max-sm:hidden">{{ __('portal.new_appointment') }}</span>
            <span class="sm:hidden">{{ __('common.add') }}</span>
        </a>
    </div>

    <!-- Appointments List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        @if($appointments->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($appointments as $appointment)
                    <a href="{{ route('portal.appointments.show', $appointment) }}" class="block p-4 max-sm:p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-3 max-sm:gap-2">
                            <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                <span class="text-xs max-sm:text-[10px] font-medium text-primary-600 dark:text-primary-300">{{ $appointment->appointment_date->format('M') }}</span>
                                <span class="text-base max-sm:text-sm font-bold text-primary-700 dark:text-primary-200">{{ $appointment->appointment_date->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm max-sm:text-xs font-medium text-gray-900 dark:text-white truncate">
                                        {{ $appointment->service->name ?? '-' }}
                                    </p>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ __('appointments.status_' . $appointment->status) }}
                                    </span>
                                </div>
                                <div class="mt-1 flex items-center gap-3 max-sm:gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('H:i') : '-' }}
                                    </span>
                                    @if($appointment->staff)
                                        <span class="flex items-center gap-1 max-sm:hidden">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $appointment->staff->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('portal.no_appointments') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('portal.no_appointments_desc') }}</p>
                <a href="{{ route('booking.index') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                    {{ __('portal.book_first_appointment') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
