@extends('layouts.app')
@section('title', 'Business Profile')
@section('page-title', 'Business Profile')

@section('content')
<div class="p-4 md:p-6 max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Business Profile</h2>
                    <p class="text-gray-600 text-sm mt-1">Complete your business information for professional quotations and invoices</p>
                    @if(!$isProfileComplete)
                        <div class="mt-2 text-sm text-orange-600 bg-orange-50 px-3 py-1 rounded-lg inline-block">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Step 1 of 3: Complete your business profile
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                {{-- Load Sample Data button removed as per requirements --}}
                    {{-- Temporarily disabled PDF download button --}}
                    {{-- <form action="{{ route('business.profile.preview') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </form> --}}
                </div>
            </div>
        </div>

        <form id="businessForm" action="{{ route('business.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PATCH')
            <!-- Business Identity Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building mr-2 text-blue-600"></i>Business Identity
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" title="Appears on all your PDF documents (max 2MB, JPG/PNG)">Business Logo</label>
                        <div class="flex flex-col md:flex-row md:items-start gap-4">
                            <div class="flex-shrink-0">
                                <div id="logoPreview" class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-white">
                                    @if($business->logo_path)
                                        <img src="{{ url($business->logo_path) }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-bold text-lg">{{ substr($business->name ?? 'B', 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo" id="logoInput" accept="image/jpeg,image/png,image/jpg" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-sm text-gray-500 mt-2">JPG, PNG only. Max 2MB. Auto-resized to 200x200px for PDFs.</p>
                                @error('logo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="This appears on your invoices and quotes">Business Name *</label>
                            <input type="text" name="name" id="businessName" value="{{ old('name', $business->name) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="Legal entity name for official documents">Legal Name</label>
                            <input type="text" name="legal_name" value="{{ old('legal_name', $business->legal_name) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Same as business name if not different">
                            @error('legal_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact & Address Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-address-card mr-2 text-green-600"></i>Contact & Address
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="Business contact email for invoices and quotes">Email</label>
                            <input type="email" name="email" value="{{ old('email', $business->email) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="Primary business phone number">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $business->phone) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="+91 98765 43210">
                            @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" title="Complete business address for invoices and legal documents">Address *</label>
                        <textarea name="address" rows="2" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Street address, building name, area">{{ old('address', $business->address) }}</textarea>
                        @error('address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                            <select name="state" id="business_state" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->name }}" {{ old('state', $business->business_state ?? $business->state) == $state->name ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <select name="city" id="business_city" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select City</option>
                            </select>
                            @error('city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIN Code</label>
                            <input type="text" name="pin_code" value="{{ old('pin_code', $business->pin_code) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   pattern="[0-9]{6}" maxlength="6" placeholder="400001">
                            @error('pin_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tax Compliance Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-file-invoice mr-2 text-purple-600"></i>Tax Compliance
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="Mandatory for B2B invoices. Format: 22AAAAA0000A1Z5">GSTIN</label>
                            <input type="text" name="gstin" id="gstin" value="{{ old('gstin', $business->gstin) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}" maxlength="15" 
                                   placeholder="22AAAAA0000A1Z5">
                            <p class="text-xs text-gray-500 mt-1">15 characters. Required for B2B invoices.</p>
                            @error('gstin')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PAN</label>
                            <input type="text" name="pan" id="pan" value="{{ old('pan', $business->pan) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10" placeholder="ABCDE1234F">
                            <p class="text-xs text-gray-500 mt-1">10 characters. Format: ABCDE1234F</p>
                            @error('pan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">HSN/SAC Prefix</label>
                        <input type="text" name="hsn_prefix" value="{{ old('hsn_prefix', $business->hsn_prefix) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="9954" maxlength="8">
                        <p class="text-xs text-gray-500 mt-1">Default HSN/SAC code for your products/services</p>
                        @error('hsn_prefix')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <!-- Financial Settings Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-coins mr-2 text-yellow-600"></i>Financial Settings
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Currency field hidden as per requirements --}}
                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="INR" {{ old('currency', $business->currency) == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                        </select>
                        @error('currency')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div> --}}
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="Invoices reset numbering on this date (e.g., Apr 1 → INV-2526-0001)">Financial Year Start</label>
                            <input type="date" name="financial_year_start" value="{{ old('financial_year_start', $business->financial_year_start ? $business->financial_year_start->format('Y-m-d') : '') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Invoice numbering resets annually</p>
                            @error('financial_year_start')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" title="Invoice due [X] days after issue date">Payment Terms (Days)</label>
                            <input type="number" name="payment_terms" value="{{ old('payment_terms', $business->payment_terms ?? 30) }}" 
                                   min="0" max="365"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Default payment due period</p>
                            @error('payment_terms')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- Branding Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-palette mr-2 text-pink-600"></i>Branding
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" title="This appears at the bottom of your quotes and invoices">Terms & Conditions</label>
                        <textarea name="terms_and_conditions" id="termsTextarea" rows="6" maxlength="3500"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Default terms and conditions for quotations and invoices...">{{ old('terms_and_conditions', $business->terms_and_conditions) }}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">This will appear on your quotes and invoices</p>
                            <span id="charCount" class="text-xs text-gray-500">0/3,500 characters</span>
                        </div>
                        @error('terms_and_conditions')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-100">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-save mr-1"></i>Changes are auto-saved as draft
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium">
                        <i class="fas fa-check mr-2"></i>Save Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Logo preview
document.getElementById('logoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').innerHTML = 
                `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-contain rounded-lg">`;
        };
        reader.readAsDataURL(file);
    }
});

// Character counter
const textarea = document.getElementById('termsTextarea');
const charCount = document.getElementById('charCount');

function updateCharCount() {
    const count = textarea.value.length;
    charCount.textContent = `${count}/3,500 characters`;
    charCount.className = count > 3500 ? 'text-xs text-red-500' : 'text-xs text-gray-500';
}

textarea.addEventListener('input', updateCharCount);
updateCharCount();

// GSTIN validation
document.getElementById('gstin').addEventListener('input', function(e) {
    let value = e.target.value.toUpperCase();
    e.target.value = value;
    
    const isValid = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/.test(value);
    e.target.style.borderColor = value.length === 0 ? '' : (isValid ? '#10b981' : '#ef4444');
});

// PAN validation
document.getElementById('pan').addEventListener('input', function(e) {
    let value = e.target.value.toUpperCase();
    e.target.value = value;
    
    const isValid = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value);
    e.target.style.borderColor = value.length === 0 ? '' : (isValid ? '#10b981' : '#ef4444');
});

// Auto-save draft
let formData = {};
const form = document.getElementById('businessForm');

function saveDraft() {
    const data = new FormData(form);
    const draft = {};
    for (let [key, value] of data.entries()) {
        if (key !== 'logo') draft[key] = value;
    }
    localStorage.setItem('businessProfileDraft', JSON.stringify(draft));
}

form.addEventListener('input', () => {
    clearTimeout(window.draftTimer);
    window.draftTimer = setTimeout(saveDraft, 1000);
});

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('businessProfileDraft');
    if (draft) {
        const data = JSON.parse(draft);
        Object.keys(data).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && !input.value) input.value = data[key];
        });
    }
});

