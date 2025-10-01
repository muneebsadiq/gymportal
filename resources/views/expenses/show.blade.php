@extends('layouts.app')

@section('page-title', 'Expense Receipt')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Expense Receipt</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Expense No: {{ $expense->expense_number }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Back</a>
                    <button onclick="window.print()" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Print</button>
                </div>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6 print:p-0">
                <div class="print:p-8">
                    <div class="flex items-start justify-between">
                        <div>
                            @if($globalSettings->logo)
                                <img src="{{ asset('storage/' . $globalSettings->logo) }}" alt="{{ $globalSettings->gym_name }}" class="h-16 w-auto object-contain mb-2">
                            @endif
                            <h2 class="text-xl font-bold">{{ $globalSettings->gym_name }}</h2>
                            @if($globalSettings->gym_address)
                                <p class="text-xs text-gray-500">{{ $globalSettings->gym_address }}</p>
                            @endif
                            @if($globalSettings->gym_phone)
                                <p class="text-xs text-gray-500">Phone: {{ $globalSettings->gym_phone }}</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-2 font-semibold">Expense Receipt</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Date: {{ optional($expense->expense_date)->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">Category: {{ ucfirst($expense->category) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Expense Details</h3>
                            <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $expense->title }}</p>
                            @if($expense->expense_type)
                                <p class="text-sm text-gray-500">Type: {{ $expense->expense_type }}</p>
                            @endif
                            @if($expense->vendor_name)
                                <p class="text-sm text-gray-500">Vendor: {{ $expense->vendor_name }}</p>
                            @endif
                            @if($expense->coach)
                                <p class="text-sm text-gray-500">Coach: {{ $expense->coach->name }}</p>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Payment Info</h3>
                            <p class="mt-1 text-sm text-gray-900 font-bold text-lg">Amount: {{ $globalSettings->currency_symbol }} {{ number_format($expense->amount, 2) }}</p>
                            <p class="text-sm text-gray-500">Method: {{ ucfirst(str_replace('_',' ', $expense->payment_method)) }}</p>
                            <p class="text-sm text-gray-500">Category: {{ ucfirst($expense->category) }}</p>
                        </div>
                    </div>

                    @if($expense->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700">Description</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $expense->description }}</p>
                    </div>
                    @endif

                    @if($expense->receipt_file)
                    <div class="mt-6 print:hidden">
                        <h3 class="text-sm font-medium text-gray-700">Attached Receipt</h3>
                        <a href="{{ asset('storage/' . $expense->receipt_file) }}" target="_blank" class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Attached Receipt
                        </a>
                    </div>
                    @endif

                    <div class="mt-8 border-t pt-4 text-sm text-gray-500">
                        <p>This is a computer-generated expense receipt.</p>
                        <p class="text-xs mt-1">Generated on: {{ now()->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .print\:p-0, .print\:p-0 * { padding: 0 !important; }
    .print\:p-8, .print\:p-8 * { visibility: visible; }
    .print\:p-8 { position: absolute; left: 0; top: 0; width: 100%; }
    .print\:hidden { display: none !important; }
}
</style>
@endsection
