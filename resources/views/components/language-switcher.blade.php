@props(['class' => ''])

@php
    $currentLocale = app()->getLocale();
    $locales = [
        'id' => ['name' => 'Indonesia', 'flag' => '🇮🇩'],
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
    ];
@endphp

<div x-data="{ open: false }" class="relative {{ $class }}">
    <button
        @click="open = !open"
        @click.away="open = false"
        type="button"
        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 transition"
    >
        <span class="text-base">{{ $locales[$currentLocale]['flag'] }}</span>
        <span class="max-sm:hidden">{{ $locales[$currentLocale]['name'] }}</span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 py-1"
        style="display: none;"
    >
        @foreach($locales as $code => $locale)
            <a
                href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                class="flex items-center gap-3 px-4 py-2 text-sm {{ $currentLocale === $code ? 'bg-rose-50 text-rose-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span class="text-base">{{ $locale['flag'] }}</span>
                <span>{{ $locale['name'] }}</span>
                @if($currentLocale === $code)
                    <svg class="w-4 h-4 ml-auto text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
