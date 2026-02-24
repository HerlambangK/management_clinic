@extends('layouts.landing')

@section('title', __('setup.business_details') . ' - ' . brand_name())

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full space-y-8 relative z-10">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg">
                    @if($businessConfig['icon'] === 'sparkles')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    @elseif($businessConfig['icon'] === 'scissors')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                        </svg>
                    @elseif($businessConfig['icon'] === 'dumbbell')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6v12m10-12v12M4 9h16M4 15h16M2 10v4m20-4v4" />
                        </svg>
                    @endif
                </div>
            </div>
            <h1 class="text-3xl font-serif font-bold text-gray-900">
                {{ app()->getLocale() === 'en' ? $businessConfig['name_en'] : $businessConfig['name'] }}
            </h1>
            <p class="mt-2 text-gray-600">
                {{ __('setup.enter_business_details') }}
            </p>

            <!-- Step indicator -->
            <div class="flex justify-center items-center mt-6 space-x-4 max-sm:space-x-2">
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span class="ml-2 text-sm text-gray-500 max-sm:hidden">{{ __('setup.business_type') }}</span>
                </div>
                <div class="w-8 h-px bg-gray-300 max-sm:w-4"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-medium">2</span>
                    <span class="ml-2 text-sm font-medium text-primary-600 max-sm:hidden">{{ __('setup.details') }}</span>
                </div>
                <div class="w-8 h-px bg-gray-300 max-sm:w-4"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">3</span>
                    <span class="ml-2 text-sm text-gray-500 max-sm:hidden">{{ __('setup.account') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 max-sm:p-6">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="setupDetailsForm" action="{{ route('setup.storeDetails') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="business_type" value="{{ old('business_type') ?: request()->query('type') }}">

                <!-- Business Name -->
                <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.business_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="business_name" id="business_name"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                           placeholder="{{ __('setup.enter_business_name') }}"
                           value="{{ old('business_name') }}"
                           required>
                    @error('business_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Phone -->
                <div>
                    <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.phone_number') }}
                    </label>
                    <input type="tel" name="business_phone" id="business_phone"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                           placeholder="{{ __('common.example') }}: 0812-3456-7890"
                           value="{{ old('business_phone') }}">
                    @error('business_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Address -->
                <div>
                    <label for="business_address" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('setup.address') }}
                    </label>
                    <textarea name="business_address" id="business_address" rows="3"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors resize-none"
                              placeholder="{{ __('setup.enter_address') }}">{{ old('business_address') }}</textarea>
                    @error('business_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('setup.index') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('setup.back') }}
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ __('setup.continue') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('setupDetailsForm');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        console.log('Form is being submitted');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
    });

    submitBtn.addEventListener('click', function(e) {
        console.log('Submit button clicked');
    });
});
</script>
@endpush
