@extends('layouts.app')

@section('page-title', 'Edit Quotation')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Quotation {{ $quotation->number }}</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('quotations.index') }}" class="hover:text-blue-600 transition-colors">Quotations</a>
                    > <span class="font-medium">Edit</span>
                </nav>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('quotations.show', $quotation) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    View
                </a>
                <a href="{{ route('quotations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('quotations.update', $quotation) }}" method="POST" id="quotationForm" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Customer & Validity Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-semibold text-gray-700 mb-2">Customer *</label>
                        <select name="customer_id" id="customer_id" class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('customer_id', $quotation->customer_id) == $customer->id) ? 'selected' : '' }}>
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
                               value="{{ old('valid_until', $quotation->valid_until->format('Y-m-d')) }}" required>
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
                              placeholder="Additional notes, terms, or special instructions for this quotation...">{{ old('notes', $quotation->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Quotation Items</h5>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="itemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Material</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Tax %</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            <!-- Items will be added here -->
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                                <td><strong id="subtotalDisplay">₹0.00</strong></td>
                                                <td></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td colspan="5" class="text-end"><strong>Tax:</strong></td>
                                                <td><strong id="taxDisplay">₹0.00</strong></td>
                                                <td></td>
                                            </tr>
                                            <tr class="table-success">
                                                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                                <td><strong id="totalDisplay">₹0.00</strong></td>
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
                    @canEditInModule('quotations')
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Quotation
                    </button>
                    @endcanEditInModule
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
    row.innerHTML = `
        <td>
            <select name="items[${itemIndex}][material_id]" class="form-select" required onchange="updateDescription(${itemIndex})">
                <option value="">Select Material</option>
                ${materials.map(m => `<option value="${m.id}" data-price="${m.unit_price}" data-name="${m.name}">${m.name} (${m.code})</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="text" name="items[${itemIndex}][description]" class="form-control" required>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control" step="0.01" min="0.01" required onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][unit_price]" class="form-control" step="0.01" min="0" required onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][tax_rate]" class="form-control" step="0.01" min="0" max="100" value="18" required onchange="calculateRowTotal(${itemIndex})">
        </td>
        <td>
            <span class="row-total">₹0.00</span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
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
    const taxRate = parseFloat(document.querySelector(`input[name="items[${index}][tax_rate]"]`).value) || 0;
    
    const subtotal = qty * price;
    const tax = (subtotal * taxRate) / 100;
    const total = subtotal + tax;
    
    document.querySelector(`tr:nth-child(${index + 1}) .row-total`).textContent = `₹${total.toFixed(2)}`;
    updateTotals();
}

function removeItem(button) {
    button.closest('tr').remove();
    updateTotals();
}

function updateTotals() {
    let subtotal = 0;
    let totalTax = 0;
    
    document.querySelectorAll('#itemsBody tr').forEach((row, index) => {
        const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
        const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;
        
        const itemSubtotal = qty * price;
        const itemTax = (itemSubtotal * taxRate) / 100;
        
        subtotal += itemSubtotal;
        totalTax += itemTax;
    });
    
    document.getElementById('subtotalDisplay').textContent = `₹${subtotal.toFixed(2)}`;
    document.getElementById('taxDisplay').textContent = `₹${totalTax.toFixed(2)}`;
    document.getElementById('totalDisplay').textContent = `₹${(subtotal + totalTax).toFixed(2)}`;
}

// Add existing items or first item on page load
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($quotation) && $quotation->items->count() > 0)
        @foreach($quotation->items as $index => $item)
            addItem();
            setTimeout(() => {
                const row = document.querySelector(`#itemsBody tr:nth-child({{ $index + 1 }})`);
                if (row) {
                    const materialSelect = row.querySelector('select[name*="[material_id]"]');
                    const descInput = row.querySelector('input[name*="[description]"]');
                    const qtyInput = row.querySelector('input[name*="[quantity]"]');
                    const priceInput = row.querySelector('input[name*="[unit_price]"]');
                    const taxInput = row.querySelector('input[name*="[tax_rate]"]');
                    
                    if (materialSelect) materialSelect.value = '{{ $item->material_id }}';
                    if (descInput) descInput.value = '{{ addslashes($item->description) }}';
                    if (qtyInput) qtyInput.value = '{{ $item->quantity }}';
                    if (priceInput) priceInput.value = '{{ $item->unit_price }}';
                    if (taxInput) taxInput.value = '{{ $item->tax_rate }}';
                    
                    calculateRowTotal({{ $index }});
                }
            }, 100);
        @endforeach
    @else
        addItem();
    @endif
});
</script>
@endsection