@extends('layouts.app')
@section('title', 'Inventory')
@section('page-title', 'Inventory Batches')

@section('content')
<div class="p-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-rupee-sign text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Stock Value</p>
                    <p class="text-2xl font-semibold text-gray-900">₹2,45,000</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
                    <p class="text-2xl font-semibold text-gray-900">3</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-boxes text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total SKUs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $inventory->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Inventory</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a> 
                    > <span class="font-medium">Inventory</span>
                </nav>
            </div>
            <div>
                <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    Purchase Orders
                </a>
            </div>
        </div>

        <div class="overflow-hidden">
        @if($inventory->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inventory as $batch)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $batch->material->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $batch->batch_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $batch->quantity ?? $batch->current_quantity ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $batch->storage_location ?? 'Warehouse A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $qty = $batch->quantity ?? $batch->current_quantity ?? 0;
                                $status = $qty > 50 ? 'In Stock' : ($qty > 0 ? 'Low Stock' : 'Out of Stock');
                                $statusClass = $qty > 50 ? 'bg-green-100 text-green-800' : ($qty > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                    <i class="fas fa-boxes text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Inventory Batches</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                    Inventory batches are automatically created when purchase orders are approved and received.
                </p>
                <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    View Purchase Orders
                </a>
            </div>
        @endif
    </div>
</div>
@endsection