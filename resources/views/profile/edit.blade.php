@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')
        
        <!-- Basic Information Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name" name="name" type="text" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                <div class="ml-2 relative" x-data="{ show: false }">
                    <button type="button" @mouseenter="show = true" @mouseleave="show = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <div x-show="show" x-transition class="absolute left-6 top-0 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10">
                        Phone number for business communications
                    </div>
                </div>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <div class="mt-1 flex">
                    <select class="px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-sm">
                        <option value="+91">+91 (India)</option>
                    </select>
                    <input id="phone" name="phone" type="tel" 
                           class="flex-1 px-3 py-2 border-t border-b border-r border-gray-300 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('phone', auth()->user()->phone) }}" placeholder="9876543210"
                           pattern="[6-9][0-9]{9}" maxlength="10" required
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                </div>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <!-- Company Address Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Company Address</h3>
                <div class="ml-2 relative" x-data="{ show: false }">
                    <button type="button" @mouseenter="show = true" @mouseleave="show = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <div x-show="show" x-transition class="absolute left-6 top-0 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10">
                        Primary business address for invoices and documents (Admin only)
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label for="company_address" class="block text-sm font-medium text-gray-700">Street Address</label>
                    <textarea id="company_address" name="company_address" rows="2"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Building, Street, Area">{{ old('company_address', auth()->user()->company_address) }}</textarea>
                    @error('company_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="company_city" class="block text-sm font-medium text-gray-700">City</label>
                        <input id="company_city" name="company_city" type="text" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('company_city', auth()->user()->company_city) }}">
                        @error('company_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="company_state" class="block text-sm font-medium text-gray-700">State</label>
                        <input id="company_state" name="company_state" type="text" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('company_state', auth()->user()->company_state) }}">
                        @error('company_state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="company_pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
                        <input id="company_pincode" name="company_pincode" type="text" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('company_pincode', auth()->user()->company_pincode) }}"
                               pattern="[0-9]{6}" maxlength="6" placeholder="123456"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                        @error('company_pincode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="company_country" class="block text-sm font-medium text-gray-700">Country</label>
                        <select id="company_country" name="company_country" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="India" {{ old('company_country', auth()->user()->company_country) == 'India' ? 'selected' : '' }}>India</option>
                        </select>
                        @error('company_country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouse Address Card -->
        <div class="bg-white rounded-lg shadow p-6" x-data="{ sameAsCompany: {{ old('warehouse_same_as_company', auth()->user()->warehouse_same_as_company) ? 'true' : 'false' }} }">
            <div class="flex items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Warehouse Address</h3>
                <div class="ml-2 relative" x-data="{ show: false }">
                    <button type="button" @mouseenter="show = true" @mouseleave="show = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <div x-show="show" x-transition class="absolute left-6 top-0 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10">
                        Warehouse location for inventory management (Admin only)
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="warehouse_same_as_company" value="1" 
                           x-model="sameAsCompany"
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Same as Company Address</span>
                </label>
            </div>
            <div x-show="!sameAsCompany" x-transition class="space-y-4">
                <div>
                    <label for="warehouse_address" class="block text-sm font-medium text-gray-700">Street Address</label>
                    <textarea id="warehouse_address" name="warehouse_address" rows="2"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Building, Street, Area">{{ old('warehouse_address', auth()->user()->warehouse_address) }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="warehouse_city" class="block text-sm font-medium text-gray-700">City</label>
                        <input id="warehouse_city" name="warehouse_city" type="text" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('warehouse_city', auth()->user()->warehouse_city) }}">
                    </div>
                    <div>
                        <label for="warehouse_state" class="block text-sm font-medium text-gray-700">State</label>
                        <input id="warehouse_state" name="warehouse_state" type="text" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('warehouse_state', auth()->user()->warehouse_state) }}">
                    </div>
                    <div>
                        <label for="warehouse_pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
                        <input id="warehouse_pincode" name="warehouse_pincode" type="text" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('warehouse_pincode', auth()->user()->warehouse_pincode) }}"
                               pattern="[0-9]{6}" maxlength="6" placeholder="123456"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                    </div>
                    <div>
                        <label for="warehouse_country" class="block text-sm font-medium text-gray-700">Country</label>
                        <select id="warehouse_country" name="warehouse_country" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="India" {{ old('warehouse_country', auth()->user()->warehouse_country) == 'India' ? 'selected' : '' }}>India</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Non-Admin Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Limited Access</h3>
                    <p class="text-sm text-blue-700 mt-1">You can only edit your personal information. Business addresses can only be modified by administrators.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Save Button -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
