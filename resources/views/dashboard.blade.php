@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Welcome Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Here's what's happening at {{ $settings->gym_name }} today.
                </p>
            </div>
        </div>

        <!-- Search Section with Tabs -->
        <div class="mt-6 bg-white shadow-lg rounded-lg p-8">
            <h3 class="text-2xl font-semibold text-gray-900 mb-6">Quick Search</h3>
            
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="switchTab('member')" id="memberTab" class="tab-button border-b-2 border-indigo-600 py-4 px-1 text-base font-semibold text-indigo-600">
                        Member Search
                    </button>
                    <button onclick="switchTab('coach')" id="coachTab" class="tab-button border-b-2 border-transparent py-4 px-1 text-base font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Coach Search
                    </button>
                </nav>
            </div>
            
            <!-- Member Search -->
            <div id="memberSearchSection">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="memberSearchInput" 
                               placeholder="Enter Member ID (e.g., MEM0001)" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-3 px-4">
                    </div>
                    <button type="button" 
                            onclick="searchMember()" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Member
                    </button>
                </div>
                <div id="searchResult" class="mt-6"></div>
            </div>
            
            <!-- Coach Search -->
            <div id="coachSearchSection" style="display: none;">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="coachSearchInput" 
                               placeholder="Enter Coach ID (e.g., TRN0001)" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-lg py-3 px-4">
                    </div>
                    <button type="button" 
                            onclick="searchCoach()" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Coach
                    </button>
                </div>
                <div id="coachSearchResult" class="mt-6"></div>
            </div>
        </div>
        
        <!-- Key Metrics -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Members -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Members</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalMembers }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Members -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Members</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $activeMembers }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Due Fees Alert -->
            <div class="bg-white overflow-hidden shadow rounded-lg cursor-pointer" onclick="openDueFeesModal()">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $dueFeesCount > 0 ? 'bg-red-500' : 'bg-gray-400' }} rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Due Fees</dt>
                                <dd class="text-lg font-medium {{ $dueFeesCount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $dueFeesCount }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- This Month Revenue -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">This Month Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">@currency($monthlyRevenue)</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Revenue Chart -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Revenue</h3>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
            
            <!-- Member Growth Chart -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Member Growth</h3>
                <canvas id="memberChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Payments</h3>
                @if($recentPayments->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($recentPayments as $payment)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                <strong class="font-medium text-gray-900">{{ $payment->member->name }}</strong>
                                                paid @currency($payment->amount) for {{ $payment->payment_type }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $payment->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No recent payments</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Due Fees Modal -->
@include('partials.due-fees-modal')

<script>
// Chart configurations
const revenueData = @json($revenueChartData);
const memberData = @json($memberChartData);

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueData.labels,
        datasets: [{
            label: 'Revenue (PKR)',
            data: revenueData.data,
            borderColor: 'rgb(79, 70, 229)',
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Member Growth Chart
const memberCtx = document.getElementById('memberChart').getContext('2d');
const memberChart = new Chart(memberCtx, {
    type: 'bar',
    data: {
        labels: memberData.labels,
        datasets: [{
            label: 'New Members',
            data: memberData.data,
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Due Fees Modal
function openDueFeesModal() {
    fetch('{{ route("due-fees-modal") }}')
        .then(response => response.json())
        .then(data => {
            if (data && data.html) {
                document.body.insertAdjacentHTML('beforeend', data.html);
            }
        })
        .catch(() => {});
}

// Member Search Functionality
function searchMember() {
    const memberId = document.getElementById('memberSearchInput').value.trim();
    const resultDiv = document.getElementById('searchResult');
    
    if (!memberId) {
        resultDiv.innerHTML = '<div class="text-base text-red-600 font-medium bg-red-50 p-4 rounded-lg border border-red-200">Please enter a member ID</div>';
        return;
    }
    
    resultDiv.innerHTML = '<div class="text-base text-gray-600 font-medium bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center"><svg class="animate-spin h-5 w-5 mr-3 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Searching...</div>';
    
    fetch(`{{ route("search-member") }}?member_id=${encodeURIComponent(memberId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMemberResult(data.member);
            } else {
                resultDiv.innerHTML = `<div class="text-base text-red-600 font-medium bg-red-50 p-4 rounded-lg border border-red-200">${data.message}</div>`;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="text-base text-red-600 font-medium bg-red-50 p-4 rounded-lg border border-red-200">An error occurred while searching</div>';
        });
}

// Store current member data globally
let currentMemberData = null;

function displayMemberResult(member) {
    const resultDiv = document.getElementById('searchResult');
    
    // Store member data globally
    currentMemberData = member;
    console.log('Member data stored:', currentMemberData);
    
    // Determine payment status badge
    let paymentStatusBadge = '';
    if (member.active_plan) {
        const feeStatus = member.active_plan.fee_status;
        if (feeStatus === 'paid') {
            paymentStatusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-green-100 text-green-800">✓ Paid</span>';
        } else if (feeStatus === 'overdue') {
            paymentStatusBadge = `<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-red-100 text-red-800">⚠ Overdue (PKR ${member.active_plan.fee_amount_due})</span>`;
        } else if (feeStatus === 'partial') {
            paymentStatusBadge = `<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-yellow-100 text-yellow-800">⚠ Partial (PKR ${member.active_plan.fee_amount_due} due)</span>`;
        } else if (feeStatus === 'excess') {
            paymentStatusBadge = `<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-blue-100 text-blue-800">+ Excess (PKR ${member.active_plan.fee_amount_excess})</span>`;
        }
    } else if (member.has_due_fees) {
        paymentStatusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-red-100 text-red-800">⚠ Due Fees</span>';
    } else {
        paymentStatusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-gray-100 text-gray-800">No Active Plan</span>';
    }
    
    resultDiv.innerHTML = `
        <div class="border-t-2 border-gray-200 pt-6">
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        ${member.profile_photo ? 
                            `<img src="${member.profile_photo}" alt="${member.name}" class="h-16 w-16 rounded-full border-2 border-indigo-200">` :
                            `<div class="h-16 w-16 rounded-full bg-indigo-200 flex items-center justify-center border-2 border-indigo-300">
                                <span class="text-2xl font-bold text-indigo-700">${member.name.charAt(0)}</span>
                            </div>`
                        }
                        <div class="ml-5 flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="text-xl font-bold text-gray-900">
                                    ${member.name}
                                </p>
                                ${paymentStatusBadge}
                            </div>
                            <div class="flex items-center gap-4 flex-wrap">
                                <p class="text-base font-medium text-gray-600 bg-white px-3 py-1 rounded-md">${member.member_id}</p>
                                ${member.next_due_date ? 
                                    `<p class="text-base text-gray-700 bg-white px-3 py-1 rounded-md">
                                        <span class="font-semibold text-indigo-600">Next Due:</span> ${member.next_due_date}
                                    </p>` : 
                                    ''
                                }
                                ${member.active_plan ? 
                                    `<p class="text-base text-gray-700 bg-white px-3 py-1 rounded-md">
                                        <span class="font-semibold text-indigo-600">${member.active_plan.days_remaining} days</span> remaining
                                    </p>` : 
                                    ''
                                }
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 ml-6">
                        <button type="button" onclick="showMemberSearchModal()" class="inline-flex items-center px-5 py-2.5 border border-indigo-300 shadow-sm text-base font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function showMemberSearchModal() {
    const member = currentMemberData;
    if (!member) {
        console.error('No member data available');
        return;
    }
    
    console.log('Opening modal for member:', member);
    
    // Determine payment status badge
    let paymentStatusBadge = '';
    if (member.active_plan) {
        const feeStatus = member.active_plan.fee_status;
        if (feeStatus === 'paid') {
            paymentStatusBadge = '<span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold bg-green-100 text-green-800">✓ Paid</span>';
        } else if (feeStatus === 'overdue') {
            paymentStatusBadge = `<span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold bg-red-100 text-red-800">⚠ Overdue (PKR ${member.active_plan.fee_amount_due})</span>`;
        } else if (feeStatus === 'partial') {
            paymentStatusBadge = `<span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold bg-yellow-100 text-yellow-800">⚠ Partial (PKR ${member.active_plan.fee_amount_due} due)</span>`;
        } else if (feeStatus === 'excess') {
            paymentStatusBadge = `<span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold bg-blue-100 text-blue-800">+ Excess (PKR ${member.active_plan.fee_amount_excess})</span>`;
        }
    } else if (member.has_due_fees) {
        paymentStatusBadge = '<span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold bg-red-100 text-red-800">⚠ Due Fees</span>';
    } else {
        paymentStatusBadge = '<span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold bg-gray-100 text-gray-800">No Active Plan</span>';
    }
    
    const modalHTML = `
        <div id="memberSearchModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeMemberSearchModal()"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full z-50">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-bold text-white">Member Found</h3>
                            <button type="button" onclick="closeMemberSearchModal()" class="rounded-md text-white hover:text-gray-200 focus:outline-none">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-8">
                        <div class="flex items-start gap-8">
                            <!-- Large Member Photo -->
                            <div class="flex-shrink-0">
                                ${member.profile_photo ? 
                                    `<img src="${member.profile_photo}" alt="${member.name}" class="h-48 w-48 rounded-xl border-4 border-indigo-200 shadow-lg object-cover">` :
                                    `<div class="h-48 w-48 rounded-xl bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center border-4 border-indigo-200 shadow-lg">
                                        <span class="text-8xl font-bold text-white">${member.name.charAt(0)}</span>
                                    </div>`
                                }
                            </div>
                            
                            <!-- Member Details -->
                            <div class="flex-1">
                                <div class="mb-4">
                                    <h2 class="text-3xl font-bold text-gray-900 mb-2">${member.name}</h2>
                                    <p class="text-lg text-gray-600 font-medium mb-3">${member.member_id}</p>
                                    ${paymentStatusBadge}
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    ${member.phone ? `
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-500 mb-1">Phone</p>
                                        <p class="text-base font-semibold text-gray-900">${member.phone}</p>
                                    </div>` : ''}
                                    
                                    ${member.email ? `
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-500 mb-1">Email</p>
                                        <p class="text-base font-semibold text-gray-900">${member.email}</p>
                                    </div>` : ''}
                                    
                                    ${member.next_due_date ? `
                                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                        <p class="text-sm text-yellow-700 mb-1">Next Due Date</p>
                                        <p class="text-lg font-bold text-yellow-900">${member.next_due_date}</p>
                                    </div>` : ''}
                                    
                                    ${member.active_plan ? `
                                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                                        <p class="text-sm text-indigo-700 mb-1">Days Remaining</p>
                                        <p class="text-lg font-bold text-indigo-900">${member.active_plan.days_remaining} days</p>
                                    </div>` : ''}
                                </div>
                                
                                ${member.active_plan ? `
                                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg border border-indigo-200 mb-6">
                                    <p class="text-sm text-gray-600 mb-1">Current Plan</p>
                                    <p class="text-xl font-bold text-gray-900">${member.active_plan.name}</p>
                                    <p class="text-base text-gray-700 mt-1">PKR ${member.active_plan.fee} • ${member.active_plan.duration}</p>
                                </div>` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button type="button" onclick="closeMemberSearchModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-base font-semibold text-gray-700 bg-white hover:bg-gray-50">
                            Close
                        </button>
                        <a href="${member.payment_url}" class="px-6 py-3 border border-transparent rounded-lg text-base font-semibold text-white bg-green-600 hover:bg-green-700">
                            Record Payment
                        </a>
                        <a href="${member.view_url}" class="px-6 py-3 border border-transparent rounded-lg text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700">
                            View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeMemberSearchModal() {
    const modal = document.getElementById('memberSearchModal');
    if (modal) {
        modal.remove();
    }
}

function openMemberPanel(memberId) {
    fetch(`{{ route("search-member") }}?member_id=${encodeURIComponent(memberId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMemberProfilePanel(data.member);
            }
        })
        .catch(error => {
            console.error('Error loading member details:', error);
        });
}

function showMemberProfilePanel(member) {
    // Create modal overlay
    const modalHTML = `
        <div id="memberProfilePanel" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeMemberPanel()"></div>
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-2xl">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <!-- Header -->
                            <div class="bg-indigo-600 px-4 py-6 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        ${member.profile_photo ? 
                                            `<img src="${member.profile_photo}" alt="${member.name}" class="h-12 w-12 rounded-full border-2 border-white">` :
                                            `<div class="h-12 w-12 rounded-full bg-white flex items-center justify-center">
                                                <span class="text-xl font-medium text-indigo-600">${member.name.charAt(0)}</span>
                                            </div>`
                                        }
                                        <div class="ml-3">
                                            <h2 class="text-xl font-bold text-white">${member.name}</h2>
                                            <p class="text-sm text-indigo-200">${member.member_id}</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="closeMemberPanel()" class="rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="relative flex-1 px-4 py-6 sm:px-6">
                                <!-- Status Badges -->
                                <div class="mb-6 flex gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${
                                        member.status === 'active' ? 'bg-green-100 text-green-800' : 
                                        member.status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'
                                    }">
                                        ${member.status.charAt(0).toUpperCase() + member.status.slice(1)}
                                    </span>
                                    ${member.has_due_fees ? 
                                        '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Due Fees</span>' : 
                                        ''
                                    }
                                </div>

                                <!-- Personal Information -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-3">Personal Information</h3>
                                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.phone || 'N/A'}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.email || 'N/A'}</dd>
                                        </div>
                                        ${member.date_of_birth ? `
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.date_of_birth} (${member.age} years)</dd>
                                        </div>` : ''}
                                        ${member.gender ? `
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.gender}</dd>
                                        </div>` : ''}
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.joined_date}</dd>
                                        </div>
                                        ${member.coach ? `
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Coach</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.coach}</dd>
                                        </div>` : ''}
                                        ${member.address ? `
                                        <div class="sm:col-span-2">
                                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.address}</dd>
                                        </div>` : ''}
                                        ${member.emergency_contact ? `
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Emergency Contact</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.emergency_contact}</dd>
                                        </div>` : ''}
                                        ${member.emergency_phone ? `
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Emergency Phone</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.emergency_phone}</dd>
                                        </div>` : ''}
                                        ${member.medical_conditions ? `
                                        <div class="sm:col-span-2">
                                            <dt class="text-sm font-medium text-gray-500">Medical Conditions</dt>
                                            <dd class="mt-1 text-sm text-gray-900">${member.medical_conditions}</dd>
                                        </div>` : ''}
                                    </dl>
                                </div>

                                ${member.active_plan ? `
                                <!-- Current Membership Plan -->
                                <div class="mb-6 bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-3">Current Membership Plan</h3>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${member.active_plan.name}</p>
                                            ${member.active_plan.description ? `<p class="text-xs text-gray-500">${member.active_plan.description}</p>` : ''}
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <dt class="text-xs text-gray-500">Fee</dt>
                                                <dd class="text-sm font-medium text-gray-900">PKR ${member.active_plan.fee}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs text-gray-500">Duration</dt>
                                                <dd class="text-sm font-medium text-gray-900">${member.active_plan.duration}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs text-gray-500">Current Period</dt>
                                                <dd class="text-sm text-gray-900">${member.active_plan.period_start} → ${member.active_plan.period_end}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs text-gray-500">Days Remaining</dt>
                                                <dd class="text-sm font-medium ${member.active_plan.days_remaining > 30 ? 'text-green-600' : member.active_plan.days_remaining > 0 ? 'text-yellow-600' : 'text-red-600'}">
                                                    ${member.active_plan.days_remaining} days
                                                </dd>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 border-t">
                                            <span class="text-xs text-gray-500">Fee Status</span>
                                            ${member.active_plan.fee_status === 'paid' ? 
                                                '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>' :
                                                member.active_plan.fee_status === 'overdue' ?
                                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Overdue (PKR ${member.active_plan.fee_amount_due})</span>` :
                                                member.active_plan.fee_status === 'partial' ?
                                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Partial Due (PKR ${member.active_plan.fee_amount_due})</span>` :
                                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Excess (PKR ${member.active_plan.fee_amount_excess})</span>`
                                            }
                                        </div>
                                    </div>
                                </div>` : ''}

                                <!-- Payment History -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-3">Recent Payment History</h3>
                                    ${member.payments.length > 0 ? `
                                    <div class="space-y-3">
                                        ${member.payments.map(payment => `
                                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full ${payment.status === 'paid' ? 'bg-green-100' : 'bg-red-100'}">
                                                    <svg class="h-4 w-4 ${payment.status === 'paid' ? 'text-green-600' : 'text-red-600'}" fill="currentColor" viewBox="0 0 20 20">
                                                        ${payment.status === 'paid' ? 
                                                            '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>' :
                                                            '<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>'
                                                        }
                                                    </svg>
                                                </span>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">${payment.payment_type}</p>
                                                    <p class="text-xs text-gray-500">${payment.payment_date} • ${payment.receipt_number}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">PKR ${payment.amount}</p>
                                                <p class="text-xs text-gray-500">${payment.payment_method}</p>
                                            </div>
                                        </div>
                                        `).join('')}
                                    </div>` : 
                                    '<p class="text-sm text-gray-500 text-center py-4">No payment history</p>'}
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3 pt-4 border-t">
                                    <a href="${member.payment_url}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                        Record Payment
                                    </a>
                                    <a href="${member.edit_url}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                        Edit Member
                                    </a>
                                    <a href="${member.view_url}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                        Full Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeMemberPanel() {
    const panel = document.getElementById('memberProfilePanel');
    if (panel) {
        panel.remove();
    }
}

