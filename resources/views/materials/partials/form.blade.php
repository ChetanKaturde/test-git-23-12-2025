<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ isset($material) ? 'Edit' : 'Create' }} Commodity</h2>
        <a href="{{ route('materials.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back to Commodities
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Commodity Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Commodity Name *</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name', $material->name ?? '') }}" required>
                <div class="text-red-500 text-sm mt-1" id="nameError"></div>
            </div>

            <!-- Type -->
            <div>
                <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2" title="Goods are tracked in inventory. Services are non-physical (e.g., hours).">Type *</label>
                <select name="item_type" id="item_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="good" {{ old('item_type', $material->item_type ?? 'good') == 'good' ? 'selected' : '' }}>📦 Good (Physical Product)</option>
                    <option value="service" {{ old('item_type', $material->item_type ?? '') == 'service' ? 'selected' : '' }}>💼 Service</option>
                </select>
                <div class="text-red-500 text-sm mt-1" id="item_typeError"></div>
            </div>

            <!-- Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Code *</label>
                <input type="text" name="code" id="code" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" value="{{ old('code', $material->code ?? '') }}" required readonly>
                <div class="text-red-500 text-sm mt-1" id="codeError"></div>
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('category', $material->category ?? '') }}" placeholder="e.g., Metal, Electronics">
                <div class="text-red-500 text-sm mt-1" id="categoryError"></div>
            </div>

            <!-- Unit -->
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                <select name="unit" id="unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" selected disabled>Select Unit</option>
                    <!-- Good units -->
                    <optgroup label="For Goods" id="good_units">
                        <option value="kg" {{ old('unit', $material->unit ?? '') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="g" {{ old('unit', $material->unit ?? '') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="liter" {{ old('unit', $material->unit ?? '') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="meter" {{ old('unit', $material->unit ?? '') == 'meter' ? 'selected' : '' }}>Meter</option>
                        <option value="piece" {{ old('unit', $material->unit ?? '') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="box" {{ old('unit', $material->unit ?? '') == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="dozen" {{ old('unit', $material->unit ?? '') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                        <option value="ton" {{ old('unit', $material->unit ?? '') == 'ton' ? 'selected' : '' }}>Ton</option>
                    </optgroup>
                    <!-- Service units -->
                    <optgroup label="For Services" id="service_units">
                        <option value="hour" {{ old('unit', $material->unit ?? '') == 'hour' ? 'selected' : '' }}>Hour</option>
                        <option value="day" {{ old('unit', $material->unit ?? '') == 'day' ? 'selected' : '' }}>Day</option>
                        <option value="session" {{ old('unit', $material->unit ?? '') == 'session' ? 'selected' : '' }}>Session</option>
                        <option value="project" {{ old('unit', $material->unit ?? '') == 'project' ? 'selected' : '' }}>Project</option>
                        <option value="visit" {{ old('unit', $material->unit ?? '') == 'visit' ? 'selected' : '' }}>Visit</option>
                        <option value="unit" {{ old('unit', $material->unit ?? '') == 'unit' ? 'selected' : '' }}>Unit</option>
                    </optgroup>
                </select>
                <div class="text-red-500 text-sm mt-1" id="unitError"></div>
            </div>

            <!-- Unit Price -->
            <div>
                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price (₹) *</label>
                <input type="number" step="0.01" name="unit_price" id="unit_price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('unit_price', $material->unit_price ?? '') }}" required>
                <div class="text-red-500 text-sm mt-1" id="unit_priceError"></div>
            </div>

            <!-- GST Rate -->
            <div>
                <label for="gst_rate" class="block text-sm font-medium text-gray-700 mb-2">GST Rate (%) *</label>
                <input type="number" step="0.01" name="gst_rate" id="gst_rate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('gst_rate', $material->gst_rate ?? 18.00) }}" required>
                <div class="text-red-500 text-sm mt-1" id="gst_rateError"></div>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $material->description ?? '') }}</textarea>
            <div class="text-red-500 text-sm mt-1" id="descriptionError"></div>
        </div>

        <!-- Is Active -->
        <div class="mt-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="is_active" id="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1" {{ old('is_active', $material->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $material->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            <div class="text-red-500 text-sm mt-1" id="is_activeError"></div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save"></i> {{ isset($material) ? 'Update' : 'Save' }} Commodity
            </button>
            <a href="{{ route('materials.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemType = document.getElementById('item_type');
    const unitSelect = document.getElementById('unit');
    const goodUnits = document.getElementById('good_units');
    const serviceUnits = document.getElementById('service_units');
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');

    function toggleUnits() {
        const isService = itemType.value === 'service';

        // Show/hide unit groups
        goodUnits.style.display = isService ? 'none' : 'block';
        serviceUnits.style.display = isService ? 'block' : 'none';

        // Set default unit for goods only
        if (!unitSelect.value && !isService) {
            unitSelect.value = 'kg';
        }
    }

    if (itemType) {
        itemType.addEventListener('change', toggleUnits);
        // Initial check
        toggleUnits();
    }

    // Auto-generate code from name
    if (nameInput && codeInput) {
        nameInput.addEventListener('input', function() {
            if (this.value) {
                const code = this.value.substring(0, 3).toUpperCase() + Math.floor(1000 + Math.random() * 9000);
                codeInput.value = code;
            }
        });
    }
});
</script>