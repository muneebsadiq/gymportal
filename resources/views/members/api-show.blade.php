@extends('layouts.app')

@section('page-title', $memberData['name'] ?? 'Member Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    @if(isset($memberData['profile_photo']) && $memberData['profile_photo'])
                    <img class="h-16 w-16 rounded-full" src="{{ $memberData['profile_photo'] }}" alt="{{ $memberData['name'] }}">
                    @else
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-2xl font-medium text-gray-700">{{ substr($memberData['name'], 0, 1) }}</span>
                    </div>
                    @endif
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">{{ $memberData['name'] }}</h1>
                        <p class="text-sm text-gray-500">{{ $memberData['member_id'] }} • Joined {{ $memberData['joined_date'] }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back to Members
                </a>
                @if($memberData['has_due_fees'])
                <a href="{{ $memberData['payment_url'] }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Collect Fee
                </a>
                @endif
                <a href="{{ $memberData['edit_url'] }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Edit
                </a>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Member Details -->
            <div class="lg:col-span-2">
                <!-- Personal Information -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Personal Information</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['phone'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['email'] ?: 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $memberData['date_of_birth'] ? $memberData['date_of_birth'] . ' (' . $memberData['age'] . ' years)' : 'Not provided' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['gender'] ?: 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['address'] ?: 'Not provided' }}</dd>
                            </div>
                            @if($memberData['emergency_contact'])
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Emergency Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['emergency_contact'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Emergency Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['emergency_phone'] ?: 'Not provided' }}</dd>
                            </div>
                            @endif
                            @if($memberData['medical_conditions'])
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Medical Conditions</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['medical_conditions'] }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Coach</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['coach'] ?: 'Not assigned' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $memberData['status'] === 'active' ? 'bg-green-100 text-green-800' :
                                           ($memberData['status'] === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($memberData['status']) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Active Plan Information -->
                @if(isset($memberData['active_plan']) && $memberData['active_plan'])
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Active Membership Plan</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Plan Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['active_plan']['name'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['active_plan']['duration'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Monthly Fee</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['active_plan']['fee'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fee Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $memberData['active_plan']['fee_status'] === 'paid' ? 'bg-green-100 text-green-800' :
                                           ($memberData['active_plan']['fee_status'] === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($memberData['active_plan']['fee_status']) }}
                                    </span>
                                    @if($memberData['active_plan']['fee_amount_due'])
                                    <span class="ml-2 text-red-600 font-medium">Due: {{ $memberData['active_plan']['fee_amount_due'] }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['active_plan']['start_date'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['active_plan']['end_date'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Days Remaining</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['active_plan']['days_remaining'] }} days</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                <!-- Payment History -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Payment History</h3>
                    </div>
                    @if(isset($memberData['payments']) && count($memberData['payments']) > 0)
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($memberData['payments'] as $payment)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment['receipt_number'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment['payment_type'] }} • {{ $payment['payment_method'] }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment['amount'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment['payment_date'] }}</div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                        No payment history available
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Actions -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6 space-y-3">
                        <a href="{{ $memberData['view_url'] }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            View Full Details
                        </a>
                        <a href="{{ $memberData['edit_url'] }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Edit Member
                        </a>
                        <a href="{{ $memberData['payment_url'] }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Record Payment
                        </a>
                    </div>
                </div>

                <!-- Member Status -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Member Status</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $memberData['status'] === 'active' ? 'bg-green-100 text-green-800' :
                                           ($memberData['status'] === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($memberData['status']) }}
                                    </span>
                                </dd>
                            </div>
                            @if($memberData['has_due_fees'])
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Fees Due
                                    </span>
                                </dd>
                            </div>
                            @if($memberData['next_due_date'])
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Next Due Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $memberData['next_due_date'] }}</dd>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
