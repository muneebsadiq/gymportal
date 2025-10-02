@extends('layouts.app')

@section('page-title', 'Expense Report')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Expense Report</h2>
                <p class="mt-1 text-sm text-gray-500">Business expenses and cost breakdown analysis</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Reports
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6 border border-gray-200">
            <form method="GET" action="{{ route('reports.expenses') }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Categories</option>
                            <option value="salary" {{ request('category') === 'salary' ? 'selected' : '' }}>Salary</option>
                            <option value="rent" {{ request('category') === 'rent' ? 'selected' : '' }}>Rent</option>
                            <option value="utilities" {{ request('category') === 'utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="equipment" {{ request('category') === 'equipment' ? 'selected' : '' }}>Equipment</option>
                            <option value="maintenance" {{ request('category') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="marketing" {{ request('category') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('reports.expenses') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Card -->
        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-6 mb-6 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-800">Total Expenses</p>
                    <p class="text-3xl font-bold text-red-900 mt-1">PKR {{ number_format($totalAmount, 2) }}</p>
                    <p class="text-sm text-red-700 mt-1">From {{ $expenses->total() }} transactions</p>
                </div>
                <div class="h-16 w-16 bg-red-200 rounded-full flex items-center justify-center">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
            @if($expenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coach</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->expense_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $expense->category === 'salary' ? 'bg-purple-100 text-purple-800' : 
                                       ($expense->category === 'rent' ? 'bg-blue-100 text-blue-800' : 
                                       ($expense->category === 'utilities' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucwords(str_replace('_', ' ', $expense->category)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $expense->description ?: '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucwords(str_replace('_', ' ', $expense->payment_method)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->coach ? $expense->coach->name : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                PKR {{ number_format($expense->amount, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total:</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-red-600">PKR {{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $expenses->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No expenses found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
