@extends('layouts.app')
@section('title', 'Create Vendor')
@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Create New Vendor</h2>
                <p class="text-gray-600 text-sm">Add a new supplier to your vendor network</p>
            </div>
            <a href="{{ route('vendors.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
        
        <form id="vendor-form" action="{{ route('vendors.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Company Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Company Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="name" id="name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror" 
                               value="{{ old('name') }}" required minlength="2" maxlength="100" placeholder="Enter vendor name">
                        @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        <div class="text-red-600 text-sm mt-1" id="name-error" style="display: none;"></div>
                    </div>
                    
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                        <input type="text" name="business_name" id="business_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('business_name') border-red-300 @enderror" 
                               value="{{ old('business_name') }}" maxlength="150" placeholder="Enter business name">
                        @error('business_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        <div class="text-red-600 text-sm mt-1" id="business_name-error" style="display: none;"></div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror" 
                               value="{{ old('email') }}" maxlength="100" placeholder="Enter email address">
                        @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        <div class="text-red-600 text-sm mt-1" id="email-error" style="display: none;"></div>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">+91</span>
                            <input type="text" name="phone" id="phone" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-300 @enderror"
                                   value="{{ old('phone') }}" maxlength="10" pattern="[6-9]\d{9}" required
                                   placeholder="Enter 10-digit mobile number">
                        </div>
                        @error('phone')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        <div class="text-red-600 text-sm mt-1" id="phone-error" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Address Information</h3>
                </div>
                
                <!-- Company Address -->
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-800 mb-4 flex items-center">
                        <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Company Address *
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                            <textarea name="company_address" id="company_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('company_address') border-red-300 @enderror"
                                      placeholder="Enter street address, building name, etc." required minlength="10" maxlength="500">{{ old('company_address') }}</textarea>
                            @error('company_address')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            <div class="text-red-600 text-sm mt-1" id="company_address-error" style="display: none;"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="company_state" class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                <select name="company_state" id="company_state" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('company_state') border-red-300 @enderror" required>
                                    <option value="">Select State</option>
                                    <!-- States will be populated by JavaScript -->
                                </select>
                                @error('company_state')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="company_state-error" style="display: none;"></div>
                            </div>
                            
                            <div>
                                <label for="company_city" class="block text-sm font-medium text-gray-700 mb-2">City/District *</label>
                                <select name="company_city" id="company_city" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('company_city') border-red-300 @enderror" required disabled>
                                    <option value="">Select City/District</option>
                                </select>
                                @error('company_city')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="company_city-error" style="display: none;"></div>
                            </div>
                            
                            <div>
                                <label for="company_pincode" class="block text-sm font-medium text-gray-700 mb-2">Pincode *</label>
                                <input type="text" name="company_pincode" id="company_pincode" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('company_pincode') border-red-300 @enderror" 
                                       value="{{ old('company_pincode') }}" maxlength="6" pattern="[0-9]{6}" required
                                       placeholder="Enter 6-digit pincode"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                                @error('company_pincode')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="company_pincode-error" style="display: none;"></div>
                            </div>
                            
                            <div>
                                <label for="company_country" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                <select name="company_country" id="company_country" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('company_country') border-red-300 @enderror" required>
                                    <option value="India" selected>India</option>
                                </select>
                                @error('company_country')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="company_country-error" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Address -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-800 flex items-center">
                            <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                            Warehouse Address
                        </h4>
                        <div class="flex items-center">
                            <input type="checkbox" id="same_as_company" name="same_as_company" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="same_as_company" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                Same as Company Address
                            </label>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="warehouse_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                            <textarea name="warehouse_address" id="warehouse_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('warehouse_address') border-red-300 @enderror"
                                      placeholder="Enter warehouse street address" maxlength="500">{{ old('warehouse_address') }}</textarea>
                            @error('warehouse_address')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            <div class="text-red-600 text-sm mt-1" id="warehouse_address-error" style="display: none;"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="warehouse_state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                <select name="warehouse_state" id="warehouse_state" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('warehouse_state') border-red-300 @enderror">
                                    <option value="">Select State</option>
                                    <!-- States will be populated by JavaScript -->
                                </select>
                                @error('warehouse_state')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="warehouse_state-error" style="display: none;"></div>
                            </div>
                            
                            <div>
                                <label for="warehouse_city" class="block text-sm font-medium text-gray-700 mb-2">City/District</label>
                                <select name="warehouse_city" id="warehouse_city" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('warehouse_city') border-red-300 @enderror" disabled>
                                    <option value="">Select City/District</option>
                                </select>
                                @error('warehouse_city')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="warehouse_city-error" style="display: none;"></div>
                            </div>
                            
                            <div>
                                <label for="warehouse_pincode" class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                                <input type="text" name="warehouse_pincode" id="warehouse_pincode" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('warehouse_pincode') border-red-300 @enderror" 
                                       value="{{ old('warehouse_pincode') }}" maxlength="6" pattern="[0-9]{6}"
                                       placeholder="Enter 6-digit pincode"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                                @error('warehouse_pincode')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="warehouse_pincode-error" style="display: none;"></div>
                            </div>
                            
                            <div>
                                <label for="warehouse_country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <select name="warehouse_country" id="warehouse_country" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('warehouse_country') border-red-300 @enderror">
                                    <option value="">Select Country</option>
                                    <option value="India">India</option>
                                </select>
                                @error('warehouse_country')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                                <div class="text-red-600 text-sm mt-1" id="warehouse_country-error" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Note: Material entry has been moved to Purchase Orders workflow -->
            <!-- This section was removed as it belongs in PO creation, not vendor setup -->

            <!-- Bank Details Section (Initially Hidden) -->
            <div id="bank-details-section" class="bg-white border border-gray-200 rounded-lg p-6" style="display: none;">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Bank Details</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bank_holder_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Bank Account Holder Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="bank_holder_name" id="bank_holder_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('bank_holder_name') border-red-300 @enderror"
                                   placeholder="Enter account holder name" value="{{ old('bank_holder_name') }}" required>
                            @error('bank_holder_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            <div class="text-red-600 text-sm mt-1" id="bank_holder_name-error" style="display: none;"></div>
                        </div>
                        
                        <div>
                            <label for="branch_name" class="block text-sm font-medium text-gray-700 mb-2">Branch Name <span class="text-red-500">*</span></label>
                            <input type="text" name="branch_name" id="branch_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('branch_name') border-red-300 @enderror" 
                                   value="{{ old('branch_name') }}" required minlength="2" maxlength="100" placeholder="Enter branch name">
                            @error('branch_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            <div class="text-red-600 text-sm mt-1" id="branch_name-error" style="display: none;"></div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="bank_search" class="block text-sm font-medium text-gray-700 mb-2">
                            Bank Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" id="bank_search" 
                                       class="flex-1 px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Search or select bank..." value="{{ old('bank_name') }}" autocomplete="off">
                                <button class="px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100" type="button" id="bank_dropdown_toggle">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <ul class="absolute w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto z-50" id="bankDropdown" style="display: none;">
                                <!-- Bank options will be populated here -->
                            </ul>
                            <input type="hidden" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" required>
                        </div>
                        @error('bank_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        <div class="text-red-600 text-sm mt-1" id="bank_name-error" style="display: none;"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Account Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="account_number" id="account_number" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('account_number') border-red-300 @enderror"
                                   placeholder="Enter account number" value="{{ old('account_number') }}" required>
                            @error('account_number')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            <div class="text-red-600 text-sm mt-1" id="account_number-error" style="display: none;"></div>
                        </div>
                        
                        <div>
                            <label for="ifsc_code" class="block text-sm font-medium text-gray-700 mb-2">
                                IFSC Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ifsc_code" id="ifsc_code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ifsc_code') border-red-300 @enderror uppercase"
                                   placeholder="Enter IFSC code" value="{{ old('ifsc_code') }}" required>
                            @error('ifsc_code')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            <div class="text-red-600 text-sm mt-1" id="ifsc_code-error" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('vendors.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    Cancel
                </a>
                <button type="button" id="continue-btn" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                    Continue
                </button>
                <button type="button" id="submit-btn" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors" 
                        style="display: none;">
                    Save Vendor
                </button>
            </div>
        </form>
    </div>
</div>


<!-- jQuery and Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/state-city.js') }}"></script>

<script>
  
  
// Bank list for dropdown
const bankList = [
    { name: 'State Bank of India', code: 'SBIN' },
    { name: 'HDFC Bank', code: 'HDFC' },
    { name: 'ICICI Bank', code: 'ICIC' },
    { name: 'Axis Bank', code: 'UTIB' },
    { name: 'Kotak Mahindra Bank', code: 'KKBK' },
    { name: 'Punjab National Bank', code: 'PUNB' },
    { name: 'Bank of Baroda', code: 'BARB' },
    { name: 'Canara Bank', code: 'CNRB' },
    { name: 'Union Bank of India', code: 'UBIN' },
    { name: 'Bank of India', code: 'BKID' },
    { name: 'Indian Bank', code: 'IDIB' },
    { name: 'Central Bank of India', code: 'CBIN' },
    { name: 'Indian Overseas Bank', code: 'IOBA' },
    { name: 'UCO Bank', code: 'UCBA' },
    { name: 'Bank of Maharashtra', code: 'MAHB' },
    { name: 'Punjab & Sind Bank', code: 'PSIB' },
    { name: 'IndusInd Bank', code: 'INDB' },
    { name: 'Yes Bank', code: 'YESB' },
    { name: 'IDFC First Bank', code: 'IDFB' },
    { name: 'Federal Bank', code: 'FDRL' },
    { name: 'South Indian Bank', code: 'SIBL' },
    { name: 'Karur Vysya Bank', code: 'KVBL' },
    { name: 'City Union Bank', code: 'CIUB' },
    { name: 'Dhanlaxmi Bank', code: 'DLXB' },
    { name: 'RBL Bank', code: 'RATN' },
    { name: 'Bandhan Bank', code: 'BDBL' },
    { name: 'IDBI Bank', code: 'IBKL' },
    { name: 'Tamil Nadu Mercantile Bank', code: 'TMBL' },
    { name: 'DCB Bank', code: 'DCBL' },
    { name: 'Lakshmi Vilas Bank', code: 'LAVB' }
];

// Validation functions
function validateName(name) {
    if (!name || name.trim().length < 2) {
        return 'Name must be at least 2 characters long';
    }
    if (name.length > 100) {
        return 'Name must not exceed 100 characters';
    }
    if (!/^[a-zA-Z\s]+$/.test(name)) {
        return 'Name can only contain letters and spaces';
    }
    return null;
}

function validateEmail(email) {
    if (email && email.length > 0) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return 'Please enter a valid email address';
        }
        if (email.length > 100) {
            return 'Email must not exceed 100 characters';
        }
    }
    return null;
}

function validateBranchName(branchName) {
    if (!branchName || branchName.trim().length < 2) {
        return 'Branch name must be at least 2 characters long';
    }
    if (branchName.length > 100) {
        return 'Branch name must not exceed 100 characters';
    }
    if (!/^[a-zA-Z0-9\s\-&]+$/.test(branchName)) {
        return 'Branch name can only contain letters, numbers, spaces, hyphens, and ampersands';
    }
    return null;
}

function validatePhone(phone) {
    if (!phone || phone.trim().length === 0) {
        return 'Phone number is required';
    }
    if (!/^[6-9]\d{9}$/.test(phone)) {
        return 'Phone number must be 10 digits starting with 6-9';
    }
    return null;
}

function validateAddress(address, fieldName) {
    if (!address || address.trim().length < 10) {
        return `${fieldName} must be at least 10 characters long`;
    }
    if (address.length > 500) {
        return `${fieldName} must not exceed 500 characters`;
    }
    return null;
}

function validateIFSC(ifsc) {
    if (!ifsc || ifsc.trim().length === 0) {
        return 'IFSC code is required';
    }
    
    // Remove any spaces and convert to uppercase
    ifsc = ifsc.trim().toUpperCase();
    
    // IFSC format: 4 letters + 1 zero + 6 alphanumeric characters
    if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc)) {
        return 'Invalid IFSC code format (e.g., SBIN0001234)';
    }
    
    return null;
}

function validateAccountNumber(accountNumber) {
    if (!accountNumber || accountNumber.trim().length === 0) {
        return 'Account number is required';
    }
    if (!/^\d{9,18}$/.test(accountNumber)) {
        return 'Account number must be 9-18 digits only';
    }
    return null;
}

function validateBankName(bankName) {
    if (!bankName || bankName.trim().length === 0) {
        return 'Bank name is required';
    }
    return null;
}

function validateBankHolderName(holderName) {
    if (!holderName || holderName.trim().length < 2) {
        return 'Bank holder name must be at least 2 characters long';
    }
    if (holderName.length > 100) {
        return 'Bank holder name must not exceed 100 characters';
    }
    if (!/^[a-zA-Z\s]+$/.test(holderName)) {
        return 'Bank holder name can only contain letters and spaces';
    }
    return null;
}

// Show error function
function showError(fieldId, message) {
    console.error(`Validation error in field "${fieldId}": ${message}`);
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '-error');
    
    if (field && errorElement) {
        field.classList.add('border-red-300');
        field.classList.remove('border-gray-300');
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

// Clear error function
function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '-error');
    
    if (field) {
        field.classList.remove('border-red-300');
        field.classList.add('border-gray-300');
    }
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Bank dropdown functionality
function setupBankDropdown() {
    const bankSearch = document.getElementById('bank_search');
    const bankDropdown = document.getElementById('bankDropdown');
    const bankNameHidden = document.getElementById('bank_name');
    const dropdownToggle = document.getElementById('bank_dropdown_toggle');
    
    if (!bankSearch || !bankDropdown) {
        console.error('Bank search elements not found');
        return;
    }
    
    let currentHighlightIndex = -1;
    let filteredBanks = [];
    
    // Show dropdown on focus
    bankSearch.addEventListener('focus', function() {
        showBankOptions('');
    });
    
    // Show dropdown on toggle button click
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (bankDropdown.classList.contains('show')) {
                hideBankDropdown();
            } else {
                showBankOptions(bankSearch.value);
            }
        });
    }
    
    // Filter options on input
    bankSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        showBankOptions(searchTerm);
        currentHighlightIndex = -1;
    });
    
    // Handle keyboard navigation
    bankSearch.addEventListener('keydown', function(e) {
        const options = bankDropdown.querySelectorAll('.bank-option');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentHighlightIndex = Math.min(currentHighlightIndex + 1, options.length - 1);
                updateHighlight(options);
                break;
            case 'ArrowUp':
                e.preventDefault();
                currentHighlightIndex = Math.max(currentHighlightIndex - 1, -1);
                updateHighlight(options);
                break;
            case 'Enter':
                e.preventDefault();
                if (currentHighlightIndex >= 0 && options[currentHighlightIndex]) {
                    selectBank(filteredBanks[currentHighlightIndex]);
                }
                break;
            case 'Escape':
                hideBankDropdown();
                break;
        }
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-container')) {
            hideBankDropdown();
        }
    });
    
    function showBankOptions(searchTerm) {
        filteredBanks = bankList.filter(bank => 
            bank.name.toLowerCase().includes(searchTerm.toLowerCase())
        );
        
        bankDropdown.innerHTML = '';
        
        if (filteredBanks.length === 0) {
            const noOption = document.createElement('div');
            noOption.className = 'bank-option text-muted';
            noOption.textContent = 'No banks found';
            bankDropdown.appendChild(noOption);
        } else {
            filteredBanks.forEach((bank, index) => {
                const option = document.createElement('div');
                option.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 border-b border-gray-100 last:border-b-0';
                option.textContent = bank.name;
                option.addEventListener('click', function() {
                    selectBank(bank);
                });
                bankDropdown.appendChild(option);
            });
        }
        
        bankDropdown.style.display = 'block';
        currentHighlightIndex = -1;
    }
    
    function selectBank(bank) {
        bankSearch.value = bank.name;
        bankNameHidden.value = bank.name;
        hideBankDropdown();
        clearError('bank_name');
        console.log('Bank selected:', bank.name);
    }
    
    function hideBankDropdown() {
        bankDropdown.style.display = 'none';
        currentHighlightIndex = -1;
    }
    
    function updateHighlight(options) {
        options.forEach((option, index) => {
            if (index === currentHighlightIndex) {
                option.classList.add('bg-indigo-100');
                option.classList.remove('hover:bg-gray-100');
            } else {
                option.classList.remove('bg-indigo-100');
                option.classList.add('hover:bg-gray-100');
            }
        });
        
        // Scroll highlighted option into view
        if (currentHighlightIndex >= 0 && options[currentHighlightIndex]) {
            options[currentHighlightIndex].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }
}

