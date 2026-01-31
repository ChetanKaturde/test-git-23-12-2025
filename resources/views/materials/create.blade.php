@extends('layouts.app')
@section('title', 'Add Commodity')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Add New Commodity</h2>
        <a href="{{ route('materials.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Commodities
        </a>
    </div>

    <form action="{{ route('materials.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Commodity Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2" title="Goods are tracked in inventory. Services are non-physical (e.g., hours).">Type *</label>
                <select name="item_type" id="item_type" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="good" {{ old('item_type', 'good') == 'good' ? 'selected' : '' }}>📦 Good (Physical Product)</option>
                    <option value="service" {{ old('item_type') == 'service' ? 'selected' : '' }}>💼 Service</option>
                </select>
                @error('item_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="hsn_code_field">
                <label for="hsn_code" class="block text-sm font-medium text-gray-700 mb-2" title="Mandatory for B2B invoices over ₹50,000. Find at gst.gov.in">HSN Code</label>
                <input type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code') }}"
                       placeholder="e.g., 7208, 3920, 8471"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('hsn_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="sac_code_field">
                <label for="sac_code" class="block text-sm font-medium text-gray-700 mb-2" title="Service Accounting Code for services">SAC Code</label>
                <input type="text" name="sac_code" id="sac_code" value="{{ old('sac_code') }}"
                       placeholder="e.g., 9983, 9992"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('sac_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="sku_field">
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" 
                       placeholder="Auto-generated if empty"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('sku')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="barcode_field">
                <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                       placeholder="Auto-generated if empty"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('barcode')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Unit System -->
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                <select name="unit" id="unit"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" selected disabled>Select Unit</option>
                    <!-- Good units -->
                    <optgroup label="For Goods" id="good_units">
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter</option>
                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                        <option value="ton" {{ old('unit') == 'ton' ? 'selected' : '' }}>Ton</option>
                    </optgroup>
                    <!-- Service units -->
                    <optgroup label="For Services" id="service_units">
                        <option value="hour" {{ old('unit') == 'hour' ? 'selected' : '' }}>Hour</option>
                        <option value="day" {{ old('unit') == 'day' ? 'selected' : '' }}>Day</option>
                        <option value="session" {{ old('unit') == 'session' ? 'selected' : '' }}>Session</option>
                        <option value="project" {{ old('unit') == 'project' ? 'selected' : '' }}>Project</option>
                        <option value="visit" {{ old('unit') == 'visit' ? 'selected' : '' }}>Visit</option>
                        <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>Unit</option>
                    </optgroup>
                </select>
                @error('unit')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="weight_per_piece_div" style="display: none;">
                <label for="estimated_weight_per_piece" class="block text-sm font-medium text-gray-700 mb-2">Est. Weight per Piece (kg)</label>
                <input type="number" name="estimated_weight_per_piece" id="estimated_weight_per_piece" 
                       value="{{ old('estimated_weight_per_piece') }}" step="0.0001" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('estimated_weight_per_piece')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing -->
            <div>
                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price (₹) *</label>
                <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price') }}" 
                       step="0.01" min="0" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('unit_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gst_rate" class="block text-sm font-medium text-gray-700 mb-2">GST Rate (%)</label>
                <input type="number" name="gst_rate" id="gst_rate" value="{{ old('gst_rate', 18) }}" 
                       step="0.01" min="0" max="100"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('gst_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" id="category" value="{{ old('category') }}" 
                       placeholder="e.g., Metals, Plastics, Electronics"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4 mt-8">
            <a href="{{ route('materials.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Add Commodity
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemType = document.getElementById('item_type');
    const skuField = document.getElementById('sku_field');
    const barcodeField = document.getElementById('barcode_field');
    const unitSelect = document.getElementById('unit');
    const goodUnits = document.getElementById('good_units');
    const serviceUnits = document.getElementById('service_units');

    function toggleFields() {
        const isService = itemType.value === 'service';

        // Hide SKU/barcode for services
        skuField.style.display = isService ? 'none' : 'block';
        barcodeField.style.display = isService ? 'none' : 'block';

        // Toggle HSN and SAC codes based on item_type
        const hsnField = document.getElementById('hsn_code_field');
        hsnField.style.display = isService ? 'none' : 'block';
        const sacField = document.getElementById('sac_code_field');
        sacField.style.display = isService ? 'block' : 'none';

        // Reset unit for services, set default for goods
        if (isService) {
            unitSelect.value = '';
        } else if (!unitSelect.value) {
            unitSelect.value = 'kg';
        }

        // Show/hide unit groups
        goodUnits.style.display = isService ? 'none' : 'block';
        serviceUnits.style.display = isService ? 'block' : 'none';
    }

    itemType.addEventListener('change', toggleFields);

    // Initial check
    toggleFields();
});
</script>
@endsection