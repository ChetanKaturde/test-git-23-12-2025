@extends('layouts.app')
@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Customer</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('customers.index') }}" class="hover:text-blue-600 transition-colors">Customers</a>
                    > <span class="font-medium">{{ $customer->name }}</span>
                </nav>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('customers.show', $customer) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    View
                </a>
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
        </div>
            <div class="p-6">
                <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                        
                    <!-- Customer Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="customer_type" value="business" {{ old('customer_type', $customer->customer_type ?? 'business') == 'business' ? 'checked' : '' }} class="mr-2">
                                <span>Business</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="customer_type" value="individual" {{ old('customer_type', $customer->customer_type) == 'individual' ? 'checked' : '' }} class="mr-2">
                                <span>Individual</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                            <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $customer->contact_person) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_person') border-red-500 @enderror">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <select id="state" name="state" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('state') border-red-500 @enderror">
                                <option value="">Select State</option>
                                <!-- States will be populated by JavaScript -->
                            </select>
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <select id="city" name="city" disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror">
                                <option value="">Select City</option>
                            </select>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pincode" class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                            <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $customer->pincode) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pincode') border-red-500 @enderror">
                            @error('pincode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Billing Address -->
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                        <textarea name="billing_address" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('billing_address', $customer->billing_address) }}</textarea>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                        <textarea name="shipping_address" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                  placeholder="Leave blank to use billing address">{{ old('shipping_address', $customer->shipping_address) }}</textarea>
                    </div>

                    <!-- Business Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="gstin" class="block text-sm font-medium text-gray-700 mb-2">GSTIN</label>
                            <input type="text" id="gstin" name="gstin" value="{{ old('gstin', $customer->gstin) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gstin') border-red-500 @enderror">
                            @error('gstin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                            <select name="payment_terms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="due_on_receipt" {{ old('payment_terms', $customer->payment_terms ?? 'due_on_receipt') == 'due_on_receipt' ? 'selected' : '' }}>Due on Receipt</option>
                                <option value="net_7" {{ old('payment_terms', $customer->payment_terms) == 'net_7' ? 'selected' : '' }}>Net 7 Days</option>
                                <option value="net_15" {{ old('payment_terms', $customer->payment_terms) == 'net_15' ? 'selected' : '' }}>Net 15 Days</option>
                                <option value="net_30" {{ old('payment_terms', $customer->payment_terms) == 'net_30' ? 'selected' : '' }}>Net 30 Days</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm font-medium text-gray-700">Active Customer</span>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('customers.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Cancel
                        </a>
                        @canEditInModule('customers')
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Update Customer
                        </button>
                        @endcanEditInModule
                    </div>
                </form>
            </div>
        </div>
</div>

<script>
// State-City dropdown functionality for customer edit form
document.addEventListener('DOMContentLoaded', function() {
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    const currentState = '{{ old("state", $customer->state) }}';
    const currentCity = '{{ old("city", $customer->city) }}';
    
    // Fetch states from database
    fetch('/api/states')
        .then(res => res.json())
        .then(states => {
            states.forEach(state => {
                const option = new Option(state.name, state.name);
                if (state.name === currentState) {
                    option.selected = true;
                }
                stateSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching states:', error);
            stateSelect.innerHTML = '<option value="">Error loading states</option>';
        });
    
    // Load cities for current state if exists
    if (currentState) {
        loadCities(currentState, currentCity);
    }
    
    // Handle state change
    stateSelect.addEventListener('change', function() {
        const stateName = this.value;
        loadCities(stateName);
    });
    
    function loadCities(stateName, selectedCity = '') {
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;
        
        if (stateName) {
            fetch(`/api/cities/${encodeURIComponent(stateName)}`)
                .then(res => res.json())
                .then(cities => {
                    cities.forEach(cityName => {
                        const option = new Option(cityName, cityName);
                        if (cityName === selectedCity) {
                            option.selected = true;
                        }
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                });
        }
    }
});
</script>
@endsection