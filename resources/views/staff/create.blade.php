@extends('layouts.dashboard')

@section('title', __('staff.add'))
@section('page-title', __('staff.add'))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('staff.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <form action="{{ route('staff.store') }}" method="POST" class="space-y-6 max-sm:space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('staff.name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('name') border-red-400 @enderror"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('staff.email') }} <span class="text-red-500">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('email') border-red-400 @enderror"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('staff.phone') }}</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('phone') border-red-400 @enderror"
                    placeholder="08xxxxxxxxxx"
                >
                @error('phone')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('staff.role') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="role"
                        name="role"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('role') border-red-400 @enderror"
                        required
                    >
                        <option value="">{{ __('staff.select_role') }}</option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>{{ __('staff.role_owner') }}</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('staff.role_admin') }}</option>
                        <option value="beautician" {{ old('role') === 'beautician' ? 'selected' : '' }}>{{ __('staff.role_beautician') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('role')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('staff.password') }} <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('password') border-red-400 @enderror"
                    required
                >
                @error('password')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('staff.password_confirmation') }} <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition"
                    required
                >
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
                <label for="is_active" class="text-sm max-sm:text-xs text-gray-700 dark:text-gray-300">{{ __('common.active') }}</label>
            </div>

            <!-- Submit -->
            <div class="flex flex-row max-sm:flex-col items-center gap-3 max-sm:gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('staff.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
