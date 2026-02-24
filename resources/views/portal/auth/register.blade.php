@extends('layouts.portal')

@section('title', __('portal.register'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-50 to-primary-50 dark:from-gray-900 dark:to-gray-800 px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                @if(brand_logo('logo'))
                    <img src="{{ brand_logo('logo') }}" alt="{{ brand_name() }}" class="h-12 mx-auto">
                @else
                    <span class="text-2xl font-bold text-primary-600">{{ brand_name() }}</span>
                @endif
            </a>
            <h1 class="mt-6 text-2xl font-bold text-gray-900 dark:text-white">{{ __('portal.create_account') }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('portal.register_subtitle') }}</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('portal.register.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="business_type" value="{{ request('business_type', request('type', session('business_type_filter'))) }}">

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.full_name') }}
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.enter_full_name') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.email_address') }}
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.enter_email') }}">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.phone_number') }}
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.enter_phone') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.password') }}
                        </label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.min_8_chars') }}">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.confirm_password') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.reenter_password') }}">
                    </div>

                    @if(config('referral.enabled', true))
                    <div>
                        <label for="referral_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.referral_code') }} <span class="text-gray-400 font-normal">({{ __('common.optional') }})</span>
                        </label>
                        <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code', request('ref')) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors font-mono uppercase"
                            placeholder="{{ __('portal.enter_referral_code') }}"
                            maxlength="20">
                    </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="w-full px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            {{ __('portal.register_button') }}
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('portal.already_have_account') }}
                    <a href="{{ route('portal.login') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('portal.login_now') }}
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                &larr; {{ __('portal.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
