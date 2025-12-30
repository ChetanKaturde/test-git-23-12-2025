@extends('layouts.app')

@section('page-title', 'Edit Vendor')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Vendor</h1>
        <a href="{{ route('vendors.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" id="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                           value="{{ old('name', $vendor->name) }}" required minlength="2" maxlength="100">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                    <input type="text" name="business_name" id="business_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('business_name') border-red-500 @enderror" 
                           value="{{ old('business_name', $vendor->business_name) }}" maxlength="150">
                    @error('business_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                           value="{{ old('email', $vendor->email) }}" maxlength="100">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">+91</span>
                        <input type="text" name="phone" id="phone" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                               value="{{ old('phone', $vendor->phone) }}" maxlength="10" pattern="[6-9]\d{9}" required
                               title="Enter 10 digit mobile number starting with 6-9">
                    </div>
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
 
             <!-- Address Information -->
            <div class="bg-white border border-gray-200 rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Address Information</h3>
                </div>
                <div class="p-6">
                    <!-- Company Address -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-900 mb-4"><i class="fas fa-building mr-2"></i> Company Address *</h4>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                                <textarea name="company_address" id="company_address"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_address') border-red-500 @enderror"
                                          rows="2" required minlength="10" maxlength="500"
                                          placeholder="Enter street address, building name, etc.">{{ old('company_address', $vendor->company_address ?? '') }}</textarea>
                                @error('company_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="company_state" class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                    <select name="company_state" id="company_state" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_state') border-red-500 @enderror" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $stateName)
                                            <option value="{{ $stateName }}"
                                                {{ old('company_state', $vendor->company_state) == $stateName ? 'selected' : '' }}>
                                                {{ $stateName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_state')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="company_city" class="block text-sm font-medium text-gray-700 mb-2">City/District *</label>
                                    <select name="company_city" id="company_city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_city') border-red-500 @enderror" required>
                                        <option value="">Select City/District</option>
                                    </select>
                                    @error('company_city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="company_pincode" class="block text-sm font-medium text-gray-700 mb-2">Pincode *</label>
                                    <input type="text" name="company_pincode" id="company_pincode"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_pincode') border-red-500 @enderror"
                                           value="{{ old('company_pincode', $vendor->company_pincode ?? '') }}" maxlength="6"
                                           pattern="[0-9]{6}" required placeholder="Enter 6-digit pincode">
                                    @error('company_pincode')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="company_country" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                    <select name="company_country" id="company_country"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_country') border-red-500 @enderror" required>
                                        <option value="India" {{ old('company_country', $vendor->company_country ?? 'India') === 'India' ? 'selected' : '' }}>India</option>
                                    </select>
                                    @error('company_country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warehouse Address -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900"><i class="fas fa-warehouse mr-2"></i> Warehouse Address</h4>
                            <div class="flex items-center">
                                <input class="mr-2" type="checkbox" id="same_as_company" name="same_as_company">
                                <label class="text-sm text-gray-700" for="same_as_company">
                                    Same as Company Address
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="warehouse_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                                <textarea name="warehouse_address" id="warehouse_address"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_address') border-red-500 @enderror"
                                          rows="2" maxlength="500"
                                          placeholder="Enter warehouse street address">{{ old('warehouse_address', $vendor->warehouse_address ?? '') }}</textarea>
                                @error('warehouse_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="warehouse_state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                    <select name="warehouse_state" id="warehouse_state"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_state') border-red-500 @enderror">
                                        <option value="">Select State</option>
                                        @foreach($states as $stateName)
                                            <option value="{{ $stateName }}"
                                                {{ old('warehouse_state', $vendor->warehouse_state) == $stateName ? 'selected' : '' }}>
                                                {{ $stateName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_state')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="warehouse_city" class="block text-sm font-medium text-gray-700 mb-2">City/District</label>
                                    <select name="warehouse_city" id="warehouse_city"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_city') border-red-500 @enderror">
                                        <option value="">Select City/District</option>
                                    </select>
                                    @error('warehouse_city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="warehouse_pincode" class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                                    <input type="text" name="warehouse_pincode" id="warehouse_pincode"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_pincode') border-red-500 @enderror"
                                           value="{{ old('warehouse_pincode', $vendor->warehouse_pincode ?? '') }}" maxlength="6"
                                           pattern="[0-9]{6}" placeholder="Enter 6-digit pincode">
                                    @error('warehouse_pincode')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="warehouse_country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                    <select name="warehouse_country" id="warehouse_country"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_country') border-red-500 @enderror">
                                        <option value="India" {{ old('warehouse_country', $vendor->warehouse_country ?? 'India') === 'India' ? 'selected' : '' }}>India</option>
                                    </select>
                                    @error('warehouse_country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

      
             <!-- Materials Table -->
            <div class="bg-white border border-gray-200 rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Materials</h3>
                </div>
                <div class="overflow-x-auto">
                    <table id="materials-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barcode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remove</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($vendor->materials as $index => $row)
                                <tr data-row-id="{{ $row->pivot->id ?? $row->id }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="materials[{{ $index }}][name]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 material-name-select">
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}"
                                                    data-sku="{{ $material->sku }}"
                                                    data-barcode="{{ $material->barcode }}"
                                                    data-unit="{{ $material->unit }}"
                                                    data-gst="{{ $material->gst_rate }}"
                                                    {{ $material->id == $row->id ? 'selected' : '' }}>
                                                    {{ $material->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 sku-input" value="{{ $row->sku }}" readonly></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 barcode-input" value="{{ $row->barcode }}" readonly></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 unit-input" value="{{ $row->unit }}" readonly></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 unit-price-input"
                                               name="materials[{{ $index }}][price]"
                                               value="{{ $row->pivot->unit_price }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" step="1" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 quantity-input"
                                               name="materials[{{ $index }}][quantity]"
                                               value="{{ $row->pivot->quantity ?? '' }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><button type="button" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm remove-row">Remove</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium" id="add-material-row">Add Material</button>
                </div>
            </div>

<!-- Template for new material row -->
<template id="material-row-template">
  <tr>
    <td>
      <select class="form-control material-name-select">
        @foreach ($materials as $material)
          <option value="{{ $material->id }}"
            data-sku="{{ $material->sku }}"
            data-barcode="{{ $material->barcode }}"
            data-unit="{{ $material->unit }}"
            data-gst="{{ $material->gst_rate }}">
            {{ $material->name }}
          </option>
        @endforeach
      </select>
    </td>
    <td><input type="text" class="form-control sku-input" readonly></td>
    <td><input type="text" class="form-control barcode-input" readonly></td>
    <td><input type="text" class="form-control unit-input" readonly></td>
    <td><input type="number" step="0.01" class="form-control unit-price-input" value="0.00"></td>
    <td><input type="number" step="1" min="1" class="form-control quantity-input" value="1"></td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
  </tr>
</template>
<!-- Load jQuery (place this BEFORE your custom script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- JavaScript -->
<script>
$(document).ready(function () {
  // Track initial selections
  $('.material-name-select').each(function () {
    $(this).data('last-value', $(this).val());
  });

  // On material change
  $(document).on('change', '.material-name-select', function () {
    const select = $(this);
    const row = select.closest('tr');
    const rowId = row.data('row-id');
    const oldMaterialId = select.data('last-value');
    const vendorId = $('#selectedVendorId').val();
    const currentPrice = row.find('.unit-price-input').val();

    // Update old material if existing row
    if (vendorId && rowId) {
      $.ajax({
        url: `/api/material-row/${rowId}`,
        type: 'PUT',
        data: {
          material_id: oldMaterialId,
          vendor_id: vendorId,
          unit_price: currentPrice
        }
      }).always(function () {
        updateMaterialDetails(select, vendorId, row, rowId);
      });
    } else {
      updateMaterialDetails(select, vendorId, row, rowId);
    }
  });

  // Update material details (SKU, price, etc.)
  function updateMaterialDetails(select, vendorId, row, rowId) {
    const selected = select.find('option:selected');
    const newMaterialId = selected.val();

    row.find('.sku-input').val(selected.data('sku'));
    row.find('.barcode-input').val(selected.data('barcode'));
    row.find('.unit-input').val(selected.data('unit'));

    $.get(`/api/vendor-material-price?vendor_id=${vendorId}&material_id=${newMaterialId}`, function (res) {
      const price = res.unit_price ?? 0;
      row.find('.unit-price-input').val(price);

      if (rowId) {
        $.ajax({
          url: `/api/material-row/${rowId}`,
          type: 'PUT',
          data: {
            material_id: newMaterialId,
            vendor_id: vendorId,
            unit_price: price
          }
        });
      }

      select.data('last-value', newMaterialId);
    });
  }

  // Remove row
  $(document).on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
  });

  // Add new row
  $('#add-material-row').on('click', function () {
    const index = $('#materials-table tbody tr').length;
    const $template = $($('#material-row-template').html());

    $template.find('.material-name-select').attr('name', `materials[${index}][name]`);
    $template.find('.unit-price-input').attr('name', `materials[${index}][price]`);
    $template.find('.quantity-input').attr('name', `materials[${index}][quantity]`);

    $('#materials-table tbody').append($template);
  });
});
</script>


<!-- Hidden input for vendor ID (used in JS) -->
<input type="hidden" id="selectedVendorId" value="{{ $vendor->id }}">

            <!-- Bank Details -->
            <div class="bg-white border border-gray-200 rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Bank Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="bank_holder_name" class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name *</label>
                            <input type="text" name="bank_holder_name" id="bank_holder_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bank_holder_name') border-red-500 @enderror" 
                                   value="{{ old('bank_holder_name', $vendor->bank_holder_name) }}" required>
                            @error('bank_holder_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="branch_name" class="block text-sm font-medium text-gray-700 mb-2">Branch Name *</label>
                            <input type="text" name="branch_name" id="branch_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('branch_name') border-red-500 @enderror" 
                                   value="{{ old('branch_name', $vendor->branch_name) }}" required minlength="2" maxlength="100">
                            @error('branch_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

<div class="row">
          
      <div class="col-md-6">
    <div class="mb-3">
        <label for="bank_search" class="form-label">
            Bank Name <span class="text-danger">*</span>
        </label>
        <div class="dropdown-container position-relative">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="bank_search" 
                    class="form-control search-input"
                    placeholder="Search or select bank..."
                    value="{{ old('bank_name', $vendor->bank_name) }}" 
                    autocomplete="off"
                >
            </div>
            <div class="bank-dropdown position-absolute bg-white border w-100" id="bankDropdown" style="max-height: 200px; overflow-y: auto; z-index: 1000;"></div>
            <input type="hidden" name="bank_name" id="bank_name" value="{{ old('bank_name', $vendor->bank_name) }}" required>
        </div>
        @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="invalid-feedback" id="bank_name-error"></div>
    </div>
</div>


            <div class="col-md-6 mb-3">
                <label for="account_number" class="form-label">Account Number *</label>
                <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                       value="{{ old('account_number', $vendor->account_number) }}" required>
                @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="ifsc_code" class="form-label">IFSC Code *</label>
                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" 
                       value="{{ old('ifsc_code', $vendor->ifsc_code) }}" style="text-transform: uppercase" required>
                @error('ifsc_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('vendors.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                @canEditInModule('vendors')
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">
                    Update Vendor
                </button>
                @endcanEditInModule
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('same_as_company').addEventListener('change', function () {
    const checked = this.checked;

    const fields = ['address', 'state', 'city', 'pincode', 'country'];
    fields.forEach(field => {
        const companyVal = document.getElementById(`company_${field}`).value;
        const warehouseInput = document.getElementById(`warehouse_${field}`);

        if (checked) {
            warehouseInput.value = companyVal;
            warehouseInput.disabled = true;
        } else {
            warehouseInput.disabled = false;
            warehouseInput.value = '';
        }

        // If it's a select, also trigger change for dependent fields
        if (warehouseInput.tagName === 'SELECT') {
            warehouseInput.dispatchEvent(new Event('change'));
        }
    });
});
  
  $(document).ready(function () {
    // Get selected state names instead of IDs
    const companyStateName = $('#company_state option:selected').text();
    const companyCityName = "{{ old('company_city', $vendor->company_city ?? '') }}";

    const warehouseStateName = $('#warehouse_state option:selected').text();
    const warehouseCityName = "{{ old('warehouse_city', $vendor->warehouse_city ?? '') }}";

    // Auto-load cities if company state is pre-selected
    if (companyStateName && companyStateName !== 'Select State') {
        $('#company_city').prop('disabled', false);
        $.get(`/api/cities/${encodeURIComponent(companyStateName)}`, function (cities) {
            $('#company_city').empty().append('<option value="">Select City/District</option>');
            cities.forEach(cityName => {
                const selected = cityName == companyCityName ? 'selected' : '';
                $('#company_city').append(`<option value="${cityName}" ${selected}>${cityName}</option>`);
            });
        }).fail(function() {
            console.error('Failed to load cities for company state:', companyStateName);
        });
    }

    // Auto-load cities if warehouse state is pre-selected
    if (warehouseStateName && warehouseStateName !== 'Select State') {
        $('#warehouse_city').prop('disabled', false);
        $.get(`/api/cities/${encodeURIComponent(warehouseStateName)}`, function (cities) {
            $('#warehouse_city').empty().append('<option value="">Select City/District</option>');
            cities.forEach(cityName => {
                const selected = cityName == warehouseCityName ? 'selected' : '';
                $('#warehouse_city').append(`<option value="${cityName}" ${selected}>${cityName}</option>`);
            });
        }).fail(function() {
            console.error('Failed to load cities for warehouse state:', warehouseStateName);
        });
    }

    // Handle state change events
    $('#company_state').on('change', function() {
        const stateName = $(this).find('option:selected').text();
        const citySelect = $('#company_city');
        
        citySelect.empty().append('<option value="">Select City/District</option>').prop('disabled', true);
        
        if (stateName && stateName !== 'Select State') {
            $.get(`/api/cities/${encodeURIComponent(stateName)}`, function (cities) {
                cities.forEach(cityName => {
                    citySelect.append(`<option value="${cityName}">${cityName}</option>`);
                });
                citySelect.prop('disabled', false);
            }).fail(function() {
                console.error('Failed to load cities for state:', stateName);
            });
        }
    });

    $('#warehouse_state').on('change', function() {
        const stateName = $(this).find('option:selected').text();
        const citySelect = $('#warehouse_city');
        
        citySelect.empty().append('<option value="">Select City/District</option>').prop('disabled', true);
        
        if (stateName && stateName !== 'Select State') {
            $.get(`/api/cities/${encodeURIComponent(stateName)}`, function (cities) {
                cities.forEach(cityName => {
                    citySelect.append(`<option value="${cityName}">${cityName}</option>`);
                });
                citySelect.prop('disabled', false);
            }).fail(function() {
                console.error('Failed to load cities for state:', stateName);
            });
        }
    });
});

</script>





<script>
 document.addEventListener('DOMContentLoaded', () => {
    const bankSearch = document.getElementById('bank_search');
    const bankDropdown = document.getElementById('bankDropdown');
    const bankHiddenInput = document.getElementById('bank_name');

    const bankList = [
        { name: 'State Bank of India' }, { name: 'HDFC Bank' }, { name: 'ICICI Bank' },
        { name: 'Axis Bank' }, { name: 'Kotak Mahindra Bank' }, { name: 'Punjab National Bank' },
        { name: 'Bank of Baroda' }, { name: 'Canara Bank' }, { name: 'Union Bank of India' },
        { name: 'Bank of India' }, { name: 'Indian Bank' }, { name: 'Central Bank of India' },
        { name: 'Indian Overseas Bank' }, { name: 'UCO Bank' }, { name: 'Bank of Maharashtra' },
        { name: 'Punjab & Sind Bank' }, { name: 'IndusInd Bank' }, { name: 'Yes Bank' },
        { name: 'IDFC First Bank' }, { name: 'Federal Bank' }, { name: 'South Indian Bank' },
        { name: 'Karur Vysya Bank' }, { name: 'City Union Bank' }, { name: 'Dhanlaxmi Bank' },
        { name: 'RBL Bank' }, { name: 'Bandhan Bank' }, { name: 'IDBI Bank' },
        { name: 'Tamil Nadu Mercantile Bank' }, { name: 'DCB Bank' }, { name: 'Lakshmi Vilas Bank' }
    ];

    function populateDropdown(searchTerm = '') {
        bankDropdown.innerHTML = '';
        const filteredBanks = bankList.filter(bank => 
            bank.name.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (filteredBanks.length === 0) {
            const noResult = document.createElement('div');
            noResult.classList.add('dropdown-item', 'text-muted');
            noResult.textContent = 'No banks found';
            bankDropdown.appendChild(noResult);
            return;
        }

        filteredBanks.forEach(bank => {
            const div = document.createElement('div');
            div.classList.add('dropdown-item');
            div.style.cursor = 'pointer';
            div.textContent = bank.name;
            div.addEventListener('click', () => {
                bankSearch.value = bank.name;
                bankHiddenInput.value = bank.name;
                bankDropdown.innerHTML = '';
            });
            bankDropdown.appendChild(div);
        });
    }

    bankSearch.addEventListener('input', () => {
        populateDropdown(bankSearch.value);
    });

    // On load, make sure hidden input has correct value
    bankHiddenInput.value = bankSearch.value;

    // Close dropdown if clicked outside
    document.addEventListener('click', (e) => {
        if (!bankSearch.contains(e.target) && !bankDropdown.contains(e.target)) {
            bankDropdown.innerHTML = '';
        }
    });
});

</script>
@endsection