// Clear draft on successful submit
form.addEventListener('submit', function() {
    localStorage.removeItem('businessProfileDraft');
});

// Confirm before leaving with unsaved changes
let hasUnsavedChanges = false;
form.addEventListener('input', () => hasUnsavedChanges = true);
form.addEventListener('submit', () => hasUnsavedChanges = false);

window.addEventListener('beforeunload', function(e) {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});


// Update logo fallback when business name changes
document.getElementById('businessName').addEventListener('input', function(e) {
    const preview = document.getElementById('logoPreview');
    if (!preview.querySelector('img')) {
        const initial = e.target.value.charAt(0).toUpperCase() || 'B';
        preview.innerHTML = `
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-bold text-lg">${initial}</span>
            </div>
        `;
    }
});

// State-City dropdown logic
const businessStateSelect = document.getElementById('business_state');
const businessCitySelect = document.getElementById('business_city');
const savedCity = '{{ old('city', $business->business_city ?? $business->city) }}';

if (businessStateSelect && businessCitySelect) {
    // Load cities on page load if state is selected
    if (businessStateSelect.value) {
        loadCities(businessStateSelect.value, savedCity);
    }

    businessStateSelect.addEventListener('change', function() {
        loadCities(this.value);
    });
}

function loadCities(stateName, selectedCity = '') {
    businessCitySelect.innerHTML = '<option value="">Select City</option>';
    businessCitySelect.disabled = true;

    if (stateName) {
        fetch(`/api/cities/${encodeURIComponent(stateName)}`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(cityName => {
                    const option = new Option(cityName, cityName, false, cityName === selectedCity);
                    businessCitySelect.appendChild(option);
                });
                businessCitySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
                businessCitySelect.innerHTML = '<option value="">Error loading cities</option>';
            });
    }
}
</script>
@endsection