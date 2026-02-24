@extends('layouts.dashboard')

@section('title', __('customer.title'))
@section('page-title', __('customer.title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('customer.subtitle') }} ({{ $customers->total() }} customer)</p>
        <a href="{{ route('customers.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $themeButton }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('customer.add') }}
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('customers.index') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('customer.search_placeholder') }}"
                        class="w-full pl-9 pr-4 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    >
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="flex gap-2 max-sm:flex-wrap">
                <!-- Skin Type Filter -->
                <div class="relative">
                    <select
                        name="skin_type"
                        class="w-auto max-sm:flex-1 pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    >
                        <option value="">{{ __('customer.skin_type') }}</option>
                        <option value="normal" {{ request('skin_type') === 'normal' ? 'selected' : '' }}>{{ __('customer.skin_normal') }}</option>
                        <option value="oily" {{ request('skin_type') === 'oily' ? 'selected' : '' }}>{{ __('customer.skin_oily') }}</option>
                        <option value="dry" {{ request('skin_type') === 'dry' ? 'selected' : '' }}>{{ __('customer.skin_dry') }}</option>
                        <option value="combination" {{ request('skin_type') === 'combination' ? 'selected' : '' }}>{{ __('customer.skin_combination') }}</option>
                        <option value="sensitive" {{ request('skin_type') === 'sensitive' ? 'selected' : '' }}>{{ __('customer.skin_sensitive') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Sort -->
                <div class="relative">
                    <select
                        name="sort"
                        class="w-auto max-sm:flex-1 pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        onchange="this.form.submit()"
                    >
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>{{ __('common.newest') }}</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ __('common.name_asc') }}</option>
                        <option value="total_visits" {{ request('sort') === 'total_visits' ? 'selected' : '' }}>{{ __('customer.visits') }}</option>
                        <option value="total_spent" {{ request('sort') === 'total_spent' ? 'selected' : '' }}>{{ __('customer.sort_spending') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.filter') }}
                </button>

                @if(request()->hasAny(['search', 'skin_type', 'sort']))
                    <a href="{{ route('customers.index') }}" class="px-3 py-2 max-sm:py-1.5 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-gray-200 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Desktop Table -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer.title') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.phone') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer.skin_type') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer.visits') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer.total_spent') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('customers.show', $customer) }}" class="flex items-center gap-3 group">
                                    <div class="w-9 h-9 bg-gradient-to-br {{ $themeGradient }} rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100 group-hover:{{ $themeAccent }} transition">{{ $customer->name }}</span>
                                        @if($customer->gender)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $customer->gender === 'male' ? __('customer.male') : ($customer->gender === 'female' ? __('customer.female') : __('customer.other')) }}
                                                @if($customer->age), {{ $customer->age }} th @endif
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $customer->phone }}</div>
                                @if($customer->email)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->email }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if($customer->skin_type)
                                    @php
                                        $skinTypeColors = [
                                            'normal' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                            'oily' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                                            'dry' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400',
                                            'combination' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                            'sensitive' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $skinTypeColors[$customer->skin_type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }} capitalize">
                                        {{ __('customer.skin_' . $customer->skin_type) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $customer->total_visits }}x</div>
                                @if($customer->last_visit)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->last_visit->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $customer->formatted_total_spent }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm font-medium">{{ __('common.detail') }}</a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">{{ __('common.edit') }}</a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('customer.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium">{{ __('common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('customer.no_customers') }}</p>
                                <a href="{{ route('customers.create') }}" class="mt-3 inline-flex items-center text-sm {{ $themeLink }} font-medium">
                                    {{ __('customer.add_first') }}
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Cards -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($customers as $customer)
                @php
                    $skinTypeColors = [
                        'normal' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                        'oily' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                        'dry' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400',
                        'combination' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                        'sensitive' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                    ];
                @endphp
                <a href="{{ route('customers.show', $customer) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br {{ $themeGradient }} rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $customer->name }}</p>
                                @if($customer->skin_type)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium flex-shrink-0 {{ $skinTypeColors[$customer->skin_type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }} capitalize">
                                        {{ __('customer.skin_' . $customer->skin_type) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $customer->phone }}</p>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400">{{ $customer->total_visits }}x {{ __('customer.visits') }}</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $customer->formatted_total_spent }}</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('customer.no_customers') }}</p>
                </div>
            @endforelse
        </div>

        @if($customers->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
