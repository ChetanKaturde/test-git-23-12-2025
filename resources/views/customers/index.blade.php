@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Customers</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a> 
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">Customers</span>
                </nav>
                <p class="text-gray-600 mt-1">Manage your customer relationships and contacts</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex items-center space-x-2 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span>{{ $customers->total() }} Total</span>
                </div>
                @canCreateInModule('customers')
                <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <i class="fas fa-plus w-5 h-5 mr-2"></i>
                    New Customer
                </a>
                @endcanCreateInModule
            </div>
        </div>
    </div>

    @if($customers->count() > 0)
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Customers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customers->total() }}</p>
                        <div class="flex items-center mt-2 text-xs text-blue-600">
                            <i class="fas fa-users mr-1"></i>
                            <span>All contacts</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Active Customers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customers->where('is_active', true)->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span>Currently active</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">With GST</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customers->whereNotNull('gstin')->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-purple-600">
                            <i class="fas fa-file-invoice mr-1"></i>
                            <span>GST registered</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Business Type</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customers->where('customer_type', 'business')->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-orange-600">
                            <i class="fas fa-building mr-1"></i>
                            <span>B2B customers</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-building text-orange-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Directory</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-filter mr-1"></i>
                            <span>All customers</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-sort mr-1"></i>
                            <span>A-Z</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact Info</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Business</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-700">{{ substr($customer->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $customer->name }}</div>
                                            @if($customer->contact_person)
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-user mr-1"></i>{{ $customer->contact_person }}
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">
                                                ID: {{ $customer->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                                            {{ $customer->phone }}
                                        </div>
                                        @if($customer->email)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                                                {{ $customer->email }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($customer->city)
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                                {{ $customer->city }}
                                            </div>
                                        @endif
                                        @if($customer->state)
                                            <div class="text-xs text-gray-500 ml-6">{{ $customer->state }}</div>
                                        @endif
                                        @if(!$customer->city && !$customer->state)
                                            <span class="text-gray-400 text-sm">No location</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ ucfirst($customer->customer_type ?? 'business') }}
                                        </div>
                                        @if($customer->gstin)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fas fa-certificate mr-1"></i>
                                                GST: {{ substr($customer->gstin, 0, 6) }}...
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                No GST
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($customer->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <div class="w-1.5 h-1.5 mr-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            <div class="w-1.5 h-1.5 mr-2 bg-gray-400 rounded-full"></div>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @canViewModule('customers')
                                        <a href="{{ route('customers.show', $customer) }}"
                                           class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                                            <i class="fas fa-eye w-4 h-4 mr-1"></i>
                                            View
                                        </a>
                                        @endcanViewModule
                                        @canEditInModule('customers')
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                                            <i class="fas fa-edit w-4 h-4 mr-1"></i>
                                            Edit
                                        </a>
                                        @endcanEditInModule
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="text-center py-12 px-6">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">No Customers Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Start building your customer base. Add your first customer to begin sending quotes and invoices.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @php $customerCount = \App\Models\Customer::where('business_id', auth()->user()->business_id)->count(); @endphp
                    @if($customerCount < 2)
                        <form action="{{ route('business.load-sample-data') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-magic mr-2"></i>
                                Add Sample Data
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('customers.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-plus w-5 h-5 mr-2"></i>
                        Add Your First Customer
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection