@extends('layouts.app')

@section('page-title', 'Reports')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Monthly Report</h2>
                <p class="text-sm text-gray-500">{{ $date->format('F Y') }}</p>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0 md:ml-4">
                <form method="GET" action="{{ route('reports.index') }}">
                    <select name="month" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @for($i = 11; $i >= 0; $i--)
                        @php $monthDate = \Carbon\Carbon::now()->subMonths($i) @endphp
                        <option value="{{ $monthDate->format('Y-m') }}" {{ $month === $monthDate->format('Y-m') ? 'selected' : '' }}>
                            {{ $monthDate->format('F Y') }}
                        </option>
                        @endfor
                    </select>
                </form>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Revenue -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6 card-hover">
                <dt>
                    <div class="absolute rounded-md bg-green-500 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Total Revenue</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-green-600">@currency($revenue)</p>
                </dd>
            </div>

            <!-- Expenses -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6 card-hover">
                <dt>
                    <div class="absolute rounded-md bg-red-500 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Total Expenses</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-red-600">@currency($expenses)</p>
                </dd>
            </div>

            <!-- Net Profit -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6 card-hover">
                <dt>
                    <div class="absolute rounded-md {{ $netProfit >= 0 ? 'bg-green-500' : 'bg-red-500' }} p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 00-2 2h-2a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Net Profit</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">@currency($netProfit)</p>
                </dd>
            </div>

            <!-- New Members -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6 card-hover">
                <dt>
                    <div class="absolute rounded-md bg-indigo-500 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-gray-500">New Members</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-indigo-600">{{ $newMembers }}</p>
                </dd>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Daily Revenue Chart -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Daily Revenue</h3>
                    <div class="mt-4">
                        <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Revenue by Type -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Revenue by Type</h3>
                    <div class="mt-4">
                        @if($revenueByType->count() > 0)
                        <canvas id="revenueTypeChart" width="400" height="200"></canvas>
                        @else
                        <p class="text-gray-500 text-center py-8">No revenue data available for this month</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Expenses by Category -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Expenses by Category</h3>
                    <div class="mt-4">
                        @if($expensesByCategory->count() > 0)
                        <canvas id="expensesCategoryChart" width="400" height="200"></canvas>
                        @else
                        <p class="text-gray-500 text-center py-8">No expense data available for this month</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Methods</h3>
                    <div class="mt-4">
                        @if($paymentMethods->count() > 0)
                        <div class="space-y-3">
                            @foreach($paymentMethods as $method)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $method->payment_method)) }}</span>
                                <span class="text-sm text-gray-900">@currency($method->total)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $revenue > 0 ? ($method->total / $revenue) * 100 : 0 }}%"></div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-8">No payment data available for this month</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-8">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detailed Reports</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('reports.members') }}" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">Member Report</p>
                        <p class="text-sm text-gray-500 truncate">Detailed member analysis</p>
                    </div>
                </a>

                <a href="{{ route('reports.payments') }}" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">Payment Report</p>
                        <p class="text-sm text-gray-500 truncate">Payment transactions analysis</p>
                    </div>
                </a>

                <a href="{{ route('reports.expenses') }}" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">Expense Report</p>
                        <p class="text-sm text-gray-500 truncate">Business expenses breakdown</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Daily Revenue Chart
    @if($dailyRevenue->count() > 0)
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    const daysInMonth = {{ $date->daysInMonth }};
    const dailyData = @json($dailyRevenue);
    
    // Create array with all days of the month
    const dailyLabels = [];
    const dailyValues = [];
    
    for (let i = 1; i <= daysInMonth; i++) {
        dailyLabels.push(i.toString());
        const dayData = dailyData.find(d => d.day == i);
        dailyValues.push(dayData ? parseFloat(dayData.total) : 0);
    }
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Revenue',
                data: dailyValues,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'PKR ' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
    @endif

    // Revenue by Type Chart
    @if($revenueByType->count() > 0)
    const revenueTypeCtx = document.getElementById('revenueTypeChart').getContext('2d');
    new Chart(revenueTypeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($revenueByType->pluck('payment_type')->map(fn($type) => ucwords(str_replace('_', ' ', $type)))),
            datasets: [{
                data: @json($revenueByType->pluck('total')),
                backgroundColor: [
                    '#3b82f6',
                    '#06b6d4',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    // Expenses by Category Chart
    @if($expensesByCategory->count() > 0)
    const expensesCategoryCtx = document.getElementById('expensesCategoryChart').getContext('2d');
    new Chart(expensesCategoryCtx, {
        type: 'bar',
        data: {
            labels: @json($expensesByCategory->pluck('category')->map(fn($cat) => ucwords(str_replace('_', ' ', $cat)))),
            datasets: [{
                label: 'Amount',
                data: @json($expensesByCategory->pluck('total')),
                backgroundColor: 'rgba(239, 68, 68, 0.5)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'PKR ' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
    @endif
</script>
@endsection