// Fetch bank details from IFSC code
function fetchBankDetails(ifscCode) {
    // Convert to uppercase and trim
    ifscCode = ifscCode.trim().toUpperCase();
    const ifscField = document.getElementById('ifsc_code');
    if (ifscField) {
        ifscField.value = ifscCode;
    }
    
    // Clear previous errors
    clearError('ifsc_code');
    
    if (ifscCode.length === 11) {
        // Validate IFSC format first
        const validationError = validateIFSC(ifscCode);
        if (validationError) {
            showError('ifsc_code', validationError);
            return;
        }
        
        // Make API call to fetch bank details
        fetch(`https://ifsc.razorpay.com/${ifscCode}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.BANK) {
                    // Auto-fill bank name
                    const bankNameField = document.getElementById('bank_name');
                    const bankSearchField = document.getElementById('bank_search');
                    
                    if (bankNameField && bankSearchField) {
                        bankNameField.value = data.BANK;
                        bankSearchField.value = data.BANK;
                        clearError('bank_name');
                    }
                    
                    // Auto-fill branch name if available
                    const branchField = document.getElementById('branch_name');
                    if (branchField && data.BRANCH) {
                        branchField.value = data.BRANCH;
                        clearError('branch_name');
                    }
                    
                    console.log('Bank details fetched successfully:', data);
                } else {
                    throw new Error('Invalid IFSC code or bank not found');
                }
            })
            .catch(error => {
                console.log('IFSC lookup failed:', error);
                showError('ifsc_code', 'Invalid IFSC code or bank details not found');
                
                // Clear auto-filled fields
                const bankNameField = document.getElementById('bank_name');
                const bankSearchField = document.getElementById('bank_search');
                
                if (bankNameField) bankNameField.value = '';
                if (bankSearchField) bankSearchField.value = '';
            });
    } else if (ifscCode.length > 0) {
        // Partial IFSC entered, clear validation errors
        clearError('ifsc_code');
    }
}

// Modern toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5`;
    
    const bgColor = type === 'success' ? 'bg-green-50' : type === 'error' ? 'bg-red-50' : 'bg-blue-50';
    const iconColor = type === 'success' ? 'text-green-400' : type === 'error' ? 'text-red-400' : 'text-blue-400';
    const textColor = type === 'success' ? 'text-green-800' : type === 'error' ? 'text-red-800' : 'text-blue-800';
    
    toast.innerHTML = `
        <div class="flex-1 w-0 p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 ${iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />'}
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium ${textColor}">${message}</p>
                </div>
            </div>
        </div>
        <div class="flex border-l border-gray-200">
            <button onclick="this.parentElement.parentElement.remove()" class="w-full border border-transparent rounded-none rounded-r-lg p-4 flex items-center justify-center text-sm font-medium text-gray-600 hover:text-gray-500 focus:outline-none">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    `;
    
    document.getElementById('toast-container').appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function () {
    // Initialize bank functionality
    setupBankDropdown();
    setupRealTimeValidation();
    setupInputFormatting();
    setupContinueButton();
    setupFormSubmission();
    setupSameAsCompanyAddress();
});

// Same as company address functionality
function setupSameAsCompanyAddress() {
    const checkbox = document.getElementById('same_as_company');
    if (!checkbox) return;
    
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            copyCompanyToWarehouse();
        } else {
            clearWarehouseFields();
        }
    });
}

