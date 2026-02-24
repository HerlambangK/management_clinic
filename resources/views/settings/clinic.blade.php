@extends('layouts.dashboard')

@section('title', __('setting.clinic_profile'))
@section('page-title', __('setting.clinic_profile'))

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
        <form action="{{ route('settings.clinic.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Business Type Selection -->
            <div x-data="{ selected: '{{ old('business_type', $settings['business_type']) }}' }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('setting.business_type') }} <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-3">
                    @foreach($businessTypes as $key => $type)
                        @php
                            $colors = [
                                'clinic' => ['border' => 'border-rose-500 dark:border-rose-400', 'bg' => 'bg-rose-50 dark:bg-rose-900/30'],
                                'salon' => ['border' => 'border-purple-500 dark:border-purple-400', 'bg' => 'bg-purple-50 dark:bg-purple-900/30'],
                                'barbershop' => ['border' => 'border-blue-500 dark:border-blue-400', 'bg' => 'bg-blue-50 dark:bg-blue-900/30'],
                                'gym' => ['border' => 'border-emerald-500 dark:border-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/30'],
                            ];
                            $color = $colors[$key] ?? $colors['clinic'];
                        @endphp
                        <label class="relative cursor-pointer" @click="selected = '{{ $key }}'">
                            <input type="radio" name="business_type" value="{{ $key }}"
                                   {{ old('business_type', $settings['business_type']) === $key ? 'checked' : '' }}
                                   class="sr-only">
                            <div class="p-4 rounded-xl border-2 transition-all"
                                 :class="selected === '{{ $key }}' ? '{{ $color['border'] }} {{ $color['bg'] }}' : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800'">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $type['theme']['gradient_from'] }} {{ $type['theme']['gradient_to'] }} rounded-lg flex items-center justify-center">
                                        @if($type['icon'] === 'sparkles')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        @elseif($type['icon'] === 'scissors')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                                            </svg>
                                        @elseif($type['icon'] === 'dumbbell')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6v12m10-12v12M4 9h16M4 15h16M2 10v4m20-4v4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ app()->getLocale() === 'en' ? $type['name_en'] : $type['name'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ app()->getLocale() === 'en' ? $type['description_en'] : $type['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.business_type_hint') }}</p>
                @error('business_type')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            <!-- Current Logo -->
            @if($settings['clinic_logo'])
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.current_logo') }}</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ Storage::url($settings['clinic_logo']) }}" alt="Logo" class="h-16 w-16 object-contain rounded-lg border border-gray-200 dark:border-gray-600">
                    </div>
                </div>
            @endif

            <!-- Upload Logo -->
            <div>
                <label for="clinic_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $settings['clinic_logo'] ? __('setting.change_logo') : __('setting.clinic_logo') }}</label>
                <input
                    type="file"
                    id="clinic_logo"
                    name="clinic_logo"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition @error('clinic_logo') border-red-400 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.clinic_logo_help') }}</p>
                @error('clinic_logo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Business Name -->
            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.business_name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="business_name"
                    name="business_name"
                    value="{{ old('business_name', $settings['business_name']) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition @error('business_name') border-red-400 @enderror"
                    required
                >
                @error('business_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="business_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.business_address') }}</label>
                <textarea
                    id="business_address"
                    name="business_address"
                    rows="3"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition @error('business_address') border-red-400 @enderror"
                >{{ old('business_address', $settings['business_address']) }}</textarea>
                @error('business_address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="business_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.business_phone') }}</label>
                <input
                    type="text"
                    id="business_phone"
                    name="business_phone"
                    value="{{ old('business_phone', $settings['business_phone']) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition @error('business_phone') border-red-400 @enderror"
                    placeholder="08xxxxxxxxxx"
                >
                @error('business_phone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="business_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.business_email') }}</label>
                <input
                    type="email"
                    id="business_email"
                    name="business_email"
                    value="{{ old('business_email', $settings['business_email']) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition @error('business_email') border-red-400 @enderror"
                >
                @error('business_email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.invoice_settings') }}</h3>

            <!-- Invoice Prefix -->
            <div>
                <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.invoice_prefix') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="invoice_prefix"
                    name="invoice_prefix"
                    value="{{ old('invoice_prefix', $settings['invoice_prefix']) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('invoice_prefix') border-red-400 @enderror"
                    maxlength="10"
                    required
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.invoice_prefix_help') }}</p>
                @error('invoice_prefix')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tax Percentage -->
            <div>
                <label for="tax_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.tax_percentage') }} <span class="text-red-500">*</span></label>
                <input
                    type="number"
                    id="tax_percentage"
                    name="tax_percentage"
                    value="{{ old('tax_percentage', $settings['tax_percentage']) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('tax_percentage') border-red-400 @enderror"
                    min="0"
                    max="100"
                    required
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.tax_percentage_help') }}</p>
                @error('tax_percentage')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('setting.appointment_settings') }}</h3>

            <!-- Slot Duration -->
            <div>
                <label for="slot_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.slot_duration') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="slot_duration"
                        name="slot_duration"
                        class="w-full pl-4 pr-12 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none @error('slot_duration') border-red-400 @enderror"
                        required
                    >
                        <option value="15" {{ old('slot_duration', $settings['slot_duration']) == 15 ? 'selected' : '' }}>15 {{ __('common.minutes') }}</option>
                        <option value="30" {{ old('slot_duration', $settings['slot_duration']) == 30 ? 'selected' : '' }}>30 {{ __('common.minutes') }}</option>
                        <option value="45" {{ old('slot_duration', $settings['slot_duration']) == 45 ? 'selected' : '' }}>45 {{ __('common.minutes') }}</option>
                        <option value="60" {{ old('slot_duration', $settings['slot_duration']) == 60 ? 'selected' : '' }}>60 {{ __('common.minutes') }}</option>
                        <option value="90" {{ old('slot_duration', $settings['slot_duration']) == 90 ? 'selected' : '' }}>90 {{ __('common.minutes') }}</option>
                        <option value="120" {{ old('slot_duration', $settings['slot_duration']) == 120 ? 'selected' : '' }}>120 {{ __('common.minutes') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.slot_duration_help') }}</p>
                @error('slot_duration')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('setting.save_settings') }}
                </button>
                <a href="{{ route('settings.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
