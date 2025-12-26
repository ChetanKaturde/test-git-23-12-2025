@extends('layouts.app')
@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')

@section('content')
<div class="p-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $orders->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $orders->where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Received Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $orders->where('status', 'received')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-dollar-sign text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Value</p>
                    <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($orders->sum('total_amount'), 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Purchase Orders</h2>
                <p class="text-gray-600 text-sm">Manage procurement and supplier orders</p>
            </div>
            <a href="{{ route('purchase-orders.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Create Order</span>
            </a>
        </div>





        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $order->po_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->vendor->name ?? 'Unknown Vendor' }}</div>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($order->po_date)
                                    {{ \Carbon\Carbon::parse($order->po_date)->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status === 'approved') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'received') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'ordered') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status ?? 'Unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₹{{ number_format($order->total_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('purchase-orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!in_array($order->status, ['received', 'cancelled']))
                                    <a href="{{ route('purchase-orders.edit', $order) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        @else
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                    <i class="fas fa-shopping-cart text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No purchase orders yet</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first purchase order.</p>
                <a href="{{ route('purchase-orders.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Create First Order</span>
                </a>
            </div>
        @endif
    </div>
</div>


@endsection