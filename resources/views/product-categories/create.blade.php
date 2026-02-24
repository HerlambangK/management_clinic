@extends('layouts.dashboard')

@section('title', __('product.add_category'))
@section('page-title', __('product.add_category'))

@include('components.theme-classes')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('product-categories.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <form action="{{ route('product-categories.store') }}" method="POST" class="space-y-6 max-sm:space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('name') border-red-400 @enderror"
                    placeholder="Contoh: Skincare"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('common.description') }}</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('description') border-red-400 @enderror"
                    placeholder="Deskripsi kategori..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    class="w-4 h-4 {{ $themeCheckbox }} border-gray-300 rounded focus:ring-2 {{ $themeRing }}"
                    {{ old('is_active', true) ? 'checked' : '' }}
                >
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">{{ __('common.active') }}</label>
            </div>

            <!-- Submit -->
            <div class="flex flex-row max-sm:flex-col items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $themeButton }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('product-categories.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
