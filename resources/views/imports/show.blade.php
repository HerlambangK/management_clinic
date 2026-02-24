@extends('layouts.dashboard')

@section('title', __('import.detail_title'))
@section('page-title', __('import.detail_title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Back Link -->
    <div>
        <a href="{{ route('imports.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('common.back') }}
        </a>
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

    <!-- Summary Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $import->entity_type_label }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $import->original_file_name }}</p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'processing' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$import->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                {{ $import->status_label }}
            </span>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('import.total_rows_label') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $import->total_rows }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4">
                <p class="text-sm text-green-600 dark:text-green-400 mb-1">{{ __('import.success_count') }}</p>
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ $import->success_count }}</p>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4">
                <p class="text-sm text-yellow-600 dark:text-yellow-400 mb-1">{{ __('import.skipped_count') }}</p>
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">{{ $import->skipped_count }}</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4">
                <p class="text-sm text-red-600 dark:text-red-400 mb-1">{{ __('import.error_count') }}</p>
                <p class="text-2xl font-bold text-red-700 dark:text-red-400">{{ $import->error_count }}</p>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 max-sm:grid-cols-1 gap-4 text-sm">
            <div>
                <span class="text-gray-500 dark:text-gray-400">{{ __('import.imported_by') }}:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ $import->user->name ?? '-' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">{{ __('import.import_time') }}:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ format_datetime($import->created_at) }}</span>
            </div>
            @if($import->started_at && $import->completed_at)
                <div>
                    <span class="text-gray-500 dark:text-gray-400">{{ __('import.duration') }}:</span>
                    <span class="text-gray-900 dark:text-white ml-2">{{ $import->started_at->diffForHumans($import->completed_at, true) }}</span>
                </div>
            @endif
            <div>
                <span class="text-gray-500 dark:text-gray-400">{{ __('import.success_rate') }}:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ $import->success_rate }}%</span>
            </div>
        </div>
    </div>

    <!-- Errors Section -->
    @if($import->errors && count($import->errors) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
                <h3 class="text-base font-semibold text-red-800 dark:text-red-400">{{ __('import.error_details') }} ({{ count($import->errors) }})</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.row') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.error_message') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('import.data') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($import->errors as $error)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">
                                    {{ $error['row'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-red-600 dark:text-red-400">
                                    {{ $error['message'] ?? 'Unknown error' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs font-mono max-w-md truncate">
                                    @if(isset($error['data']))
                                        {{ json_encode($error['data'], JSON_UNESCAPED_UNICODE) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('imports.create', $import->entity_type) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            {{ __('import.import_again') }}
        </a>

        <form action="{{ route('imports.destroy', $import) }}" method="POST" onsubmit="return confirm('{{ __('import.delete_confirm') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-red-600 dark:text-red-400 text-sm font-medium hover:text-red-700 dark:hover:text-red-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                {{ __('import.delete_log') }}
            </button>
        </form>
    </div>
</div>
@endsection
