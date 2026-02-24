@extends('layouts.dashboard')

@section('title', __('attendance.qr_title'))
@section('page-title', __('attendance.qr_title'))

@section('content')
@php
    $qrImageUrl = $qrPayload ? 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($qrPayload) : null;
@endphp
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('member-attendance.index') }}" class="inline-flex items-center gap-2 hover:text-gray-700 dark:hover:text-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.qr_title') }}</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.qr_desc') }}</p>

        @if(! $isOpen)
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 text-sm p-4">
                {{ __('attendance.qr_closed') }}
            </div>
        @endif

        <div class="mt-6 flex flex-col items-center gap-6">
            <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-200 dark:border-gray-700">
                @if($qrImageUrl)
                    <img src="{{ $qrImageUrl }}" alt="QR Attendance" class="w-56 h-56">
                @else
                    <div class="w-56 h-56 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('attendance.qr_missing') }}
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('member-attendance.qr.regenerate') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition disabled:opacity-60 disabled:cursor-not-allowed" {{ $isOpen ? '' : 'disabled' }}>
                    {{ __('attendance.qr_regenerate_button') }}
                </button>
            </form>

            <div class="w-full">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('attendance.qr_link_label') }}</label>
                <div class="mt-2 flex items-center gap-2">
                    <input
                        type="text"
                        id="qr-link"
                        value="{{ $qrPayload }}"
                        readonly
                        class="flex-1 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                    >
                    <button type="button" id="copy-qr-link" class="px-3 py-2 text-sm {{ $tc->buttonOutline }} rounded-lg transition">
                        {{ __('attendance.qr_copy') }}
                    </button>
                </div>
                @if($publicUrl)
                    <div class="mt-3">
                        <a href="{{ $publicUrl }}" target="_blank" class="text-sm {{ $tc->link }}">
                            {{ __('attendance.qr_public_page') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('copy-qr-link')?.addEventListener('click', () => {
        const input = document.getElementById('qr-link');
        if (! input) {
            return;
        }
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard?.writeText(input.value);
    });
</script>
@endpush
