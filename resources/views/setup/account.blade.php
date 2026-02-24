@extends('layouts.landing')

@section('title', __('setup.create_owner_account') . ' - ' . brand_name())

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full space-y-8 relative z-10">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-serif font-bold text-gray-900">
                {{ __('setup.create_owner_account') }}
            </h1>
            <p class="mt-2 text-gray-600">
                {{ __('setup.main_admin_account') }}
            </p>

            <!-- Step indicator -->
            <div class="flex justify-center items-center mt-6 space-x-4 max-sm:space-x-2">
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span class="ml-2 text-sm text-gray-500 max-sm:hidden">{{ __('setup.business_type') }}</span>
                </div>
                <div class="w-8 h-px bg-gray-300 max-sm:w-4"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span class="ml-2 text-sm text-gray-500 max-sm:hidden">{{ __('setup.details') }}</span>
                </div>
                <div class="w-8 h-px bg-gray-300 max-sm:w-4"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-medium">3</span>
                    <span class="ml-2 text-sm font-medium text-primary-600 max-sm:hidden">{{ __('setup.account') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 max-sm:p-6">
            <form action="{{ route('setup.complete') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.full_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                           placeholder="{{ __('setup.enter_full_name') }}"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.email') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                           placeholder="{{ __('setup.enter_email') }}"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.password') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                           placeholder="{{ __('setup.min_8_chars') }}"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.confirm_password') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                           placeholder="{{ __('setup.reenter_password') }}"
                           required>
                </div>

                <!-- Summary -->
                <div class="bg-primary-50 rounded-lg p-4 mt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('setup.setup_summary') }}</h4>
                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">{{ __('setup.business_type') }}:</dt>
                            <dd class="font-medium text-primary-600">
                                {{ app()->getLocale() === 'en' ? $businessConfig['name_en'] : $businessConfig['name'] }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">{{ __('setup.business_name') }}:</dt>
                            <dd class="font-medium text-gray-900">{{ session('setup.business_name') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('setup.details', ['type' => $businessType]) }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('setup.back') }}
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ __('setup.complete_setup') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Note -->
        <p class="text-center text-sm text-gray-500">
            {{ __('setup.terms_agreement') }}
        </p>
    </div>
</div>
@endsection
