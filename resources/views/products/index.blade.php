@extends('layouts.dashboard')

@section('title', __('product.title'))
@section('page-title', __('product.title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('product.subtitle') }}</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('product-categories.index') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('product.category_title') }}
            </a>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $themeButton }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('common.add') }}
            </a>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockCount > 0)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <a href="{{ route('products.index', ['stock' => 'low']) }}" class="underline">
                {{ trans_choice('product.low_stock_alert', $lowStockCount, ['count' => $lowStockCount]) }}
            </a>
        </div>
    @endif

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

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    placeholder="{{ __('product.search_placeholder') }}"
                >
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <select
                        name="category"
                        class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('product.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <select
                        name="stock"
                        class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('product.all_stock') }}</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>{{ __('product.low_stock') }}</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>{{ __('product.out_of_stock') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-800 dark:bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-900 dark:hover:bg-gray-500 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search', 'category', 'stock']))
                    <a href="{{ route('products.index') }}" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
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
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('product.name') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('product.sku') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('product.category') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('product.price') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('product.stock') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        @if($product->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $product->sku ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if($product->category)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->formatted_price }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if($product->track_stock)
                                    <span class="text-sm {{ $product->is_low_stock ? 'text-yellow-600 dark:text-yellow-400 font-medium' : ($product->is_out_of_stock ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-900 dark:text-white') }}">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <form action="{{ route('products.toggle-active', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition">
                                        {{ $product->is_active ? __('common.active') : __('common.inactive') }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-sm font-medium">{{ __('common.edit') }}</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 text-sm font-medium">{{ __('common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('product.no_products') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Cards -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($products as $product)
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <form action="{{ route('products.toggle-active', $product) }}" method="POST" class="inline flex-shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $product->is_active ? __('common.active') : __('common.inactive') }}
                                    </button>
                                </form>
                            </div>
                            @if($product->sku)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">SKU: {{ $product->sku }}</p>
                            @endif
                            @if($product->category)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $product->category->name }}</p>
                            @endif
                            <div class="flex items-center justify-between mb-2">
                                @if($product->track_stock)
                                    <span class="text-xs {{ $product->is_low_stock ? 'text-yellow-600 dark:text-yellow-400' : ($product->is_out_of_stock ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400') }}">
                                        {{ __('product.stock') }}: {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->formatted_price }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('products.edit', $product) }}" class="text-blue-600 dark:text-blue-400 text-xs font-medium">{{ __('common.edit') }}</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 text-xs font-medium">{{ __('common.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('product.no_products') }}</p>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
