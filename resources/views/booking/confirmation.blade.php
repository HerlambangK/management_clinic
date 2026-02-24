@extends('layouts.landing')

@section('title', __('booking.confirmation_title') . ' - ' . brand_name())

@section('content')
<div class="relative min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto relative z-10">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">{{ __('booking.booking_confirmed') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('booking.confirmation_message') }}</p>
        </div>

        <!-- Booking Details Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="space-y-4">
                <!-- Service -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 {{ $tc->bgLight ?? 'bg-rose-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 {{ $tc->iconColor ?? 'text-rose-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('booking.service') }}</p>
                        <p class="font-semibold text-gray-900">{{ $appointment->service->name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->service->formatted_duration }} - {{ $appointment->service->formatted_price }}</p>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('booking.date_time') }}</p>
                        <p class="font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                        <p class="text-sm text-gray-500">{{ format_time($appointment->start_time) }} - {{ format_time($appointment->end_time) }}</p>
                    </div>
                </div>

                <!-- Staff (if assigned) -->
                @if($appointment->staff)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ business_staff_label() }}</p>
                            <p class="font-semibold text-gray-900">{{ $appointment->staff->name }}</p>
                        </div>
                    </div>
                @endif

                <!-- Customer -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('booking.customer') }}</p>
                        <p class="font-semibold text-gray-900">{{ $appointment->customer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->customer->phone }}</p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('common.status') }}</span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ $appointment->status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-amber-800">
                    <p class="font-medium mb-1">{{ __('booking.important_notes') }}</p>
                    <ul class="list-disc list-inside space-y-1 text-amber-700">
                        <li>{{ __('booking.note_arrive_early') }}</li>
                        <li>{{ __('booking.note_cancel_policy') }}</li>
                        <li>{{ __('booking.note_contact_us') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 space-y-3">
            <a href="{{ route('home') }}" class="block w-full text-center px-6 py-3 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white font-medium rounded-xl transition">
                {{ __('booking.back_to_home') }}
            </a>

            @if(in_array($appointment->status, ['pending', 'confirmed']))
                <form action="{{ route('booking.cancel', $appointment) }}" method="POST" onsubmit="return confirm('{{ __('booking.cancel_confirm') }}')">
                    @csrf
                    <button type="submit" class="w-full text-center px-6 py-3 border border-red-300 text-red-600 font-medium rounded-xl hover:bg-red-50 transition">
                        {{ __('booking.cancel_booking') }}
                    </button>
                </form>
            @endif
        </div>

        <!-- Contact Info -->
        @if(brand_contact('phone') || brand_contact('whatsapp'))
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>{{ __('booking.need_help') }}</p>
                @if(brand_contact('whatsapp'))
                    <a href="https://wa.me/{{ brand_contact('whatsapp') }}" target="_blank" class="{{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">
                        WhatsApp: {{ brand_contact('whatsapp') }}
                    </a>
                @elseif(brand_contact('phone'))
                    <a href="tel:{{ brand_contact('phone') }}" class="{{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">
                        {{ brand_contact('phone') }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
