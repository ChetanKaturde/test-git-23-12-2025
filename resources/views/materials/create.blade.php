@extends('layouts.app')
@section('title', 'Add Commodity')
@section('page-title', 'Add New Commodity')

@section('content')
<div class="p-6">
    <!-- Onboarding Header -->
    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border border-blue-200 rounded-xl p-6 mb-6">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Adding Your First Commodity</h3>
                <p class="text-gray-700 mb-4">Commodities are the foundation of your business - materials you buy, products you make, or services you offer. Fill in the details below to get started.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-blue-200">
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fas fa-lightbulb text-blue-600"></i>
                            <span class="font-semibold text-gray-900 text-sm">Pro Tip</span>
                        </div>
                        <p class="text-sm text-gray-600">Start with your most commonly used materials or best-selling products.</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-blue-200">
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fas fa-clock text-green-600"></i>
                            <span class="font-semibold text-gray-900 text-sm">Quick Setup</span>
                        </div>
                        <p class="text-sm text-gray-600">Only name, type, unit, and price are required. Add details later as needed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Add New Commodity</h2>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a> 
                > <a href="{{ route('materials.index') }}" class="hover:text-indigo-600">Commodities</a>
                > <span class="font-medium">Add New</span>
            </nav>
        </div>
        <a href="{{ route('materials.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors"
           data-tooltip="Return to commodities list">
            <i class="fas fa-arrow-left mr-2"></i>Back to Commodities
        </a>
    </div>

    <form action="{{ route('materials.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                <span>Basic Information</span>
                <span>Pricing & Details</span>
                <span>Optional Settings</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 33%" id="progress-bar"></div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information Section -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Basic Information
                </h3>
            </div>
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Commodity Name *
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Enter a clear, descriptive name for your commodity. This will appear on invoices and reports."></i>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                       placeholder="e.g., Mild Steel Sheet, Welding Service, Aluminum Rod"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       required data-hint="material-name">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Type *
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Goods are physical items tracked in inventory. Services are non-physical offerings like labor or consulting."></i>
                </label>
                <select name="item_type" id="item_type" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        required>
                    <option value="good" {{ old('item_type', 'good') == 'good' ? 'selected' : '' }}>📦 Good (Physical Product)</option>
                    <option value="service" {{ old('item_type') == 'service' ? 'selected' : '' }}>💼 Service</option>
                </select>
                @error('item_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="hsn_code_field">
                <label for="hsn_code" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    HSN Code
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Harmonized System of Nomenclature code for GST compliance. Required for B2B invoices over ₹50,000. Find codes at gst.gov.in"></i>
                </label>
                <input type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code') }}" 
                       placeholder="e.g., 7208 (for steel), 3920 (for plastic), 8471 (for computers)"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       data-hint="material-hsn">
                @error('hsn_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="sku_field">
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    SKU (Stock Keeping Unit)
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Unique identifier for inventory tracking. Leave empty to auto-generate based on category and name."></i>
                </label>
                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" 
                       placeholder="Auto-generated if empty (e.g., METMIL001)"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       data-hint="material-sku">
                @error('sku')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="barcode_field">
                <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Barcode
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="EAN-13 compatible barcode for scanning. Leave empty to auto-generate."></i>
                </label>
                <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" 
                       placeholder="Auto-generated EAN-13 barcode"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('barcode')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing Section -->
            <div class="md:col-span-2 mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-rupee-sign text-green-600 mr-2"></i>
                    Pricing & Units
                </h3>
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Unit of Measurement *
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="How you measure and sell this commodity. Choose the most common unit for your business."></i>
                </label>
                <select name="unit" id="unit" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        required>
                    <!-- Good units -->
                    <optgroup label="For Physical Goods" id="good_units">
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

            <div>
                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Unit Price (₹) *
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Cost per unit for purchasing or selling. This will be used for cost calculations and pricing."></i>
                </label>
                <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price') }}" 
                       step="0.01" min="0" required
                       placeholder="e.g., 65.00, 500.00"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('unit_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gst_rate" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    GST Rate (%)
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Goods and Services Tax rate applicable to this commodity. Common rates: 5%, 12%, 18%, 28%"></i>
                </label>
                <select name="gst_rate" id="gst_rate" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="0" {{ old('gst_rate') == '0' ? 'selected' : '' }}>0% (Exempt)</option>
                    <option value="5" {{ old('gst_rate') == '5' ? 'selected' : '' }}>5%</option>
                    <option value="12" {{ old('gst_rate') == '12' ? 'selected' : '' }}>12%</option>
                    <option value="18" {{ old('gst_rate', '18') == '18' ? 'selected' : '' }}>18% (Most Common)</option>
                    <option value="28" {{ old('gst_rate') == '28' ? 'selected' : '' }}>28%</option>
                </select>
                @error('gst_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Category
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Group similar commodities together for better organization and reporting."></i>
                </label>
                <input type="text" name="category" id="category" value="{{ old('category') }}" 
                       placeholder="e.g., Metals, Plastics, Electronics, Services"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Advanced Fields for Manufacturing -->
            @if(auth()->user()->business->subscription_tier !== 'billing_sales')
            <div class="md:col-span-2 mt-8" id="advanced_section">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cogs text-purple-600 mr-2"></i>
                    Manufacturing Details (Optional)
                </h3>
            </div>

            <div id="material_type_field">
                <label for="material_type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Product Type
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Classify your commodity for better inventory management and costing."></i>
                </label>
                <select name="material_type" id="material_type" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="raw_material" {{ old('material_type') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                    <option value="component" {{ old('material_type') == 'component' ? 'selected' : '' }}>Component</option>
                    <option value="finished_good" {{ old('material_type') == 'finished_good' ? 'selected' : '' }}>Finished Good</option>
                    <option value="consumable" {{ old('material_type') == 'consumable' ? 'selected' : '' }}>Consumable</option>
                </select>
                @error('material_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="material_form_field">
                <label for="material_form" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Product Form
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Physical form of the material - helps with storage and handling requirements."></i>
                </label>
                <select name="material_form" id="material_form" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Form</option>
                    <option value="bar" {{ old('material_form') == 'bar' ? 'selected' : '' }}>Bar</option>
                    <option value="pipe" {{ old('material_form') == 'pipe' ? 'selected' : '' }}>Pipe</option>
                    <option value="sheet" {{ old('material_form') == 'sheet' ? 'selected' : '' }}>Sheet</option>
                    <option value="rod" {{ old('material_form') == 'rod' ? 'selected' : '' }}>Rod</option>
                    <option value="casting" {{ old('material_form') == 'casting' ? 'selected' : '' }}>Casting</option>
                    <option value="plate" {{ old('material_form') == 'plate' ? 'selected' : '' }}>Plate</option>
                </select>
                @error('material_form')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="grade_field">
                <label for="grade" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    Grade/Specification
                    <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                       data-tooltip="Technical specification or grade of the material - important for quality control and supplier communication."></i>
                </label>
                <input type="text" name="grade" id="grade" value="{{ old('grade') }}" 
                       placeholder="e.g., 6061-T6, SS316, IS 2062, C36000"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('grade')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                Description
                <i class="fas fa-info-circle text-gray-400 ml-1 cursor-help" 
                   data-tooltip="Additional details about the commodity - appears on quotations and invoices."></i>
            </label>
            <textarea name="description" id="description" rows="3" 
                      placeholder="Detailed description of the commodity, its uses, or special characteristics..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Fields marked with * are required
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('materials.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                   data-tooltip="Cancel and return to commodities list">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center"
                        data-tooltip="Save this commodity to your inventory">
                    <i class="fas fa-plus mr-2"></i>
                    Add Commodity
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemType = document.getElementById('item_type');
    const materialTypeField = document.getElementById('material_type_field');
    const materialFormField = document.getElementById('material_form_field');
    const gradeField = document.getElementById('grade_field');
    const skuField = document.getElementById('sku_field');
    const barcodeField = document.getElementById('barcode_field');
    const unitSelect = document.getElementById('unit');
    const goodUnits = document.getElementById('good_units');
    const serviceUnits = document.getElementById('service_units');
    const progressBar = document.getElementById('progress-bar');
    const isBillingSales = {{ auth()->user()->business->subscription_tier === 'billing_sales' ? 'true' : 'false' }};
    
    function toggleFields() {
        const isService = itemType.value === 'service';
        
        // Hide SKU/barcode for services
        skuField.style.display = isService ? 'none' : 'block';
        barcodeField.style.display = isService ? 'none' : 'block';
        
        // Hide HSN code for services (only for goods)
        const hsnField = document.getElementById('hsn_code_field');
        hsnField.style.display = isService ? 'none' : 'block';
        
        // For billing_sales tier, hide advanced fields
        if (!isBillingSales) {
            materialTypeField.style.display = isService ? 'none' : 'block';
            materialFormField.style.display = isService ? 'none' : 'block';
            gradeField.style.display = isService ? 'none' : 'block';
            
            const advancedSection = document.getElementById('advanced_section');
            advancedSection.style.display = isService ? 'none' : 'block';
        }
        
        // Set default unit for services
        if (isService && !unitSelect.value) {
            unitSelect.value = 'hour';
        }
        
        // Show/hide unit groups
        goodUnits.style.display = isService ? 'none' : 'block';
        serviceUnits.style.display = isService ? 'block' : 'none';
    }
    
    // Progress tracking
    function updateProgress() {
        const requiredFields = ['name', 'item_type', 'unit', 'unit_price'];
        const filledFields = requiredFields.filter(field => {
            const element = document.getElementById(field);
            return element && element.value.trim() !== '';
        });
        
        const progress = (filledFields.length / requiredFields.length) * 100;
        progressBar.style.width = `${Math.max(33, progress)}%`;
        
        if (progress === 100) {
            progressBar.classList.add('bg-green-600');
            progressBar.classList.remove('bg-blue-600');
        } else {
            progressBar.classList.add('bg-blue-600');
            progressBar.classList.remove('bg-green-600');
        }
    }
    
    // Event listeners
    itemType.addEventListener('change', toggleFields);
    
    // Progress tracking on input
    ['name', 'item_type', 'unit', 'unit_price'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        }
    });
    
    // Initial setup
    toggleFields();
    updateProgress();
    
    // Form validation feedback
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
        }
    });
});
</script>
@endsection