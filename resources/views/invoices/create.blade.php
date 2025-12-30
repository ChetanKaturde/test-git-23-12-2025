@extends('layouts.app')

@section('page-title', 'Create Invoice')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Invoice</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('invoices.index') }}" class="hover:text-blue-600 transition-colors">Invoices</a>
                    > <span class="font-medium">Create</span>
                </nav>
            </div>
            <a href="{{ route('invoices.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Invoices
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm" class="space-y-6">
        @csrf
        
        <!-- Invoice Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Invoice Details</h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Quotation Selection & Due Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quotation_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Quotation *</label>
                        <select name="quotation_id" id="quotation_id" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <option value="">Select Quotation</option>
                            @foreach($quotations as $quotation)
                                <option value="{{ $quotation->id }}" data-customer="{{ $quotation->customer->name }}" data-customer-email="{{ $quotation->customer->email }}" data-customer-phone="{{ $quotation->customer->phone }}" data-customer-address="{{ $quotation->customer->address }}" data-customer-gstin="{{ $quotation->customer->gstin }}">
                                    {{ $quotation->number }} - {{ $quotation->customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">Due Date *</label>
                        <input type="date" name="due_date" id="due_date" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                    </div>
                </div>

                <!-- Customer Information (Auto-populated from quotation) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">Customer Email</label>
                        <input type="email" name="customer_email" id="customer_email" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">Customer Phone</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                    </div>
                    <div>
                        <label for="customer_gstin" class="block text-sm font-semibold text-gray-700 mb-2">Customer GSTIN</label>
                        <input type="text" name="customer_gstin" id="customer_gstin" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                    </div>
                </div>

                <div>
                    <label for="customer_address" class="block text-sm font-semibold text-gray-700 mb-2">Customer Address</label>
                    <textarea name="customer_address" id="customer_address" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 resize-none" rows="3" readonly></textarea>
                </div>
            </div>
        </div>

        <!-- Invoice Items Card (Auto-populated from quotation) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Invoice Items</h3>
                <p class="text-sm text-gray-600 mt-1">Items will be automatically loaded from the selected quotation</p>
            </div>
            <div class="p-6">
                <div id="items-container" class="space-y-4">
                    <!-- Items will be populated via JavaScript -->
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-file-invoice-dollar text-4xl mb-4"></i>
                        <p>Select a quotation to load invoice items</p>
                    </div>
                </div>

                <!-- Invoice Totals -->
                <div id="invoice-totals" class="mt-6 border-t pt-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2"></div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                <span id="subtotal-display" class="text-sm font-semibold text-gray-900">₹0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Tax Amount:</span>
                                <span id="tax-display" class="text-sm font-semibold text-gray-900">₹0.00</span>
                            </div>
                            <div class="flex justify-between items-center border-t pt-2">
                                <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                                <span id="grand-total-display" class="text-lg font-bold text-gray-900">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('invoices.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </a>
            @canCreateInModule('invoices')
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Create Invoice
            </button>
            @endcanCreateInModule
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quotationSelect = document.getElementById('quotation_id');
    const customerNameField = document.getElementById('customer_name');
    const customerEmailField = document.getElementById('customer_email');
    const customerPhoneField = document.getElementById('customer_phone');
    const customerAddressField = document.getElementById('customer_address');
    const customerGstinField = document.getElementById('customer_gstin');
    const itemsContainer = document.getElementById('items-container');

    quotationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            // Populate customer information
            customerNameField.value = selectedOption.getAttribute('data-customer') || '';
            customerEmailField.value = selectedOption.getAttribute('data-customer-email') || '';
            customerPhoneField.value = selectedOption.getAttribute('data-customer-phone') || '';
            customerAddressField.value = selectedOption.getAttribute('data-customer-address') || '';
            customerGstinField.value = selectedOption.getAttribute('data-customer-gstin') || '';

            // Load quotation items
            loadQuotationItems(this.value);
        } else {
            // Clear customer information
            customerNameField.value = '';
            customerEmailField.value = '';
            customerPhoneField.value = '';
            customerAddressField.value = '';
            customerGstinField.value = '';

            // Clear items
            itemsContainer.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-invoice-dollar text-4xl mb-4"></i>
                    <p>Select a quotation to load invoice items</p>
                </div>
            `;
            hideInvoiceTotals();
        }
    });

    function loadQuotationItems(quotationId) {
        fetch(`/api/quotations/${quotationId}/items`)
            .then(response => response.json())
            .then(data => {
                if (data.items && data.items.length > 0) {
                    let itemsHtml = '';
                    data.items.forEach((item, index) => {
                        itemsHtml += `
                            <div class="item-row bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <input type="text" name="items[${index}][description]" value="${item.description || ''}" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                        <input type="number" name="items[${index}][quantity]" value="${item.quantity || ''}" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" step="0.01" readonly>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                                        <input type="number" name="items[${index}][unit_price]" value="${item.unit_price || ''}" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" step="0.01" readonly>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                                        <input type="number" name="items[${index}][tax_rate]" value="${item.tax_rate || ''}" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" step="0.01" readonly>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                                        <input type="number" value="${item.total_amount || ''}" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 font-semibold" step="0.01" readonly>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    itemsContainer.innerHTML = itemsHtml;

                    // Calculate and display totals
                    calculateInvoiceTotals(data.items);
                } else {
                    itemsContainer.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                            <p>No items found in this quotation</p>
                        </div>
                    `;
                    hideInvoiceTotals();
                }
            })
            .catch(error => {
                console.error('Error loading quotation items:', error);
                itemsContainer.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                        <p>Error loading quotation items</p>
                    </div>
                `;
                hideInvoiceTotals();
            });
    }

    function calculateInvoiceTotals(items) {
        let subtotal = 0;
        let totalTax = 0;

        items.forEach(item => {
            const quantity = parseFloat(item.quantity) || 0;
            const unitPrice = parseFloat(item.unit_price) || 0;
            const taxRate = parseFloat(item.tax_rate) || 0;

            const lineTotal = quantity * unitPrice;
            const lineTax = lineTotal * (taxRate / 100);

            subtotal += lineTotal;
            totalTax += lineTax;
        });

        const grandTotal = subtotal + totalTax;

        // Update display
        document.getElementById('subtotal-display').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('tax-display').textContent = '₹' + totalTax.toFixed(2);
        document.getElementById('grand-total-display').textContent = '₹' + grandTotal.toFixed(2);

        // Show totals section
        document.getElementById('invoice-totals').classList.remove('hidden');
    }

    function hideInvoiceTotals() {
        document.getElementById('invoice-totals').classList.add('hidden');
    }
});
</script>
@endsection