function copyCompanyToWarehouse() {
    // Copy basic address fields
    document.getElementById('warehouse_address').value = document.getElementById('company_address').value;
    document.getElementById('warehouse_pincode').value = document.getElementById('company_pincode').value;
    document.getElementById('warehouse_country').value = document.getElementById('company_country').value;
    
    // Handle state and city with proper dependencies
    const companyState = document.getElementById('company_state').value;
    const companyCity = document.getElementById('company_city').value;
    const warehouseStateSelect = document.getElementById('warehouse_state');
    const warehouseCitySelect = document.getElementById('warehouse_city');
    
    if (companyState) {
        // Set warehouse state
        warehouseStateSelect.value = companyState;
        
        // Enable and populate warehouse city
        warehouseCitySelect.disabled = false;
        
        // Copy all city options from company dropdown
        const companyCityOptions = document.getElementById('company_city').innerHTML;
        warehouseCitySelect.innerHTML = companyCityOptions;
        
        // Set the selected city
        if (companyCity) {
            warehouseCitySelect.value = companyCity;
        }
    } else {
        warehouseStateSelect.value = '';
        warehouseCitySelect.innerHTML = '<option value="">Select City/District</option>';
        warehouseCitySelect.disabled = true;
    }
}

function clearWarehouseFields() {
    document.getElementById('warehouse_address').value = '';
    document.getElementById('warehouse_state').value = '';
    document.getElementById('warehouse_city').value = '';
    document.getElementById('warehouse_pincode').value = '';
    document.getElementById('warehouse_country').value = '';
    
    // Disable warehouse city dropdown
    const warehouseCitySelect = document.getElementById('warehouse_city');
    warehouseCitySelect.disabled = true;
    warehouseCitySelect.innerHTML = '<option value="">Select City/District</option>';
}


    
// Setup real-time validation
function setupRealTimeValidation() {
    const validationFields = [
        { id: 'name', validator: validateName },
        { id: 'email', validator: validateEmail },
        { id: 'phone', validator: validatePhone },
        { id: 'company_address', validator: (val) => validateAddress(val, 'Company address') },
        { id: 'warehouse_address', validator: (val) => val ? validateAddress(val, 'Warehouse address') : null },
        { id: 'account_number', validator: validateAccountNumber },
        { id: 'ifsc_code', validator: validateIFSC },
        { id: 'bank_name', validator: validateBankName },
        { id: 'bank_holder_name', validator: validateBankHolderName },
        { id: 'branch_name', validator: validateBranchName },
    ];

    validationFields.forEach(field => {
        const element = document.getElementById(field.id);
        
        if (element) {
            element.addEventListener('blur', function() {
                const error = field.validator(this.value);
                if (error) {
                    showError(field.id, error);
                } else {
                    clearError(field.id);
                }
            });
        }
    });
    
    // Special handling for IFSC field with debouncing
    const ifscField = document.getElementById('ifsc_code');
    if (ifscField) {
        let ifscTimeout;
        
        ifscField.addEventListener('input', function() {
            clearTimeout(ifscTimeout);
            ifscTimeout = setTimeout(() => {
                fetchBankDetails(this.value);
            }, 500); // 500ms delay
        });
        
        ifscField.addEventListener('blur', function() {
            const error = validateIFSC(this.value);
            if (error) {
                showError('ifsc_code', error);
            } else {
                clearError('ifsc_code');
            }
        });
    }
}

