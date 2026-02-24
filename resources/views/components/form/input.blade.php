@props([
    'type' => 'text',
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autocomplete' => null,
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
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        {{ $attributes->merge(['class' => "w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 {$themeRing} transition"]) }}
        @error($name) aria-invalid="true" @enderror
    >
    @error($name)
        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
