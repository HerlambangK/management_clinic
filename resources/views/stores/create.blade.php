@extends('layouts.dashboard')

@section('title', __('store.add'))
@section('page-title', __('store.add'))

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('stores.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <form action="{{ route('stores.store') }}" method="POST" class="space-y-6 max-sm:space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('store.name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition @error('name') border-red-400 @enderror"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="business_type" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('store.business_type') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="business_type"
                        name="business_type"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('business_type') border-red-400 @enderror"
                        required
                    >
                        <option value="">{{ __('store.select_business_type') }}</option>
                        @foreach($businessTypes as $key => $config)
                            <option value="{{ $key }}" {{ old('business_type') === $key ? 'selected' : '' }}>
                                {{ $config['name'] ?? $key }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('business_type')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            @if(auth()->user()->isAdmin())
                <div>
                    <label for="owner_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('store.owner') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select
                            id="owner_id"
                            name="owner_id"
                            class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} focus:border-{{ $tc->primary ?? 'rose' }}-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('owner_id') border-red-400 @enderror"
                            required
                        >
                            <option value="">{{ __('store.select_owner') }}</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ (string) old('owner_id') === (string) $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('owner_id')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 {{ $tc->checkbox ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 rounded">
                        {{ __('store.active') }}
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="hidden" name="is_approved" value="0">
                        <input type="checkbox" name="is_approved" value="1" {{ old('is_approved') ? 'checked' : '' }} class="w-4 h-4 {{ $tc->checkbox ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 rounded">
                        {{ __('store.approve_now') }}
                    </label>
                </div>
            @else
                <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 p-4 text-sm text-yellow-700 dark:text-yellow-300">
                    {{ __('store.owner_needs_approval') }}
                </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('stores.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
