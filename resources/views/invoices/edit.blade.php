@extends('layouts.app')

@section('title', 'Edit Invoice')
@section('page-title', 'Edit Invoice')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Warning Banner -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    Warning: You are editing an invoice
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>• Only draft invoices can be edited</p>
                    <p>• All changes will be logged for audit purposes</p>
                    <p>• You must provide a reason for this edit</p>
                    <p>• Consider creating a new invoice instead if this has been sent to customer</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Edit Invoice {{ $invoice->invoice_number }}
                </h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Edit Reason (Required) -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <label for="edit_reason" class="block text-sm font-medium text-red-800 mb-2">
                    Reason for Edit <span class="text-red-600">*</span>
                </label>
                <textarea id="edit_reason" 
                          name="edit_reason" 
                          rows="3" 
                          required
                          class="w-full px-3 py-2 border border-red-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('edit_reason') border-red-500 @enderror"
                          placeholder="Please explain why you are editing this invoice (e.g., customer requested address change, amount correction, etc.)">{{ old('edit_reason') }}</textarea>
                @error('edit_reason')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-red-600 text-xs mt-1">This reason will be logged and cannot be changed later.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="customer_name" 
                           name="customer_name" 
                           value="{{ old('customer_name', $invoice->customer_name) }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('customer_name') border-red-500 @enderror">
                    @error('customer_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Email -->
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Email
                    </label>
                    <input type="email" 
                           id="customer_email" 
                           name="customer_email" 
                           value="{{ old('customer_email', $invoice->customer_email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('customer_email') border-red-500 @enderror">
                    @error('customer_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₹</span>
                        </div>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount', $invoice->total_amount) }}" 
                               step="0.01" 
                               min="0" 
                               required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="due_date" 
                           name="due_date" 
                           value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('invoices.show', $invoice) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancel
                </a>
                
                @canEditInModule('invoices')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Update Invoice
                </button>
                @endcanEditInModule
            </div>
        </form>
    </div>
</div>
@endsection