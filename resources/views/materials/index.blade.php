@extends('layouts.app')
@section('title', 'Commodities')
@section('page-title', 'Commodities')

@section('content')
<div class="p-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-boxes text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Commodities</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $materials->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Commodities</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $materials->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6" style="display: none;">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6" style="display: none;">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-truck text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vendors</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Vendor::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Commodities</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a> 
                    > <span class="font-medium">Commodities</span>
                </nav>
            </div>
            <div>
                @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_commodity'))
                    <a href="{{ route('materials.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Commodity
                    </a>
                @endif
            </div>
        </div>

        @if($materials->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="display: none;">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="display: none;">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="display: none;">
                                <div class="flex items-center space-x-1">
                                    <span>Reorder Point</span>
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <span @mouseover="open = true" @mouseleave="open = false" class="cursor-pointer w-5 h-5 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 text-xs">?</span>
                                        <div x-show="open" class="absolute z-10 mt-1 left-0 bg-gray-800 text-white text-xs p-2 rounded shadow whitespace-nowrap">
                                            Minimum stock level before reordering
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($materials as $material)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                        @if($material->item_type === 'service')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                💼 Service
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                📦 Good
                                            </span>
                                        @endif
                                    </div>
                                    @if($material->sku && $material->item_type !== 'service')
                                        <div class="text-sm text-gray-500">SKU: {{ $material->sku }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" style="display: none;">Primary Supplier</td>
                            <td class="px-6 py-4 whitespace-nowrap" style="display: none;">
                                @if($material->item_type === 'service')
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Service</span>
                                    <div class="text-sm text-gray-500 mt-1">N/A</div>
                                @else
                                    @php
                                        $currentStock = $material->getCurrentStock();
                                    @endphp
                                    @if($currentStock > 0)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">In Stock</span>
                                        <div class="text-sm text-gray-500 mt-1">{{ number_format($currentStock, 2) }} {{ $material->unit }}</div>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Out of Stock</span>
                                        <div class="text-sm text-gray-500 mt-1">0 {{ $material->unit }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" style="display: none;">50 {{ $material->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($material->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $material->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('materials.show', $material) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_commodity'))
                                    <a href="{{ route('materials.edit', $material) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_commodity'))
                                    <form action="{{ route('materials.destroy', $material) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $materials->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                    <i class="fas fa-boxes text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No commodities yet</h3>
                <p class="text-gray-500 mb-6">Get started by adding your first commodity to the inventory.</p>
                @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('manage_commodity'))
                    <a href="{{ route('materials.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add First Commodity</span>
                    </a>
                @else
                    <p class="text-gray-500">You have read-only access to commodities.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
