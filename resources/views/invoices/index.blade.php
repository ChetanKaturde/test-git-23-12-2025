@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    @php
        $business = auth()->user()->business;
        $canCreateInvoice = $business->canCreateInvoice();
        $invoiceCount = $business->getInvoiceCount();
    @endphp
    
    <!-- Free Plan Limit Banner -->
    @if($business->subscription_plan === 'free' && !$canCreateInvoice)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                <div>
                    <p class="text-blue-800 font-medium">You've reached your Free Plan limit of 50 invoices/month.</p>
                    <p class="text-blue-700 text-sm">Current usage: {{ $invoiceCount }}/50 invoices this month</p>
                </div>
            </div>
            <a href="/pricing" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                Upgrade to Unlimited
            </a>
        </div>
    </div>
    @endif
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Invoices</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a> 
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">Invoices</span>
                </nav>
                <p class="text-gray-600 mt-1">Track billing, payments, and customer invoices</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex items-center space-x-2 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span>{{ $invoices->count() }} Total</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $invoices->count() }}</p>
                    <div class="flex items-center mt-2 text-xs text-blue-600">
                        <i class="fas fa-file-invoice mr-1"></i>
                        <span>All time</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Paid Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $invoices->where('status', 'paid')->count() }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>Completed</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Payment</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $invoices->where('status', 'draft')->count() + $invoices->where('status', 'sent')->count() }}</p>
                    <div class="flex items-center mt-2 text-xs text-yellow-600">
                        <i class="fas fa-clock mr-1"></i>
                        <span>Awaiting payment</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($invoices->sum('total_amount'), 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-purple-600">
                        <i class="fas fa-rupee-sign mr-1"></i>
                        <span>Invoice value</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    @if($invoices->count() > 0)
        <!-- Invoices Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Filter:</label>
                            <select id="statusFilter" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-sort mr-1"></i>
                            <span>Latest first</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                            @if(auth()->user()->business->subscription_tier !== 'billing_sales')
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Work Order</th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-invoice text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $invoice->invoice_number }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($invoice->quotation_id)
                                                <i class="fas fa-link mr-1"></i>From quotation
                                            @else
                                                <i class="fas fa-file mr-1"></i>Direct invoice
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600">{{ substr($invoice->customer_name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->customer_name }}</div>
                                        @if($invoice->customer_email)
                                            <div class="text-xs text-gray-500">{{ $invoice->customer_email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @if(auth()->user()->business->subscription_tier !== 'billing_sales')
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($invoice->workOrder)
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        <a href="{{ route('work-orders.show', $invoice->workOrder) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $invoice->workOrder->work_order_number ?? 'WO-' . $invoice->workOrder->id }}
                                        </a>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 flex items-center">
                                        <i class="fas fa-minus mr-2"></i>No work order
                                    </span>
                                @endif
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'Not set' }}
                                </div>
                                @if($invoice->issue_date)
                                    <div class="text-xs text-gray-500">
                                        {{ $invoice->issue_date->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                    {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'Not set' }}
                                </div>
                                @if($invoice->due_date)
                                    <div class="text-xs {{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-500' : 'text-gray-500' }}">
                                        @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                                        @else
                                            {{ $invoice->due_date->diffForHumans() }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">₹{{ number_format($invoice->total_amount, 2) }}</div>
                                @if($invoice->items && $invoice->items->count() > 0)
                                    <div class="text-xs text-gray-500">{{ $invoice->items->count() }} items</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($invoice->status === 'paid') bg-green-100 text-green-800
                                    @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                                    @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($invoice->status === 'paid')
                                        <i class="fas fa-check-circle mr-1"></i>
                                    @elseif($invoice->status === 'overdue')
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                    @elseif($invoice->status === 'sent')
                                        <i class="fas fa-paper-plane mr-1"></i>
                                    @else
                                        <i class="fas fa-edit mr-1"></i>
                                    @endif
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                    @if($invoice->status !== 'paid')
                                        <a href="{{ route('invoices.edit', $invoice) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs font-medium">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                    @endif
                                    <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs font-medium"
                                       title="Download PDF">
                                        <i class="fas fa-download mr-1"></i>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="text-center py-12 px-6">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">No Invoices Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Start billing your customers by creating your first invoice. Track payments and manage your revenue efficiently.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('quotations.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        View Quotations
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const statusElement = row.querySelector('.inline-flex');
        if (!statusElement) return;
        
        const statusText = statusElement.textContent.trim().toLowerCase();
        const isVisible = selectedStatus === '' || statusText.includes(selectedStatus);
        row.style.display = isVisible ? '' : 'none';
    });
});
</script>
@endsection