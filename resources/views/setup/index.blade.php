@extends('layouts.landing')

@section('title', __('setup.title') . ' - ' . brand_name())

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8 relative z-10">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-serif font-bold text-gray-900">
                {{ __('setup.welcome') }}
            </h1>
            <p class="mt-2 text-gray-600">
                {{ __('setup.select_business_type') }}
            </p>

            <!-- Step indicator -->
            <div class="flex justify-center items-center mt-6 space-x-4 max-sm:space-x-2">
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-sm font-medium">1</span>
                    <span class="ml-2 text-sm font-medium text-primary-600 max-sm:hidden">{{ __('setup.business_type') }}</span>
                </div>
                <div class="w-8 h-px bg-gray-300 max-sm:w-4"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">2</span>
                    <span class="ml-2 text-sm text-gray-500 max-sm:hidden">{{ __('setup.details') }}</span>
                </div>
                <div class="w-8 h-px bg-gray-300 max-sm:w-4"></div>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">3</span>
                    <span class="ml-2 text-sm text-gray-500 max-sm:hidden">{{ __('setup.account') }}</span>
                </div>
            </div>
        </div>

        <!-- Business Type Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            @foreach($businessTypes as $type)
                <a href="{{ route('setup.details', ['type' => $type['key']]) }}"
                   class="group relative bg-white rounded-2xl shadow-sm border-2 border-transparent hover:shadow-xl transition-all duration-300 overflow-hidden"
                   style="--hover-border-color: {{ $type['theme']['primary_hex'] }};"
                   onmouseover="this.style.borderColor=this.style.getPropertyValue('--hover-border-color')"
                   onmouseout="this.style.borderColor='transparent'">

                    <!-- Gradient Header -->
                    <div class="h-24 bg-gradient-to-r {{ $type['theme']['gradient_from'] }} {{ $type['theme']['gradient_to'] }} flex items-center justify-center">
                        @if($type['icon'] === 'sparkles')
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        @elseif($type['icon'] === 'scissors')
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                            </svg>
                        @elseif($type['icon'] === 'dumbbell')
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6v12m10-12v12M4 9h16M4 15h16M2 10v4m20-4v4" />
                            </svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                            {{ app()->getLocale() === 'en' ? $type['name_en'] : $type['name'] }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ app()->getLocale() === 'en' ? $type['description_en'] : $type['description'] }}
                        </p>

                        <!-- Arrow indicator -->
                        <div class="mt-4 flex justify-center">
                            <span class="text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Footer text -->
        <p class="text-center text-sm text-gray-500 mt-8">
            {{ __('setup.can_change_later') }}
        </p>
    </div>
</div>
@endsection
