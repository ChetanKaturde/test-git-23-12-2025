@extends('layouts.app')

@section('title', 'Purchase Team Dashboard')
@section('page-title', 'Purchase Management')

@section('content')
<div class="p-6 space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Purchase Operations Center</h2>
        <p class="text-gray-600">Manage purchase orders, vendor relationships, and procurement activities.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-truck text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Vendors</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_vendors'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-rupee-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Budget Utilized</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['budget_utilized']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('purchase-orders.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus text-blue-600 mr-3"></i>
                <span class="text-blue-700 font-medium">New Purchase Order</span>
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-list text-green-600 mr-3"></i>
                <span class="text-green-700 font-medium">View Orders</span>
            </a>
            <a href="{{ route('vendors.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-truck text-yellow-600 mr-3"></i>
                <span class="text-yellow-700 font-medium">Manage Vendors</span>
            </a>
            <a href="{{ route('materials.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-boxes text-purple-600 mr-3"></i>
                <span class="text-purple-700 font-medium">Materials</span>
            </a>
        </div>
    </div>
</div>
@endsection