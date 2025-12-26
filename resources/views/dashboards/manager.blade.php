@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('page-title', 'Manager Dashboard')

@section('content')
<div class="p-6 space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-600">Here's your production overview and team management dashboard.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Work Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_work_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-cogs text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Work Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_work_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Team Members</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['team_members'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['low_stock_items'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_approvals'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('work-orders.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus text-blue-600 mr-3"></i>
                <span class="text-blue-700 font-medium">Create Work Order</span>
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-shopping-cart text-green-600 mr-3"></i>
                <span class="text-green-700 font-medium">Review Purchase Orders</span>
            </a>
            <a href="{{ route('inventory.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-warehouse text-yellow-600 mr-3"></i>
                <span class="text-yellow-700 font-medium">Check Inventory</span>
            </a>
            <a href="{{ route('team.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-users text-purple-600 mr-3"></i>
                <span class="text-purple-700 font-medium">Manage Team</span>
            </a>
        </div>
    </div>
</div>
@endsection