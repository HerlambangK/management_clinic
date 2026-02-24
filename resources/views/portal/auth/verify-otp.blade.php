@extends('layouts.portal')

@section('title', __('portal.verify_otp'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-50 to-primary-50 dark:from-gray-900 dark:to-gray-800 px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                @if(brand_logo('logo'))
                    <img src="{{ brand_logo('logo') }}" alt="{{ brand_name() }}" class="h-12 mx-auto">
                @else
                    <span class="text-2xl font-bold text-primary-600">{{ brand_name() }}</span>
                @endif
            </a>
            <h1 class="mt-6 text-2xl font-bold text-gray-900 dark:text-white">{{ __('portal.verify_otp') }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('portal.otp_sent_to', ['email' => $email]) }}</p>
        </div>

        <!-- OTP Verification Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-xl">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('portal.verify-otp.submit') }}" method="POST" x-data="otpForm()">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 text-center">
                            {{ __('portal.enter_otp_code') }}
                        </label>

                        <!-- OTP Input Fields -->
                        <div class="flex justify-center gap-2">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1" x-ref="otp{{ $i }}" @input="handleInput($event, {{ $i }})" @keydown="handleKeydown($event, {{ $i }})" @paste="handlePaste($event)" class="w-12 h-14 text-center text-xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors" required>
                            @endfor
                        </div>

                        <input type="hidden" name="otp" x-model="otpValue">

                        @error('otp')
                            <p class="mt-4 text-sm text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        {{ __('portal.verify') }}
                    </button>
                </div>
            </form>

            <!-- Resend OTP -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ __('portal.didnt_receive_otp') }}</p>
                <form action="{{ route('portal.resend-otp') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                        {{ __('portal.resend_otp') }}
                    </button>
                </form>
            </div>

            <!-- Change Email -->
            <div class="mt-4 text-center">
                <a href="{{ route('portal.login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">
                    {{ __('portal.use_different_email') }}
                </a>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                &larr; {{ __('portal.back_to_home') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function otpForm() {
    return {
        otpValue: '',
        handleInput(event, index) {
            const value = event.target.value;
            if (value.length === 1 && index < 5) {
                this.$refs['otp' + (index + 1)].focus();
            }
            this.updateOtpValue();
        },
        handleKeydown(event, index) {
            if (event.key === 'Backspace' && !event.target.value && index > 0) {
                this.$refs['otp' + (index - 1)].focus();
            }
        },
        handlePaste(event) {
            event.preventDefault();
            const paste = (event.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/\D/g, '').slice(0, 6);

            for (let i = 0; i < digits.length; i++) {
                if (this.$refs['otp' + i]) {
                    this.$refs['otp' + i].value = digits[i];
                }
            }

            if (digits.length > 0 && this.$refs['otp' + Math.min(digits.length, 5)]) {
                this.$refs['otp' + Math.min(digits.length - 1, 5)].focus();
            }

            this.updateOtpValue();
        },
        updateOtpValue() {
            let otp = '';
            for (let i = 0; i < 6; i++) {
                otp += this.$refs['otp' + i]?.value || '';
            }
            this.otpValue = otp;
        }
    }
}
</script>
@endpush
@endsection
