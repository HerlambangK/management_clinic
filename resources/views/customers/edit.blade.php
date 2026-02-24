@extends('layouts.dashboard')

@section('title', __('customer.edit'))
@section('page-title', __('customer.edit'))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Form -->
    <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6 max-sm:space-y-4">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('customer.basic_info') }}</h3>

            <div class="grid sm:grid-cols-2 gap-4 max-sm:gap-3">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.name') }} <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $customer->name) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20 focus:border-rose-400' }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('name') border-red-400 @enderror"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.phone') }} <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $customer->phone) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20 focus:border-rose-400' }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('phone') border-red-400 @enderror"
                        placeholder="{{ __('customer.phone_placeholder') }}"
                        required
                    >
                    @error('phone')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.email') }}</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $customer->email) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20 focus:border-rose-400' }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('email') border-red-400 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birthdate -->
                <div>
                    <label for="birthdate" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.birthdate') }}</label>
                    <input
                        type="date"
                        id="birthdate"
                        name="birthdate"
                        value="{{ old('birthdate', $customer->birthdate?->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20 focus:border-rose-400' }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('birthdate') border-red-400 @enderror"
                    >
                    @error('birthdate')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Gender -->
            <div class="mt-4 max-sm:mt-3">
                <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.gender') }}</label>
                <div class="flex flex-wrap gap-4 max-sm:gap-3">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="male" class="w-4 h-4 {{ $tc->radioColor ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 focus:ring-rose-500/20 dark:bg-gray-700" {{ old('gender', $customer->gender) === 'male' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm max-sm:text-xs text-gray-700 dark:text-gray-300">{{ __('customer.male') }}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="female" class="w-4 h-4 {{ $tc->radioColor ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 focus:ring-rose-500/20 dark:bg-gray-700" {{ old('gender', $customer->gender) === 'female' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm max-sm:text-xs text-gray-700 dark:text-gray-300">{{ __('customer.female') }}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="other" class="w-4 h-4 {{ $tc->radioColor ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 focus:ring-rose-500/20 dark:bg-gray-700" {{ old('gender', $customer->gender) === 'other' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm max-sm:text-xs text-gray-700 dark:text-gray-300">{{ __('customer.other') }}</span>
                    </label>
                </div>
                @error('gender')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="mt-4 max-sm:mt-3">
                <label for="address" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.address') }}</label>
                <textarea
                    id="address"
                    name="address"
                    rows="2"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20 focus:border-rose-400' }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('address') border-red-400 @enderror"
                >{{ old('address', $customer->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Profile Section (Dynamic based on business type) -->
        @include('customers.partials.profile-fields', ['customer' => $customer])

        <!-- Submit -->
        <div class="flex flex-row max-sm:flex-col items-center gap-3 max-sm:gap-2">
            <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                {{ __('common.save_changes') }}
            </button>
            <a href="{{ route('customers.show', $customer) }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center">
                {{ __('common.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
