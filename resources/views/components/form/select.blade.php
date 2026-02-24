@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $themeRing = $tc->ring ?? 'focus:ring-primary-500/20 focus:border-primary-400';
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm max-sm:text-xs font-medium text-gray-700 mb-2 max-sm:mb-1.5">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => "w-full pl-4 pr-10 py-2.5 max-sm:py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 {$themeRing} transition appearance-none bg-white"]) }}
            @error($name) aria-invalid="true" @enderror
        >
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>
    @error($name)
        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
