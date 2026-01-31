@extends('layouts.app')

@section('page-title', 'Quotation Details')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    @php
    $business = auth()->user()->business;
    @endphp
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $quotation->number }}</h1>
                        <nav class="text-sm text-gray-500 flex items-center space-x-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                                <i class="fas fa-home mr-1"></i>Home
                            </a> 
                            <i class="fas fa-chevron-right text-xs"></i>
                            <a href="{{ route('quotations.index') }}" class="hover:text-blue-600 transition-colors">Quotations</a>
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span class="font-medium text-gray-700">{{ $quotation->number }}</span>
                        </nav>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600">{{ substr($quotation->customer->name, 0, 2) }}</span>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ $quotation->customer->name }}</span>
                    </div>
                    
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $quotation->status === 'converted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 
                           ($quotation->status === 'accepted' ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-amber-100 text-amber-800 border border-amber-300') }}">
                        @if($quotation->status === 'converted')
                            <i class="fas fa-check-circle mr-2"></i>
                        @elseif($quotation->status === 'accepted')
                            <i class="fas fa-thumbs-up mr-2"></i>
                        @else
                            <i class="fas fa-edit mr-2"></i>
                        @endif
                        {{ ucfirst($quotation->status) }}
                    </span>
                    
                    @if($quotation->isSent())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Sent {{ $quotation->sent_at->format('M d') }}
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('quotations.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                    <i class="fas fa-arrow-left w-5 h-5 mr-2"></i>
                    Back
                </a>
                
                @if(!$quotation->isSent() && $quotation->status !== 'converted')
                    <form action="{{ route('quotations.mark-sent', $quotation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="fas fa-paper-plane w-5 h-5 mr-2"></i>
                            Mark as Sent
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('quotations.pdf', $quotation) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-file-pdf w-5 h-5 mr-2"></i>
                    Download PDF
                </a>
                
                @if($quotation->status !== 'converted' && (auth()->user()->isAdmin() || auth()->user()->hasPermission('edit_quotation')))
                    <a href="{{ route('quotations.edit', $quotation) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                        <i class="fas fa-edit w-5 h-5 mr-2"></i>
                        Edit
                    </a>
                @endif
                
                @if($quotation->status !== 'converted')
                    @if(auth()->user()->isAdmin() || ($canCreateInvoice && auth()->user()->hasPermission('convert_quotation_to_invoice')))
                        <form action="{{ route('quotations.convert', $quotation) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                                    onclick="return confirm('Convert to invoice?')">
                                <i class="fas fa-file-invoice w-5 h-5 mr-2"></i>
                                Convert to Invoice
                            </button>
                        </form>
                    @elseif(!$canCreateInvoice)
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" title="Upgrade to create more invoices">
                            <i class="fas fa-file-invoice w-5 h-5 mr-2"></i>
                            Convert to Invoice
                        </button>
                    @else
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" title="You don't have permission to convert quotations to invoices">
                            <i class="fas fa-file-invoice w-5 h-5 mr-2"></i>
                            Convert to Invoice
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Items Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Quotation Items</h3>
                        <span class="text-sm text-gray-500">{{ $quotation->items->count() }} items</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tax</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($quotation->items as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-blue-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->description }}</div>
                                                @if($item->material)
                                                    <div class="text-xs text-gray-500">SKU: {{ $item->material->sku ?? 'N/A' }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                            {{ ucfirst($item->unit ?? 'piece') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 border border-green-300">
                                            {{ $item->discount_percentage ?? 0 }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 border border-amber-300">
                                            {{ $item->tax_rate }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">₹{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                            <tr>
                                <td colspan="6" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Subtotal:</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">₹{{ number_format($quotation->subtotal, 2) }}</td>
                            </tr>
                            @if($quotation->discount_amount > 0)
                            <tr>
                                <td colspan="6" class="px-6 py-3 text-right text-sm font-semibold text-green-700">Discount:</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-green-700">-₹{{ number_format($quotation->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="6" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Tax Amount:</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">₹{{ number_format($quotation->tax_amount, 2) }}</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td colspan="6" class="px-6 py-4 text-right text-base font-bold text-gray-900">Total Amount:</td>
                                <td class="px-6 py-4 text-right text-xl font-bold text-blue-600">₹{{ number_format($quotation->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Details Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quotation Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Details</h3>
                    <i class="fas fa-info-circle text-gray-400"></i>
                </div>
                <dl class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <dt class="text-sm font-medium text-gray-600">Valid Until</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $quotation->valid_until->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <dt class="text-sm font-medium text-gray-600">Created Date</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $quotation->created_at->format('M d, Y') }}</dd>
                    </div>
                    
                    @if($quotation->isSent())
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Sent Date</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $quotation->sent_at->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between py-2">
                        <dt class="text-sm font-medium text-gray-600">Items Count</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $quotation->items->count() }} items</dd>
                    </div>
                </dl>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-900">Total Amount</span>
                            <span class="text-xl font-bold text-blue-600">₹{{ number_format($quotation->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Customer</h3>
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-700">{{ substr($quotation->customer->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $quotation->customer->name }}</div>
                            @if($quotation->customer->phone)
                                <div class="text-xs text-gray-500">{{ $quotation->customer->phone }}</div>
                            @endif
                        </div>
                    </div>
                    
                    @if($quotation->customer->email)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                            {{ $quotation->customer->email }}
                        </div>
                    @endif
                    
                    @if($quotation->customer->city || $quotation->customer->state)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-4"></i>
                            {{ $quotation->customer->city }}{{ $quotation->customer->city && $quotation->customer->state ? ', ' : '' }}{{ $quotation->customer->state }}
                        </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('customers.show', $quotation->customer) }}" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View Customer Details
                    </a>
                </div>
            </div>
            
            @if($quotation->notes)
                <!-- Notes Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                        <i class="fas fa-sticky-note text-gray-400"></i>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $quotation->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection