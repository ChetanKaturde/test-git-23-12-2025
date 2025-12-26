@extends('layouts.app')

@section('title', 'Overview Dashboard')
@section('page-title', 'System Overview')

@section('content')
<div class="p-6 space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">System Overview</h2>
        <p class="text-gray-600">Read-only view of manufacturing operations and statistics.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Materials</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_materials'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Work Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_work_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-industry text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Machines</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_machines'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inventory Value</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['inventory_value'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- View Options -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">View Reports & Data</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('materials.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-eye text-blue-600 mr-3"></i>
                <span class="text-blue-700 font-medium">View Materials</span>
            </a>
            <a href="{{ route('work-orders.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-eye text-green-600 mr-3"></i>
                <span class="text-green-700 font-medium">View Work Orders</span>
            </a>
            <a href="{{ route('machines.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-eye text-yellow-600 mr-3"></i>
                <span class="text-yellow-700 font-medium">View Machines</span>
            </a>
            <a href="{{ route('inventory.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-eye text-purple-600 mr-3"></i>
                <span class="text-purple-700 font-medium">View Inventory</span>
            </a>
        </div>
    </div>

    <!-- Notice -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-3"></i>
            <div>
                <h4 class="text-yellow-800 font-medium">Read-Only Access</h4>
                <p class="text-yellow-700 text-sm mt-1">You have view-only permissions. Contact your administrator to request additional access.</p>
            </div>
        </div>
    </div>
</div>
@endsection