@extends('layouts.dashboard')

@section('title', __('setting.branding'))
@section('page-title', __('setting.branding'))

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('settings.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/50 dark:border-green-800 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/50 dark:border-red-800 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/50 dark:border-red-800 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.branding.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- App Identity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.app_identity') }}</h3>

            <div class="space-y-4">
                <div>
                    <label for="brand_app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.app_name') }}</label>
                    <input type="text" name="brand_app_name" id="brand_app_name"
                           value="{{ old('brand_app_name', $settings['brand_app_name']) }}"
                           placeholder="{{ config('branding.app.name') }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.app_name_hint') }}</p>
                    @error('brand_app_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <div>
                        <label for="brand_app_tagline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.tagline_en') }}</label>
                        <input type="text" name="brand_app_tagline" id="brand_app_tagline"
                               value="{{ old('brand_app_tagline', $settings['brand_app_tagline']) }}"
                               placeholder="{{ config('branding.app.tagline') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                        @error('brand_app_tagline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand_app_tagline_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.tagline_id') }}</label>
                        <input type="text" name="brand_app_tagline_id" id="brand_app_tagline_id"
                               value="{{ old('brand_app_tagline_id', $settings['brand_app_tagline_id']) }}"
                               placeholder="{{ config('branding.app.tagline_id') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                        @error('brand_app_tagline_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <div>
                        <label for="brand_app_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.description_en') }}</label>
                        <textarea name="brand_app_description" id="brand_app_description" rows="2"
                                  placeholder="{{ config('branding.app.description') }}"
                                  class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">{{ old('brand_app_description', $settings['brand_app_description']) }}</textarea>
                        @error('brand_app_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand_app_description_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.description_id') }}</label>
                        <textarea name="brand_app_description_id" id="brand_app_description_id" rows="2"
                                  placeholder="{{ config('branding.app.description_id') }}"
                                  class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">{{ old('brand_app_description_id', $settings['brand_app_description_id']) }}</textarea>
                        @error('brand_app_description_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.logo_settings') }}</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-6">
                    <!-- Main Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.main_logo') }}</label>
                        @if($settings['brand_logo_path'])
                            <div class="mb-3 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $settings['brand_logo_path']) }}" alt="Logo" class="h-12 w-auto">
                                <button type="button" onclick="document.getElementById('remove-logo-form').submit()" class="text-red-500 text-sm hover:text-red-700 cursor-pointer">{{ __('common.remove') }}</button>
                            </div>
                        @endif
                        <input type="file" name="brand_logo_path" id="brand_logo_path" accept="image/*"
                               class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200 dark:hover:file:bg-gray-600">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.logo_hint') }}</p>
                        @error('brand_logo_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('setting.favicon') }}</label>
                        @if($settings['brand_logo_favicon'])
                            <div class="mb-3 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $settings['brand_logo_favicon']) }}" alt="Favicon" class="h-8 w-8">
                                <button type="button" onclick="document.getElementById('remove-favicon-form').submit()" class="text-red-500 text-sm hover:text-red-700 cursor-pointer">{{ __('common.remove') }}</button>
                            </div>
                        @endif
                        <input type="file" name="brand_logo_favicon" id="brand_logo_favicon" accept="image/*"
                               class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200 dark:hover:file:bg-gray-600">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.favicon_hint') }}</p>
                        @error('brand_logo_favicon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="brand_logo_show_text" value="0">
                    <input type="checkbox" name="brand_logo_show_text" id="brand_logo_show_text" value="1"
                           {{ old('brand_logo_show_text', $settings['brand_logo_show_text']) ? 'checked' : '' }}
                           class="w-4 h-4 {{ $tc->checkbox ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 rounded focus:ring-{{ $tc->primary ?? 'rose' }}-500/20">
                    <label for="brand_logo_show_text" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('setting.show_app_name_next_to_logo') }}</label>
                </div>
            </div>
        </div>

        <!-- Theme Colors -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.theme_colors') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('setting.theme_colors_desc') }}</p>

            @php
                $palettes = brand_palettes();
                $selectedPalette = old('brand_colors_palette', $settings['brand_colors_palette']);
            @endphp

            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                <label class="relative cursor-pointer">
                    <input type="radio" name="brand_colors_palette" value="" class="peer sr-only" {{ empty($selectedPalette) ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 transition-all border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-500 peer-checked:border-gray-900 peer-checked:bg-gray-50 peer-checked:dark:border-gray-200 peer-checked:dark:bg-gray-700/40 peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('setting.theme_palette_default') }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.theme_palette_default_desc') }}</p>
                    </div>
                </label>

                @foreach($palettes as $key => $palette)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="brand_colors_palette" value="{{ $key }}" class="peer sr-only" {{ $selectedPalette === $key ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 transition-all border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-500 peer-checked:border-gray-900 peer-checked:bg-gray-50 peer-checked:dark:border-gray-200 peer-checked:dark:bg-gray-700/40 peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500/20">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $palette['label'] }}</p>
                                <div class="flex items-center gap-1">
                                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $palette['colors']['400'] }};"></span>
                                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $palette['colors']['500'] }};"></span>
                                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $palette['colors']['600'] }};"></span>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('brand_colors_palette')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Information -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.contact_info') }}</h3>

            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                <div>
                    <label for="brand_contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('common.email') }}</label>
                    <input type="email" name="brand_contact_email" id="brand_contact_email"
                           value="{{ old('brand_contact_email', $settings['brand_contact_email']) }}"
                           placeholder="support@example.com"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_contact_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('common.phone') }}</label>
                    <input type="text" name="brand_contact_phone" id="brand_contact_phone"
                           value="{{ old('brand_contact_phone', $settings['brand_contact_phone']) }}"
                           placeholder="+62 812-3456-7890"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_contact_whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp</label>
                    <input type="text" name="brand_contact_whatsapp" id="brand_contact_whatsapp"
                           value="{{ old('brand_contact_whatsapp', $settings['brand_contact_whatsapp']) }}"
                           placeholder="6281234567890"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_contact_whatsapp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_contact_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('common.address') }}</label>
                    <input type="text" name="brand_contact_address" id="brand_contact_address"
                           value="{{ old('brand_contact_address', $settings['brand_contact_address']) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_contact_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.social_media') }}</h3>

            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                <div>
                    <label for="brand_social_facebook" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facebook</label>
                    <input type="url" name="brand_social_facebook" id="brand_social_facebook"
                           value="{{ old('brand_social_facebook', $settings['brand_social_facebook']) }}"
                           placeholder="https://facebook.com/..."
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_social_facebook')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_social_instagram" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</label>
                    <input type="url" name="brand_social_instagram" id="brand_social_instagram"
                           value="{{ old('brand_social_instagram', $settings['brand_social_instagram']) }}"
                           placeholder="https://instagram.com/..."
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_social_instagram')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_social_twitter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Twitter/X</label>
                    <input type="url" name="brand_social_twitter" id="brand_social_twitter"
                           value="{{ old('brand_social_twitter', $settings['brand_social_twitter']) }}"
                           placeholder="https://twitter.com/..."
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_social_twitter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_social_tiktok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">TikTok</label>
                    <input type="url" name="brand_social_tiktok" id="brand_social_tiktok"
                           value="{{ old('brand_social_tiktok', $settings['brand_social_tiktok']) }}"
                           placeholder="https://tiktok.com/@..."
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                    @error('brand_social_tiktok')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Footer Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.footer_settings') }}</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <div>
                        <label for="brand_footer_copyright" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.copyright_en') }}</label>
                        <input type="text" name="brand_footer_copyright" id="brand_footer_copyright"
                               value="{{ old('brand_footer_copyright', $settings['brand_footer_copyright']) }}"
                               placeholder="{{ config('branding.footer.copyright') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('setting.copyright_hint') }}</p>
                        @error('brand_footer_copyright')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand_footer_copyright_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.copyright_id') }}</label>
                        <input type="text" name="brand_footer_copyright_id" id="brand_footer_copyright_id"
                               value="{{ old('brand_footer_copyright_id', $settings['brand_footer_copyright_id']) }}"
                               placeholder="{{ config('branding.footer.copyright_id') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                        @error('brand_footer_copyright_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="brand_footer_show_powered_by" value="0">
                    <input type="checkbox" name="brand_footer_show_powered_by" id="brand_footer_show_powered_by" value="1"
                           {{ old('brand_footer_show_powered_by', $settings['brand_footer_show_powered_by']) ? 'checked' : '' }}
                           class="w-4 h-4 {{ $tc->checkbox ?? 'text-rose-500' }} border-gray-300 dark:border-gray-600 rounded focus:ring-{{ $tc->primary ?? 'rose' }}-500/20">
                    <label for="brand_footer_show_powered_by" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('setting.show_powered_by') }}</label>
                </div>

                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <div>
                        <label for="brand_footer_powered_by_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.powered_by_text') }}</label>
                        <input type="text" name="brand_footer_powered_by_text" id="brand_footer_powered_by_text"
                               value="{{ old('brand_footer_powered_by_text', $settings['brand_footer_powered_by_text']) }}"
                               placeholder="GlowUp"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                        @error('brand_footer_powered_by_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand_footer_powered_by_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.powered_by_url') }}</label>
                        <input type="url" name="brand_footer_powered_by_url" id="brand_footer_powered_by_url"
                               value="{{ old('brand_footer_powered_by_url', $settings['brand_footer_powered_by_url']) }}"
                               placeholder="https://glowup.app"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                        @error('brand_footer_powered_by_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Scripts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('setting.custom_scripts') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('setting.custom_scripts_desc') }}</p>

            <div class="space-y-4">
                <div>
                    <label for="brand_custom_head_scripts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.head_scripts') }}</label>
                    <textarea name="brand_custom_head_scripts" id="brand_custom_head_scripts" rows="3"
                              placeholder="<!-- Google Analytics, etc. -->"
                              class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 font-mono text-sm">{{ old('brand_custom_head_scripts', $settings['brand_custom_head_scripts']) }}</textarea>
                    @error('brand_custom_head_scripts')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_custom_body_scripts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.body_scripts') }}</label>
                    <textarea name="brand_custom_body_scripts" id="brand_custom_body_scripts" rows="3"
                              placeholder="<!-- Chat widgets, etc. -->"
                              class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 font-mono text-sm">{{ old('brand_custom_body_scripts', $settings['brand_custom_body_scripts']) }}</textarea>
                    @error('brand_custom_body_scripts')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_custom_custom_css" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('setting.custom_css') }}</label>
                    <textarea name="brand_custom_custom_css" id="brand_custom_custom_css" rows="3"
                              placeholder="/* Custom CSS */"
                              class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 font-mono text-sm">{{ old('brand_custom_custom_css', $settings['brand_custom_custom_css']) }}</textarea>
                    @error('brand_custom_custom_css')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2 rounded-lg font-medium transition cursor-pointer">
                {{ __('common.save_changes') }}
            </button>
        </div>
    </form>

    <!-- Hidden forms for removing logo/favicon (outside main form to avoid nested forms) -->
    @if($settings['brand_logo_path'])
        <form id="remove-logo-form" action="{{ route('settings.branding.remove-logo') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="type" value="logo">
        </form>
    @endif

    @if($settings['brand_logo_favicon'])
        <form id="remove-favicon-form" action="{{ route('settings.branding.remove-logo') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="type" value="favicon">
        </form>
    @endif
</div>
@endsection
