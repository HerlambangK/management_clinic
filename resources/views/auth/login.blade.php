<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login') }} - {{ brand_name() }}</title>
    @if(brand_logo('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ brand_logo('favicon') }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $palette = brand_palette();
    @endphp
    <style>
        :root {
            --brand-primary: {{ brand_color('primary') }};
            --brand-primary-hover: {{ brand_color('primary_hover') }};
            --brand-primary-light: {{ brand_color('primary_light') }};
@if($palette)
            --color-primary-50: {{ $palette['colors']['50'] }};
            --color-primary-100: {{ $palette['colors']['100'] }};
            --color-primary-200: {{ $palette['colors']['200'] }};
            --color-primary-300: {{ $palette['colors']['300'] }};
            --color-primary-400: {{ $palette['colors']['400'] }};
            --color-primary-500: {{ $palette['colors']['500'] }};
            --color-primary-600: {{ $palette['colors']['600'] }};
            --color-primary-700: {{ $palette['colors']['700'] }};
            --color-primary-800: {{ $palette['colors']['800'] }};
            --color-primary-900: {{ $palette['colors']['900'] }};
@endif
        }
    </style>
    @if(brand_custom_css())
        <style>{!! brand_custom_css() !!}</style>
    @endif
    @if(brand_custom_script('head'))
        {!! brand_custom_script('head') !!}
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-primary-100" x-data="{ showPassword: false }">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Language Switcher -->
            <div class="flex justify-end mb-4">
                <x-language-switcher />
            </div>

            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
                    @if(brand_logo())
                        <img src="{{ brand_logo() }}" alt="{{ brand_name() }}" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br {{ $tc->gradient ?? 'from-rose-400 to-rose-500' }} rounded-xl flex items-center justify-center shadow-lg {{ $tc->shadowLight ?? 'shadow-rose-200/50' }} group-hover:{{ $tc->shadowMedium ?? 'shadow-rose-300/50' }} transition-shadow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                    @endif
                    @if(!brand_logo() || brand('logo.show_text', true))
                        <span class="text-xl font-bold text-gray-900">{{ brand_name() }}</span>
                    @endif
                </a>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('auth.login_title') }}</h1>
                    <p class="text-gray-500">{{ __('auth.login_subtitle') }}</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="business_type" value="{{ request('business_type', request('type', session('business_type_filter'))) }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email') }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', 'owner@jagoflutter.com') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition @error('email') border-red-400 @enderror"
                            placeholder="email@example.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.password') }}</label>
                        <div class="relative">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                name="password"
                                value="password"
                                class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition @error('password') border-red-400 @enderror"
                                placeholder="{{ __('auth.enter_password') }}"
                                required
                            >
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500/20"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</label>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-primary-400 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-500 hover:to-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition shadow-lg shadow-primary-200/50 hover:shadow-primary-300/50"
                    >
                        {{ __('auth.login') }}
                    </button>
                </form>
            </div>

            <!-- Demo Credentials -->
            <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-sm font-medium text-amber-800 mb-2">{{ __('auth.demo_credentials') }}:</p>
                <div class="text-xs text-amber-700 space-y-1">
                    <p><span class="font-medium">{{ __('auth.owner') }}:</span> owner@jagoflutter.com</p>
                    <p><span class="font-medium">{{ __('auth.admin') }}:</span> admin@jagoflutter.com</p>
                    <p><span class="font-medium">{{ __('auth.password') }}:</span> password</p>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-500 mt-6">
                {{ brand_copyright() }} @if(brand('footer.show_powered_by', true)) Powered by <a href="{{ brand('footer.powered_by_url', 'https://glowup.app') }}" target="_blank" class="{{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">{{ brand('footer.powered_by_text', 'GlowUp') }}</a>@endif
            </p>
        </div>
    </div>
    @if(brand_custom_script('body'))
        {!! brand_custom_script('body') !!}
    @endif
</body>
</html>
