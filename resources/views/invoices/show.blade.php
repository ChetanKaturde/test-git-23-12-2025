@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Hero Title Section -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <!-- Primary Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $invoice->invoice_number }}</h1>
                <!-- Subtitle -->
                <p class="text-gray-600 mb-4">{{ $invoice->customer_name }}</p>
                
                <!-- Status Badges -->
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full 
                        {{ $invoice->isSent() ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        <div class="w-2 h-2 mr-2 rounded-full {{ $invoice->isSent() ? 'bg-blue-400' : 'bg-gray-400' }}"></div>
                        {{ $invoice->isSent() ? 'Sent' : 'Draft' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full 
                        {{ $invoice->isFullyPaid() ? 'bg-green-100 text-green-800' : ($invoice->totalPaid() > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                        <div class="w-2 h-2 mr-2 rounded-full {{ $invoice->isFullyPaid() ? 'bg-green-400' : ($invoice->totalPaid() > 0 ? 'bg-yellow-400' : 'bg-gray-400') }}"></div>
                        {{ $invoice->isFullyPaid() ? 'Payment Received' : ($invoice->totalPaid() > 0 ? 'Partial Payment' : 'Unpaid') }}
                    </span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                
                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </a>
                
                @if($invoice->status === 'draft')
                    <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
                
                @if($invoice->isFullyPaid())
                    @if(!$invoice->isSent())
                        <form action="{{ route('invoices.mark-sent', $invoice) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>Send Invoice
                            </button>
                        </form>
                    @endif
                @else
                    <button class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors" onclick="openPaymentModal()">
                        <i class="fas fa-plus mr-2"></i>Record Payment
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Success Banner -->
    @if($invoice->isFullyPaid())
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <span class="text-green-800 font-medium">Payment Received - Invoice fully paid</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Bill Details</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-600">Description</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-600">Qty</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">Unit Price</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">Tax</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($invoice->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 text-right">{{ $item->tax_rate }}%</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">₹{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            @if(!$invoice->isFullyPaid())
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm text-gray-600">Subtotal:</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-right">₹{{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm text-gray-600">Tax:</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="bg-gray-50">
                                <td colspan="4" class="px-6 py-4 text-right text-lg font-bold text-gray-900">Total:</td>
                                <td class="px-6 py-4 text-lg font-bold text-gray-900 text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                            @if($invoice->totalPaid() > 0)
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Paid:</td>
                                <td class="px-6 py-3 text-sm font-medium text-blue-600 text-right">₹{{ number_format($invoice->totalPaid(), 2) }}</td>
                            </tr>
                            <tr class="{{ $invoice->isFullyPaid() ? 'bg-green-50' : 'bg-red-50' }}">
                                <td colspan="4" class="px-6 py-4 text-right text-lg font-bold text-gray-900">
                                    {{ $invoice->isFullyPaid() ? 'Payment Received' : 'Balance Due:' }}
                                </td>
                                <td class="px-6 py-4 text-lg font-bold {{ $invoice->isFullyPaid() ? 'text-green-600' : 'text-red-600' }} text-right">
                                    {{ $invoice->isFullyPaid() ? 'PAID' : '₹' . number_format($invoice->balance(), 2) }}
                                </td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Payment Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg">
                <div class="px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900">Payment Summary</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-blue-700">Total Amount</dt>
                            <dd class="text-sm font-bold text-blue-900">₹{{ number_format($invoice->total_amount, 2) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-blue-700">Amount Paid</dt>
                            <dd class="text-sm font-bold text-blue-900">₹{{ number_format($invoice->totalPaid(), 2) }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-blue-200 pt-3">
                            <dt class="text-sm font-bold text-blue-900">Balance</dt>
                            <dd class="text-sm font-bold {{ $invoice->isFullyPaid() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $invoice->isFullyPaid() ? 'PAID' : '₹' . number_format($invoice->balance(), 2) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Invoice Dates -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Invoice Dates</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Issue Date</dt>
                            <dd class="text-sm text-gray-900">{{ $invoice->issue_date->format('M d, Y') }}</dd>
                        </div>
                        @if($invoice->due_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Due Date</dt>
                                <dd class="text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</dd>
                            </div>
                        @endif
                        @if($invoice->isSent())
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Sent Date</dt>
                                <dd class="text-sm text-gray-900">{{ $invoice->sent_at->format('M d, Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                    
                    @if($invoice->due_date && $invoice->due_date->isPast() && !$invoice->isFullyPaid())
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                <div class="text-sm text-red-700">
                                    <strong>Overdue</strong> since {{ $invoice->due_date->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Details -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Details</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Name</dt>
                            <dd class="text-sm text-gray-900">{{ $invoice->customer_name }}</dd>
                        </div>
                        @if($invoice->customer_email)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $invoice->customer_email }}</dd>
                            </div>
                        @endif
                        @if($invoice->customer_phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $invoice->customer_phone }}</dd>
                            </div>
                        @endif
                        @if($invoice->customer_gstin)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">GSTIN</dt>
                                <dd class="text-sm text-gray-900">{{ $invoice->customer_gstin }}</dd>
                            </div>
                        @endif
                        @if($invoice->customer_address)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Address</dt>
                                <dd class="text-sm text-gray-900">{{ $invoice->customer_address }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if($invoice->quotation)
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Source Quotation</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Quotation Number</dt>
                            <dd class="text-sm text-gray-900">{{ $invoice->quotation->number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Date</dt>
                            <dd class="text-sm text-gray-900">{{ $invoice->quotation->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Status</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($invoice->quotation->status) }}</dd>
                        </div>
                    </dl>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('quotations.show', $invoice->quotation) }}"
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            View Quotation
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment History -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                </div>
                <div class="px-6 py-4">
                    @if($invoice->payments->count() > 0)
                        <div class="space-y-3">
                            @foreach($invoice->payments as $payment)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">₹{{ number_format($payment->amount, 2) }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }} • {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        {{-- @if($payment->reference_no)
                                            <div class="text-xs text-gray-400">{{ $payment->reference_no }}</div>
                                        @endif --}}
                                        <a href="{{ route('invoices.payments.receipt', $payment) }}"
                                           class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium"
                                           title="Download Receipt">
                                           <i class="fas fa-download mr-1"></i>
                                           Receipt
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt text-gray-300 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">No payments recorded yet</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($invoice->notes)
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Record Payment</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount *</label>
                        <input type="number" name="amount" step="0.01" max="{{ $invoice->balance() }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="0.00" required>
                        <p class="text-xs text-gray-500 mt-1">Maximum: ₹{{ number_format($invoice->balance(), 2) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Date *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method *</label>
                        <select name="payment_method" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI</option>
                            <option value="card">Card</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reference Number</label>
                        <input type="text" name="reference_no" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Transaction ID, Cheque No, etc.">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors">
                        Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}
</script>
@endsection