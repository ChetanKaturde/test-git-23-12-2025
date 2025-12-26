@extends('layouts.app')
@section('title', 'Aging Report')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Accounts Receivable Aging</h2>
            <p class="text-gray-600">Outstanding invoices by age</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reports.aging.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-green-800">Current</h3>
            <p class="text-2xl font-bold text-green-900">₹{{ number_format($totals['current'], 2) }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-yellow-800">0-30 Days</h3>
            <p class="text-2xl font-bold text-yellow-900">₹{{ number_format($totals['0-30'], 2) }}</p>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-orange-800">31-60 Days</h3>
            <p class="text-2xl font-bold text-orange-900">₹{{ number_format($totals['31-60'], 2) }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-red-800">61-90 Days</h3>
            <p class="text-2xl font-bold text-red-900">₹{{ number_format($totals['61-90'], 2) }}</p>
        </div>
        <div class="bg-red-100 border border-red-300 rounded-lg p-4">
            <h3 class="text-sm font-medium text-red-800">90+ Days</h3>
            <p class="text-2xl font-bold text-red-900">₹{{ number_format($totals['90+'], 2) }}</p>
        </div>
    </div>

    @foreach(['current' => 'Current', '0-30' => '0-30 Days', '31-60' => '31-60 Days', '61-90' => '61-90 Days', '90+' => '90+ Days'] as $bucket => $title)
        @if($invoices->has($bucket) && $invoices[$bucket]->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Due Date</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Days Overdue</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoices[$bucket] as $invoice)
                        <tr>
                            <td class="px-3 md:px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="truncate max-w-32">{{ $invoice->customer_name }}</div>
                            </td>
                            <td class="px-3 md:px-6 py-4 text-sm text-gray-500">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $invoice->invoice_number }}
                                </a>
                                <div class="sm:hidden text-xs text-gray-400 mt-1">
                                    {{ $invoice->due_date->format('M d') }}
                                    @if($invoice->days_overdue > 0)
                                        • <span class="text-red-600">{{ $invoice->days_overdue }}d</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 md:px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">
                                {{ $invoice->due_date->format('M d, Y') }}
                            </td>
                            <td class="px-3 md:px-6 py-4 text-sm text-gray-900">
                                ₹{{ number_format($invoice->balance, 0) }}
                            </td>
                            <td class="px-3 md:px-6 py-4 text-sm text-gray-500 hidden md:table-cell">
                                @if($invoice->days_overdue > 0)
                                    <span class="text-red-600 font-medium">{{ $invoice->days_overdue }} days</span>
                                @else
                                    <span class="text-green-600">Not overdue</span>
                                @endif
                            </td>
                            <td class="px-3 md:px-6 py-4 text-sm text-gray-500 hidden lg:table-cell">
                                <button class="text-blue-600 hover:text-blue-900 text-sm" title="Send Reminder (Coming Soon)">
                                    <i class="fas fa-envelope mr-1"></i>Remind
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach

    @if(collect($totals)->sum() == 0)
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">All Caught Up!</h3>
        <p class="text-gray-500">No outstanding invoices found.</p>
    </div>
    @endif
</div>
@endsection