@extends('layouts.app')

@section('page-title', 'Record Payment')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Record Payment</h2>
                @if($selectedMember)
                <p class="text-sm text-gray-500">For {{ $selectedMember->name }} ({{ $selectedMember->member_id }})</p>
                @endif
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Payments
                </a>
            </div>
        </div>

        <form action="{{ route('payments.store') }}" method="POST" class="mt-6">
            @csrf
            
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Member -->
                        <div class="sm:col-span-2">
                            <label for="member_id" class="block text-sm font-medium text-gray-700">Member *</label>
                            <select name="member_id" id="member_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Member</option>
                                @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ ($selectedMember && $selectedMember->id === $member->id) || old('member_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->member_id }}) - {{ $member->phone }}
                                </option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Membership Assignment (auto-selected) -->
                        <div class="sm:col-span-2" id="membership_block">
                            <label class="block text-sm font-medium text-gray-700">Membership *</label>
                            <div class="mt-1 w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800">
                                @if($selectedMember && $currentAssignment)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium">{{ $currentAssignment->membershipPlan->name ?? 'Plan' }}</div>
                                            <div class="text-xs text-gray-500">Current period: {{ \Carbon\Carbon::parse($currentAssignment->start_date)->format('M d, Y') }} → {{ \Carbon\Carbon::parse($currentAssignment->end_date)->format('M d, Y') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-gray-900 font-semibold">@currency($currentAssignment->membershipPlan->fee ?? 0)</div>
                                            <div class="text-xs text-gray-500">Duration: {{ $currentAssignment->membershipPlan->duration_value }} {{ ucfirst($currentAssignment->membershipPlan->duration_type) }}</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="member_membership_plan_id" value="{{ $currentAssignment->id }}">
                                @else
                                    <span class="text-gray-500">Select a member to load current membership automatically.</span>
                                @endif
                            </div>
                            @error('member_membership_plan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (PKR) *</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                            <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Next Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                            <select name="payment_method" id="payment_method" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                                <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Type -->
                        <div>
                            <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                            <select name="payment_type" id="payment_type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Type</option>
                                <option value="membership_fee" {{ old('payment_type') === 'membership_fee' ? 'selected' : '' }}>Membership Fee</option>
                                <option value="admission_fee" {{ old('payment_type') === 'admission_fee' ? 'selected' : '' }}>Admission Fee</option>
                                <option value="personal_training" {{ old('payment_type') === 'personal_training' ? 'selected' : '' }}>Personal Training</option>
                                <option value="other" {{ old('payment_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('payment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Record Payment
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const assignmentSelect = document.getElementById('member_membership_plan_id');
    const amountInput = document.getElementById('amount');
    const paymentDateInput = document.getElementById('payment_date');
    const dueDateInput = document.getElementById('due_date');
    const memberSelect = document.getElementById('member_id');
    const paymentType = document.getElementById('payment_type');
    const url = new URL(window.location.href);

    function computeNextEndDate(baseDate, type, value) {
        const d = new Date(baseDate);
        const v = parseInt(value || 1, 10);
        switch ((type || 'month').toLowerCase()) {
            case 'day':
            case 'days': d.setDate(d.getDate() + v); break;
            case 'week':
            case 'weeks': d.setDate(d.getDate() + v * 7); break;
            case 'year':
            case 'years': d.setFullYear(d.getFullYear() + v); break;
            case 'month':
            case 'months':
            default:
                const m = d.getMonth();
                d.setMonth(m + v);
                break;
        }
        return d.toISOString().split('T')[0];
    }

    function applyAssignmentDefaults() {}

    // assignmentSelect intentionally unused; membership auto-selected server-side

    // Toggle membership block based on payment type
    function toggleMembershipBlock() {
        const block = document.getElementById('membership_block');
        if (!block) return;
        const type = paymentType ? paymentType.value : '';
        block.style.display = (type === 'membership_fee') ? '' : 'none';
    }

    if (paymentType) {
        paymentType.addEventListener('change', toggleMembershipBlock);
        toggleMembershipBlock();
    }
    if (paymentDateInput) {
        paymentDateInput.addEventListener('change', function() {
            if (assignmentSelect && assignmentSelect.value) applyAssignmentDefaults();
        });
    }
    if (memberSelect) {
        memberSelect.addEventListener('change', function() {
            // On member change, prompt user to reload the page with member preselected, or submit a GET
            // For simplicity, redirect with query param to load assignments server-side
            if (this.value) {
                const next = new URL(window.location.href);
                next.searchParams.set('member_id', this.value);
                next.searchParams.delete('member_membership_plan_id');
                next.searchParams.delete('amount');
                window.location.href = next.toString();
            }
        });
    }

    // Pre-fill amount from URL if provided
    const presetAmount = url.searchParams.get('amount');
    if (presetAmount && !amountInput.value) {
        amountInput.value = presetAmount;
    }
</script>
@endsection
