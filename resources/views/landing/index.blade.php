@extends('layouts.landing')

@php
    $businessType = business_type() ?? 'clinic';

    // Theme colors per business type
    $landingThemes = [
        'clinic' => [
            'primary' => 'rose',
            'gradient_from' => 'from-rose-500',
            'gradient_to' => 'to-primary-600',
            'bg_light' => 'bg-rose-100',
            'bg_50' => 'bg-rose-50',
            'text' => 'text-rose-600',
            'text_700' => 'text-rose-700',
            'text_300' => 'text-rose-300',
            'text_400' => 'text-rose-400',
            'border' => 'border-rose-100',
            'border_200' => 'border-rose-200',
            'border_400' => 'border-rose-400',
            'hover_bg' => 'hover:bg-rose-50',
            'hover_text' => 'hover:text-rose-600',
            'dark_hover_text' => 'dark:hover:text-rose-400',
            'shadow' => 'shadow-rose-200',
            'hover_shadow' => 'hover:shadow-rose-200',
            'hover_shadow_xl' => 'hover:shadow-rose-500/30',
            'group_hover_shadow' => 'group-hover:shadow-rose-300',
            'fill' => 'fill-rose-500',
            'bg_500' => 'bg-rose-500',
            'bg_500_10' => 'bg-rose-500/10',
            'bg_500_20' => 'bg-rose-500/20',
            'bg_400' => 'bg-rose-400',
        ],
        'salon' => [
            'primary' => 'purple',
            'gradient_from' => 'from-purple-500',
            'gradient_to' => 'to-violet-600',
            'bg_light' => 'bg-purple-100',
            'bg_50' => 'bg-purple-50',
            'text' => 'text-purple-600',
            'text_700' => 'text-purple-700',
            'text_300' => 'text-purple-300',
            'text_400' => 'text-purple-400',
            'border' => 'border-purple-100',
            'border_200' => 'border-purple-200',
            'border_400' => 'border-purple-400',
            'hover_bg' => 'hover:bg-purple-50',
            'hover_text' => 'hover:text-purple-600',
            'dark_hover_text' => 'dark:hover:text-purple-400',
            'shadow' => 'shadow-purple-200',
            'hover_shadow' => 'hover:shadow-purple-200',
            'hover_shadow_xl' => 'hover:shadow-purple-500/30',
            'group_hover_shadow' => 'group-hover:shadow-purple-300',
            'fill' => 'fill-purple-500',
            'bg_500' => 'bg-purple-500',
            'bg_500_10' => 'bg-purple-500/10',
            'bg_500_20' => 'bg-purple-500/20',
            'bg_400' => 'bg-purple-400',
        ],
        'barbershop' => [
            'primary' => 'blue',
            'gradient_from' => 'from-blue-500',
            'gradient_to' => 'to-blue-600',
            'bg_light' => 'bg-blue-100',
            'bg_50' => 'bg-blue-50',
            'text' => 'text-blue-600',
            'text_700' => 'text-blue-700',
            'text_300' => 'text-blue-300',
            'text_400' => 'text-blue-400',
            'border' => 'border-blue-200',
            'border_200' => 'border-blue-200',
            'border_400' => 'border-blue-500',
            'hover_bg' => 'hover:bg-blue-50',
            'hover_text' => 'hover:text-blue-600',
            'dark_hover_text' => 'dark:hover:text-blue-400',
            'shadow' => 'shadow-blue-200',
            'hover_shadow' => 'hover:shadow-blue-200',
            'hover_shadow_xl' => 'hover:shadow-blue-500/30',
            'group_hover_shadow' => 'group-hover:shadow-blue-300',
            'fill' => 'fill-blue-500',
            'bg_500' => 'bg-blue-500',
            'bg_500_10' => 'bg-blue-500/10',
            'bg_500_20' => 'bg-blue-500/20',
            'bg_400' => 'bg-blue-400',
        ],
        'gym' => [
            'primary' => 'emerald',
            'gradient_from' => 'from-emerald-500',
            'gradient_to' => 'to-teal-600',
            'bg_light' => 'bg-emerald-100',
            'bg_50' => 'bg-emerald-50',
            'text' => 'text-emerald-600',
            'text_700' => 'text-emerald-700',
            'text_300' => 'text-emerald-300',
            'text_400' => 'text-emerald-400',
            'border' => 'border-emerald-100',
            'border_200' => 'border-emerald-200',
            'border_400' => 'border-emerald-400',
            'hover_bg' => 'hover:bg-emerald-50',
            'hover_text' => 'hover:text-emerald-600',
            'dark_hover_text' => 'dark:hover:text-emerald-400',
            'shadow' => 'shadow-emerald-200',
            'hover_shadow' => 'hover:shadow-emerald-200',
            'hover_shadow_xl' => 'hover:shadow-emerald-500/30',
            'group_hover_shadow' => 'group-hover:shadow-emerald-300',
            'fill' => 'fill-emerald-500',
            'bg_500' => 'bg-emerald-500',
            'bg_500_10' => 'bg-emerald-500/10',
            'bg_500_20' => 'bg-emerald-500/20',
            'bg_400' => 'bg-emerald-400',
        ],
    ];

    $lt = (object) ($landingThemes[$businessType] ?? $landingThemes['clinic']);
