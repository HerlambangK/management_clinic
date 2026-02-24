@extends('layouts.dashboard')

@section('title', __('product.edit_product'))
@section('page-title', __('product.edit_product'))

@include('components.theme-classes')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-sm:space-y-4">
            @csrf
            @method('PUT')

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.category') }}</label>
                <div class="relative">
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none @error('category_id') border-red-400 @enderror"
                    >
                        <option value="">{{ __('product.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
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

            <!-- Name & SKU -->
            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                <div>
                    <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.name') }} <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('name') border-red-400 @enderror"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="sku" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.sku') }}</label>
                    <input
                        type="text"
                        id="sku"
                        name="sku"
                        value="{{ old('sku', $product->sku) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('sku') border-red-400 @enderror"
                    >
                    @error('sku')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.description') }}</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('description') border-red-400 @enderror"
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price & Cost Price -->
            <div class="grid grid-cols-2 gap-4 max-sm:gap-3">
                <div>
                    <x-currency-input
                        name="price"
                        :label="__('product.price')"
                        :value="old('price', $product->price)"
                        :required="true"
                        placeholder="150.000"
                    />
                </div>
                <div>
                    <x-currency-input
                        name="cost_price"
                        :label="__('product.cost_price')"
                        :value="old('cost_price', $product->cost_price)"
                        placeholder="100.000"
                    />
                </div>
            </div>

            <!-- Stock & Min Stock & Unit -->
            <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                <div>
                    <label for="stock" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.stock') }} <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', $product->stock) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('stock') border-red-400 @enderror"
                        min="0"
                        required
                    >
                    @error('stock')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="min_stock" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.min_stock') }} <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="min_stock"
                        name="min_stock"
                        value="{{ old('min_stock', $product->min_stock) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('min_stock') border-red-400 @enderror"
                        min="0"
                        required
                    >
                    @error('min_stock')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="unit" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.unit') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select
                            id="unit"
                            name="unit"
                            class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none @error('unit') border-red-400 @enderror"
                            required
                        >
                            <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>{{ __('product.unit_pcs') }}</option>
                            <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>{{ __('product.unit_box') }}</option>
                            <option value="bottle" {{ old('unit', $product->unit) == 'bottle' ? 'selected' : '' }}>{{ __('product.unit_bottle') }}</option>
                            <option value="tube" {{ old('unit', $product->unit) == 'tube' ? 'selected' : '' }}>{{ __('product.unit_tube') }}</option>
                            <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>{{ __('product.unit_pack') }}</option>
                            <option value="set" {{ old('unit', $product->unit) == 'set' ? 'selected' : '' }}>{{ __('product.unit_set') }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('unit')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Image & Image Upload -->
            <div>
                <label for="image" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('product.image') }}</label>
                @if($product->image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 rounded-lg object-cover">
                    </div>
                @endif
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('image') border-red-400 @enderror"
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, atau WEBP. Maks 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                @error('image')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkboxes -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        id="track_stock"
                        name="track_stock"
                        value="1"
                        class="w-4 h-4 {{ $themeCheckbox }} border-gray-300 rounded focus:ring-2 {{ $themeRing }}"
                        {{ old('track_stock', $product->track_stock) ? 'checked' : '' }}
                    >
                    <label for="track_stock" class="text-sm text-gray-700 dark:text-gray-300">{{ __('product.track_stock') }}</label>
                </div>
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        class="w-4 h-4 {{ $themeCheckbox }} border-gray-300 rounded focus:ring-2 {{ $themeRing }}"
                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                    >
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">{{ __('common.active') }}</label>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex flex-row max-sm:flex-col items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $themeButton }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('products.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
