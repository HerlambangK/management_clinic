@extends('layouts.landing')

@section('title', __('booking.check_status') . ' - ' . brand_name())

@section('content')
<div class="relative min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                @if(brand_logo())
                    <img src="{{ brand_logo() }}" alt="{{ brand_name() }}" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br {{ $tc->gradient ?? 'from-rose-400 to-rose-500' }} rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                @endif
                <span class="text-xl font-bold text-gray-900">{{ brand_name() }}</span>
            </a>
            <h1 class="text-2xl font-serif font-bold text-gray-900">{{ __('booking.check_status') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('booking.status_subtitle') }}</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <form action="{{ route('booking.status') }}" method="GET" class="flex gap-3 max-sm:flex-col">
                <input type="tel" name="phone" value="{{ request('phone') }}"
                       placeholder="{{ __('booking.enter_phone') }}"
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400"
                       required>
                <button type="submit" class="px-6 py-2.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white font-medium rounded-lg transition">
                    {{ __('booking.search') }}
                </button>
            </form>
        </div>

        <!-- Results -->
        @if(request('phone'))
            @if($appointments->count() > 0)
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('booking.upcoming_appointments') }}</h2>

                    @foreach($appointments as $appointment)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $appointment->service->name }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ format_time($appointment->start_time) }} - {{ format_time($appointment->end_time) }}
                                    </p>
                                    @if($appointment->staff)
                                        <p class="text-sm text-gray-500 mt-1">{{ business_staff_label() }}: {{ $appointment->staff->name }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                        @switch($appointment->status)
                                            @case('pending') bg-yellow-100 text-yellow-700 @break
                                            @case('confirmed') bg-blue-100 text-blue-700 @break
                                            @case('in_progress') bg-purple-100 text-purple-700 @break
                                            @case('completed') bg-green-100 text-green-700 @break
                                            @case('cancelled') bg-red-100 text-red-700 @break
                                            @default bg-gray-100 text-gray-700
                                        @endswitch
                                    ">
                                        {{ $appointment->status_label }}
                                    </span>

                                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                                        <form action="{{ route('booking.cancel', $appointment) }}" method="POST" class="mt-2"
                                              onsubmit="return confirm('{{ __('booking.cancel_confirm') }}')">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">
                                                {{ __('booking.cancel_booking') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('booking.no_appointments') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('booking.no_appointments_desc') }}</p>
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center px-4 py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white font-medium rounded-lg transition">
                        {{ __('booking.make_booking') }}
                    </a>
                </div>
            @endif
        @endif

        <!-- Back to Booking -->
        <div class="mt-8 text-center">
            <a href="{{ route('booking.index') }}" class="{{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('booking.back_to_booking') }}
            </a>
        </div>
    </div>
</div>
@endsection