// Input formatting functions
function setupInputFormatting() {
    // Format IFSC input
    const ifscField = document.getElementById('ifsc_code');
    if (ifscField) {
        ifscField.addEventListener('input', function(e) {
            // Convert to uppercase and remove any invalid characters
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            // Limit to 11 characters
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            this.value = value;
        });
    }

    // Restrict phone input to numbers only
    const phoneField = document.getElementById('phone');
    if (phoneField) {
        phoneField.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
        });
    }

    // Restrict account number to numbers only
    const accountField = document.getElementById('account_number');
    if (accountField) {
        accountField.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 18) {
                this.value = this.value.substring(0, 18);
            }
        });
    }
}

// Continue button functionality
function setupContinueButton() {
    const continueBtn = document.getElementById('continue-btn');
    if (!continueBtn) return;
    
    continueBtn.addEventListener('click', function() {
        console.log('Continue button clicked');
        
        // Validate basic information first
        let isValid = true;
        const basicFields = ['name', 'phone', 'company_address'];
        
        basicFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            
            if (field) {
                let error = null;
                
                switch(fieldId) {
                    case 'name':
                        error = validateName(field.value);
                        break;
                    case 'phone':
                        error = validatePhone(field.value);
                        break;
                    case 'company_address':
                        error = validateAddress(field.value, 'Company address');
                        break;
                }
                
                if (error) {
                    showError(fieldId, error);
                    isValid = false;
                } else {
                    clearError(fieldId);
                }
            }
        });
        
        // Validate email if provided
        const emailField = document.getElementById('email');
        if (emailField && emailField.value) {
            const emailError = validateEmail(emailField.value);
            if (emailError) {
                showError('email', emailError);
                isValid = false;
            } else {
                clearError('email');
            }
        }
        
        console.log('Validation result:', isValid);
        
        if (isValid) {
            // Show bank details section
            const bankSection = document.getElementById('bank-details-section');
            const submitBtn = document.getElementById('submit-btn');
            
            if (bankSection) {
                bankSection.style.display = 'block';
                console.log('Bank section shown');
            }
            
            // Hide continue button and show submit button
            this.style.display = 'none';
            if (submitBtn) {
                submitBtn.style.display = 'inline-block';
            }
            
            // Show modern toast notification
            showToast('Great! Now please fill in the bank details below and click "Save Vendor" to complete.', 'success');
            
            // Scroll to bank details section
            if (bankSection) {
                bankSection.scrollIntoView({ behavior: 'smooth' });
            }
        } else {
            console.log('Validation failed, scrolling to first error');
            // Scroll to first error
            const firstError = document.querySelector('.border-red-300');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
}

// Form submission
function setupFormSubmission() {
    const form = document.getElementById('vendor-form');
    const submitBtn = document.getElementById('submit-btn');

    if (!form || !submitBtn) return;

    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Submit button clicked');

        let isValid = true;

        const allFields = [
            { id: 'name', validator: validateName },
            { id: 'email', validator: validateEmail },
            { id: 'phone', validator: validatePhone },
            { id: 'company_address', validator: (val) => validateAddress(val, 'Company address') },
            { id: 'ifsc_code', validator: validateIFSC },
            { id: 'account_number', validator: validateAccountNumber },
            { id: 'bank_name', validator: validateBankName },
            { id: 'bank_holder_name', validator: validateBankHolderName },
            { id: 'branch_name', validator: validateBranchName }
        ];

        // Validate main fields
        allFields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                const error = field.validator(element.value);
                if (error) {
                    showError(field.id, error);
                    isValid = false;
                } else {
                    clearError(field.id);
                }
            }
        });

        // Optional field: warehouse_address
        const warehouseField = document.getElementById('warehouse_address');
        if (warehouseField && warehouseField.value.trim() !== '') {
            const warehouseError = validateAddress(warehouseField.value, 'Warehouse address');
            if (warehouseError) {
                showError('warehouse_address', warehouseError);
                isValid = false;
            } else {
                clearError('warehouse_address');
            }
        }

        if (isValid) {
            console.log('Validation passed. Submitting form...');
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving Vendor...';
            form.submit();
        } else {
            console.warn('Validation failed. Not submitting.');
            const firstError = document.querySelector('.border-red-300');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
}

</script>
@endsection