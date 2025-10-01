@extends('layouts.app')

@section('page-title', 'Payment Receipt')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Receipt</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Receipt No: {{ $payment->receipt_number }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Back</a>
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
                            <p class="text-sm text-gray-500 mt-2 font-semibold">Payment Receipt</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Date: {{ optional($payment->payment_date)->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">Status: {{ ucfirst($payment->status) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Billed To</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->member->name }}</p>
                            <p class="text-sm text-gray-500">Member ID: {{ $payment->member->member_id }}</p>
                            @if($payment->member->phone)
                            <p class="text-sm text-gray-500">Phone: {{ $payment->member->phone }}</p>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Payment Info</h3>
                            <p class="mt-1 text-sm text-gray-900">Amount: @currency($payment->amount)</p>
                            <p class="text-sm text-gray-500">Type: {{ ucfirst(str_replace('_',' ', $payment->payment_type)) }}</p>
                            <p class="text-sm text-gray-500">Method: {{ ucfirst(str_replace('_',' ', $payment->payment_method)) }}</p>
                            @if($payment->membershipPlan)
                            <p class="text-sm text-gray-500">Plan: {{ $payment->membershipPlan->name }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700">Notes</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->notes ?: '—' }}</p>
                    </div>

                    <div class="mt-8 border-t pt-4 text-sm text-gray-500">
                        <p>Thank you for your payment.</p>
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
}
</style>
@endsection


