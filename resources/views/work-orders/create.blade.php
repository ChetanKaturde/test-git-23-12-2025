@extends('layouts.app')

@section('title', 'Create Work Order')
@section('page-title', 'Create Work Order')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Work Order</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('work-orders.index') }}" class="hover:text-blue-600 transition-colors">Work Orders</a>
                    > <span class="font-medium">Create</span>
                </nav>
            </div>
            <a href="{{ route('work-orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Work Orders
            </a>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Work Order Details</h3>
        </div>
        <div class="p-6">

            <form method="POST" action="{{ route('work-orders.store') }}" x-data="{ loading: false }" x-on:submit="loading = true" class="space-y-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="wo_number" class="block text-sm font-semibold text-gray-700 mb-2">Work Order Number *</label>
                        <input type="text" name="wo_number" id="wo_number" 
                               value="{{ old('wo_number', 'WO-' . now()->format('Y') . '-' . str_pad(1, 3, '0', STR_PAD_LEFT)) }}"
                               class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               required>
                        @error('wo_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_id" class="block text-sm font-semibold text-gray-700 mb-2">Customer</label>
                        <select name="customer_id" id="customer_id" 
                                class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Customer (Optional)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Machine & Product -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="machine_id" class="block text-sm font-semibold text-gray-700 mb-2">Machine *</label>
                        <select name="machine_id" id="machine_id" 
                                class="w-full h-11 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Select Machine</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                                    {{ $machine->name }} ({{ ucfirst($machine->status) }})
                                </option>
                            @endforeach
                        </select>
                        @error('machine_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="product_name" id="product_name" 
                           value="{{ old('product_name') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., Steel Bracket, Aluminum Frame"
                           required>
                    @error('product_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" id="quantity" 
                           value="{{ old('quantity') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           min="1" required>
                    @error('quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quoted_rate" class="block text-sm font-medium text-gray-700 mb-2">Quoted Rate per Unit (₹)</label>
                    <input type="number" name="quoted_rate" id="quoted_rate" 
                           value="{{ old('quoted_rate') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           min="0" step="0.01">
                    @error('quoted_rate')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign to Operator (Optional)</label>
                        <select name="assigned_to" id="assigned_to" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Assign later...</option>
                            @foreach(\App\Models\User::where('business_id', auth()->user()->business_id)->where('role', 'operator')->where('is_active', true)->get() as $operator)
                                <option value="{{ $operator->id }}" {{ old('assigned_to') == $operator->id ? 'selected' : '' }}>
                                    {{ $operator->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                              placeholder="Additional instructions, requirements, or special notes for this work order...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('work-orders.index') }}" 
                       class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center disabled:opacity-50"
                            x-bind:disabled="loading">
                        <span x-show="!loading" class="flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Create Work Order
                        </span>
                        <span x-show="loading" class="flex items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Creating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection