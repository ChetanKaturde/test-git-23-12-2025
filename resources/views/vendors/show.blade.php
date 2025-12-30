@extends('layouts.app')

@section('title', 'Vendor Details')
@section('page-title', 'Vendor Details')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $vendor->name }}</h1>
                        <p class="text-gray-600">
                            <i class="fas fa-building mr-2"></i>
                            {{ $vendor->business_name ?? 'Vendor Details' }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('vendors.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>
                        @canEditInModule('vendors')
                        <a href="{{ route('vendors.edit', $vendor->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit Vendor
                        </a>
                        @endcanEditInModule
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Vendor Information -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg">
                <h6 class="font-semibold">
                    <i class="fas fa-user mr-2"></i>Vendor Information
                </h6>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Full Name</label>
                    <p class="text-gray-900 font-medium">{{ $vendor->name }}</p>
                </div>
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Business Name</label>
                    <p class="text-gray-900">{{ $vendor->business_name ?? 'Not provided' }}</p>
                </div>
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Email Address</label>
                    <p class="text-gray-900">
                        @if($vendor->email)
                            <a href="mailto:{{ $vendor->email }}" class="text-blue-600 hover:underline">
                                {{ $vendor->email }}
                            </a>
                        @else
                            Not provided
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm font-semibold">Phone Number</label>
                    <p class="text-gray-900">
                        <a href="tel:+91{{ $vendor->phone }}" class="text-green-600 hover:underline">
                            <i class="fas fa-phone mr-2"></i>+91 {{ $vendor->phone }}
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Company Address -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="bg-red-600 text-white px-4 py-3 rounded-t-lg">
                <h6 class="font-semibold">
                    <i class="fas fa-map-marker-alt mr-2"></i>Company Address
                </h6>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Address</label>
                    <p class="text-gray-900">{{ $vendor->company_address }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">State</label>
                        <p class="text-gray-900">{{ $companyState->name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">City</label>
                        <p class="text-gray-900">{{ $companyCity->name ?? 'Not specified' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">Country</label>
                        <p class="text-gray-900">{{ $vendor->company_country ?? 'India' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">Pincode</label>
                        <p class="text-gray-900">{{ $vendor->company_pincode ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouse Address -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="bg-cyan-600 text-white px-4 py-3 rounded-t-lg">
                <h6 class="font-semibold">
                    <i class="fas fa-warehouse mr-2"></i>Warehouse Address
                </h6>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Address</label>
                    <p class="text-gray-900">{{ $vendor->warehouse_address ?? 'Not provided' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">State</label>
                        <p class="text-gray-900">{{ $warehouseState->name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">City</label>
                        <p class="text-gray-900">{{ $warehouseCity->name ?? 'Not specified' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">Country</label>
                        <p class="text-gray-900">{{ $vendor->warehouse_country ?? 'India' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm font-semibold">Pincode</label>
                        <p class="text-gray-900">{{ $vendor->warehouse_pincode ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Information -->
        @if($vendor->bank_holder_name)
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="bg-green-600 text-white px-4 py-3 rounded-t-lg">
                <h6 class="font-semibold">
                    <i class="fas fa-university mr-2"></i>Bank Details
                </h6>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Account Holder</label>
                    <p class="text-gray-900 font-medium">{{ $vendor->bank_holder_name }}</p>
                </div>
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Bank Name</label>
                    <p class="text-gray-900">{{ $vendor->bank_name }}</p>
                </div>
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Branch</label>
                    <p class="text-gray-900">{{ $vendor->branch_name }}</p>
                </div>
                <div class="mb-4">
                    <label class="text-gray-500 text-sm font-semibold">Account Number</label>
                    <p class="text-gray-900 font-mono">{{ $vendor->account_number }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm font-semibold">IFSC Code</label>
                    <p class="text-gray-900 font-mono">{{ $vendor->ifsc_code }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Materials Table -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="bg-yellow-500 text-gray-900 px-4 py-3 rounded-t-lg">
                <h5 class="font-semibold">
                    <i class="fas fa-boxes mr-2"></i>Materials Supplied
                </h5>
            </div>
            <div class="p-0">
                @if($vendor->materials->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                        <h6 class="text-gray-600 font-medium mb-2">No materials associated with this vendor</h6>
                        <p class="text-gray-500 text-sm">Materials will appear here once they are linked to this vendor.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material Name</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">GST Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vendor->materials as $index => $material)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $material->pivot->material_name ?? $material->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $material->pivot->quantity ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-medium text-green-600">
                                                ₹{{ number_format($material->pivot->unit_price, 2) ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ number_format($material->pivot->gst_rate, 2) ?? '-' }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection