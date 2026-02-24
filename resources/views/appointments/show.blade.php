@extends('layouts.dashboard')

@section('title', __('appointment.detail'))
@section('page-title', __('appointment.detail'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6 max-sm:space-y-4">
    <!-- Back Button & Actions -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <a href="{{ route('appointments.index', ['date' => $appointment->appointment_date->format('Y-m-d')]) }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('common.back') }}
        </a>
        @if(!in_array($appointment->status, ['completed', 'cancelled', 'no_show']))
            <div class="flex items-center gap-2">
                <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('common.edit') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Appointment Details -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <!-- Status Badge -->
        <div class="flex items-center justify-between mb-6 max-sm:mb-4">
            @php
                $statusBadgeColors = [
                    'pending' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                    'confirmed' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                    'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                    'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                    'no_show' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 max-sm:px-2 max-sm:py-0.5 rounded-full text-sm max-sm:text-xs font-medium {{ $statusBadgeColors[$appointment->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $appointment->status_label }}
            </span>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">ID: #{{ $appointment->id }}</p>
        </div>

        <!-- Date & Time -->
        <div class="{{ $tc->bgLight ?? 'bg-rose-50' }} dark:bg-gray-700 rounded-lg p-4 max-sm:p-3 mb-6 max-sm:mb-4">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 {{ $tc->bgMedium ?? 'bg-rose-100' }} dark:bg-gray-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 {{ $tc->iconColor ?? 'text-rose-600' }} dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-gray-100">{{ $appointment->appointment_date->translatedFormat('l, d F Y') }}</p>
                    <p class="{{ $tc->iconColor ?? 'text-rose-600' }} dark:text-gray-300 font-medium text-sm max-sm:text-xs">{{ format_time($appointment->start_time) }} - {{ format_time($appointment->end_time) }}</p>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-6 max-sm:gap-4">
            <!-- Customer -->
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('appointment.customer') }}</p>
                @if($appointment->customer)
                    <a href="{{ route('customers.show', $appointment->customer) }}" class="text-gray-900 dark:text-gray-100 font-medium text-sm {{ $tc->linkHover ?? 'hover:text-rose-600' }} dark:hover:text-gray-300 transition">
                        {{ $appointment->customer->name }}
                    </a>
                    <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">{{ $appointment->customer->phone }}</p>
                @else
                    <p class="text-gray-400 dark:text-gray-500 italic text-sm">-</p>
                @endif
            </div>

            <!-- Service -->
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('appointment.service') }}</p>
                @if($appointment->service)
                    <p class="text-gray-900 dark:text-gray-100 font-medium text-sm">{{ $appointment->service->name }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">{{ $appointment->service->duration_minutes }} {{ __('common.minutes') }} - {{ format_currency($appointment->service->price) }}</p>
                @else
                    <p class="text-gray-400 dark:text-gray-500 italic text-sm">-</p>
                @endif
            </div>

            <!-- Staff -->
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $staffLabel }}</p>
                @if($appointment->staff)
                    <p class="text-gray-900 dark:text-gray-100 font-medium text-sm">{{ $appointment->staff->name }}</p>
                @else
                    <p class="text-gray-400 dark:text-gray-500 italic text-sm">{{ __('customer.not_specified') }}</p>
                @endif
            </div>

            <!-- Source -->
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('appointment.booking_source') }}</p>
                <p class="text-gray-900 dark:text-gray-100 text-sm">{{ $appointment->source_label }}</p>
            </div>
        </div>

        <!-- Notes -->
        @if($appointment->notes)
            <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('appointment.notes') }}</p>
                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $appointment->notes }}</p>
            </div>
        @endif

        <!-- Cancelled Info -->
        @if($appointment->status === 'cancelled' && $appointment->cancelled_reason)
            <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 max-sm:p-3">
                    <p class="text-sm max-sm:text-xs text-red-600 dark:text-red-400 font-medium mb-1">{{ __('appointment.cancel_reason') }}</p>
                    <p class="text-red-700 dark:text-red-300 text-sm max-sm:text-xs">{{ $appointment->cancelled_reason }}</p>
                    <p class="text-xs text-red-500 dark:text-red-400 mt-2">{{ __('appointment.cancelled_at') }}: {{ format_datetime($appointment->cancelled_at) }}</p>
                </div>
            </div>
        @endif

        <!-- Timestamps -->
        <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
            <p>{{ __('appointment.created_at') }}: {{ format_datetime($appointment->created_at) }}</p>
            @if($appointment->updated_at != $appointment->created_at)
                <p>{{ __('appointment.updated_at') }}: {{ format_datetime($appointment->updated_at) }}</p>
            @endif
        </div>
    </div>

    <!-- Status Actions -->
    @if(!in_array($appointment->status, ['completed', 'cancelled', 'no_show']))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.change_status') }}</h3>

            <div class="flex flex-wrap gap-3 max-sm:gap-2">
                @if($appointment->status === 'pending')
                    <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-yellow-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-yellow-600 transition">
                            {{ __('appointment.confirm_action') }}
                        </button>
                    </form>
                @endif

                @if($appointment->status === 'confirmed')
                    <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-blue-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-blue-600 transition">
                            {{ __('appointment.start_treatment') }}
                        </button>
                    </form>

                    <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="no_show">
                        <button type="submit" class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-gray-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-600 transition" onclick="return confirm('{{ __('appointment.confirm_no_show') }}')">
                            {{ __('appointment.status_no_show') }}
                        </button>
                    </form>
                @endif

                @if($appointment->status === 'in_progress')
                    <!-- Checkout/Payment Button -->
                    <a href="{{ route('transactions.create', ['appointment_id' => $appointment->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-green-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-green-600 transition">
                        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ __('appointment.finish_checkout') }}
                    </a>
                @endif

                <!-- Cancel Button -->
                <button
                    type="button"
                    class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-red-100 text-red-600 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-red-200 transition"
                    x-data
                    @click="$dispatch('open-cancel-modal')"
                >
                    {{ __('appointment.cancel_action') }}
                </button>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div
            x-data="{ open: false }"
            @open-cancel-modal.window="open = true"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50" @click="open = false"></div>

                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6 max-sm:p-4 max-sm:mx-4">
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.cancel_appointment') }}</h3>

                    <form action="{{ route('appointments.update-status', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">

                        <div class="mb-4 max-sm:mb-3">
                            <label for="cancelled_reason" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.cancel_reason') }} <span class="text-red-500">*</span></label>
                            <textarea
                                id="cancelled_reason"
                                name="cancelled_reason"
                                rows="3"
                                class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                required
                                placeholder="{{ __('appointment.cancel_reason_placeholder') }}"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 max-sm:gap-2">
                            <button type="button" @click="open = false" class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                {{ __('common.cancel') }}
                            </button>
                            <button type="submit" class="px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-red-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-red-600 transition">
                                {{ __('appointment.cancel_appointment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Transaction Section -->
    @if($appointment->status === 'completed')
        @php
            $transaction = $appointment->transaction;
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.transaction_payment') }}</h3>

            @if($transaction)
                <div class="bg-{{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'partial' ? 'yellow' : 'gray') }}-50 dark:bg-{{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'partial' ? 'yellow' : 'gray') }}-900/30 rounded-lg p-4 max-sm:p-3">
                    <div class="flex flex-col sm:flex-row items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $transaction->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' : '' }}
                                    {{ $transaction->status === 'partial' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' : '' }}
                                    {{ $transaction->status === 'pending' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                ">
                                    {{ $transaction->status_label }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->invoice_number }}</span>
                            </div>
                            <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-gray-100">{{ format_currency($transaction->total_amount) }}</p>
                            @if($transaction->status !== 'paid')
                                <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">{{ __('appointment.paid') }}: {{ format_currency($transaction->paid_amount) }}</p>
                            @endif
                        </div>
                        <a href="{{ route('transactions.show', $transaction) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-{{ $transaction->status === 'paid' ? 'green' : 'rose' }}-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-{{ $transaction->status === 'paid' ? 'green' : 'rose' }}-600 transition flex-shrink-0">
                            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($transaction->status === 'paid')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                @endif
                            </svg>
                            {{ $transaction->status === 'paid' ? __('appointment.view_receipt') : __('appointment.receive_payment') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-6 max-sm:py-4">
                    <svg class="mx-auto h-12 w-12 max-sm:h-10 max-sm:w-10 text-gray-300 dark:text-gray-600 mb-3 max-sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs mb-4 max-sm:mb-3">{{ __('appointment.no_transaction') }}</p>
                    <a href="{{ route('transactions.create', ['appointment_id' => $appointment->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('appointment.create_transaction') }}
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Treatment Record Section -->
    @if($appointment->status === 'completed')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.treatment_notes') }}</h3>

            @if($appointment->treatmentRecord)
                <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 max-sm:p-3">
                    <div class="flex flex-col sm:flex-row items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm max-sm:text-xs text-green-600 dark:text-green-400 font-medium mb-1">{{ __('appointment.treatment_created') }}</p>
                            @if($appointment->treatmentRecord->notes)
                                <p class="text-sm max-sm:text-xs text-gray-700 dark:text-gray-300 line-clamp-2">{{ $appointment->treatmentRecord->notes }}</p>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('appointment.created_by') }}: {{ $appointment->treatmentRecord->staff->name }} {{ __('common.at') }} {{ format_datetime($appointment->treatmentRecord->created_at) }}</p>
                        </div>
                        <a href="{{ route('treatment-records.show', $appointment->treatmentRecord) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-green-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-green-600 transition flex-shrink-0">
                            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ __('common.view') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-6 max-sm:py-4">
                    <svg class="mx-auto h-12 w-12 max-sm:h-10 max-sm:w-10 text-gray-300 dark:text-gray-600 mb-3 max-sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs mb-4 max-sm:mb-3">{{ __('appointment.no_treatment_notes') }}</p>
                    <a href="{{ route('treatment-records.create', ['appointment_id' => $appointment->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('appointment.create_treatment') }}
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
