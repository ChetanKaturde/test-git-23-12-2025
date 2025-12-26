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
                <!-- Work Order & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="work_order_id" class="block text-sm font-semibold text-gray-700 mb-2">Work Order (Optional)</label>
                        <select name="work_order_id" id="work_order_id" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Work Order</option>
                            @foreach($workOrders as $workOrder)
                                <option value="{{ $workOrder->id }}">{{ $workOrder->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="invoice_date" class="block text-sm font-semibold text-gray-700 mb-2">Invoice Date *</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Customer Name *</label>
                        <input type="text" name="customer_name" id="customer_name" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">Customer Email</label>
                        <input type="email" name="customer_email" id="customer_email" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">Customer Phone</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label for="customer_gstin" class="block text-sm font-semibold text-gray-700 mb-2">Customer GSTIN</label>
                        <input type="text" name="customer_gstin" id="customer_gstin" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_address" class="block text-sm font-semibold text-gray-700 mb-2">Customer Address *</label>
                        <textarea name="customer_address" id="customer_address" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" rows="3" required></textarea>
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Invoice Items</h3>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center" onclick="addItem()">
                    <i class="fas fa-plus mr-2"></i>
                    Add Item
                </button>
            </div>
            <div class="p-6">
                <div id="items-container" class="space-y-4">
                    <div class="item-row">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-4">
                                <input type="text" name="items[0][description]" placeholder="Item description" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="items[0][quantity]" placeholder="Qty" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="items[0][unit_price]" placeholder="Unit Price" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" step="0.01" min="0" required>
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="items[0][tax_rate]" placeholder="Tax %" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" step="0.01" min="0" max="100" value="18" required>
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="w-full h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors inline-flex items-center justify-center" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
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
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Create Invoice
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const newItem = document.createElement('div');
    newItem.className = 'item-row';
    newItem.innerHTML = `
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-4">
                <input type="text" name="items[${itemIndex}][description]" placeholder="Item description" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" step="0.01" min="0.01" required>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${itemIndex}][unit_price]" placeholder="Unit Price" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" step="0.01" min="0" required>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${itemIndex}][tax_rate]" placeholder="Tax %" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" step="0.01" min="0" max="100" value="18" required>
            </div>
            <div class="col-span-2">
                <button type="button" class="w-full h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors inline-flex items-center justify-center" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    itemIndex++;
}

function removeItem(button) {
    const itemRow = button.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        itemRow.remove();
    }
}
</script>
@endsection