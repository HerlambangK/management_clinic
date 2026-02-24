@extends('layouts.dashboard')

@section('title', __('loyalty.edit_reward'))
@section('page-title', __('loyalty.edit_reward'))

@include('components.theme-classes')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('loyalty.rewards.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <form action="{{ route('loyalty.rewards.update', $reward) }}" method="POST" class="space-y-6 max-sm:space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.reward_name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $reward->name) }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('name') border-red-400 @enderror"
                    placeholder="{{ __('loyalty.reward_name_placeholder') }}"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.description') }}</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('description') border-red-400 @enderror"
                    placeholder="{{ __('loyalty.description_placeholder') }}"
                >{{ old('description', $reward->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Points Required & Reward Type -->
            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                <div>
                    <label for="points_required" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.points_required') }} <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="points_required"
                        name="points_required"
                        value="{{ old('points_required', $reward->points_required) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition @error('points_required') border-red-400 @enderror"
                        min="1"
                        placeholder="100"
                        required
                    >
                    @error('points_required')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="reward_type" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.reward_type') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select
                            id="reward_type"
                            name="reward_type"
                            class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none @error('reward_type') border-red-400 @enderror"
                            required
                            onchange="toggleRewardFields()"
                        >
                            <option value="">{{ __('loyalty.select_type') }}</option>
                            @foreach($rewardTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('reward_type', $reward->reward_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('reward_type')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reward Value (for discount types) -->
            <div id="reward_value_field" class="hidden">
                <label for="reward_value" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.reward_value') }}</label>
                <input
                    type="number"
                    id="reward_value"
                    name="reward_value"
                    value="{{ old('reward_value', $reward->reward_value) }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    min="0"
                    step="0.01"
                    placeholder="10"
                >
                <p id="reward_value_help" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                @error('reward_value')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Service (for free_service type) -->
            <div id="service_field" class="hidden">
                <label for="service_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.select_service') }}</label>
                <div class="relative">
                    <select
                        id="service_id"
                        name="service_id"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('loyalty.select_service') }}</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $reward->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('service_id')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product (for free_product type) -->
            <div id="product_field" class="hidden">
                <label for="product_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.select_product') }}</label>
                <div class="relative">
                    <select
                        id="product_id"
                        name="product_id"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('loyalty.select_product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $reward->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('product_id')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock & Max Per Customer -->
            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                <div>
                    <label for="stock" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.stock') }}</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', $reward->stock) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                        min="0"
                        placeholder="{{ __('loyalty.unlimited') }}"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.stock_help') }}</p>
                    @error('stock')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="max_per_customer" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.max_per_customer') }}</label>
                    <input
                        type="number"
                        id="max_per_customer"
                        name="max_per_customer"
                        value="{{ old('max_per_customer', $reward->max_per_customer) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                        min="1"
                        placeholder="{{ __('loyalty.unlimited') }}"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.max_per_customer_help') }}</p>
                    @error('max_per_customer')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Valid Period -->
            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                <div>
                    <label for="valid_from" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.valid_from') }}</label>
                    <input
                        type="date"
                        id="valid_from"
                        name="valid_from"
                        value="{{ old('valid_from', $reward->valid_from?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    >
                    @error('valid_from')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="valid_until" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('loyalty.valid_until') }}</label>
                    <input
                        type="date"
                        id="valid_until"
                        name="valid_until"
                        value="{{ old('valid_until', $reward->valid_until?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    >
                    @error('valid_until')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    class="w-4 h-4 {{ $themeCheckbox }} border-gray-300 rounded focus:ring-2 {{ $themeRing }}"
                    {{ old('is_active', $reward->is_active) ? 'checked' : '' }}
                >
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">{{ __('common.active') }}</label>
            </div>

            <!-- Submit -->
            <div class="flex flex-row max-sm:flex-col items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $themeButton }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('loyalty.rewards.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRewardFields() {
    const rewardType = document.getElementById('reward_type').value;
    const rewardValueField = document.getElementById('reward_value_field');
    const serviceField = document.getElementById('service_field');
    const productField = document.getElementById('product_field');
    const rewardValueHelp = document.getElementById('reward_value_help');

    // Hide all first
    rewardValueField.classList.add('hidden');
    serviceField.classList.add('hidden');
    productField.classList.add('hidden');

    if (rewardType === 'discount_percent') {
        rewardValueField.classList.remove('hidden');
        rewardValueHelp.textContent = '{{ __("loyalty.discount_percent_help") }}';
    } else if (rewardType === 'discount_amount') {
        rewardValueField.classList.remove('hidden');
        rewardValueHelp.textContent = '{{ __("loyalty.discount_amount_help") }}';
    } else if (rewardType === 'free_service') {
        serviceField.classList.remove('hidden');
    } else if (rewardType === 'free_product') {
        productField.classList.remove('hidden');
    }
}

// Run on page load
document.addEventListener('DOMContentLoaded', toggleRewardFields);
</script>
@endsection
