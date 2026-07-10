@extends('Content::layouts.pages')

@push('head')
    <title>{{ __('Module Dashboard') }} | {{ config('app.name') }}</title>
    <meta name="description" content="User dashboard example for module base template">
    @module_vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts Inter -->
    <link nonce="{{ $nonce }}"
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
@endpush

@push('css')
    <style>
        /* Custom scrollbar & transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .card-hover {
            transition: all 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .loading-spinner {
            border-top-color: #3b82f6;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Hide scrollbar for clean look (optional) */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Dark mode custom overrides */
        .dark .card-hover:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        .dark .bg-gray-100 {
            background-color: #111827 !important;
        }

        .dark .bg-white {
            background-color: #1f2937 !important;
        }

        .dark .border-gray-100,
        .dark .border-gray-200 {
            border-color: #374151 !important;
        }

        .dark .text-gray-400 {
            color: #9ca3af !important;
        }

        .dark .text-gray-500 {
            color: #9ca3af !important;
        }

        .dark .text-gray-600 {
            color: #d1d5db !important;
        }

        .dark .text-gray-700 {
            color: #e5e7eb !important;
        }

        .dark .text-gray-800 {
            color: #f3f4f6 !important;
        }

        .dark .bg-gray-100\/50 {
            background-color: rgba(31, 41, 55, 0.5) !important;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Dashboard wrapper with sidebar + main content (responsive layout) -->
        <div class="flex h-screen overflow-hidden">

            <!-- SIDEBAR - collapsible example -->
            <aside id="sidebar"
                class="sidebar-transition fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-sm transform -translate-x-full md:relative md:translate-x-0 md:flex md:flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center shadow-sm">
                            <i class="fas fa-cube text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-800 dark:text-white text-lg">{{ __('ModuleBase') }}</span>
                    </div>

                    <button id="closeSidebarBtn"
                        class="md:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition">
                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                        <span>{{ __('Analytics') }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        <span>{{ __('Users') }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition">
                        <i class="fas fa-folder-open w-5 h-5 mr-3"></i>
                        <span>{{ __('Projects') }}</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-circle text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ __('John Doe') }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ __('admin@example.com') }}</p>
                        </div>
                        <button id="logoutBtnPlaceholder" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top navbar -->
                <header
                    class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700 sticky top-0 z-20">
                    <div class="flex items-center justify-between px-6 py-3 md:px-8">
                        <button id="openSidebarBtn"
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none md:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <div class="flex items-center space-x-4 ml-auto">
                            <div class="relative">
                                <button id="notificationBtn"
                                    class="relative text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <i class="fas fa-bell text-xl"></i>
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">3</span>
                                </button>
                                <!-- Simple notification dropdown (hidden by default) -->
                                <div id="notificationDropdown"
                                    class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ __('Notifications') }}</p>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        <div
                                            class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-300">
                                            📢
                                            {{ __('Welcome to your new module!') }}</div>
                                        <div
                                            class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-300">
                                            ✅
                                            {{ __('Setup completed successfully') }}</div>
                                        <div
                                            class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-300">
                                            🎉 {{ __('Example notification') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>
                            <x-theme />
                            <x-profile />
                        </div>
                    </div>
                </header>

                <!-- Page content with padding & animations -->
                <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-900 p-4 md:p-6 lg:p-8">
                    <!-- Welcome banner - slide up animation -->
                    <div class="mb-6 animate-slide-up">
                        <div
                            class="bg-linear-to-r from-primary-600 to-primary-800 rounded-xl shadow-md p-5  dark:text-white">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <div>
                                    <h1 class="text-2xl font-bold">{{ __('Dashboard Overview') }}</h1>
                                    <p class="text-primary-100 mt-1">
                                        {{ __('Welcome back! Here\'s what\'s happening with your module today.') }}</p>
                                </div>
                                <div class="mt-3 md:mt-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ now()->format('F j, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 card-hover animate-fade-in">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">{{ __('Total Users') }}</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1" id="totalUsersCount">
                                        1,284</p>
                                    <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up"></i> +12%</p>
                                </div>
                                <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg">
                                    <i class="fas fa-users text-primary-600 dark:text-primary-400 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 card-hover animate-fade-in"
                            style="animation-delay: 0.05s">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">{{ __('Revenue') }}</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">$12,450</p>
                                    <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up"></i> +8%</p>
                                </div>
                                <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-lg">
                                    <i class="fas fa-dollar-sign text-emerald-600 dark:text-emerald-400 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 card-hover animate-fade-in"
                            style="animation-delay: 0.1s">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">{{ __('Active Projects') }}</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1"
                                        id="activeProjectsCount">24</p>
                                    <p class="text-xs text-amber-500 mt-2"><i class="fas fa-chart-line"></i> +3 this week
                                    </p>
                                </div>
                                <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg">
                                    <i class="fas fa-project-diagram text-amber-600 dark:text-amber-400 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 card-hover animate-fade-in"
                            style="animation-delay: 0.15s">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">{{ __('Completion Rate') }}</p>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">87%</p>
                                    <p class="text-xs text-green-500 mt-2"><i class="fas fa-check-circle"></i> +5%</p>
                                </div>
                                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                                    <i class="fas fa-chart-pie text-purple-600 dark:text-purple-400 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats & Recent Activity Row (Two columns) -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Stats Overview Card (replaces chart) -->
                        <div
                            class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 animate-slide-up">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">{{ __('Statistics Overview') }}
                                </h3>
                                <div class="flex space-x-2">
                                    <button
                                        class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 stat-period-btn"
                                        data-period="week">{{ __('Week') }}</button>
                                    <button
                                        class="text-xs px-2 py-1 rounded bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400 stat-period-btn"
                                        data-period="month">{{ __('Month') }}</button>
                                    <button
                                        class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 stat-period-btn"
                                        data-period="year">{{ __('Year') }}</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="statsOverview">
                                <!-- Dynamic stats content via jQuery -->
                                <div class="text-center p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400"
                                        id="statVisitors">0</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Visitors') }}</div>
                                </div>
                                <div class="text-center p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400" id="statSales">
                                        0</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Sales') }}</div>
                                </div>
                                <div class="text-center p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400" id="statTasks">0
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Tasks Completed') }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Average Session') }}:</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300" id="avgSession">--</span>
                                </div>
                                <div class="flex justify-between items-center text-sm mt-2">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Bounce Rate') }}:</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300" id="bounceRate">--</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent activities list -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 animate-slide-up"
                            style="animation-delay: 0.1s">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-gray-800 dark:text-white">{{ __('Recent Activity') }}</h3>
                                <button id="refreshActivityBtn"
                                    class="text-primary-500 dark:text-primary-400 text-sm hover:text-primary-700 dark:hover:text-primary-300">
                                    <i class="fas fa-sync-alt"></i> {{ __('Refresh') }}
                                </button>
                            </div>
                            <div id="activityFeed" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                                <!-- Dynamic content loaded via jQuery -->
                                <div class="flex items-start space-x-3 text-sm">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center shrink-0">
                                        <i class="fas fa-user-plus text-blue-600 dark:text-blue-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300"><span
                                                class="font-medium">{{ __('New user') }}</span> {{ __('registered') }}
                                        </p>
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">2
                                            {{ __('minutes ago') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 text-sm">
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center shrink-0">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300">{{ __('Project') }} <span
                                                class="font-medium">"Dashboard UI"</span>
                                            {{ __('completed') }}</p>
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">1
                                            {{ __('hour ago') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 text-sm">
                                    <div
                                        class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center shrink-0">
                                        <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300">{{ __('Analytics report generated') }}
                                        </p>
                                        <span
                                            class="text-gray-400 dark:text-gray-500 text-xs">{{ __('Yesterday at 10:23 AM') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions / Module Setup Info -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6 animate-fade-in">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white flex items-center"><i
                                        class="fas fa-rocket text-primary-500 mr-2"></i> {{ __('Module Quick Setup') }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('This is your base module template. Configure your first entities, permissions, or data sources.') }}
                                </p>
                            </div>
                            <div class="mt-3 md:mt-0">
                                <button id="setupWizardBtn"
                                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                    <i class="fas fa-magic mr-2"></i> {{ __('Run Setup Wizard') }}
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div
                                class="border border-gray-100 dark:border-gray-700 rounded-lg p-3 flex items-center space-x-2 bg-gray-100 dark:bg-gray-700/50">
                                <i class="fas fa-database text-gray-400 dark:text-gray-500"></i>
                                <span class="text-gray-600 dark:text-gray-300">{{ __('Database seeded') }} <span
                                        class="font-mono text-green-600 dark:text-green-400">✔️</span></span>
                            </div>
                            <div
                                class="border border-gray-100 dark:border-gray-700 rounded-lg p-3 flex items-center space-x-2 bg-gray-100 dark:bg-gray-700/50">
                                <i class="fas fa-shield-alt text-gray-400 dark:text-gray-500"></i>
                                <span class="text-gray-600 dark:text-gray-300">{{ __('Permissions configured') }} <span
                                        class="font-mono text-yellow-500 dark:text-yellow-400">⚠️ pending</span></span>
                            </div>
                            <div
                                class="border border-gray-100 dark:border-gray-700 rounded-lg p-3 flex items-center space-x-2 bg-gray-100 dark:bg-gray-700/50">
                                <i class="fas fa-envelope text-gray-400 dark:text-gray-500"></i>
                                <span class="text-gray-600 dark:text-gray-300">{{ __('Mail notifications') }} <span
                                        class="font-mono text-green-600 dark:text-green-400">✔️</span></span>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        (function($) {
            "use strict";

            // Sidebar toggles for mobile
            $('#openSidebarBtn').on('click', function() {
                $('#sidebar').removeClass('-translate-x-full');
            });
            $('#closeSidebarBtn').on('click', function() {
                $('#sidebar').addClass('-translate-x-full');
            });

            // Notification dropdown toggle
            $('#notificationBtn').on('click', function(e) {
                e.stopPropagation();
                $('#notificationDropdown').toggleClass('hidden');
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#notificationBtn').length && !$(e.target).closest(
                        '#notificationDropdown').length) {
                    $('#notificationDropdown').addClass('hidden');
                }
            });

            // Refresh activity feed simulation (AJAX example)
            $('#refreshActivityBtn').on('click', function() {
                const $feed = $('#activityFeed');
                $feed.html(
                    '<div class="text-center py-4"><div class="loading-spinner w-6 h-6 border-2 border-gray-200 dark:border-gray-600 border-t-primary-500 rounded-full mx-auto"></div><p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Loading activities...</p></div>'
                );

                // Simulate AJAX call to fetch latest activities
                setTimeout(function() {
                    const activities = [{
                            icon: 'fa-user-plus',
                            color: 'blue',
                            text: 'New user "Sarah" registered',
                            time: 'Just now'
                        },
                        {
                            icon: 'fa-file-alt',
                            color: 'indigo',
                            text: 'Document "ModuleGuide.pdf" uploaded',
                            time: '3 minutes ago'
                        },
                        {
                            icon: 'fa-check-circle',
                            color: 'green',
                            text: 'Task "Review code" completed',
                            time: '15 minutes ago'
                        },
                        {
                            icon: 'fa-chart-line',
                            color: 'purple',
                            text: 'Weekly report generated',
                            time: '1 hour ago'
                        },
                    ];
                    let html = '';
                    activities.forEach(act => {
                        html += `<div class="flex items-start space-x-3 text-sm">
                                    <div class="w-8 h-8 rounded-full bg-${act.color}-100 dark:bg-${act.color}-900/50 flex items-center justify-center shrink-0">
                                        <i class="fas ${act.icon} text-${act.color}-600 dark:text-${act.color}-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300">${act.text}</p>
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">${act.time}</span>
                                    </div>
                                </div>`;
                    });
                    $feed.html(html);
                }, 800);
            });

            // Setup Wizard simulation
            $('#setupWizardBtn').on('click', function() {
                alert(
                    '🚀 Setup wizard would start here. This is a demo interaction for your module base template.'
                );
            });

            // Statistics Overview - dynamic data simulation
            let currentStatsPeriod = 'month';

            function updateStatistics(period) {
                let visitors, sales, tasks, avgSession, bounceRate;

                if (period === 'week') {
                    visitors = '2,340';
                    sales = '184';
                    tasks = '47';
                    avgSession = '4m 32s';
                    bounceRate = '34%';
                } else if (period === 'month') {
                    visitors = '9,482';
                    sales = '756';
                    tasks = '203';
                    avgSession = '5m 12s';
                    bounceRate = '28%';
                } else {
                    visitors = '112,450';
                    sales = '8,234';
                    tasks = '2,847';
                    avgSession = '5m 48s';
                    bounceRate = '26%';
                }

                $('#statVisitors').text(visitors);
                $('#statSales').text(sales);
                $('#statTasks').text(tasks);
                $('#avgSession').text(avgSession);
                $('#bounceRate').text(bounceRate);
            }

            $('.stat-period-btn').on('click', function() {
                $('.stat-period-btn').removeClass(
                        'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400')
                    .addClass('bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300');
                $(this).removeClass('bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300')
                    .addClass('bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400');
                let period = $(this).data('period');
                currentStatsPeriod = period;
                updateStatistics(period);
            });

            // Initialize stats
            updateStatistics('month');

            // Example of dynamic stats update (simulating real-time or API)
            function updateStatsCards() {
                const newUsers = Math.floor(1200 + Math.random() * 150);
                const newProjects = Math.floor(20 + Math.random() * 10);
                $('#totalUsersCount').text(newUsers.toLocaleString());
                $('#activeProjectsCount').text(newProjects);
            }
            // Uncomment if you want periodic update: setInterval(updateStatsCards, 30000);

            // Also close sidebar on window resize if needed (optional)
            $(window).on('resize', function() {
                if ($(window).width() >= 768) {
                    $('#sidebar').removeClass('-translate-x-full');
                }
            });
        });
    </script>
@endpush
