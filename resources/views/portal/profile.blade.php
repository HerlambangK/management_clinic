@extends('layouts.portal')

@section('title', __('portal.my_profile'))
@section('page-title', __('portal.my_profile'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 max-sm:p-4">
        <div class="flex items-center gap-4 max-sm:gap-3">
            <div class="w-16 h-16 max-sm:w-12 max-sm:h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-2xl max-sm:text-xl font-bold text-primary-700 dark:text-primary-300">{{ substr($customer->name, 0, 1) }}</span>
            </div>
            <div class="min-w-0">
                <h2 class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white truncate">{{ $customer->name }}</h2>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $customer->email }}</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $customer->loyalty_tier_color }}">
                        {{ $customer->loyalty_tier_label }} - {{ format_number($customer->loyalty_points) }} {{ __('portal.points') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Profile Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.personal_information') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('portal.update_your_info') }}</p>
        </div>
        <form action="{{ route('portal.profile.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('portal.full_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('portal.email') }}
                    </label>
                    <input type="email" id="email" value="{{ $customer->email }}" disabled class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('portal.email_cannot_change') }}</p>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('portal.phone') }}
                    </label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors" placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('portal.birthdate') }}
                    </label>
                    <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $customer->birthdate?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('birthdate')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('portal.gender') }}
                    </label>
                    <select name="gender" id="gender" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors">
                        <option value="">{{ __('portal.select_gender') }}</option>
                        <option value="female" {{ old('gender', $customer->gender) === 'female' ? 'selected' : '' }}>{{ __('portal.female') }}</option>
                        <option value="male" {{ old('gender', $customer->gender) === 'male' ? 'selected' : '' }}>{{ __('portal.male') }}</option>
                    </select>
                    @error('gender')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('portal.address') }}
                    </label>
                    <textarea name="address" id="address" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors resize-none">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    {{ __('portal.save_changes') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Account Stats -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 max-sm:p-4">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.account_stats') }}</h3>
        <div class="grid grid-cols-2 gap-3 max-sm:gap-2">
            <div class="text-center p-3 max-sm:p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <p class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $customer->total_visits ?? 0 }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.total_visits') }}</p>
            </div>
            <div class="text-center p-3 max-sm:p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <p class="text-lg max-sm:text-base font-bold text-gray-900 dark:text-white truncate">{{ $customer->formatted_total_spent }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.total_spent') }}</p>
            </div>
            <div class="text-center p-3 max-sm:p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <p class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ format_number($customer->loyalty_points) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.loyalty_points') }}</p>
            </div>
            <div class="text-center p-3 max-sm:p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <p class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ format_number($customer->lifetime_points ?? 0) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('portal.lifetime_points') }}</p>
            </div>
        </div>
        @if($customer->last_visit)
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                {{ __('portal.last_visit') }}: {{ format_date($customer->last_visit) }}
            </p>
        @endif
    </div>
</div>
@endsection
