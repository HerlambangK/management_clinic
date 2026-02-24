@extends('layouts.dashboard')

@section('title', __('staff.title'))
@section('page-title', __('staff.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('staff.subtitle') }}</p>
        <a href="{{ route('staff.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('staff.add') }}
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Desktop Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('staff.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('staff.email') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('staff.phone') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('staff.role') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($staff as $user)
                        @php
                            $roleColors = [
                                'owner' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400',
                                'admin' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                'beautician' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-400',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $tc->gradient }} rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $user->phone ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }} capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400">
                                        {{ __('common.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        {{ __('common.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('staff.edit', $user) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                                        {{ __('common.edit') }}
                                    </a>
                                    <form action="{{ route('staff.reset-password', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('staff.reset_password_confirm') }}')">
                                        @csrf
                                        <button type="submit" class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 text-sm font-medium">
                                            {{ __('common.reset') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('staff.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('staff.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium">
                                            {{ __('common.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('staff.no_staff') }}</p>
                                <a href="{{ route('staff.create') }}" class="mt-3 inline-flex items-center text-sm {{ $tc->link }} font-medium">
                                    {{ __('staff.add_first') }}
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

        <!-- Mobile Cards -->
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($staff as $user)
                @php
                    $roleColors = [
                        'owner' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400',
                        'admin' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                        'beautician' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-400',
                    ];
                @endphp
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br {{ $tc->gradient }} rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</h3>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }} capitalize">
                                        {{ $user->role }}
                                    </span>
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400">
                                            {{ __('common.active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ __('common.inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-2">
                                <a href="{{ route('staff.edit', $user) }}" class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                                    {{ __('common.edit') }}
                                </a>
                                <form action="{{ route('staff.reset-password', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('staff.reset_password_confirm') }}')">
                                    @csrf
                                    <button type="submit" class="text-orange-600 dark:text-orange-400 text-xs font-medium">
                                        {{ __('common.reset') }}
                                    </button>
                                </form>
                                <form action="{{ route('staff.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('staff.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 text-xs font-medium">
                                        {{ __('common.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('staff.no_staff') }}</p>
                    <a href="{{ route('staff.create') }}" class="mt-3 inline-flex items-center text-xs {{ $tc->link }} font-medium">
                        {{ __('staff.add_first') }}
                        <svg class="ml-1 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>

        @if($staff->hasPages())
            <div class="px-6 py-4 max-sm:px-4 max-sm:py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $staff->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
