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
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      /* Enhanced UI Styles */
      body {
        font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
      }
      
      /* Smooth transitions */
      * {
        transition: all 0.2s ease-in-out;
      }
      
      /* Card hover effects */
      .shadow:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
      }
      
      /* Button enhancements */
      .btn-primary, .btn-secondary, .btn-danger {
        transition: all 0.2s ease-in-out;
      }
      
      .btn-primary:hover, .btn-secondary:hover, .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      }
      
      /* Gradient backgrounds */
      .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      }
      
      /* Custom scrollbar */
      ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
      }
      
      ::-webkit-scrollbar-track {
        background: #f1f1f1;
      }
      
      ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
      }
      
      ::-webkit-scrollbar-thumb:hover {
        background: #555;
      }
      
      /* Table row hover */
      tbody tr:hover {
        background-color: #f9fafb;
        cursor: pointer;
      }
      
      /* Badge animations */
      .badge-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
      }
      
      @keyframes pulse {
        0%, 100% {
          opacity: 1;
        }
        50% {
          opacity: .8;
        }
      }
      
      /* Loading spinner */
      .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
      }
      
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      
      /* Success/Error message animations */
      .alert {
        animation: slideIn 0.3s ease-out;
      }
      
      @keyframes slideIn {
        from {
          transform: translateY(-20px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      /* Top Navbar & Left Sidebar Layout */
      .app-layout {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
      }

      .top-navbar {
        height: 64px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        z-index: 50;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
      }

      .navbar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
        padding: 0 1.5rem;
        max-width: 100%;
      }

      .content-area {
        display: flex;
        flex: 1;
        margin-top: 64px;
      }

      .left-sidebar {
        position: fixed;
        top: 64px;
        left: 0;
        width: 240px;
        height: calc(100vh - 64px);
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 4px 0 15px -3px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(148, 163, 184, 0.1);
        border-right: 1px solid #e2e8f0;
        z-index: 40;
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        overflow-y: auto;
      }

      .left-sidebar.open {
        transform: translateX(0);
      }

      .sidebar-content {
        padding: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
      }

      .sidebar-nav {
        flex: 1;
        padding: 0 1rem;
      }

      .sidebar-nav .sidebar-nav-item:first-child {
        margin-top: 0.5rem;
      }

      .sidebar-nav .sidebar-nav-item:last-child {
        margin-bottom: 1rem;
      }

      /* Navigation section dividers */
      .sidebar-nav::after {
        content: '';
        display: block;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(148, 163, 184, 0.3) 50%, transparent 100%);
        margin: 1.5rem 1rem 1rem;
      }

      /* Improve scrollbar styling for sidebar */
      .left-sidebar::-webkit-scrollbar {
        width: 4px;
      }

      .left-sidebar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
      }

      .left-sidebar::-webkit-scrollbar-thumb {
        background: rgba(102, 126, 234, 0.3);
        border-radius: 2px;
      }

      .left-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 126, 234, 0.5);
      }

      .sidebar-nav-item {
        display: flex;
        align-items: center;
        width: 100%;
        text-align: left;
        padding: 0.875rem 1rem;
        margin-bottom: 0.125rem;
        border-radius: 0.75rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        position: relative;
        overflow: hidden;
        color: #6b7280;
        text-decoration: none;
        cursor: pointer;
      }

      .sidebar-nav-item:hover {
        color: #374151;
      }

      .sidebar-nav-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scaleY(0);
        transition: transform 0.2s ease;
        border-radius: 0 2px 2px 0;
      }

      .sidebar-nav-item.active {
        background: rgba(102, 126, 234, 0.1);
        color: #4f46e5;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        font-weight: 600;
      }

      .sidebar-nav-item.active::before {
        transform: scaleY(1);
      }

      .sidebar-nav-item:hover:not(.active) {
        background: rgba(0, 0, 0, 0.05);
        color: #1f2937;
        transform: translateX(4px);
      }

      .main-content {
        flex: 1;
        margin-left: 240px;
        transition: margin-left 0.3s ease;
        padding: 0;
        min-height: calc(100vh - 64px);
      }

      .sidebar-toggle {
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 60;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
      }

      .sidebar-toggle:hover {
        background: #f9fafb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      }

      /* Responsive Breakpoints */
      @media (max-width: 1024px) {
        .main-content {
          margin-left: 0;
        }
        .left-sidebar {
          width: 300px;
        }
        .sidebar-toggle {
          display: block;
        }
        .navbar-logo-text {
          display: none;
        }
        .sidebar-nav-item {
          padding: 0.75rem;
          font-size: 0.9rem;
        }
        .sidebar-nav-item svg {
          width: 1.125rem;
          height: 1.125rem;
        }
      }

      @media (max-width: 768px) {
        .left-sidebar {
          width: 280px;
        }
        .sidebar-nav {
          padding: 0 0.5rem;
        }
        .sidebar-nav-item {
          padding: 1rem 0.75rem;
          font-size: 1rem;
        }
        .sidebar-nav-item svg {
          width: 1.25rem;
          height: 1.25rem;
        }
        .navbar-content {
          padding: 0 1rem;
        }
        .top-navbar {
          height: 60px;
        }
        .left-sidebar {
          top: 60px;
          height: calc(100vh - 60px);
        }
        .main-content {
          margin-top: 60px;
          min-height: calc(100vh - 60px);
        }
      }

      @media (max-width: 640px) {
        .left-sidebar {
          width: 100%;
          max-width: 320px;
        }
        .sidebar-nav-item {
          padding: 1.125rem 1rem;
          font-size: 1.1rem;
        }
        .sidebar-nav-item svg {
          width: 1.375rem;
          height: 1.375rem;
        }
        .navbar-content {
          padding: 0 0.75rem;
        }
        .top-navbar {
          height: 56px;
        }
        .left-sidebar {
          top: 56px;
          height: calc(100vh - 56px);
        }
        .main-content {
          margin-top: 56px;
          min-height: calc(100vh - 56px);
        }
        .sidebar-toggle {
          top: 0.75rem;
          left: 0.75rem;
        }
      }

      @media (min-width: 1024px) {
        .left-sidebar {
          transform: translateX(0);
        }
        .main-content {
          margin-left: 240px;
        }
        .sidebar-toggle {
          display: none;
        }
      }

      @media (min-width: 1280px) {
        .left-sidebar {
          width: 260px;
        }
        .main-content {
          margin-left: 260px;
        }
      }

      /* Content responsiveness for all screens */
      .main-content > * {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      @media (min-width: 640px) {
        .main-content > * {
          padding-left: 1.5rem;
          padding-right: 1.5rem;
        }
      }

      @media (min-width: 768px) {
        .main-content > * {
          padding-left: 2rem;
          padding-right: 2rem;
        }
      }

      @media (min-width: 1024px) {
        .main-content > * {
          padding-left: 2.5rem;
          padding-right: 2.5rem;
        }
      }

      @media (min-width: 1280px) {
        .main-content > * {
          padding-left: 3rem;
          padding-right: 3rem;
        }
      }

      /* Ensure tables and forms are responsive */
      .main-content table {
        width: 100%;
        overflow-x: auto;
        display: block;
        white-space: nowrap;
      }

      .main-content table thead,
      .main-content table tbody {
        display: table-header-group;
      }

      @media (max-width: 768px) {
        .main-content table {
          font-size: 0.875rem;
        }
        .main-content .btn {
          padding: 0.5rem 1rem;
          font-size: 0.875rem;
        }
      }

      /* Card responsiveness */
      .main-content .card,
      .main-content .shadow {
        margin-bottom: 1rem;
      }

      @media (max-width: 640px) {
        .main-content .card,
        .main-content .shadow {
          margin-left: -0.5rem;
          margin-right: -0.5rem;
          border-radius: 0;
        }
      }
    </style>
</head>
<body class="font-sans bg-gray-50 antialiased">
    <div id="app" class="app-layout">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle lg:hidden" x-data="{ open: false }" @click="$dispatch('toggle-sidebar')" aria-label="Toggle sidebar">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-content">
                <!-- Logo Section -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        @if($globalSettings->logo)
                            <img src="{{ asset('storage/' . $globalSettings->logo) }}" alt="{{ $globalSettings->gym_name }}" class="w-10 h-10 rounded-lg shadow-lg object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg" style="display: none;">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent navbar-logo-text">{{ $globalSettings->gym_name }}</span>
                    </a>
                </div>

                <!-- User Dropdown -->
                <div class="flex items-center" x-data="{ open: false }">
                    <div class="ml-3 relative">
                        <button @click="open = ! open" class="flex items-center space-x-3 bg-gray-50 hover:bg-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 border border-gray-200">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-sm">
                                <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="hidden md:flex flex-col items-start">
                                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-64 rounded-lg shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-sm">
                                        <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                                        @if(auth()->user()->role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mt-1">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('settings.index') }}" class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>
                            <div class="border-t border-gray-100"></div>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="content-area">
            <!-- Left Sidebar -->
            <nav class="left-sidebar" x-data="{ open: false }" @toggle-sidebar.window="open = !open" :class="{ 'open': open }">
                <div class="sidebar-content">
                    <!-- Sidebar Header -->
                    <div class="px-4 py-3 border-b border-gray-200/50">
                        <div class="flex items-center space-x-3">
                            @if($globalSettings->logo)
                                <img src="{{ asset('storage/' . $globalSettings->logo) }}" alt="{{ $globalSettings->gym_name }}" class="w-8 h-8 rounded-lg shadow-sm object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-sm" style="display: none;">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900">{{ $globalSettings->gym_name }}</h2>
                                <p class="text-xs text-gray-500">Management Portal</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="sidebar-nav">
                        <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="flex-1">Dashboard</span>
                        </a>
                        <a href="{{ route('members.index') }}" class="sidebar-nav-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="flex-1">Members</span>
                        </a>
                        <a href="{{ route('payments.index') }}" class="sidebar-nav-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="flex-1">Payments</span>
                        </a>
                        <a href="{{ route('coaches.index') }}" class="sidebar-nav-item {{ request()->routeIs('coaches.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="flex-1">Coaches</span>
                        </a>
                        <a href="{{ route('expenses.index') }}" class="sidebar-nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="flex-1">Expenses</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="flex-1">Reports</span>
                        </a>
                        @if(auth()->user() && auth()->user()->role === 'admin')
                            <a href="{{ route('membership_plans.index') }}" class="sidebar-nav-item {{ request()->routeIs('membership_plans.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span class="flex-1">Plans</span>
                            </a>
                        @endif
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="main-content pb-10">
                <!-- Success/Error Messages -->
                @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="alert bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-green-400 hover:text-green-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="alert bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-red-400 hover:text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>
        </div>
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
