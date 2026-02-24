@php
    // Use $tc from BusinessServiceProvider for theme consistency
    $buttonClass = $tc->button ?? 'bg-rose-500 hover:bg-rose-600';
    $accentClass = $tc->accentBg ?? 'bg-rose-500';
    $avatarGradient = $tc->gradient ?? 'from-rose-400 to-rose-500';

    // Language data for mobile dropdown
    $currentLocale = app()->getLocale();
    $locales = [
        'id' => ['name' => 'Indonesia', 'flag' => '🇮🇩'],
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
    ];
@endphp
<!-- Header -->
<header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors">
    <div class="flex items-center justify-between h-16 px-6 max-md:px-4">
        <!-- Left: Menu Button (Mobile) + Page Title -->
        <div class="flex items-center gap-4">
            <button
                @click="sidebarOpen = true"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 max-lg:block hidden"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">@yield('page-title', 'Dashboard')</h1>

            @if(auth()->user()->hasRole(['owner', 'admin']) && $availableStores->isNotEmpty())
                @php
                    $storeQuery = request()->except(['store', 'page']);
                    $currentStoreId = $currentStore?->id;
                @endphp
                <form method="GET" action="{{ url()->current() }}" class="hidden md:block">
                    @foreach($storeQuery as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select
                        name="store"
                        onchange="this.form.submit()"
                        class="select-compact rounded-lg border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-200 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400"
                    >
                        @if(auth()->user()->isAdmin() && ! $businessTypeFilter)
                            <option value="all" {{ $currentStoreId ? '' : 'selected' }}>{{ __('store.all_stores') }}</option>
                        @endif
                        @foreach($availableStores as $store)
                            <option value="{{ $store->id }}" {{ $currentStoreId === $store->id ? 'selected' : '' }}>
                                {{ $store->name }} ({{ $store->business_type }})
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
            <!-- Language Switcher (Desktop Only) -->
            <div class="max-sm:hidden">
                <x-language-switcher />
            </div>

            <!-- Quick Actions (Desktop Only) -->
            <a
                href="{{ route('appointments.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 {{ $buttonClass }} text-white text-sm font-medium rounded-lg transition max-md:hidden"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('menu.new_booking') }}
            </a>

            <!-- Dark Mode Toggle (Desktop Only) -->
            <button
                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="max-sm:hidden p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"
                :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
            >
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            <!-- Notifications (Desktop Only) -->
            <button class="max-sm:hidden relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 {{ $accentClass }} rounded-full"></span>
            </button>

            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                >
                    <div class="w-8 h-8 bg-gradient-to-br {{ $avatarGradient }} rounded-full flex items-center justify-center text-white font-semibold text-xs">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="block text-left max-md:hidden">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <svg class="block w-4 h-4 text-gray-400 max-md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 max-sm:w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                    style="display: none;"
                >
                    <!-- User Info (Mobile) -->
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 hidden max-md:block">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>

                    <!-- Mobile Only: Language, Dark Mode, Notifications -->
                    <div class="sm:hidden border-b border-gray-100 dark:border-gray-700 py-1">
                        <!-- Language Switcher -->
                        <div class="px-4 py-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('menu.language') }}</p>
                            <div class="flex gap-2">
                                @foreach($locales as $code => $locale)
                                    <a
                                        href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs rounded-lg {{ $currentLocale === $code ? ($tc->buttonLight ?? 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400') : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition"
                                    >
                                        <span>{{ $locale['flag'] }}</span>
                                        <span>{{ $locale['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button
                            @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                        >
                            <span class="flex items-center gap-2">
                                <svg x-show="!darkMode" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="darkMode" x-cloak class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span x-text="darkMode ? '{{ __('menu.light_mode') }}' : '{{ __('menu.dark_mode') }}'"></span>
                            </span>
                            <div class="relative w-10 h-5 rounded-full transition" :class="darkMode ? '{{ $accentClass }}' : 'bg-gray-300 dark:bg-gray-600'">
                                <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform" :class="darkMode ? 'translate-x-5' : 'translate-x-0.5'"></div>
                            </div>
                        </button>

                        <!-- Notifications -->
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {{ __('menu.notifications') }}
                            <span class="ml-auto w-2 h-2 {{ $accentClass }} rounded-full"></span>
                        </a>
                    </div>

                    <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('menu.profile') }}
                    </a>
                    @if(auth()->user()->hasRole(['owner', 'admin']))
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('menu.settings') }}
                    </a>
                    @endif
                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('menu.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
