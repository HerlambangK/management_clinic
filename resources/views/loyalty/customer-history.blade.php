@extends('layouts.dashboard')

@section('title', __('loyalty.customer_history_title', ['name' => $customer->name]))
@section('page-title', __('loyalty.customer_history_title', ['name' => $customer->name]))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ $customer->phone }} {{ $customer->email ? '| '.$customer->email : '' }}</p>
        </div>
        <a href="{{ route('loyalty.customers') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Customer Stats Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $customer->loyalty_tier_color }}">
                    <span class="text-2xl font-bold">{{ strtoupper(substr($customer->loyalty_tier ?? 'B', 0, 1)) }}</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $customer->name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $customer->loyalty_tier_color }}">
                            {{ $customer->loyalty_tier_label }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('loyalty.member_since', ['date' => format_date($customer->created_at)]) }}
                    </p>
                </div>
            </div>
            <div class="flex gap-6 max-sm:w-full max-sm:justify-between">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ format_number($customer->loyalty_points) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.current_points') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ format_number($customer->lifetime_points) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.lifetime_points') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Points History & Adjust -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Points History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('loyalty.points_history') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.date') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.type') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.points') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.description') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($points as $point)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ format_datetime($point->created_at) }}
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        @php
                                            $typeColors = [
                                                'earn' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'redeem' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                                'expire' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                'adjust' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$point->type] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $point->type_label }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <span class="text-sm font-medium {{ $point->points > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $point->points > 0 ? '+' : '' }}{{ format_number($point->points) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $point->description ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('loyalty.no_history') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($points->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $points->links() }}
                    </div>
                @endif
            </div>

            <!-- Adjust Points Form (Admin Only) -->
            @if(auth()->user()->role === 'owner' || auth()->user()->role === 'admin')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('loyalty.adjust_points') }}</h3>
                    <form action="{{ route('loyalty.adjust-points', $customer) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="flex-1">
                            <input
                                type="number"
                                name="points"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                                placeholder="{{ __('loyalty.points_amount') }}"
                                required
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('loyalty.adjust_points_help') }}</p>
                        </div>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="description"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                                placeholder="{{ __('loyalty.reason') }}"
                                required
                            >
                        </div>
                        <button type="submit" class="px-4 py-2 {{ $themeButton }} text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                            {{ __('loyalty.adjust') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Right Column: Rewards & Redemptions -->
        <div class="space-y-6">
            <!-- Available Rewards -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('loyalty.available_rewards') }}</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($availableRewards as $reward)
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $reward->name }}</h4>
                                    @if($reward->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $reward->description }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 whitespace-nowrap">
                                    {{ format_number($reward->points_required) }} pts
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $reward->reward_type_label }}: {{ $reward->formatted_reward_value }}
                                </span>
                                <form action="{{ route('loyalty.redeem', $customer) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                    <button type="submit" class="px-3 py-1 {{ $themeButton }} text-white text-xs font-medium rounded-lg transition" onclick="return confirm('{{ __('loyalty.confirm_redeem') }}')">
                                        {{ __('loyalty.redeem') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_available_rewards') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Redemptions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('loyalty.active_redemptions') }}</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($redemptions->where('status', 'pending') as $redemption)
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $redemption->reward?->name ?? '-' }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $redemption->code }}</p>
                                </div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'used' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$redemption->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $redemption->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ __('loyalty.valid_until') }}: {{ format_date($redemption->valid_until) }}</span>
                                @if($redemption->status === 'pending')
                                    <form action="{{ route('loyalty.cancel-redemption', $redemption) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('{{ __('loyalty.confirm_cancel') }}')">
                                            {{ __('common.cancel') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_active_redemptions') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
