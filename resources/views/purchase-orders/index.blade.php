@extends('layouts.app')
@section('title', 'Purchase Orders')
@section('content')

<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Purchase Orders</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a> 
                > <span class="font-medium">Purchase Orders</span>
            </nav>
        </div>
        <div>
            @if(auth()->user()->canCreateInModule('purchase_orders'))
                <a href="{{ route('purchase-orders.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Purchase Order
                </a>
            @endif
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $purchaseOrders->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $purchaseOrders->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $purchaseOrders->where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-rupee-sign text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Value</p>
                    <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($purchaseOrders->sum('total_amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Orders Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Purchase Orders</h2>
        </div>
        
        @if($purchaseOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchaseOrders as $po)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $po->po_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $po->vendor->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $po->po_date ? $po->po_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₹{{ number_format($po->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'received' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$po->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('purchase-orders.edit', $po) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($po->status === 'pending' && in_array(auth()->user()->role, ['admin', 'manager']))
                                        <form method="POST" action="{{ route('purchase-orders.approve', $po) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Approve this purchase order?')" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($po->status === 'approved' && in_array(auth()->user()->role, ['admin', 'inventory_manager']))
                                        <form method="POST" action="{{ route('purchase-orders.receive', $po) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900" onclick="return confirm('Mark as received? This will update inventory.')" title="Mark as Received">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🛒</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Purchase Orders</h3>
                <p class="text-gray-500 mb-4">Create your first purchase order to start procurement.</p>
                <a href="{{ route('purchase-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Create Purchase Order
                </a>
            </div>
        @endif
    </div>
</div>

@endsection