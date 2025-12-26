@extends('layouts.app')

@section('title', 'Operator Dashboard')
@section('page-title', 'My Work Dashboard')

@section('content')
<div class="p-6 space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Hello, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-600">Your assigned tasks and machine operations dashboard.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-tasks text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">My Work Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['my_work_orders'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-industry text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Machines</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_machines'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-check-circle text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_today'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-boxes text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Materials Available</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['materials_available'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <a href="{{ route('work-orders.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-list text-blue-600 mr-3"></i>
                <span class="text-blue-700 font-medium">My Tasks</span>
            </a>
            <a href="{{ route('machines.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-cogs text-green-600 mr-3"></i>
                <span class="text-green-700 font-medium">Machine Status</span>
            </a>
            <a href="{{ route('materials.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-boxes text-yellow-600 mr-3"></i>
                <span class="text-yellow-700 font-medium">Check Materials</span>
            </a>
        </div>
    </div>
</div>
@endsection