// Tab Switching
function switchTab(tab) {
    const memberTab = document.getElementById('memberTab');
    const coachTab = document.getElementById('coachTab');
    const memberSection = document.getElementById('memberSearchSection');
    const coachSection = document.getElementById('coachSearchSection');
    
    if (tab === 'member') {
        memberTab.classList.add('border-indigo-600', 'text-indigo-600', 'font-semibold');
        memberTab.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
        coachTab.classList.remove('border-green-600', 'text-green-600', 'font-semibold');
        coachTab.classList.add('border-transparent', 'text-gray-500', 'font-medium');
        memberSection.style.display = 'block';
        coachSection.style.display = 'none';
    } else {
        coachTab.classList.add('border-green-600', 'text-green-600', 'font-semibold');
        coachTab.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
        memberTab.classList.remove('border-indigo-600', 'text-indigo-600', 'font-semibold');
        memberTab.classList.add('border-transparent', 'text-gray-500', 'font-medium');
        coachSection.style.display = 'block';
        memberSection.style.display = 'none';
    }
}

// Store current coach data globally
let currentCoachData = null;

// Coach Search Functionality
function searchCoach() {
    const coachId = document.getElementById('coachSearchInput').value.trim();
    const resultDiv = document.getElementById('coachSearchResult');
    
    if (!coachId) {
        resultDiv.innerHTML = '<div class="text-base text-red-600 font-medium bg-red-50 p-4 rounded-lg border border-red-200">Please enter a coach ID</div>';
        return;
    }
    
    resultDiv.innerHTML = '<div class="text-base text-gray-600 font-medium bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center"><svg class="animate-spin h-5 w-5 mr-3 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Searching...</div>';
    
    fetch(`{{ route("search-coach") }}?coach_id=${encodeURIComponent(coachId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayCoachResult(data.coach);
            } else {
                resultDiv.innerHTML = `<div class="text-base text-red-600 font-medium bg-red-50 p-4 rounded-lg border border-red-200">${data.message}</div>`;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="text-base text-red-600 font-medium bg-red-50 p-4 rounded-lg border border-red-200">An error occurred while searching</div>';
        });
}

function displayCoachResult(coach) {
    const resultDiv = document.getElementById('coachSearchResult');
    
    // Store coach data globally
    currentCoachData = coach;
    console.log('Coach data stored:', currentCoachData);
    
    // Determine status badge
    let statusBadge = coach.status === 'active' ? 
        '<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-green-100 text-green-800">✓ Active</span>' :
        '<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-gray-100 text-gray-800">Inactive</span>';
    
    resultDiv.innerHTML = `
        <div class="border-t-2 border-gray-200 pt-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <div class="h-16 w-16 rounded-full bg-green-200 flex items-center justify-center border-2 border-green-300">
                            <span class="text-2xl font-bold text-green-700">${coach.name.charAt(0)}</span>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="text-xl font-bold text-gray-900">
                                    ${coach.name}
                                </p>
                                ${statusBadge}
                            </div>
                            <div class="flex items-center gap-4 flex-wrap">
                                <p class="text-base font-medium text-gray-600 bg-white px-3 py-1 rounded-md">${coach.coach_id}</p>
                                ${coach.specialization ? 
                                    `<p class="text-base text-gray-700 bg-white px-3 py-1 rounded-md">
                                        <span class="font-semibold text-green-600">Specialization:</span> ${coach.specialization}
                                    </p>` : 
                                    ''
                                }
                                <p class="text-base text-gray-700 bg-white px-3 py-1 rounded-md">
                                    <span class="font-semibold text-green-600">${coach.active_members}</span> Active Members
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 ml-6">
                        <button type="button" onclick="showCoachSearchModal()" class="inline-flex items-center px-5 py-2.5 border border-green-300 shadow-sm text-base font-semibold rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function showCoachSearchModal() {
    const coach = currentCoachData;
    if (!coach) {
        console.error('No coach data available');
        return;
    }
    
    console.log('Opening modal for coach:', coach);
    
    const modalHTML = `
        <div id="coachSearchModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCoachSearchModal()"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full z-50">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-bold text-white">Coach Found</h3>
                            <button type="button" onclick="closeCoachSearchModal()" class="rounded-md text-white hover:text-gray-200 focus:outline-none">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-8">
                        <div class="flex items-start gap-8">
                            <!-- Large Coach Initial -->
                            <div class="flex-shrink-0">
                                <div class="h-48 w-48 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center border-4 border-green-200 shadow-lg">
                                    <span class="text-8xl font-bold text-white">${coach.name.charAt(0)}</span>
                                </div>
                            </div>
                            
                            <!-- Coach Details -->
                            <div class="flex-1">
                                <div class="mb-4">
                                    <h2 class="text-3xl font-bold text-gray-900 mb-2">${coach.name}</h2>
                                    <p class="text-lg text-gray-600 font-medium mb-3">${coach.coach_id}</p>
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-semibold ${coach.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                        ${coach.status === 'active' ? '✓ Active' : 'Inactive'}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    ${coach.phone ? `
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-500 mb-1">Phone</p>
                                        <p class="text-base font-semibold text-gray-900">${coach.phone}</p>
                                    </div>` : ''}
                                    
                                    ${coach.email ? `
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-500 mb-1">Email</p>
                                        <p class="text-base font-semibold text-gray-900">${coach.email}</p>
                                    </div>` : ''}
                                    
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                        <p class="text-sm text-green-700 mb-1">Total Members</p>
                                        <p class="text-lg font-bold text-green-900">${coach.total_members}</p>
                                    </div>
                                    
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                        <p class="text-sm text-green-700 mb-1">Active Members</p>
                                        <p class="text-lg font-bold text-green-900">${coach.active_members}</p>
                                    </div>
                                    
                                    ${coach.specialization ? `
                                    <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                                        <p class="text-sm text-gray-500 mb-1">Specialization</p>
                                        <p class="text-base font-semibold text-gray-900">${coach.specialization}</p>
                                    </div>` : ''}
                                    
                                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                                        <p class="text-sm text-indigo-700 mb-1">Monthly Salary</p>
                                        <p class="text-lg font-bold text-indigo-900">PKR ${coach.salary}</p>
                                    </div>
                                    
                                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                                        <p class="text-sm text-indigo-700 mb-1">Commission Rate</p>
                                        <p class="text-lg font-bold text-indigo-900">${coach.commission_rate}%</p>
                                    </div>
                                    
                                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                        <p class="text-sm text-yellow-700 mb-1">Total Commission</p>
                                        <p class="text-lg font-bold text-yellow-900">PKR ${coach.total_commission}</p>
                                    </div>
                                    
                                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                        <p class="text-sm text-yellow-700 mb-1">This Month</p>
                                        <p class="text-lg font-bold text-yellow-900">PKR ${coach.monthly_commission}</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                                        <p class="text-sm text-gray-500 mb-1">Join Date</p>
                                        <p class="text-base font-semibold text-gray-900">${coach.join_date}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button type="button" onclick="closeCoachSearchModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-base font-semibold text-gray-700 bg-white hover:bg-gray-50">
                            Close
                        </button>
                        <a href="${coach.salary_url}" class="px-6 py-3 border border-transparent rounded-lg text-base font-semibold text-white bg-yellow-600 hover:bg-yellow-700">
                            Salary History
                        </a>
                        <a href="${coach.view_url}" class="px-6 py-3 border border-transparent rounded-lg text-base font-semibold text-white bg-green-600 hover:bg-green-700">
                            View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeCoachSearchModal() {
    const modal = document.getElementById('coachSearchModal');
    if (modal) {
        modal.remove();
    }
}

// Allow Enter key to trigger search
document.addEventListener('DOMContentLoaded', function() {
    const memberSearchInput = document.getElementById('memberSearchInput');
    if (memberSearchInput) {
        memberSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchMember();
            }
        });
    }
    
    const coachSearchInput = document.getElementById('coachSearchInput');
    if (coachSearchInput) {
        coachSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchCoach();
            }
        });
    }
});
</script>
@endsection
