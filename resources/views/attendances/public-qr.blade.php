@extends('layouts.portal')

@section('title', __('attendance.qr_title'))

@section('content')
@php
    $qrImageUrl = $qrPayload ? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($qrPayload) : null;
@endphp
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.qr_title') }}</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.qr_public_desc', ['store' => $store->name]) }}</p>

        @if(! $isOpen)
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 text-sm p-4">
                {{ __('attendance.qr_closed') }}
            </div>
        @endif

        <div class="mt-6 flex justify-center">
            <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-200 dark:border-gray-700">
                @if($qrImageUrl)
                    <img src="{{ $qrImageUrl }}" alt="QR Attendance" class="w-64 h-64">
                @else
                    <div class="w-64 h-64 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('attendance.qr_missing') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-6 text-left">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('attendance.qr_link_label') }}</label>
            <div class="mt-2 flex items-center gap-2">
                <input
                    type="text"
                    id="qr-public-link"
                    value="{{ $qrPayload }}"
                    readonly
                    class="flex-1 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                >
                <button type="button" id="copy-qr-public-link" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg transition hover:bg-gray-50 dark:hover:bg-gray-700">
                    {{ __('attendance.qr_copy') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('copy-qr-public-link')?.addEventListener('click', () => {
        const input = document.getElementById('qr-public-link');
        if (! input) {
            return;
        }
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard?.writeText(input.value);
    });
</script>
@endpush
