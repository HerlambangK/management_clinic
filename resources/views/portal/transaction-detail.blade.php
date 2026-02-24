@extends('layouts.portal')

@section('title', __('portal.transaction_detail'))
@section('page-title', __('portal.transaction_detail'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('portal.transactions') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ __('portal.back_to_transactions') }}
    </a>

    <!-- Transaction Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $transaction->invoice_number }}</h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">{{ format_datetime($transaction->created_at) }}</p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                    'partial' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                    'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                    'refunded' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700' }}">
                {{ __('portal.payment_status_' . $transaction->status) }}
            </span>
        </div>

        @if($transaction->cashier)
            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.served_by') }}</p>
                <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $transaction->cashier->name }}</p>
            </div>
        @endif
    </div>

    <!-- Items -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.items') }}</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($transaction->items as $item)
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                @if($item->service)
                                    {{ $item->service->name }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('portal.service') }})</span>
                                @elseif($item->product)
                                    {{ $item->product->name }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('portal.product') }})</span>
                                @elseif($item->package)
                                    {{ $item->package->name }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('portal.package') }})</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->quantity }} x {{ format_currency($item->unit_price) }}
                            </p>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ format_currency($item->total_price) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.payment_summary') }}</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">{{ __('portal.subtotal') }}</span>
                <span class="text-gray-900 dark:text-white">{{ format_currency($transaction->subtotal) }}</span>
            </div>
            @if($transaction->discount_amount > 0)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('portal.discount') }}</span>
                    <span class="text-green-600 dark:text-green-400">-{{ format_currency($transaction->discount_amount) }}</span>
                </div>
            @endif
            @if($transaction->tax_amount > 0)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('portal.tax') }}</span>
                    <span class="text-gray-900 dark:text-white">{{ format_currency($transaction->tax_amount) }}</span>
                </div>
            @endif
            <hr class="border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-gray-900 dark:text-white">{{ __('portal.total') }}</span>
                <span class="text-xl font-bold text-primary-600">{{ format_currency($transaction->total_amount) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
