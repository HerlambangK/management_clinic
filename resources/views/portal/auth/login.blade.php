@extends('layouts.portal')

@section('title', __('portal.login'))

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
            <h1 class="mt-6 text-2xl font-bold text-gray-900 dark:text-white">{{ __('portal.welcome_back') }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('portal.login_subtitle') }}</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-xl">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('portal.login.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="business_type" value="{{ request('business_type', request('type', session('business_type_filter'))) }}">

                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.email_address') }}
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.enter_email') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('portal.password') }}
                        </label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="{{ __('portal.enter_password') }}">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('portal.remember_me') }}</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        {{ __('portal.login_button') }}
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('portal.no_account') }}
                    <a href="{{ route('portal.register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('portal.register_now') }}
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
