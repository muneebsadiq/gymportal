<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Gym Management') - {{ config('app.name', 'GymApp') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/gym-xpert-logo-black.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      /* Fallback spacing for nav links */
      .main-nav > a { margin-right: 1rem; }
      .main-nav > a:last-child { margin-right: 0; }
      .main-nav > a:first-child { margin-left: 1rem; }

    </style>
</head>
<body class="font-sans bg-gray-50 antialiased">
    <div id="app" class="min-h-screen">
        <!-- Top Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Primary Nav -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <img src="/gym-xpert-logo-black.png" alt="Gym Xpert" class="h-8 w-auto">
                            </a>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden sm:flex sm:items-center sm:ml-8 main-nav">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} flex items-center px-1 pt-1 border-b-2 text-sm font-medium mr-8 last:mr-0">
                                Dashboard
                            </a>
                            <a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} flex items-center px-1 pt-1 border-b-2 text-sm font-medium mr-8 last:mr-0">
                                Members
                            </a>
                            <a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} flex items-center px-1 pt-1 border-b-2 text-sm font-medium mr-8 last:mr-0">
                                Payments
                            </a>
                            <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} flex items-center px-1 pt-1 border-b-2 text-sm font-medium mr-8 last:mr-0">
                                Expenses
                            </a>
                            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} flex items-center px-1 pt-1 border-b-2 text-sm font-medium mr-8 last:mr-0">
                                Reports
                            </a>
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <a href="{{ route('membership_plans.index') }}" class="{{ request()->routeIs('membership_plans.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} flex items-center px-1 pt-1 border-b-2 text-sm font-medium mr-8 last:mr-0">
                                    Membership Plans
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Right Side -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- User Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = ! open" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <div class="font-medium">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden" x-data="{ open: false }">
                        <button @click="open = ! open" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="block h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="pb-10">
            @yield('content')
            @isset($slot)
                {{ $slot }}
            @endisset
        </main>
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
