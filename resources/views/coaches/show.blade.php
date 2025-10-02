@extends('layouts.app')

@section('page-title', 'Coach Details')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">{{ $coach->name }}</h2>
                <p class="text-sm text-gray-500">{{ $coach->coach_id }} • {{ ucfirst($coach->status) }}</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                <a href="{{ route('coaches.salary-history', $coach) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Salary History
                </a>
                <a href="{{ route('coaches.edit', $coach) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                <a href="{{ route('coaches.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Back</a>
            </div>
        </div>

        <!-- Coach Details -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Details</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Coach ID</dt>
                        <dd class="mt-1 text-sm font-semibold text-indigo-600 sm:mt-0 sm:col-span-2">{{ $coach->coach_id ?: '—' }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $coach->phone ?: '—' }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $coach->email ?: '—' }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $coach->specialization ?: '—' }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Join Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ optional($coach->join_date)->format('M d, Y') ?: '—' }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Monthly Salary</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $coach->salary ? number_format($coach->salary, 2) : '—' }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $coach->commission_rate ? $coach->commission_rate . '%' : '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Assigned Members</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $coach->members->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Member Fees</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($totalMemberFees, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Commission ({{ $coach->commission_rate }}%)</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($calculatedCommission, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Salary Paid</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($totalSalaryPaid, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Calculation Breakdown -->
        @if($coach->commission_rate && count($memberFeesBreakdown) > 0)
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Commission Calculation Breakdown</h3>
                <p class="mt-1 text-sm text-gray-500">Commission based on PAID monthly fees of assigned members ({{ now()->format('F Y') }})</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Fee</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($memberFeesBreakdown as $item)
                            <tr class="{{ $item['paid'] ? '' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['member']->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['plan']->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($item['paid'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Paid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Not Paid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item['paid'] ? 'text-gray-900 font-medium' : 'text-gray-400 line-through' }} text-right">{{ number_format($item['fee'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-semibold">
                                <td colspan="3" class="px-6 py-4 text-sm text-gray-900 text-right">Total Paid Member Fees:</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ number_format($totalMemberFees, 2) }}</td>
                            </tr>
                            <tr class="bg-indigo-50 font-bold">
                                <td colspan="3" class="px-6 py-4 text-sm text-indigo-900 text-right">Commission ({{ $coach->commission_rate }}% of {{ number_format($totalMemberFees, 2) }}):</td>
                                <td class="px-6 py-4 text-sm text-indigo-900 text-right">{{ number_format($calculatedCommission, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if(collect($memberFeesBreakdown)->where('paid', false)->count() > 0)
                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Note:</strong> Commission is only calculated for members who have paid their fees this month. Members marked as "Not Paid" are excluded from the commission calculation.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Pay Salary Form -->
        @if($coach->salary)
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Pay Salary</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ now()->format('F Y') }} - Record salary payment (will be logged as expense)</p>
                    </div>
                    @if($salaryPaidThisMonth)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Already Paid
                    </span>
                    @endif
                </div>
            </div>
            
            <!-- Salary Calculation Summary -->
            <div class="px-4 py-4 {{ $salaryPaidThisMonth ? 'bg-green-50' : 'bg-gray-50' }} border-b border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Basic Salary</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($coach->salary, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Paid Member Fees</p>
                        <p class="mt-1 text-lg font-semibold text-green-600">{{ number_format($totalMemberFees, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Commission ({{ $coach->commission_rate }}%)</p>
                        <p class="mt-1 text-lg font-semibold text-yellow-600">{{ number_format($calculatedCommission, 2) }}</p>
                    </div>
                    <div class="border-l-2 {{ $salaryPaidThisMonth ? 'border-green-500' : 'border-indigo-500' }} pl-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Payable</p>
                        <p class="mt-1 text-2xl font-bold {{ $salaryPaidThisMonth ? 'text-green-600' : 'text-indigo-600' }}" id="totalPayable">{{ number_format($coach->salary + $calculatedCommission, 2) }}</p>
                    </div>
                </div>
            </div>

            @if($salaryPaidThisMonth)
            <!-- Already Paid Message -->
            <div class="px-4 py-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Salary Already Paid for {{ now()->format('F Y') }}</h3>
                <p class="text-sm text-gray-500 mb-6">This coach's salary has already been paid for the current month. You can view the payment history below.</p>
                <a href="{{ route('coaches.salary-history', $coach) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    View Salary History
                </a>
            </div>
            @else
            <form action="{{ route('coaches.pay-salary', $coach) }}" method="POST">
                @csrf
                <div class="px-4 py-5 sm:p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="include_commission" id="include_commission" value="1" checked onchange="updateTotalAmount()" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700">Include commission ({{ number_format($calculatedCommission, 2) }})</span>
                            </label>
                            <button type="button" onclick="setBasicSalaryOnly()" class="text-xs text-indigo-600 hover:text-indigo-800">Basic Salary Only</button>
                        </div>
                        @if($calculatedCommission > 0)
                        <div id="commissionBreakdown" class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                            <p class="text-xs font-medium text-blue-900 mb-2">Commission Calculation:</p>
                            <p class="text-xs text-blue-800">Total Member Fees: {{ number_format($totalMemberFees, 2) }}</p>
                            <p class="text-xs text-blue-800">Commission Rate: {{ $coach->commission_rate }}%</p>
                            <p class="text-xs text-blue-800 font-semibold">Commission Amount: {{ number_format($calculatedCommission, 2) }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount to Pay *</label>
                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $coach->salary + $calculatedCommission) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Adjust if needed</p>
                    </div>
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('payment_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                        <select name="payment_method" id="payment_method" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="bank_transfer" {{ old('payment_method')==='bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ old('payment_method')==='cash' ? 'selected' : '' }}>Cash</option>
                            <option value="cheque" {{ old('payment_method')==='cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="card" {{ old('payment_method')==='card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ old('payment_method')==='other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description / Notes</label>
                        <textarea name="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g., Salary for October 2025">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            <svg class="inline h-4 w-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Payment will be recorded as an <strong>expense</strong>, not revenue
                        </p>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Record Payment as Expense
                        </button>
                    </div>
                </div>
            </form>
            <script>
                const basicSalary = {{ $coach->salary }};
                const calculatedCommission = {{ $calculatedCommission }};
                
                function updateTotalAmount() {
                    const includeCommission = document.getElementById('include_commission').checked;
                    const amountInput = document.getElementById('amount');
                    const commissionBreakdown = document.getElementById('commissionBreakdown');
                    
                    if (includeCommission) {
                        amountInput.value = (basicSalary + calculatedCommission).toFixed(2);
                        if (commissionBreakdown) commissionBreakdown.style.display = 'block';
                    } else {
                        amountInput.value = basicSalary.toFixed(2);
                        if (commissionBreakdown) commissionBreakdown.style.display = 'none';
                    }
                }
                
                function setBasicSalaryOnly() {
                    document.getElementById('include_commission').checked = false;
                    updateTotalAmount();
                }
            </script>
            @endif
        </div>
        @endif

        <!-- Assigned Members -->
        @if($coach->members->count() > 0)
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Assigned Members ({{ $coach->members->count() }})</h3>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @foreach($coach->members as $member)
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-indigo-600">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->member_id }} • {{ $member->phone }}</p>
                            </div>
                            <a href="{{ route('members.show', $member) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


