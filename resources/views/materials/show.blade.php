@extends('layouts.app')
@section('title', 'View Commodity')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Commodity Details</h2>
        <a href="{{ route('materials.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Commodities
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commodity Name</label>
                    <p class="text-gray-900">{{ $material->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Code</label>
                    <p class="text-gray-900">{{ $material->code }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <p class="text-gray-900">
                        @if($material->item_type === 'service')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                💼 Service
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                📦 Good
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <p class="text-gray-900">{{ $material->category ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="text-gray-900">{{ $material->description ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <p class="text-gray-900">{{ $material->unit }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                    <p class="text-gray-900">₹{{ number_format($material->unit_price, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">GST Rate</label>
                    <p class="text-gray-900">{{ $material->gst_rate }}%</p>
                </div>
                @if($material->sku && $material->item_type !== 'service')
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU</label>
                    <p class="text-gray-900">{{ $material->sku }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Status & Meta Information -->
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Meta Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $material->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Created At</label>
                    <p class="text-gray-900">{{ $material->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Updated At</label>
                    <p class="text-gray-900">{{ $material->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
