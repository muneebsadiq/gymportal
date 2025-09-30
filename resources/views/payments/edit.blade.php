@extends('layouts.app')

@section('page-title', 'Edit Payment')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Edit Payment</h2>
                <p class="text-sm text-gray-500">Receipt: {{ $payment->receipt_number }}</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Back to Details</a>
            </div>
        </div>

        <form action="{{ route('payments.update', $payment) }}" method="POST" class="mt-6">
            @csrf
            @method('PUT')

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="member_id" class="block text-sm font-medium text-gray-700">Member *</label>
                            <select name="member_id" id="member_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id', $payment->member_id) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->member_id }}) - {{ $member->phone }}
                                </option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (PKR) *</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" required min="0" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                            <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', optional($payment->payment_date)->format('Y-m-d')) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Next Due Date *</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', optional($payment->due_date)->format('Y-m-d')) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                            <select name="payment_method" id="payment_method" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach(['cash','card','bank_transfer','upi','other'] as $method)
                                <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method) === $method ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$method)) }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                            <select name="payment_type" id="payment_type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach(['membership_fee','admission_fee','personal_training','other'] as $type)
                                <option value="{{ $type }}" {{ old('payment_type', $payment->payment_type) === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$type)) }}</option>
                                @endforeach
                            </select>
                            @error('payment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach(['paid','pending','partial','overdue','cancelled'] as $s)
                                <option value="{{ $s }}" {{ old('status', $payment->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Any additional notes...">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


