@php
    // Theme-based classes
    $logoGradient = 'from-primary-400 to-primary-500 shadow-primary-200/50';
    $activeClass = 'bg-primary-50 text-primary-600';
@endphp
<!-- Sidebar -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:static lg:inset-0"
>
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                @if(brand_logo())
                    <img src="{{ brand_logo() }}" alt="{{ brand_name() }}" class="h-8 w-auto">
                @else
                    <div class="w-8 h-8 bg-gradient-to-br {{ $logoGradient }} rounded-lg flex items-center justify-center shadow-lg">
                        @if($businessType === 'gym')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6v12m10-12v12M4 9h16M4 15h16M2 10v4m20-4v4" />
                            </svg>
                        @elseif($businessType === 'barbershop')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                            </svg>
                        @elseif($businessType === 'salon')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        @endif
                    </div>
                @endif
                @if(!brand_logo() || brand('logo.show_text', true))
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ brand_name() }}</span>
                @endif
            </a>
            <button @click="sidebarOpen = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 max-lg:block hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            @php
                // Build nav items based on enabled features
                $navItems = [
                    ['name' => __('menu.dashboard'), 'route' => 'dashboard', 'icon' => 'home', 'dividerAfter' => true],
                    ['name' => __('menu.appointments'), 'route' => 'appointments.index', 'icon' => 'calendar'],
                    ['name' => __('menu.customers'), 'route' => 'customers.index', 'icon' => 'users'],
                ];

                // Member Attendance - conditional
                if (has_feature('attendance')) {
                    $navItems[] = ['name' => __('menu.attendance'), 'route' => 'member-attendance.index', 'icon' => 'qrcode'];
                }

                // Treatment Records - only for clinic
                if (has_feature('treatment_records')) {
                    $navItems[] = ['name' => __('menu.treatment_records'), 'route' => 'treatment-records.index', 'icon' => 'document-text'];
                }

                // Service categories & services - always visible
                $navItems[] = ['name' => __('menu.service_categories'), 'route' => 'service-categories.index', 'icon' => 'collection'];
                $navItems[] = ['name' => __('menu.services'), 'route' => 'services.index', 'icon' => 'sparkles'];

                // Products - conditional
                if (has_feature('products')) {
                    $navItems[] = ['name' => __('menu.products'), 'route' => 'products.index', 'icon' => 'cube'];
                }

                // Packages - conditional
                if (has_feature('packages')) {
                    $navItems[] = ['name' => __('menu.packages'), 'route' => 'packages.index', 'icon' => 'gift'];
                }

                // Customer Packages - conditional
                if (has_feature('customer_packages')) {
                    $navItems[] = ['name' => __('menu.customer_packages'), 'route' => 'customer-packages.index', 'icon' => 'shopping-bag'];
                }

                // Add divider before transactions
                $navItems[count($navItems) - 1]['dividerAfter'] = true;

                // Transactions - always visible
                $navItems[] = ['name' => __('menu.transactions'), 'route' => 'transactions.index', 'icon' => 'credit-card'];

                // Loyalty - conditional
                if (has_feature('loyalty')) {
                    $navItems[] = ['name' => __('menu.loyalty'), 'route' => 'loyalty.index', 'icon' => 'star'];
                }

                // Reports - always visible
                $navItems[] = ['name' => __('menu.reports'), 'route' => 'reports.index', 'icon' => 'chart-bar'];

                $ownerItems = [
                    ['name' => __('menu.import_data'), 'route' => 'imports.index', 'icon' => 'upload'],
                    ['name' => __('menu.staff'), 'route' => 'staff.index', 'icon' => 'user-group'],
                    ['name' => __('menu.settings'), 'route' => 'settings.index', 'icon' => 'cog'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*') || request()->routeIs(str_replace('.index', '.*', $item['route']));
                @endphp
                <a
                    href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ $isActive ? $activeClass : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}"
                >
                    @include('components.icons.' . $item['icon'])
                    {{ $item['name'] }}
                </a>
                @if(isset($item['dividerAfter']) && $item['dividerAfter'])
                    <div class="border-t border-gray-100 dark:border-gray-700 my-3"></div>
                @endif
            @endforeach

            @if(auth()->user()->hasRole(['owner', 'admin']))
                <div class="border-t border-gray-100 dark:border-gray-700 my-3"></div>
                @foreach($ownerItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*') || request()->routeIs(str_replace('.index', '.*', $item['route']));
                    @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ $isActive ? $activeClass : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        @include('components.icons.' . $item['icon'])
                        {{ $item['name'] }}
                    </a>
                @endforeach
            @endif
        </nav>

    </div>
</aside>
