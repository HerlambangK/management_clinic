@extends('layouts.dashboard')

@section('title', __('import.import_entity', ['entity' => $entityLabel]))
@section('page-title', __('import.import_entity', ['entity' => $entityLabel]))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Back Link -->
    <div>
        <a href="{{ route('imports.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('common.back') }} {{ __('import.import_data') }}
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-6">
        <!-- Upload Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('import.upload_title') }}</h2>

            <form action="{{ route('imports.upload', $entity) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('import.select_file') }} <span class="text-red-500">*</span>
                    </label>

                    <div class="relative" x-data="{ fileName: '' }">
                        <input
                            type="file"
                            name="file"
                            accept=".xlsx,.xls"
                            required
                            class="sr-only"
                            id="file-upload"
                            x-on:change="fileName = $event.target.files[0]?.name || ''"
                        >
                        <label
                            for="file-upload"
                            class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-rose-300 dark:hover:border-rose-500 hover:bg-rose-50/50 dark:hover:bg-rose-900/20 transition"
                        >
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400" x-show="!fileName">
                                    <span class="font-medium">{{ __('import.click_to_upload') }}</span> {{ __('import.drag_drop') }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500" x-show="!fileName">{{ __('import.file_format') }}</p>
                                <p class="text-sm font-medium text-rose-600 dark:text-rose-400" x-show="fileName" x-text="fileName"></p>
                            </div>
                        </label>
                    </div>

                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        {{ __('import.upload_preview') }}
                    </button>

                    <a href="{{ route('imports.template', $entity) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        {{ __('import.download_template') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('import.instructions') }}</h2>

            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('import.file_format_title') }}</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('import.format_excel') }}
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('import.format_header') }}
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('import.format_size') }}
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('import.format_sheet') }}
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('import.required_columns') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($requiredColumns as $column)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400">
                                {{ $column }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('import.available_columns') }}</h3>
                    <div class="space-y-2">
                        @foreach($availableColumns as $column => $description)
                            <div class="text-sm">
                                <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $column }}</span>
                                <span class="text-gray-500 dark:text-gray-400 ml-2">{{ $description }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-2 text-sm text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>{{ __('import.update_warning') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
