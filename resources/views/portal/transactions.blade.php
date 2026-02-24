@extends('layouts.portal')

@section('title', __('portal.transactions'))
@section('page-title', __('portal.transactions'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <p class="text-gray-500 dark:text-gray-400">{{ __('portal.transactions_subtitle') }}</p>
    </div>

    <!-- Transactions List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        @if($transactions->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($transactions as $transaction)
                    <a href="{{ route('portal.transactions.show', $transaction) }}" class="block p-4 max-sm:p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 max-sm:gap-2 min-w-0">
                                <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm max-sm:text-xs font-medium text-gray-900 dark:text-white truncate">{{ $transaction->invoice_number }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ format_datetime($transaction->created_at) }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm max-sm:text-xs font-semibold text-gray-900 dark:text-white">{{ format_currency($transaction->total_amount) }}</p>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                        'partial' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                        'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                        'refunded' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ __('portal.payment_status_' . $transaction->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ $transaction->items->count() }} {{ __('portal.items') }}:
                            {{ $transaction->items->take(2)->map(fn($i) => $i->service?->name ?? $i->product?->name ?? $i->package?->name)->filter()->join(', ') }}
                            @if($transaction->items->count() > 2)
                                {{ __('portal.and_more', ['count' => $transaction->items->count() - 2]) }}
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('portal.no_transactions') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('portal.no_transactions_desc') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
