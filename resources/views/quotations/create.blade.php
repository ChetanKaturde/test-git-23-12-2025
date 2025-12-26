@extends('layouts.app')

@section('page-title', 'Create Quotation')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Quotation</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('quotations.index') }}" class="hover:text-blue-600 transition-colors">Quotations</a>
                    > <span class="font-medium">Create</span>
                </nav>
            </div>
            <a href="{{ route('quotations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Quotations
            </a>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('quotations.store') }}" method="POST" id="quotationForm" class="space-y-6">
                @csrf
                
                <!-- Customer & Validity Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-semibold text-gray-700 mb-2">Customer *</label>
                        <select name="customer_id" id="customer_id" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="valid_until" class="block text-sm font-semibold text-gray-700 mb-2">Valid Until *</label>
                        <input type="date" name="valid_until" id="valid_until" 
                               class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d')) }}" required>
                        @error('valid_until')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes Section -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                              placeholder="Additional notes, terms, or special instructions for this quotation...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Items Section -->
                <div class="border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900">Quotation Items</h3>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center" onclick="addItem()">
                            <i class="fas fa-plus mr-2"></i>
                            Add Item
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full" id="itemsTable">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Discount %</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Tax %</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="text-center py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody" class="divide-y divide-gray-200">
                                    <!-- Items will be added here -->
                                </tbody>
                                <tfoot class="border-t-2 border-gray-300">
                                    <tr class="bg-gray-50">
                                        <td colspan="7" class="py-3 px-4 text-right font-semibold text-gray-900">Subtotal:</td>
                                        <td class="py-3 px-4 font-semibold text-gray-900" id="subtotalDisplay">₹0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td colspan="7" class="py-3 px-4 text-right font-semibold text-gray-900">Tax:</td>
                                        <td class="py-3 px-4 font-semibold text-gray-900" id="taxDisplay">₹0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td colspan="7" class="py-3 px-4 text-right font-bold text-gray-900">Total:</td>
                                        <td class="py-3 px-4 font-bold text-blue-600 text-lg" id="totalDisplay">₹0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('quotations.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create Quotation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let itemIndex = 0;
const materials = @json($materials);

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50';
    row.innerHTML = `
        <td class="py-3 px-4">
            <select name="items[${itemIndex}][material_id]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" required onchange="updateDescription(${itemIndex})">
                <option value="">Choose a commodity</option>
                ${materials.map(m => `<option value="${m.id}" data-price="${m.unit_price}" data-name="${m.name}">${m.name} (${m.code})</option>`).join('')}
            </select>
        </td>
        <td class="py-3 px-4">
            <input type="text" name="items[${itemIndex}][description]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" required>
        </td>
        <td class="py-3 px-4">
            <input type="number" name="items[${itemIndex}][quantity]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" step="0.01" min="0.01" required onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td class="py-3 px-4">
            <select name="items[${itemIndex}][unit]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" required>
                <option value="piece">Piece</option>
                <option value="kg">Kg</option>
                <option value="hour">Hour</option>
                <option value="meter">Meter</option>
                <option value="liter">Liter</option>
                <option value="box">Box</option>
                <option value="pack">Pack</option>
            </select>
        </td>
        <td class="py-3 px-4">
            <input type="number" name="items[${itemIndex}][unit_price]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" step="0.01" min="0" required onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td class="py-3 px-4">
            <input type="number" name="items[${itemIndex}][discount_percentage]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" step="0.01" min="0" max="100" value="0" onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td class="py-3 px-4">
            <input type="number" name="items[${itemIndex}][tax_rate]" class="w-full h-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" step="0.01" min="0" max="100" value="18" required onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td class="py-3 px-4">
            <span class="row-total font-medium text-gray-900">₹0.00</span>
        </td>
        <td class="py-3 px-4 text-center">
            <button type="button" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" onclick="removeItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    itemIndex++;
}

function updateDescription(index) {
    const select = document.querySelector(`select[name="items[${index}][material_id]"]`);
    const descInput = document.querySelector(`input[name="items[${index}][description]"]`);
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    
    if (select.value) {
        const option = select.selectedOptions[0];
        descInput.value = option.dataset.name;
        priceInput.value = option.dataset.price;
        calculateRowTotal(index);
    }
}

function calculateRowTotal(index) {
    const qty = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value) || 0;
    const discountPercent = parseFloat(document.querySelector(`input[name="items[${index}][discount_percentage]"]`).value) || 0;
    const taxRate = parseFloat(document.querySelector(`input[name="items[${index}][tax_rate]"]`).value) || 0;
    
    const subtotal = qty * price;
    const discountAmount = (subtotal * discountPercent) / 100;
    const taxableAmount = subtotal - discountAmount;
    const tax = (taxableAmount * taxRate) / 100;
    const total = taxableAmount + tax;
    
    document.querySelector(`tr:nth-child(${index + 1}) .row-total`).textContent = `₹${total.toFixed(2)}`;
    updateTotals();
}

function removeItem(button) {
    button.closest('tr').remove();
    updateTotals();
}

function updateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    
    document.querySelectorAll('#itemsBody tr').forEach((row, index) => {
        const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
        const discountPercent = parseFloat(row.querySelector('input[name*="[discount_percentage]"]').value) || 0;
        const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;
        
        const itemSubtotal = qty * price;
        const itemDiscount = (itemSubtotal * discountPercent) / 100;
        const taxableAmount = itemSubtotal - itemDiscount;
        const itemTax = (taxableAmount * taxRate) / 100;
        
        subtotal += itemSubtotal;
        totalDiscount += itemDiscount;
        totalTax += itemTax;
    });
    
    document.getElementById('subtotalDisplay').textContent = `₹${subtotal.toFixed(2)}`;
    document.getElementById('taxDisplay').textContent = `₹${totalTax.toFixed(2)}`;
    document.getElementById('totalDisplay').textContent = `₹${(subtotal - totalDiscount + totalTax).toFixed(2)}`;
}

// Add first item on page load
document.addEventListener('DOMContentLoaded', function() {
    addItem();
});
</script>
@endsection