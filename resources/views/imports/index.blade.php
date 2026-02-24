@extends('layouts.dashboard')

@section('title', __('import.title'))
@section('page-title', __('import.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('import.subtitle') }}</p>
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

    <!-- Import Options -->
    <div class="grid grid-cols-3 max-lg:grid-cols-2 max-sm:grid-cols-1 gap-4">
        @foreach($entities as $entity)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:border-rose-200 dark:hover:border-rose-800 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-rose-100 dark:bg-rose-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                        @if($entity['key'] === 'customers')
                            <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        @elseif($entity['key'] === 'services')
                            <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        @elseif($entity['key'] === 'packages')
                            <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base max-sm:text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $entity['label'] }}</h3>
                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-4">
                            @if($entity['key'] === 'customers')
                                {{ __('import.customers_desc') }}
                            @elseif($entity['key'] === 'services')
                                {{ __('import.services_desc') }}
                            @elseif($entity['key'] === 'packages')
                                {{ __('import.packages_desc') }}
                            @endif
                        </p>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('imports.create', $entity['key']) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                {{ __('common.import') }}
                            </a>
                            <a href="{{ route('imports.template', $entity['key']) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                {{ __('import.template') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Import History -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('import.history') }}</h2>
        </div>

        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.date') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.data_type') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.file') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.result') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.status') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($imports as $import)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ format_date($import->created_at) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ format_time($import->created_at) }}</div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $import->entity_type_label }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">{{ $import->original_file_name }}</div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-sm">
                                    <span class="text-green-600 dark:text-green-400">{{ $import->success_count }} {{ __('import.success') }}</span>
                                    @if($import->skipped_count > 0)
                                        <span class="text-yellow-600 dark:text-yellow-400">, {{ $import->skipped_count }} {{ __('import.updated') }}</span>
                                    @endif
                                    @if($import->error_count > 0)
                                        <span class="text-red-600 dark:text-red-400">, {{ $import->error_count }} {{ __('import.failed') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                        'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                        'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$import->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $import->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('imports.show', $import) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm font-medium">{{ __('common.detail') }}</a>
                                    <form action="{{ route('imports.destroy', $import) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('import.delete_confirm') }}')">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('import.no_history') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($imports as $import)
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                        'processing' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                        'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                        'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                    ];
                @endphp
                <a href="{{ route('imports.show', $import) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $import->entity_type_label }}</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$import->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $import->status_label }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 truncate">{{ $import->original_file_name }}</p>
                            <div class="text-xs">
                                <span class="text-green-600 dark:text-green-400">{{ $import->success_count }} {{ __('import.success') }}</span>
                                @if($import->error_count > 0)
                                    <span class="text-red-600 dark:text-red-400">, {{ $import->error_count }} {{ __('import.failed') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ format_date($import->created_at) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ format_time($import->created_at) }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('import.no_history') }}</p>
                </div>
            @endforelse
        </div>

        @if($imports->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $imports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
