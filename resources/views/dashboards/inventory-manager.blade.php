@extends('layouts.app')

@section('title', 'Inventory Manager Dashboard')
@section('page-title', 'Inventory Management')

@section('content')
<div class="p-6 space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Inventory Control Center</h2>
        <p class="text-gray-600">Monitor stock levels, manage inventory, and track material movements.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_items'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['low_stock_items'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['out_of_stock'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-warehouse text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Warehouses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_warehouses'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('inventory.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-list text-blue-600 mr-3"></i>
                <span class="text-blue-700 font-medium">View Inventory</span>
            </a>
            <a href="{{ route('materials.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-boxes text-green-600 mr-3"></i>
                <span class="text-green-700 font-medium">Manage Materials</span>
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-shopping-cart text-yellow-600 mr-3"></i>
                <span class="text-yellow-700 font-medium">Purchase Orders</span>
            </a>
            <a href="{{ route('vendors.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-truck text-purple-600 mr-3"></i>
                <span class="text-purple-700 font-medium">Vendors</span>
            </a>
        </div>
    </div>
</div>
@endsection