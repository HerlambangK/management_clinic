@extends('layouts.dashboard')

@section('title', __('service.add'))
@section('page-title', __('service.add'))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('services.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-sm:space-y-4">
            @csrf

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('service.category') }}</label>
                <div class="relative">
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('category_id') border-red-400 @enderror"
                    >
                        <option value="">{{ __('service.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('category_id')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('service.name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('name') border-red-400 @enderror"
                    placeholder="Contoh: Facial Brightening"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('service.description') }}</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('description') border-red-400 @enderror"
                    placeholder="{{ __('service.description_placeholder') }}"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duration & Price -->
            <div class="grid grid-cols-2 gap-4 max-sm:gap-3">
                <div>
                    <label for="duration_minutes" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('service.duration_minutes') }} <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="duration_minutes"
                        name="duration_minutes"
                        value="{{ old('duration_minutes', 60) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('duration_minutes') border-red-400 @enderror"
                        min="5"
                        max="480"
                        required
                    >
                    @error('duration_minutes')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-currency-input
                        name="price"
                        :label="__('service.price')"
                        :value="old('price')"
                        :required="true"
                        placeholder="250.000"
                    />
                </div>
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('common.image') }}</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('image') border-red-400 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('service.image_format') }}</p>
                @error('image')
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
                    class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-500/20"
                    {{ old('is_active', true) ? 'checked' : '' }}
                >
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">{{ __('common.active') }}</label>
            </div>

            <!-- Submit -->
            <div class="flex flex-row max-sm:flex-col items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('services.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
