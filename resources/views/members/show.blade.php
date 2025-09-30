@extends('layouts.app')

@section('page-title', $member->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    @if($member->profile_photo)
                    <img class="h-16 w-16 rounded-full" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                    @else
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-2xl font-medium text-gray-700">{{ substr($member->name, 0, 1) }}</span>
                    </div>
                    @endif
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">{{ $member->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $member->member_id }} • Joined {{ $member->joined_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0">
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back to Members
                </a>
                @if($member->hasDueFees())
                <a href="{{ route('payments.create', ['member_id' => $member->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Collect Fee
                </a>
                @endif
                <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->email ?: 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') . ' (' . $member->age . ' years)' : 'Not provided' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->gender ? ucfirst($member->gender) : 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->address ?: 'Not provided' }}</dd>
                            </div>
                            @if($member->emergency_contact)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Emergency Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->emergency_contact }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Emergency Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->emergency_phone ?: 'Not provided' }}</dd>
                            </div>
                            @endif
                            @if($member->medical_conditions)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Medical Conditions</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->medical_conditions }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Payment History</h3>
                    </div>
                    @if($member->payments->count() > 0)
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($member->payments->take(10) as $payment)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $payment->status === 'paid' ? 'bg-green-100' : 'bg-red-100' }}">
                                            <svg class="h-4 w-4 {{ $payment->status === 'paid' ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                @if($payment->status === 'paid')
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                @else
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</p>
                                        <p class="text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }} • {{ $payment->receipt_number }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">@currency($payment->amount)</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($payment->payment_method) }}</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No payments recorded</h3>
                        <p class="mt-1 text-sm text-gray-500">Start by recording the first payment.</p>
                        <div class="mt-6">
                            <a href="{{ route('payments.create', ['member_id' => $member->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Record Payment
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Member Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($member->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </div>
                        @if($member->hasDueFees())
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Fee Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Due
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Next Due Date</span>
                            <span class="text-sm text-gray-900">{{ $member->next_due_date ? \Carbon\Carbon::parse($member->next_due_date)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Current Membership -->
                @php
                    $activeAssignment = $member->memberMembershipPlans
                        ->where('status', 'active')
                        ->sortByDesc('start_date')
                        ->first();
                    $dueAssignment = null;
                    if (!$activeAssignment) {
                        $dueAssignment = $member->memberMembershipPlans
                            ->filter(function($a){
                                return ($a->status !== 'cancelled') && \Carbon\Carbon::parse($a->end_date)->isPast();
                            })
                            ->sortByDesc('end_date')
                            ->first();
                    }
                @endphp
                @if($activeAssignment)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Membership</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $activeAssignment->membershipPlan->name ?? 'Plan' }}</p>
                            @if(!empty($activeAssignment->membershipPlan?->description))
                            <p class="text-xs text-gray-500">{{ $activeAssignment->membershipPlan->description }}</p>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Fee</span>
                            <span class="text-sm font-medium text-gray-900">@currency($activeAssignment->membershipPlan->fee ?? 0)</span>
                        </div>
                        @php
                            $planFee = (float) ($activeAssignment->membershipPlan->fee ?? 0);
                            $windowEnd = \Carbon\Carbon::parse($activeAssignment->end_date);
                            $dtype = strtolower($activeAssignment->membershipPlan?->duration_type ?? 'month');
                            $dval = (int) ($activeAssignment->membershipPlan?->duration_value ?? 1);
                            $windowStartCarbon = match ($dtype) {
                                'day','days' => $windowEnd->copy()->subDays($dval),
                                'week','weeks' => $windowEnd->copy()->subWeeks($dval),
                                'month','months' => $windowEnd->copy()->subMonths($dval),
                                'year','years' => $windowEnd->copy()->subYears($dval),
                                default => $windowEnd->copy()->subMonths($dval),
                            };
                            $windowStart = $windowStartCarbon->toDateString();
                            $windowEndStr = $windowEnd->toDateString();
                            $paidInWindow = \App\Models\Payment::where('member_membership_plan_id', $activeAssignment->id)
                                ->where('payment_type', 'membership_fee')
                                ->whereBetween('payment_date', [$windowStart, $windowEndStr])
                                ->sum('amount');
                            $diff = $paidInWindow - $planFee; // negative means due, positive means excess
                            $isOverdue = ($diff < 0) && $windowEnd->isPast();
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Fee Status</span>
                            @if($diff === 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                            @elseif($diff < 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isOverdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $isOverdue ? 'Overdue' : 'Partial Due' }} (@currency(abs($diff)))
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Excess (@currency($diff))</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Current Period</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($windowStart)->format('M d, Y') }} → {{ $windowEnd->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Duration</span>
                            <span class="text-sm text-gray-900">{{ $activeAssignment->membershipPlan?->duration_value }} {{ ucfirst($activeAssignment->membershipPlan?->duration_type ?? '') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Start Date</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($activeAssignment->start_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">End Date</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($activeAssignment->end_date)->format('M d, Y') }}</span>
                        </div>
                        @php
                            $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($activeAssignment->end_date), false);
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Days Remaining</span>
                            <span class="text-sm {{ $daysRemaining > 30 ? 'text-green-600' : ($daysRemaining > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ max($daysRemaining, 0) }} days
                            </span>
                        </div>
                        <div class="pt-4 flex items-center space-x-2">
                            <a href="{{ route('payments.create', ['member_id' => $member->id, 'member_membership_plan_id' => $activeAssignment->id]) }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                Record Payment
                            </a>
                            <a href="{{ route('member_membership_plans.edit', $activeAssignment) }}"
                               class="inline-flex items-center px-3 py-2 border text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                Edit Membership
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('payments.create', ['member_id' => $member->id]) }}" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Record Payment
                        </a>
                        <a href="{{ route('members.edit', $member) }}" class="block w-full text-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Edit Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
