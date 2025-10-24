@extends('layouts.app')

@section('page-title', 'Expenses')

@section('content')
<div class="py-6 overflow-x-hidden">
    <div class="max-w-full sm:max-w-4xl md:max-w-5xl lg:max-w-6xl xl:max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Expenses</h2>
                <p class="mt-1 text-sm text-gray-500">Track and manage gym expenses</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('expenses.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Expense
                </a>
            </div>
        </div>

        <!-- Summary Card -->
        @if(isset($totalAmount) && $totalAmount > 0)
        <div class="mt-6 bg-white overflow-hidden shadow rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Expenses (Filtered Results)</dt>
                        <dd class="text-lg font-medium text-gray-900">@currency($totalAmount)</dd>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="mt-8 bg-white shadow-lg rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Filters</h3>
            <form method="GET" action="{{ route('expenses.index') }}" class="space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    <div class="sm:col-span-2 lg:col-span-2 xl:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Expenses</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4" placeholder="Title or vendor">
                    </div>
                    <div class="sm:col-span-1 lg:col-span-1 xl:col-span-1">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" id="category" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4">
                            <option value="">All Categories</option>
                            <option value="equipment" {{ request('category') === 'equipment' ? 'selected' : '' }}>Equipment</option>
                            <option value="maintenance" {{ request('category') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="utilities" {{ request('category') === 'utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="supplies" {{ request('category') === 'supplies' ? 'selected' : '' }}>Supplies</option>
                            <option value="marketing" {{ request('category') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="sm:col-span-1 lg:col-span-1 xl:col-span-1">
                        <label for="from_date" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4">
                    </div>
                    <div class="sm:col-span-1 lg:col-span-1 xl:col-span-1 flex items-end">
                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('expenses.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Expenses Table -->
        <div class="mt-6">
            <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
                @if($expenses->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed md:table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40 md:px-4 md:w-auto">Expense</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 md:px-4 md:w-auto">Amount</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 md:px-4 md:w-auto">Date</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 md:px-4 md:w-auto">Category</th>
                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 md:px-4 md:w-auto">Vendor</th>
                                    <th class="px-2 py-3 w-24 md:px-4 md:w-auto"/>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($expenses as $expense)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm font-medium text-gray-900 truncate max-w-36">{{ $expense->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $expense->expense_number }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm font-medium text-gray-900">@currency($expense->amount)</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm text-gray-900">{{ $expense->expense_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <span class="inline-flex items-center px-1.5 py-0.5 md:px-2.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucwords(str_replace('_', ' ', $expense->category)) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap md:px-4">
                                        <div class="text-xs md:text-sm text-gray-900 truncate max-w-28">{{ $expense->vendor_name ?: 'N/A' }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-right text-xs md:text-sm font-medium md:px-4">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('expenses.show', $expense) }}" class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">View</a>
                                            <a href="{{ route('expenses.edit', $expense) }}" class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this expense? This action cannot be undone.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50">Del</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($expenses as $expense)
                        <div class="p-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $expense->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $expense->expense_number }} • {{ ucwords(str_replace('_', ' ', $expense->category)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900">@currency($expense->amount)</div>
                                    <div class="text-xs text-gray-500">{{ $expense->expense_date->format('M d, Y') }}</div>
                                </div>
                            </div>

                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-gray-500">Vendor</p>
                                    <p class="font-medium">{{ $expense->vendor_name ?: 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="mt-3 flex space-x-2">
                                        <a href="{{ route('expenses.show', $expense) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">View</a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Edit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $expenses->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No expenses found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first expense.</p>
                    <div class="mt-6">
                        <a href="{{ route('expenses.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Expense
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
