@extends('layouts.dashboard')

@section('title', __('setting.operating_hours'))
@section('page-title', __('setting.operating_hours'))

@section('content')
<div class="w-full">
    <!-- Back Button -->
    <a href="{{ route('settings.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/50 dark:border-green-800 dark:text-green-400 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('settings.hours.update') }}" method="POST" class="space-y-4">
            @csrf

            @php
                $dayNames = [
                    __('setting.days.sunday'),
                    __('setting.days.monday'),
                    __('setting.days.tuesday'),
                    __('setting.days.wednesday'),
                    __('setting.days.thursday'),
                    __('setting.days.friday'),
                    __('setting.days.saturday'),
                ];
            @endphp

            @foreach($hours as $hour)
                <div
                    class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 transition"
                    x-data="{ isClosed: {{ $hour->is_closed ? 'true' : 'false' }} }"
                >
                    <!-- Day Name -->
                    <div class="w-24 flex-shrink-0">
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $dayNames[$hour->day_of_week] }}</span>
                    </div>

                    <!-- Closed Toggle -->
                    <div class="flex items-center gap-2">
                        <input
                            type="hidden"
                            name="hours[{{ $hour->day_of_week }}][is_closed]"
                            value="0"
                        >
                        <input
                            type="checkbox"
                            id="is_closed_{{ $hour->day_of_week }}"
                            name="hours[{{ $hour->day_of_week }}][is_closed]"
                            value="1"
                            class="w-4 h-4 text-rose-500 border-gray-300 dark:border-gray-600 rounded focus:ring-rose-500/20"
                            x-model="isClosed"
                            {{ old("hours.{$hour->day_of_week}.is_closed", $hour->is_closed) ? 'checked' : '' }}
                        >
                        <label for="is_closed_{{ $hour->day_of_week }}" class="text-sm text-gray-600 dark:text-gray-400">{{ __('setting.is_closed') }}</label>
                    </div>

                    <!-- Time Inputs -->
                    <div class="flex-1 flex items-center gap-2" x-show="!isClosed" x-cloak>
                        <input
                            type="time"
                            name="hours[{{ $hour->day_of_week }}][open_time]"
                            value="{{ old("hours.{$hour->day_of_week}.open_time", $hour->open_time ? \Carbon\Carbon::parse($hour->open_time)->format('H:i') : '09:00') }}"
                            class="flex-1 px-3 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition text-sm"
                            :disabled="isClosed"
                        >
                        <span class="text-gray-400 dark:text-gray-500">-</span>
                        <input
                            type="time"
                            name="hours[{{ $hour->day_of_week }}][close_time]"
                            value="{{ old("hours.{$hour->day_of_week}.close_time", $hour->close_time ? \Carbon\Carbon::parse($hour->close_time)->format('H:i') : '18:00') }}"
                            class="flex-1 px-3 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition text-sm"
                            :disabled="isClosed"
                        >
                    </div>

                    <!-- Closed Label -->
                    <div class="flex-1" x-show="isClosed" x-cloak>
                        <span class="text-sm text-gray-400 dark:text-gray-500 italic">{{ __('setting.clinic_closed') }}</span>
                    </div>
                </div>
            @endforeach

            @error('hours')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('setting.save_hours') }}
                </button>
                <a href="{{ route('settings.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
