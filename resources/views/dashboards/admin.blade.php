@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administrator Dashboard')

@section('content')
<div class="p-4 md:p-6 space-y-4 md:space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Administrator Dashboard</h2>
        <p class="text-gray-600">Complete system overview and management controls.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-boxes text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Materials</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['materials'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Purchase Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['purchase_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-industry text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Machines</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['machines'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-clipboard-list text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Work Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['work_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <i class="fas fa-truck text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vendors</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['vendors'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Administration</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            <a href="{{ route('team.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-users-cog text-blue-600 mr-3"></i>
                <span class="text-blue-700 font-medium">Manage Team</span>
            </a>
            <a href="{{ route('materials.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-boxes text-green-600 mr-3"></i>
                <span class="text-green-700 font-medium">Materials</span>
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-shopping-cart text-yellow-600 mr-3"></i>
                <span class="text-yellow-700 font-medium">Purchase Orders</span>
            </a>
            <a href="{{ route('activity-log.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-history text-purple-600 mr-3"></i>
                <span class="text-purple-700 font-medium">Activity Log</span>
            </a>
        </div>
    </div>
</div>
@endsection