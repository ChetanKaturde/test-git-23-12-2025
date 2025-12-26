<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ isset($material) ? 'Edit' : 'Create' }} Material</h2>
        <a href="{{ route('materials.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back to Materials
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

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Material Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Material Name *</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name', $material->name ?? '') }}" required>
                <div class="text-red-500 text-sm mt-1" id="nameError"></div>
            </div>

            <!-- Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Code *</label>
                <input type="text" name="code" id="code" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" value="{{ old('code', $material->code ?? '') }}" required>
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
                    <option value="">Select Unit</option>
                    <option value="kg" {{ old('unit', $material->unit ?? '') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                    <option value="gram" {{ old('unit', $material->unit ?? '') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                    <option value="liter" {{ old('unit', $material->unit ?? '') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                    <option value="piece" {{ old('unit', $material->unit ?? '') == 'piece' ? 'selected' : '' }}>Piece (pc)</option>
                    <option value="meter" {{ old('unit', $material->unit ?? '') == 'meter' ? 'selected' : '' }}>Meter (m)</option>
                    <option value="box" {{ old('unit', $material->unit ?? '') == 'box' ? 'selected' : '' }}>Box</option>
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
                <i class="fas fa-save"></i> {{ isset($material) ? 'Update' : 'Save' }} Material
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
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    // Auto-generate code from name
    nameInput.addEventListener('input', function() {
        if (this.value) {
            const code = this.value.substring(0, 3).toUpperCase() + Math.floor(1000 + Math.random() * 9000);
            codeInput.value = code;
        }
    });
});
</script>