@endphp

@section('content')
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    @if(brand_logo())
                        <img src="{{ brand_logo() }}" alt="{{ brand_name() }}" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br {{ $lt->gradient_from }} {{ $lt->gradient_to }} rounded-2xl flex items-center justify-center shadow-lg {{ $lt->shadow }} {{ $lt->group_hover_shadow }} transition-shadow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    @endif
                    @if(!brand_logo() || brand('logo.show_text', true))
                        <span class="text-2xl font-display font-bold text-gray-900">{{ brand_name() ?: 'GlowUp' }}</span>
                    @endif
                </a>

                <!-- Desktop Menu (Left aligned) -->
                <div class="flex-1 flex items-center ml-16 max-lg:hidden">
                    <!-- Menu Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1.5 text-gray-600 {{ $lt->hover_text }} transition-colors font-medium">
                            Menu
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-3 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                            <a href="#features" @click="open = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                {{ __('landing.nav_features') }}
                            </a>
                            <a href="#solutions" @click="open = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                {{ __('landing.nav_solutions') }}
                            </a>
                            <a href="#mobile-apps" @click="open = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                {{ __('landing.nav_mobile_apps') }}
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <a href="#testimonials" @click="open = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ __('landing.nav_testimonials') }}
                            </a>
                            <a href="#faq" @click="open = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('landing.nav_faq') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons & Language Switcher -->
                <div class="flex items-center gap-4 max-lg:hidden">
                    <!-- Language Switcher -->
                    <x-language-switcher />

                    <!-- Login Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1.5 px-3 py-2 text-gray-700 {{ $lt->hover_text }} font-medium text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Login
                            <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                            <a href="{{ route('portal.login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('landing.nav_customer_portal') }}
                            </a>
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 {{ $lt->hover_bg }} {{ $lt->hover_text }} transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('landing.nav_login') }}
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('booking.index') }}" class="px-5 py-2.5 border-2 {{ $lt->border_400 }} {{ $lt->text }} font-medium text-sm rounded-full {{ $lt->hover_bg }} transition-all whitespace-nowrap">
                        {{ __('landing.nav_book_now') }}
                    </a>
                    <a href="https://jagoflutter.com/glowupclinic" target="_blank" class="px-5 py-2.5 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} text-white font-semibold text-sm rounded-full hover:shadow-lg {{ $lt->hover_shadow }} transition-all hover:-translate-y-0.5 whitespace-nowrap">
                        {{ __('landing.nav_get_sourcecode') }}
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-lg {{ $lt->hover_bg }} transition-colors hidden max-lg:block">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenu" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="hidden max-lg:block bg-white/95 backdrop-blur-lg border-t {{ $lt->border }}">
            <div class="px-4 py-6 space-y-4">
                <a href="#features" @click="mobileMenu = false" class="block text-gray-700 {{ $lt->hover_text }} font-medium py-2">{{ __('landing.nav_features') }}</a>
                <a href="#solutions" @click="mobileMenu = false" class="block text-gray-700 {{ $lt->hover_text }} font-medium py-2">{{ __('landing.nav_solutions') }}</a>
                <a href="#mobile-apps" @click="mobileMenu = false" class="block text-gray-700 {{ $lt->hover_text }} font-medium py-2">{{ __('landing.nav_mobile_apps') }}</a>
                <a href="#testimonials" @click="mobileMenu = false" class="block text-gray-700 {{ $lt->hover_text }} font-medium py-2">{{ __('landing.nav_testimonials') }}</a>
                <a href="#faq" @click="mobileMenu = false" class="block text-gray-700 {{ $lt->hover_text }} font-medium py-2">{{ __('landing.nav_faq') }}</a>
                <!-- Language Switcher Mobile -->
                <div class="py-2">
                    <x-language-switcher />
                </div>
                <div class="pt-4 border-t {{ $lt->border }} space-y-3">
                    <a href="{{ route('booking.index') }}" class="block text-center px-6 py-3 border-2 {{ $lt->border_400 }} {{ $lt->text }} font-semibold rounded-full">{{ __('landing.nav_book_now') }}</a>
                    <a href="{{ route('portal.login') }}" class="block text-center text-gray-700 font-medium py-2">{{ __('landing.nav_customer_portal') }}</a>
                    <a href="{{ route('login') }}" class="block text-center text-gray-700 font-medium py-2">{{ __('landing.nav_login') }}</a>
                    <a href="https://jagoflutter.com/glowupclinic" target="_blank" class="block text-center px-6 py-3 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} text-white font-semibold rounded-full">{{ __('landing.nav_get_sourcecode') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative flex items-center pt-24 pb-12 overflow-hidden">
        <div class="max-w-7xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-12 max-lg:gap-8 items-center">
                <!-- Left Content -->
                <div class="relative z-10">
                    <div class="animate-fade-in-up stagger-1">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full {{ $lt->bg_light }} {{ $lt->text_700 }} text-sm font-medium mb-4">
                            <span class="w-2 h-2 {{ $lt->bg_500 }} rounded-full mr-2 animate-pulse"></span>
                            {{ landing_text('hero_badge') }}
                        </span>
                    </div>

                    <h1 class="text-5xl max-lg:text-4xl max-sm:text-3xl font-display font-bold text-gray-900 leading-tight animate-fade-in-up stagger-2">
                        {{ landing_text('hero_title') }}
                    </h1>

                    <p class="mt-4 text-lg max-sm:text-base text-gray-600 leading-relaxed animate-fade-in-up stagger-3">
                        {{ landing_text('hero_subtitle') }}
                    </p>

                    <div class="mt-6 flex flex-row max-sm:flex-col gap-4 animate-fade-in-up stagger-4">
                        <a href="https://jagoflutter.com/glowupclinic" target="_blank" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} text-white font-semibold rounded-full hover:shadow-xl {{ $lt->hover_shadow }}/50 transition-all hover:-translate-y-1">
                            {{ __('landing.hero_cta') }}
                            <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="#solutions" class="group inline-flex items-center justify-center px-8 py-4 border-2 {{ $lt->border_200 }} text-gray-700 font-semibold rounded-full hover:{{ $lt->border_400 }} {{ $lt->hover_bg }} transition-all">
                            <svg class="mr-2 w-5 h-5 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ __('landing.hero_demo') }}
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-6 animate-fade-in-up stagger-5">
                        <p class="text-sm text-gray-500 mb-3">{{ __('landing.hero_trust_title') }}</p>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 bg-white rounded-full text-sm text-gray-600 border border-gray-200">
                                <svg class="w-4 h-4 mr-1.5 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('landing.hero_trust_website') }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 bg-white rounded-full text-sm text-gray-600 border border-gray-200">
                                <svg class="w-4 h-4 mr-1.5 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('landing.hero_trust_dashboard') }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 bg-white rounded-full text-sm text-gray-600 border border-gray-200">
                                <svg class="w-4 h-4 mr-1.5 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('landing.hero_trust_mobile') }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 bg-white rounded-full text-sm text-gray-600 border border-gray-200">
                                <svg class="w-4 h-4 mr-1.5 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('landing.hero_trust_support') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Mobile App Preview -->
                <div class="relative lg:pl-6">
                    <div class="relative animate-float">
                        <!-- Devices Showcase - Phone + Tablet -->
                        <div class="relative flex items-end justify-center gap-6 max-lg:gap-4 max-sm:gap-2">

                            <!-- Tablet (Left, behind) -->
                            <div class="relative z-10 animate-fade-in-up" style="animation-delay: 0.3s;">
                                <div class="relative w-72 max-lg:w-56 max-sm:w-40 transform -rotate-3">
                                    <div class="bg-gray-900 rounded-[1.5rem] max-lg:rounded-[1.2rem] max-sm:rounded-[0.8rem] p-1.5 max-sm:p-1 shadow-2xl shadow-gray-900/30">
                                        <div class="bg-black rounded-[1.2rem] max-lg:rounded-[1rem] max-sm:rounded-[0.6rem] overflow-hidden">
                                            <img src="{{ asset('images/tablet/2.png') }}" alt="GlowUp Tablet App - Dashboard" class="w-full">
                                        </div>
                                    </div>
                                    <!-- Tablet Label -->
                                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md border border-gray-100 max-sm:hidden">
                                        <span class="text-xs font-medium text-gray-600">Tablet</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone (Right, front) -->
                            <div class="relative z-20 animate-fade-in-up -ml-8 max-lg:-ml-6 max-sm:-ml-4" style="animation-delay: 0.5s;">
                                <div class="relative w-44 max-lg:w-36 max-sm:w-28">
                                    <div class="bg-gray-900 rounded-[2.5rem] max-lg:rounded-[2rem] max-sm:rounded-[1.5rem] p-2 max-sm:p-1.5 shadow-2xl shadow-gray-900/40 ring-2 ring-rose-500/20">
                                        <div class="bg-black rounded-[2rem] max-lg:rounded-[1.6rem] max-sm:rounded-[1.2rem] overflow-hidden">
                                            <img src="{{ asset('images/phone/2.png') }}" alt="GlowUp Mobile App - Dashboard" class="w-full">
                                        </div>
                                    </div>
                                    <!-- Phone Label -->
                                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} px-3 py-1 rounded-full shadow-md max-sm:hidden">
                                        <span class="text-xs font-medium text-white">Smartphone</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Badges -->
                        <div class="absolute -left-8 max-lg:-left-2 top-4 bg-white rounded-2xl shadow-xl {{ $lt->shadow }} px-4 py-3 {{ $lt->border }} animate-fade-in-up block max-md:hidden" style="animation-delay: 0.7s;">
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-10 bg-gradient-to-br {{ $lt->gradient_from }} {{ $lt->gradient_to }} rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Smartphone</p>
                                    <p class="text-sm font-bold text-gray-900">iOS & Android</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -right-8 max-lg:-right-2 top-4 bg-white rounded-2xl shadow-xl {{ $lt->shadow }} px-4 py-3 {{ $lt->border }} animate-fade-in-up block max-md:hidden" style="animation-delay: 0.9s;">
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tablet</p>
                                    <p class="text-sm font-bold text-gray-900">iPad & Android</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Badge -->
                        <div class="absolute left-1/2 -translate-x-1/2 -bottom-16 max-lg:-bottom-14 bg-white rounded-2xl shadow-xl {{ $lt->shadow }} px-5 py-3 {{ $lt->border }} animate-fade-in-up block max-sm:hidden" style="animation-delay: 1.1s;">
                            <div class="flex items-center space-x-3">
                                <div class="flex -space-x-2">
                                    <div class="w-8 h-8 {{ $lt->bg_light }} rounded-full flex items-center justify-center border-2 border-white">
                                        <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="w-8 h-8 bg-violet-100 rounded-full flex items-center justify-center border-2 border-white">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Multi-device Support</p>
                                    <p class="text-xs text-gray-500">Akses dari perangkat apapun</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <!-- Section Header -->
            <div class="max-w-2xl mx-auto text-center mb-16">
                <p class="{{ $lt->text }} font-medium mb-3 tracking-wide uppercase text-sm">{{ __('landing.features_badge') }}</p>
                <h2 class="text-4xl max-sm:text-3xl font-display font-bold text-gray-900 leading-tight mb-4">
                    {{ landing_text('features_title') }}
                </h2>
                <p class="text-gray-500">
                    {{ landing_text('features_subtitle') }}
                </p>
            </div>

            <!-- Features Grid - Mixed Layout -->
            <div class="grid grid-cols-12 max-lg:grid-cols-1 gap-6">
                <!-- Feature 1 - Large -->
                <div class="col-span-7 max-lg:col-span-1 group bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-12 h-12 {{ $lt->bg_50 }} rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium {{ $lt->text }} {{ $lt->bg_50 }} px-2 py-1 rounded-full">{{ __('landing.features_popular') }}</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('landing.feature_appointment_title') }}</h3>
                    <p class="text-gray-500 mb-6">
                        {{ __('landing.feature_appointment_desc') }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.feature_appointment_calendar') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.feature_appointment_prevent') }}
                        </span>
                    </div>
                </div>

                <!-- Feature 2 - Treatment Records (clinic only) or Customer Management (salon/barbershop) -->
                <div class="col-span-5 max-lg:col-span-1 group bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all duration-300">
                    @if(has_feature('treatment_records'))
                    <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ landing_text('feature_medical_title') }}</h3>
                    <p class="text-gray-500">
                        {{ landing_text('feature_medical_desc') }}
                    </p>
                    @else
                    <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ landing_text('feature_customer_title') }}</h3>
                    <p class="text-gray-500">
                        {{ landing_text('feature_customer_desc') }}
                    </p>
                    @endif
                </div>

                <!-- Feature 3 - Small -->
                <div class="col-span-4 max-lg:col-span-1 group bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('landing.feature_cashier_title') }}</h3>
                    <p class="text-gray-500">
                        {{ __('landing.feature_cashier_desc') }}
                    </p>
                </div>

                <!-- Feature 4 - Packages (clinic/salon) or Walk-in Queue (barbershop) -->
                <div class="col-span-4 max-lg:col-span-1 group bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all duration-300">
                    @if(has_feature('packages'))
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ landing_text('feature_package_title') }}</h3>
                    <p class="text-gray-500">
                        {{ landing_text('feature_package_desc') }}
                    </p>
                    @elseif(has_feature('walk_in_queue'))
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ landing_text('feature_walkin_title') }}</h3>
                    <p class="text-gray-500">
                        {{ landing_text('feature_walkin_desc') }}
                    </p>
                    @else
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ landing_text('feature_loyalty_title') }}</h3>
                    <p class="text-gray-500">
                        {{ landing_text('feature_loyalty_desc') }}
                    </p>
                    @endif
                </div>

                <!-- Feature 5 - Small -->
                <div class="col-span-4 max-lg:col-span-1 group bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('landing.feature_report_title') }}</h3>
                    <p class="text-gray-500">
                        {{ __('landing.feature_report_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile Apps Showcase Section -->
    <section id="mobile-apps" class="py-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative z-10 overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 {{ $lt->bg_500_10 }} rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-8 max-lg:px-6 max-sm:px-4 relative">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full {{ $lt->bg_500_20 }} {{ $lt->text_300 }} text-sm font-medium mb-6">
                    <span class="w-2 h-2 {{ $lt->bg_400 }} rounded-full mr-2 animate-pulse"></span>
                    {{ __('landing.mobile_badge') }}
                </span>
                <h2 class="text-4xl max-sm:text-3xl font-display font-bold text-white leading-tight mb-4">
                    {{ __('landing.mobile_title') }}
                </h2>
                <p class="text-gray-400">
                    {{ __('landing.mobile_subtitle') }}
                </p>
            </div>

            <!-- Phone & Tablet Showcase -->
            <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-16 max-lg:gap-12 items-center">
                <!-- Smartphone Version -->
                <div class="relative">
                    <div class="text-center mb-8">
                        <span class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full text-white text-sm font-medium mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Smartphone Version
                        </span>
                        <h3 class="text-2xl font-semibold text-white mb-2">Compact & Powerful</h3>
                        <p class="text-gray-400 text-sm">Semua fitur dalam genggaman Anda</p>
                    </div>

                    <!-- Phone Screenshots Grid -->
                    <div class="relative flex justify-center items-end gap-3 max-sm:gap-2">
                        <!-- Phone 1 -->
                        <div class="w-32 max-sm:w-24 transform -rotate-6 translate-y-4">
                            <div class="bg-gray-800 rounded-3xl max-sm:rounded-2xl p-1 shadow-2xl">
                                <div class="bg-black rounded-[1.3rem] max-sm:rounded-xl overflow-hidden">
                                    <img src="{{ asset('images/phone/1.png') }}" alt="Login Screen" class="w-full">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-2">Login</p>
                        </div>

                        <!-- Phone 2 (Main - Larger) -->
                        <div class="w-40 max-sm:w-32 z-10">
                            <div class="bg-gray-800 rounded-4xl max-sm:rounded-3xl p-1.5 shadow-2xl ring-2 ring-rose-500/30">
                                <div class="bg-black rounded-[1.7rem] max-sm:rounded-2xl overflow-hidden">
                                    <img src="{{ asset('images/phone/2.png') }}" alt="Dashboard" class="w-full">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-2">Dashboard</p>
                        </div>

                        <!-- Phone 3 -->
                        <div class="w-32 max-sm:w-24 transform rotate-6 translate-y-4">
                            <div class="bg-gray-800 rounded-3xl max-sm:rounded-2xl p-1 shadow-2xl">
                                <div class="bg-black rounded-[1.3rem] max-sm:rounded-xl overflow-hidden">
                                    <img src="{{ asset('images/phone/3.png') }}" alt="Calendar" class="w-full">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-2">Jadwal</p>
                        </div>
                    </div>

                    <!-- More Phone Screenshots -->
                    <div class="flex justify-center gap-3 max-sm:gap-2 mt-6">
                        <div class="w-24 max-sm:w-20">
                            <div class="bg-gray-800 rounded-2xl max-sm:rounded-xl p-1 shadow-lg">
                                <div class="bg-black rounded-2xl max-sm:rounded-lg overflow-hidden">
                                    <img src="{{ asset('images/phone/4.png') }}" alt="Menu" class="w-full">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-1">Menu</p>
                        </div>
                        <div class="w-24 max-sm:w-20">
                            <div class="bg-gray-800 rounded-2xl max-sm:rounded-xl p-1 shadow-lg">
                                <div class="bg-black rounded-2xl max-sm:rounded-lg overflow-hidden">
                                    <img src="{{ asset('images/phone/5.png') }}" alt="Features" class="w-full">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-1">Fitur</p>
                        </div>
                        <div class="w-24 max-sm:w-20">
                            <div class="bg-gray-800 rounded-2xl max-sm:rounded-xl p-1 shadow-lg">
                                <div class="bg-black rounded-2xl max-sm:rounded-lg overflow-hidden">
                                    <img src="{{ asset('images/phone/6.png') }}" alt="More" class="w-full">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-1">Lainnya</p>
                        </div>
                    </div>
                </div>

                <!-- Tablet Version -->
                <div class="relative">
                    <div class="text-center mb-8">
                        <span class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full text-white text-sm font-medium mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            {{ __('landing.mobile_tablet_version') }}
                        </span>
                        <h3 class="text-2xl font-semibold text-white mb-2">{{ __('landing.mobile_tablet_title') }}</h3>
                        <p class="text-gray-400 text-sm">{{ landing_text('mobile_tablet_desc') }}</p>
                    </div>

                    <!-- Tablet Screenshots -->
                    <div class="relative">
                        <!-- Main Tablet -->
                        <div class="relative mx-auto max-w-lg">
                            <div class="bg-gray-800 rounded-3xl max-sm:rounded-2xl p-2 max-sm:p-1.5 shadow-2xl ring-2 ring-rose-500/30">
                                <div class="bg-black rounded-2xl max-sm:rounded-xl overflow-hidden">
                                    <img src="{{ asset('images/tablet/2.png') }}" alt="Tablet Dashboard" class="w-full">
                                </div>
                            </div>
                            <p class="text-sm text-gray-400 text-center mt-3">Dashboard - Tampilan lebih lengkap</p>
                        </div>

                        <!-- Smaller Tablet Screenshots -->
                        <div class="flex justify-center gap-4 max-sm:gap-2 mt-6">
                            <div class="w-40 max-sm:w-28">
                                <div class="bg-gray-800 rounded-2xl max-sm:rounded-lg p-1 shadow-lg">
                                    <div class="bg-black rounded-xl max-sm:rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/tablet/3.png') }}" alt="Tablet Calendar" class="w-full">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-1">Jadwal</p>
                            </div>
                            <div class="w-40 max-sm:w-28">
                                <div class="bg-gray-800 rounded-2xl max-sm:rounded-lg p-1 shadow-lg">
                                    <div class="bg-black rounded-xl max-sm:rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/tablet/1.png') }}" alt="Tablet Login" class="w-full">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-1">Login</p>
                            </div>
                            <div class="w-40 max-sm:w-28 hidden lg:block">
                                <div class="bg-gray-800 rounded-2xl max-sm:rounded-lg p-1 shadow-lg">
                                    <div class="bg-black rounded-xl max-sm:rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/tablet/4.png') }}" alt="Tablet Features" class="w-full">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-1">Pelanggan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features List -->
            <div class="grid grid-cols-4 max-lg:grid-cols-2 max-sm:grid-cols-1 gap-6 mt-16">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="w-12 h-12 {{ $lt->bg_500_20 }} rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 {{ $lt->text_400 }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-2">Fast & Responsive</h4>
                    <p class="text-gray-400 text-sm">Performa optimal di semua perangkat</p>
                </div>

                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-2">Reporting</h4>
                    <p class="text-gray-400 text-sm">Laporan pendapatan & analitik bisnis</p>
                </div>

                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="w-12 h-12 bg-violet-500/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-2">Order Management</h4>
                    <p class="text-gray-400 text-sm">Kelola transaksi & pembayaran</p>
                </div>

                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-2">Real-time Sync</h4>
                    <p class="text-gray-400 text-sm">Data tersinkron di semua device</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-12">
                <a href="https://jagoflutter.com/glowupclinic" target="_blank" class="inline-flex items-center px-8 py-4 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} text-white font-semibold rounded-full hover:shadow-xl {{ $lt->hover_shadow_xl }} transition-all hover:-translate-y-1">
                    Dapatkan Full Sourcecode
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 bg-gray-50/50 relative z-10">
        <div class="max-w-6xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="{{ $lt->text }} font-medium mb-3 tracking-wide uppercase text-sm">Proses</p>
                <h2 class="text-4xl max-sm:text-3xl font-display font-bold text-gray-900 leading-tight">
                    Bagaimana cara mendapatkan sistem ini?
                </h2>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-3 max-md:grid-cols-1 gap-10 max-lg:gap-8">
                <!-- Step 1 -->
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 {{ $lt->border }} hover:shadow-lg transition-all">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl {{ $lt->bg_light }} {{ $lt->text }} font-bold text-2xl mb-6">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Konsultasi</h3>
                    <p class="text-gray-500 leading-relaxed">Hubungi kami dan ceritakan kebutuhan klinik Anda. Kami akan memberikan solusi yang tepat.</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 {{ $lt->border }} hover:shadow-lg transition-all">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl {{ $lt->bg_light }} {{ $lt->text }} font-bold text-2xl mb-6">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Setup & Kustomisasi</h3>
                    <p class="text-gray-500 leading-relaxed">Tim kami akan setup sistem sesuai kebutuhan, termasuk branding, fitur, dan migrasi data.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 {{ $lt->border }} hover:shadow-lg transition-all">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl {{ $lt->bg_light }} {{ $lt->text }} font-bold text-2xl mb-6">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Training & Go Live</h3>
                    <p class="text-gray-500 leading-relaxed">Kami training tim Anda sampai mahir. Setelah siap, sistem langsung bisa digunakan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section id="solutions" class="py-24 relative z-10">
        <div class="max-w-6xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="{{ $lt->text }} font-medium mb-3 tracking-wide uppercase text-sm">{{ __('landing.solutions_badge') }}</p>
                <h2 class="text-4xl max-sm:text-3xl font-display font-bold text-gray-900 leading-tight mb-4">
                    {{ __('landing.solutions_title') }}
                </h2>
                <p class="text-gray-500">
                    {{ landing_text('solutions_subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-2 max-md:grid-cols-1 gap-8 max-w-4xl mx-auto">
                <!-- Website Booking -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all">
                    <div class="w-14 h-14 {{ $lt->bg_light }} rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('landing.solution_website_title') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('landing.solution_website_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_website_feature1') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_website_feature2') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_website_feature3') }}
                        </li>
                    </ul>
                </div>

                <!-- Dashboard Admin -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all">
                    <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('landing.solution_dashboard_title') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('landing.solution_dashboard_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_dashboard_feature1') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ landing_text('solution_dashboard_feature2') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_dashboard_feature3') }}
                        </li>
                    </ul>
                </div>

                <!-- Mobile Apps -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('landing.solution_mobile_title') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('landing.solution_mobile_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_mobile_feature1') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_mobile_feature2') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_mobile_feature3') }}
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 {{ $lt->border_200 }} hover:shadow-lg transition-all">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('landing.solution_support_title') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('landing.solution_support_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_support_feature1') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_support_feature2') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $lt->text }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('landing.solution_support_feature3') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="https://jagoflutter.com/glowupclinic" target="_blank" class="inline-flex items-center px-8 py-4 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} text-white font-semibold rounded-full hover:shadow-xl {{ $lt->hover_shadow_xl }} transition-all hover:-translate-y-1">
                    {{ __('landing.solutions_cta') }}
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 relative z-10">
        <div class="max-w-6xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="{{ $lt->text }} font-medium mb-3 tracking-wide uppercase text-sm">{{ __('landing.testimonials_badge') }}</p>
                <h2 class="text-4xl max-sm:text-3xl font-display font-bold text-gray-900 leading-tight">
                    {{ landing_text('testimonials_title') }}
                </h2>
            </div>

            <div class="grid grid-cols-3 max-lg:grid-cols-2 max-md:grid-cols-1 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-all">
                    <div class="flex gap-1 mb-5">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ landing_text('testimonial1_text') }}</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full {{ $lt->bg_light }} flex items-center justify-center {{ $lt->text }} font-semibold">DA</div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('landing.testimonial1_name') }}</p>
                            <p class="text-sm text-gray-500">{{ landing_text('testimonial1_clinic') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-all">
                    <div class="flex gap-1 mb-5">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ landing_text('testimonial2_text') }}</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-semibold">S</div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('landing.testimonial2_name') }}</p>
                            <p class="text-sm text-gray-500">{{ landing_text('testimonial2_clinic') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-all">
                    <div class="flex gap-1 mb-5">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ landing_text('testimonial3_text') }}</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-semibold">R</div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('landing.testimonial3_name') }}</p>
                            <p class="text-sm text-gray-500">{{ landing_text('testimonial3_clinic') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-gray-50/50 relative z-10" x-data="{ openFaq: 1 }">
        <div class="max-w-3xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="text-center mb-12">
                <p class="{{ $lt->text }} font-medium mb-3 tracking-wide uppercase text-sm">{{ __('landing.faq_badge') }}</p>
                <h2 class="text-4xl max-sm:text-3xl font-display font-bold text-gray-900 leading-tight">
                    {{ __('landing.faq_title') }}
                </h2>
            </div>

            <div class="space-y-3">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium text-gray-900">Bagaimana proses implementasinya?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-5 pb-5">
                        <p class="text-gray-500 leading-relaxed">Setelah konsultasi, tim kami akan melakukan setup sistem sesuai kebutuhan klinik Anda. Proses biasanya memakan waktu 1-2 minggu tergantung kompleksitas. Kami juga menyediakan training untuk tim Anda.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium text-gray-900">Berapa biaya untuk mendapatkan sistem ini?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-5 pb-5">
                        <p class="text-gray-500 leading-relaxed">Biaya tergantung pada fitur dan kustomisasi yang Anda butuhkan. Hubungi kami untuk konsultasi gratis dan mendapatkan penawaran yang sesuai dengan kebutuhan klinik Anda.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium text-gray-900">Apakah data customer saya aman?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-5 pb-5">
                        <p class="text-gray-500 leading-relaxed">Aman. Data dienkripsi dan disimpan di server yang aman. Kami melakukan backup otomatis setiap hari. Data sepenuhnya milik Anda dan bisa di-export kapan saja.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium text-gray-900">Saya sudah punya data customer di Excel, bisa dipindahkan?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-5 pb-5">
                        <p class="text-gray-500 leading-relaxed">Bisa. Tim kami akan membantu migrasi data lama Anda ke sistem baru. Proses ini termasuk dalam paket implementasi.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium text-gray-900">Bagaimana dengan support setelah implementasi?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-5 pb-5">
                        <p class="text-gray-500 leading-relaxed">Kami menyediakan support via WhatsApp dan telepon. Tim kami siap membantu jika ada kendala atau pertanyaan. Kami juga melakukan maintenance dan update rutin untuk memastikan sistem selalu optimal.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section id="contact" class="py-24 relative z-10">
        <div class="max-w-4xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="bg-gray-900 rounded-3xl p-16 max-md:p-10 text-center">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full {{ $lt->bg_500_20 }} {{ $lt->text_300 }} text-sm font-medium mb-6">
                    <span class="w-2 h-2 {{ $lt->bg_400 }} rounded-full mr-2 animate-pulse"></span>
                    AFC Event - JagoFlutter
                </span>
                <h2 class="text-3xl max-sm:text-2xl font-display font-bold text-white mb-4">
                    Dapatkan Full Sourcecode Aplikasi Ini
                </h2>
                <p class="text-gray-400 mb-8 max-w-xl mx-auto">
                    Join event AFC untuk mendapatkan full sourcecode website + mobile apps ini. Lengkap dengan dokumentasi dan support dari tim JagoFlutter.
                </p>
                <div class="flex flex-row max-sm:flex-col gap-4 justify-center">
                    <a href="https://jagoflutter.com/glowupclinic" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r {{ $lt->gradient_from }} {{ $lt->gradient_to }} text-white font-semibold rounded-full hover:shadow-xl {{ $lt->hover_shadow_xl }} transition-all hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        Join Event & Dapatkan Sourcecode
                    </a>
                    <a href="https://wa.me/6285640899224?text=Halo,%20saya%20tertarik%20dengan%20event%20AFC%20GlowUp%20Clinic" target="_blank" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-700 text-white font-semibold rounded-full hover:bg-gray-800 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-16 max-sm:py-10 relative z-10">
        <div class="max-w-6xl mx-auto px-8 max-lg:px-6 max-sm:px-4">
            <div class="grid grid-cols-4 max-lg:grid-cols-2 gap-10 max-sm:gap-8 mb-12 max-sm:mb-8">
                <!-- Brand -->
                <div class="col-span-2 max-lg:col-span-2 max-sm:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 max-sm:w-9 max-sm:h-9 bg-gradient-to-br {{ $lt->gradient_from }} {{ $lt->gradient_to }} rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <span class="text-xl max-sm:text-lg font-display font-bold text-white">{{ brand_name() }}</span>
                    </a>
                    <p class="text-gray-400 text-sm max-sm:text-xs mb-5 max-w-sm leading-relaxed">{{ landing_text('footer_desc') }}</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div class="max-sm:col-span-1">
                    <h4 class="text-white font-semibold text-sm mb-3">{{ __('landing.footer_navigation') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors text-sm">{{ __('landing.nav_features') }}</a></li>
                        <li><a href="#solutions" class="text-gray-400 hover:text-white transition-colors text-sm">{{ __('landing.nav_solutions') }}</a></li>
                        <li><a href="#mobile-apps" class="text-gray-400 hover:text-white transition-colors text-sm">{{ __('landing.nav_mobile_apps') }}</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-white transition-colors text-sm">{{ __('landing.nav_testimonials') }}</a></li>
                        <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors text-sm">{{ __('landing.nav_faq') }}</a></li>
                    </ul>
                </div>

                <div class="max-sm:col-span-1">
                    <h4 class="text-white font-semibold text-sm mb-3">{{ __('landing.footer_contact') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="mailto:hello@jagoflutter.com" class="text-gray-400 hover:text-white transition-colors text-sm break-all">hello@jagoflutter.com</a></li>
                        <li><a href="https://wa.me/6285640899224" class="text-gray-400 hover:text-white transition-colors text-sm">+62 856-4089-9224</a></li>
                        <li><span class="text-gray-400 text-sm">Sleman, Yogyakarta</span></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-6 flex flex-row max-sm:flex-col justify-between items-center gap-4 max-sm:gap-3">
                <p class="text-xs text-gray-500 max-sm:text-center">{{ brand_copyright() }} @if(brand('footer.show_powered_by', true)) Powered by <a href="{{ brand('footer.powered_by_url', 'https://glowup.app') }}" target="_blank" class="text-primary-400 hover:text-primary-300 transition-colors">{{ brand('footer.powered_by_text', 'GlowUp') }}</a>@endif</p>
                <div class="flex gap-4 text-xs text-gray-500">
                    <a href="#" class="hover:text-gray-300 transition-colors">{{ __('landing.footer_privacy') }}</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">{{ __('landing.footer_terms') }}</a>
                </div>
            </div>
        </div>
    </footer>
@endsection

@push('scripts')
<script>
    // Collapse plugin untuk Alpine.js FAQ
    document.addEventListener('alpine:init', () => {
        Alpine.directive('collapse', (el, { expression }, { effect, cleanup }) => {
            let height = el.scrollHeight;

            el.style.overflow = 'hidden';
            el.style.height = '0px';
            el.style.transition = 'height 0.2s ease-out';

            const show = () => {
                el.style.height = el.scrollHeight + 'px';
            };

            const hide = () => {
                el.style.height = '0px';
            };

            effect(() => {
                if (el.style.display !== 'none') {
                    show();
                } else {
                    hide();
                }
            });
        });
    });
</script>
@endpush
