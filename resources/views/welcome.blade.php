<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="landingPage()" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gym-Portal - Manage Your Gym Efficiently</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/gym-xpert-logo-black.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in { animation: fadeIn 1s ease-in-out; }
        .animate-slide-up { animation: slideUp 0.8s ease-out; }
        .animate-bounce-in { animation: bounceIn 1.2s ease-out; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-bg {
            background: radial-gradient(ellipse at center, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-white shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#home" class="flex items-center">
                        <img src="/gym-xpert-logo-black.png" alt="Gym Xpert" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium">Home</a>
                    <a href="#about" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium">About</a>
                    <a href="#features" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium">Features</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium">Contact</a>

                    <div class="flex items-center space-x-4 ml-8">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">Register</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium">Dashboard</a>
                        @endguest
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="md:hidden absolute top-16 left-0 right-0 bg-white shadow-lg border-t" x-cloak>
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="#home" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">Home</a>
                    <a href="#about" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">About</a>
                    <a href="#features" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">Features</a>
                    <a href="#contact" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">Contact</a>

                    @guest
                        <div class="border-t pt-3 mt-3">
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center">Register</a>
                        </div>
                    @else
                        <div class="border-t pt-3 mt-3">
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">Dashboard</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-bg min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center animate-fade-in">
            <div class="animate-bounce-in mb-8">
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                    Welcome to <span class="text-gradient">Gym-Portal</span>
                </h1>
                <p class="text-xl sm:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Streamline your gym management with our comprehensive platform. Manage members, track payments, monitor expenses, and grow your business effortlessly.
                </p>
            </div>

            <div class="animate-slide-up flex flex-col sm:flex-row gap-4 justify-center items-center">
                @guest
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        Get Started Free
                    </a>
                    <a href="#about" class="border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-600 hover:text-white transition-all duration-300">
                        Learn More
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        Go to Dashboard
                    </a>
                @endguest
            </div>

            <!-- Animated Stats -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8 animate-slide-up">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2" x-data="{ count: 0 }" x-init="setInterval(() => { if (count < 1000) count += 10 }, 50)">1000+</div>
                    <p class="text-gray-600">Active Members</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2" x-data="{ count: 0 }" x-init="setInterval(() => { if (count < 50) count += 1 }, 100)">50+</div>
                    <p class="text-gray-600">Gym Partners</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2" x-data="{ count: 0 }" x-init="setInterval(() => { if (count < 99) count += 1 }, 50)">99%</div>
                    <p class="text-gray-600">Uptime</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600 mb-2" x-data="{ count: 0 }" x-init="setInterval(() => { if (count < 24) count += 1 }, 200)">24/7</div>
                    <p class="text-gray-600">Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">About Gym-Portal</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Gym-Portal is a comprehensive gym management system designed to simplify operations and enhance member experiences.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-slide-up">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Why Choose Gym-Portal?</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Complete member management with detailed profiles and membership tracking</p>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Automated payment processing and financial reporting</p>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Expense tracking and budget management tools</p>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Comprehensive reporting and analytics dashboard</p>
                        </li>
                    </ul>
                </div>

                <div class="animate-slide-up">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-8 text-white">
                        <h3 class="text-2xl font-semibold mb-4">Built for Gym Owners</h3>
                        <p class="mb-6">
                            Whether you're running a small fitness studio or a large gym chain, Gym-Portal adapts to your needs and scales with your business.
                        </p>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold">50+</div>
                                <div class="text-sm opacity-90">Features</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">100%</div>
                                <div class="text-sm opacity-90">Cloud-Based</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to run your gym efficiently and grow your business.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-lg p-6 animate-slide-up hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Member Management</h3>
                    <p class="text-gray-600">Complete member profiles, membership plans, and attendance tracking in one place.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 animate-slide-up hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Payment Processing</h3>
                    <p class="text-gray-600">Secure payment processing, automated invoicing, and payment history tracking.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 animate-slide-up hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                    <p class="text-gray-600">Detailed insights into your gym's performance with customizable reports.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 animate-slide-up hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Expense Tracking</h3>
                    <p class="text-gray-600">Monitor and categorize all gym expenses to maintain financial health.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 animate-slide-up hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600">Enterprise-grade security with 99.9% uptime and 24/7 support.</p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 animate-slide-up hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Fast & Intuitive</h3>
                    <p class="text-gray-600">Modern interface designed for speed and ease of use across all devices.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Envision Xperts -->
    <section id="envision" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Developed by Envision Xperts</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Professional web development and digital solutions company specializing in innovative PHP-based solutions.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-slide-up">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Why Choose Envision Xperts?</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Years of experience in PHP development and digital solutions</p>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Fast delivery without compromising quality</p>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">Enterprise-grade security built into every solution</p>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700">24/7 technical support and maintenance</p>
                        </li>
                    </ul>
                    <div class="mt-8">
                        <a href="https://envisionxperts.com" target="_blank" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            Visit Envision Xperts
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="animate-slide-up">
                    <div class="bg-gradient-to-r from-purple-500 to-blue-600 rounded-lg p-8 text-white">
                        <h3 class="text-2xl font-semibold mb-4">Our Expertise</h3>
                        <p class="mb-6">
                            We specialize in cutting-edge web development, mobile applications, and digital transformation solutions using PHP and modern technologies.
                        </p>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold">10+</div>
                                <div class="text-sm opacity-90">Years Experience</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">500+</div>
                                <div class="text-sm opacity-90">Projects Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Ready to transform your gym management? Contact us today!
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div class="animate-slide-up">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Send us a Message</h3>
                    <form x-data="contactForm()" @submit.prevent="submitForm" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" id="name" x-model="form.name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" x-model="form.email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" required>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="subject" x-model="form.subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" required>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" rows="5" x-model="form.message" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 resize-vertical" required></textarea>
                        </div>

                        <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">Send Message</span>
                            <span x-show="loading" x-text="loading ? 'Sending...' : 'Send Message'">Sending...</span>
                        </button>

                        <div x-show="success" x-transition class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            Thank you for your message! We'll get back to you soon.
                        </div>

                        <div x-show="error" x-transition class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <span x-text="error"></span>
                        </div>
                    </form>
                </div>

                <div class="animate-slide-up">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Contact Information</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">Email</h4>
                                <p class="text-gray-600">info@envisionxperts.com</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">Performance</h4>
                                <p class="text-gray-600">Optimized solutions that scale with your business</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">24/7 Support</h4>
                                <p class="text-gray-600">Round-the-clock technical support and maintenance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="/gym-xpert-logo-black.png" alt="Gym Xpert" class="h-8 w-auto brightness-0 invert">
                        <span class="font-bold text-xl text-white">Gym-Portal</span>
                    </div>
                    <p class="text-gray-400">
                        Comprehensive gym management system designed to streamline operations and enhance member experiences.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-400 hover:text-white transition-colors duration-200">Home</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white transition-colors duration-200">About</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors duration-200">Features</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors duration-200">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Member Management</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Payment Processing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Expense Tracking</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Reports & Analytics</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400">Email: info@envisionxperts.com</li>
                        <li class="text-gray-400">Website: envisionxperts.com</li>
                        <li class="text-gray-400">Support: 24/7 Available</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} Gym-Portal. Developed by <a href="https://envisionxperts.com" target="_blank" class="text-blue-400 hover:text-blue-300">Envision Xperts</a>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button x-data="{ show: false }" @scroll.window="show = (window.pageYOffset > 300)" x-show="show" x-transition class="fixed bottom-8 right-8 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors duration-200 z-50" @click="window.scrollTo({ top: 0, behavior: 'smooth' })">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        function landingPage() {
            return {
                init() {
                    // Smooth scrolling for anchor links
                    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                        anchor.addEventListener('click', function (e) {
                            e.preventDefault();
                            const target = document.querySelector(this.getAttribute('href'));
                            if (target) {
                                target.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        });
                    });
                }
            }
        }

        function contactForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    subject: '',
                    message: ''
                },
                loading: false,
                success: false,
                error: '',

                async submitForm() {
                    this.loading = true;
                    this.success = false;
                    this.error = '';

                    try {
                        // Simulate form submission
                        await new Promise(resolve => setTimeout(resolve, 2000));

                        // Here you would typically send the form data to your backend
                        console.log('Form submitted:', this.form);

                        this.success = true;
                        this.form = { name: '', email: '', subject: '', message: '' };
                    } catch (error) {
                        this.error = 'Failed to send message. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>