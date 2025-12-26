@extends('layouts.app')
@section('title', 'Create Purchase Order')

@section('content')
<!-- Add CSRF token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Purchase Order</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('purchase-orders.index') }}" class="hover:text-blue-600 transition-colors">Purchase Orders</a>
                    > <span class="font-medium">Create</span>
                </nav>
            </div>
            <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Purchase Orders
            </a>
        </div>
    </div>

    <div id="global-error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden" role="alert"></div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form -->
    <form action="{{ route('purchase-orders.store') }}" method="POST" id="purchaseOrderForm" class="space-y-6">
        @csrf

        <!-- PO Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Purchase Order Details</h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vendor -->
                    <div>
                        <label for="vendor_id" class="block text-sm font-semibold text-gray-700 mb-2">Vendor *</label>
                        <select name="vendor_id" id="vendor_id" class="w-full h-11 px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('vendor_id') border-red-300 @enderror" required>
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- PO Date -->
                    <div>
                        <label for="po_date" class="block text-sm font-semibold text-gray-700 mb-2">PO Date *</label>
                        <input type="date" name="po_date" id="po_date" class="w-full h-11 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('po_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Order Status *</label>
                        <select name="status" id="status" class="w-full h-11 px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            @php
                                $statuses = ['pending', 'approved'];
                                $selectedStatus = old('status', 'pending');
                            @endphp
                            @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption }}" {{ $selectedStatus == $statusOption ? 'selected' : '' }}>
                                    {{ ucfirst($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" rows="4" placeholder="Additional notes or instructions for this purchase order...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Purchase Order Items</h3>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center" id="addItemRow">
                    <i class="fas fa-plus mr-2"></i>
                    Add Item
                </button>
            </div>
            <div class="p-6">
            <div id="itemsContainer">
                <div class="grid grid-cols-12 gap-2 items-end item-row mb-4 p-4 border border-gray-200 rounded-lg" data-index="0">
                    <div class="col-span-4">
                        <label for="item_name_0" class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                        <input type="text" name="items[0][item_name]" id="item_name_0" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    <div class="col-span-3">
                        <label for="description_0" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="items[0][description]" id="description_0" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label for="quantity_0" class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" name="items[0][quantity]" id="quantity_0" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 quantity" min="1" step="0.01" required>
                    </div>
                    <div class="col-span-2">
                        <label for="unit_price_0" class="block text-sm font-medium text-gray-700 mb-1">Unit Price *</label>
                        <input type="number" name="items[0][unit_price]" id="unit_price_0" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md unit-price" step="0.01" required>
                    </div>
                    <div class="col-span-1">
                        <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded-md text-sm remove-row" disabled>
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="total_amount" class="block text-sm font-semibold text-gray-700 mb-2">Total Amount</label>
                        <input type="number" name="total_amount" id="total_amount" class="w-full h-11 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 font-medium text-lg" readonly>
                    </div>
                    <div>
                        <label for="item_count" class="block text-sm font-semibold text-gray-700 mb-2">Total Items</label>
                        <input type="number" id="item_count" class="w-full h-11 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 font-medium" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('purchase-orders.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Create Purchase Order
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let itemRowIndex = 0;

    updateRemoveButtons();
    updateOrderTotals();
    
    function getItemRowHtml(index) {
        return `
            <div class="grid grid-cols-12 gap-2 items-end item-row mb-4 p-4 border border-gray-200 rounded-lg" data-index="${index}">
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                    <input type="text" name="items[${index}][item_name]" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="items[${index}][description]" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="items[${index}][quantity]" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 quantity" min="1" step="0.01" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price *</label>
                    <input type="number" name="items[${index}][unit_price]" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md unit-price" step="0.01" required>
                </div>
                <div class="col-span-1">
                    <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded-md text-sm remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
    }

    $(document).on('input', '.quantity, .unit-price', function() {
        updateOrderTotals();
    });

    function updateOrderTotals() {
        let totalAmount = 0;
        let itemCount = 0;

        $('.item-row').each(function() {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
            const rowTotal = quantity * unitPrice;
            
            totalAmount += rowTotal;
            if (quantity > 0) itemCount++;
        });

        $('#total_amount').val(totalAmount.toFixed(2));
        $('#item_count').val(itemCount);
    }

    $('#vendor_id').change(function() {
        const vendorId = $(this).val();
        if (vendorId) {
            // Fetch vendor materials and prices
            $.get(`/api/vendors/${vendorId}/materials`)
                .done(function(materials) {
                    // Update item name fields with autocomplete
                    updateItemAutocomplete(materials);
                })
                .fail(function() {
                    console.log('Failed to fetch vendor materials');
                });
        }
    });
    
    function updateItemAutocomplete(materials) {
        // Add autocomplete to existing and new item name fields
        $(document).off('input', 'input[name*="[item_name]"]');
        $(document).on('input', 'input[name*="[item_name]"]', function() {
            const input = $(this);
            const value = input.val().toLowerCase();
            const row = input.closest('.item-row');
            
            // Find matching material
            const match = materials.find(m => m.name.toLowerCase().includes(value));
            if (match && value.length > 2) {
                // Auto-fill price from vendor
                row.find('.unit-price').val(match.unit_price);
                updateOrderTotals();
            }
        });
    }
        itemRowIndex++;
        $('#itemsContainer').append(getItemRowHtml(itemRowIndex));
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('.item-row').remove();
        updateRemoveButtons();
        updateOrderTotals();
    });

    function updateRemoveButtons() {
        const rows = $('.item-row');
        $('.remove-row').prop('disabled', rows.length <= 1);
    }

    $('#purchaseOrderForm').on('submit', function(e) {
        let hasValidItems = false;
        let hasErrors = false;

        $('.item-row').each(function() {
            const row = $(this);
            const itemName = row.find('input[name*="[item_name]"]').val();
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;

            if (!itemName || quantity <= 0 || unitPrice <= 0) {
                hasErrors = true;
            } else {
                hasValidItems = true;
            }
        });

        if (hasErrors || !hasValidItems) {
            e.preventDefault();
            alert('Please fill in all required fields for each item.');
        }
    });
});
</script>
@endpush

