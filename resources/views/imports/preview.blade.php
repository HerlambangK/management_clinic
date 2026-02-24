@extends('layouts.dashboard')

@section('title', __('import.preview_title') . ' ' . $entityLabel)
@section('page-title', __('import.preview_title') . ' ' . $entityLabel)

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Back Link -->
    <div>
        <a href="{{ route('imports.create', $entity) }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Validation Status -->
    @if(!$preview['valid'])
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 max-sm:p-3">
            <div class="flex items-start gap-3 max-sm:gap-2">
                <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-red-500 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-sm max-sm:text-xs font-medium text-red-800 dark:text-red-400">{{ __('import.file_invalid') }}</h3>
                    <ul class="mt-2 max-sm:mt-1 text-sm max-sm:text-xs text-red-700 dark:text-red-400 list-disc list-inside">
                        @foreach($preview['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 max-sm:p-3">
            <div class="flex items-start gap-3 max-sm:gap-2">
                <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-green-500 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-sm max-sm:text-xs font-medium text-green-800 dark:text-green-400">{{ __('import.file_valid') }}</h3>
                    <p class="mt-1 text-sm max-sm:text-xs text-green-700 dark:text-green-400">{{ __('import.rows_to_import', ['count' => $preview['total']]) }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Preview Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('import.data_preview') }} ({{ count($preview['rows']) }} {{ __('import.of_rows', ['total' => $preview['total']]) }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm table-fixed">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="w-10 px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        @foreach($preview['headers'] as $header)
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <span class="truncate block">{{ $header }}</span>
                                @if(in_array(strtolower($header), array_map('strtolower', $requiredColumns)))
                                    <span class="text-red-500">*</span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($preview['rows'] as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="w-10 px-3 py-3 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            @foreach($preview['headers'] as $header)
                                <td class="px-3 py-3 text-gray-900 dark:text-white">
                                    <span class="block truncate" title="{{ $row[$header] ?? '-' }}">{{ $row[$header] ?? '-' }}</span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($preview['total'] > count($preview['rows']))
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                ... {{ __('import.and_more_rows', ['count' => $preview['total'] - count($preview['rows'])]) }}
            </div>
        @endif
    </div>

    <!-- Preview Cards - Mobile -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('import.data_preview') }} ({{ count($preview['rows']) }} {{ __('import.of_rows', ['total' => $preview['total']]) }})</h2>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($preview['rows'] as $index => $row)
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">#{{ $index + 1 }}</span>
                    </div>
                    <div class="space-y-2">
                        @php
                            // Show only important columns on mobile (first 4-5 columns)
                            $mobileHeaders = array_slice($preview['headers'], 0, 5);
                        @endphp
                        @foreach($mobileHeaders as $header)
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">
                                    {{ ucfirst(str_replace('_', ' ', $header)) }}
                                    @if(in_array(strtolower($header), array_map('strtolower', $requiredColumns)))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </span>
                                <span class="text-xs text-gray-900 dark:text-white break-all">{{ $row[$header] ?? '-' }}</span>
                            </div>
                        @endforeach
                        @if(count($preview['headers']) > 5)
                            <div class="text-xs text-gray-400 dark:text-gray-500 pt-1">
                                {{ __('import.and_other', ['count' => count($preview['headers']) - 5]) }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($preview['total'] > count($preview['rows']))
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-700 text-center text-xs text-gray-500 dark:text-gray-400">
                ... {{ __('import.and_more_rows', ['count' => $preview['total'] - count($preview['rows'])]) }}
            </div>
        @endif
    </div>

    <!-- Column Mapping Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
        <h3 class="text-sm max-sm:text-xs font-semibold text-gray-900 dark:text-white mb-3 max-sm:mb-2">{{ __('import.column_description') }}</h3>
        <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-3 max-sm:gap-2">
            @foreach($availableColumns as $column => $description)
                <div class="flex items-start gap-2 text-sm max-sm:text-xs">
                    <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded flex-shrink-0">{{ $column }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $description }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-stretch justify-between gap-4 max-sm:gap-3">
        <a href="{{ route('imports.create', $entity) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition max-sm:order-2">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('import.upload_again') }}
        </a>

        @if($preview['valid'])
            <form action="{{ route('imports.process', $entity) }}" method="POST" class="max-sm:order-1 max-sm:w-full">
                @csrf
                <input type="hidden" name="file" value="{{ $fileName }}">
                <input type="hidden" name="original_name" value="{{ $fileName }}">

                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2 max-sm:py-2.5 max-sm:w-full {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition" onclick="this.disabled=true; this.innerHTML='<svg class=\'animate-spin w-4 h-4\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg> {{ __('common.processing') }}'; this.form.submit();">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('import.process_import') }} ({{ $preview['total'] }} {{ __('import.data') }})
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
