@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-white bg-opacity-5 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100 text-sm md:text-base">{{ auth()->user()->business?->name ?? 'Default Workshop' }} • {{ auth()->user()->getTeamDisplayName() }}</p>
                    <div class="flex items-center mt-2 text-blue-200 text-sm">
                        <i class="fas fa-clock mr-2"></i>
                        <span>{{ now()->format('l, F j, Y • g:i A') }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ auth()->user()->business_id }}</div>
                        <div class="text-xs text-blue-200">Business ID</div>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-industry text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- System Overview Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['customers'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-blue-600">
                        <i class="fas fa-users mr-1"></i>
                        <span>Active clients</span>
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
                    <p class="text-sm font-medium text-gray-600 mb-1">Quotations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['quotations'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-file-alt mr-1"></i>
                        <span>This month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['invoices'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-purple-600">
                        <i class="fas fa-file-invoice mr-1"></i>
                        <span>Total billed</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>

        @if($subscriptionTier === 'full_erp')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Materials</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['materials'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>Active items</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
        @endif

        @if($subscriptionTier === 'full_erp')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Vendors</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['vendors'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-blue-600">
                        <i class="fas fa-handshake mr-1"></i>
                        <span>Partners</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-truck text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Purchase Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['purchase_orders'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-orange-600">
                        <i class="fas fa-clock mr-1"></i>
                        <span>This month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Machines</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['machines'] ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>Operational</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cogs text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
        @endif
    </div> --}}

    <!-- Sales Summary Dashboard -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Sales Summary</h3>
                <p class="text-sm text-gray-500 mt-1">Financial performance overview</p>
            </div>
            @if($reportsEnabled)
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View Reports <i class="fas fa-arrow-right ml-1"></i>
            </a>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-green-700 mb-1">₹{{ number_format($salesMetrics['total_revenue'] ?? 0, 0) }}</div>
                        <div class="text-sm font-medium text-green-600">Revenue (This Month)</div>
                    </div>
                    <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-orange-700 mb-1">₹{{ number_format($salesMetrics['outstanding_invoices'] ?? 0, 0) }}</div>
                        <div class="text-sm font-medium text-orange-600">Outstanding Invoices</div>
                    </div>
                    <div class="w-10 h-10 bg-orange-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-blue-700 mb-1">{{ $salesMetrics['avg_payment_days'] ?? 0 }}</div>
                        <div class="text-sm font-medium text-blue-600">Avg. Payment Days</div>
                    </div>
                    <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-bold text-purple-700 mb-1">Top Customers</div>
                        <div class="text-sm font-medium text-purple-600">By Revenue</div>
                    </div>
                    <div class="w-10 h-10 bg-purple-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Customers & Items -->
        <div class="grid grid-cols-1 {{ $subscriptionTier !== 'billing_sales' ? 'md:grid-cols-2' : '' }} gap-6 mt-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Top 3 Customers</h4>
                @if(count($salesMetrics['top_customers'] ?? []) > 0)
                    <div class="space-y-2">
                        @foreach($salesMetrics['top_customers'] as $customer)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-900">{{ $customer->customer_name }}</span>
                            <span class="text-sm font-bold text-green-600">₹{{ number_format($customer->total_revenue, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No customer data available</p>
                @endif
            </div>
            
            @if($subscriptionTier !== 'billing_sales')
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Top 3 Items</h4>
                @if(count($salesMetrics['top_items'] ?? []) > 0)
                    <div class="space-y-2">
                        @foreach($salesMetrics['top_items'] as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-900">{{ Str::limit($item->description, 25) }}</span>
                            <span class="text-sm font-bold text-blue-600">₹{{ number_format($item->total_revenue, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No item data available</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                <p class="text-sm text-gray-500 mt-1">Get started with common tasks</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-bolt text-yellow-500"></i>
                <span>Fast Setup</span>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-{{ $subscriptionTier === 'billing_sales' ? '2' : '4' }} gap-4">
            <a href="{{ route('quotations.create') }}" class="group relative bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 hover:from-blue-100 hover:to-blue-200 hover:border-blue-300 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-blue-200 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-300 transition-colors">
                        <i class="fas fa-file-alt text-blue-700"></i>
                    </div>
                    <span class="text-sm font-semibold text-blue-800">Create Quote</span>
                    <span class="text-xs text-blue-600 mt-1">New quotation</span>
                </div>
            </a>
            <a href="{{ route('customers.create') }}" class="group relative bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 hover:from-green-100 hover:to-green-200 hover:border-green-300 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center mb-3 group-hover:bg-green-300 transition-colors">
                        <i class="fas fa-user-plus text-green-700"></i>
                    </div>
                    <span class="text-sm font-semibold text-green-800">Add Customer</span>
                    <span class="text-xs text-green-600 mt-1">New client</span>
                </div>
            </a>
            
            @if($subscriptionTier === 'full_erp')
            <a href="{{ route('materials.create') }}" class="group relative bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-4 hover:from-yellow-100 hover:to-yellow-200 hover:border-yellow-300 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-yellow-200 rounded-xl flex items-center justify-center mb-3 group-hover:bg-yellow-300 transition-colors">
                        <i class="fas fa-plus text-yellow-700"></i>
                    </div>
                    <span class="text-sm font-semibold text-yellow-800">Add Material</span>
                    <span class="text-xs text-yellow-600 mt-1">Raw materials & items</span>
                </div>
            </a>
            <a href="{{ route('business.profile') }}" class="group relative bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 hover:from-purple-100 hover:to-purple-200 hover:border-purple-300 transition-all duration-200 hover:shadow-md">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-purple-200 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-300 transition-colors">
                        <i class="fas fa-building text-purple-700"></i>
                    </div>
                    <span class="text-sm font-semibold text-purple-800">Business Profile</span>
                    <span class="text-xs text-purple-600 mt-1">Manage business details</span>
                </div>
            </a>
            @endif
        </div>
    </div>

    <!-- Setup Progress & Business Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Setup Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Setup Progress</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $subscriptionTier === 'billing_sales' ? 'Complete your billing setup' : 'Complete your workshop setup' }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                    <span class="text-xs font-medium text-blue-600">In Progress</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">Account Created</p>
                        <p class="text-xs text-gray-500 mt-1">Your Monitorbizz account is ready!</p>
                        <div class="w-full bg-green-100 rounded-full h-1.5 mt-2">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 {{ \App\Models\Material::where('business_id', auth()->user()->business_id)->count() > 0 ? 'bg-green-100' : 'bg-blue-100' }} rounded-xl flex items-center justify-center">
                            @if(\App\Models\Material::where('business_id', auth()->user()->business_id)->count() > 0)
                                <i class="fas fa-check text-green-600"></i>
                            @else
                                <span class="text-blue-600 text-sm font-bold">2</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">Add Commodity</p>
                        <p class="text-xs text-gray-500 mt-1">Set up your products and materials</p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ \App\Models\Material::where('business_id', auth()->user()->business_id)->count() > 0 ? '100' : '60' }}%"></div>
                        </div>
                        @if(\App\Models\Material::where('business_id', auth()->user()->business_id)->count() == 0)
                            <a href="{{ route('materials.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                Add Commodity <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 {{ \App\Models\Customer::where('business_id', auth()->user()->business_id)->count() > 0 ? 'bg-green-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center">
                            @if(\App\Models\Customer::where('business_id', auth()->user()->business_id)->count() > 0)
                                <i class="fas fa-check text-green-600"></i>
                            @else
                                <span class="text-gray-500 text-sm font-bold">3</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">Add Customers</p>
                        <p class="text-xs text-gray-500 mt-1">Build your customer database</p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-gray-400 h-1.5 rounded-full" style="width: {{ \App\Models\Customer::where('business_id', auth()->user()->business_id)->count() > 0 ? '100' : '0' }}%"></div>
                        </div>
                        @if(\App\Models\Customer::where('business_id', auth()->user()->business_id)->count() == 0)
                            <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                Add Customers <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                            <span class="text-gray-500 text-sm font-bold">4</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">Create Quotations/Invoices</p>
                        <p class="text-xs text-gray-500 mt-1">Start billing your customers</p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-gray-400 h-1.5 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Business Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Business Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Your account information</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-green-600">Active</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Business ID</span>
                        <span class="text-sm font-bold text-gray-900">{{ auth()->user()->business_id }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Plan</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $planName === 'Free' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                            {{ $planName }} Plan
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Users Allowed</span>
                        <span class="text-sm font-bold text-gray-900">{{ $userCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Owner</span>
                        <span class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</span>
                    </div>
                </div>
                @if(($subscriptionPlan ?? 'free') === 'free')
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-blue-900">Upgrade Available</p>
                                <p class="text-xs text-blue-700">{{ $subscriptionTier === 'billing_sales' ? 'Unlock manufacturing features' : 'Unlock advanced features and analytics' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('pricing') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            Upgrade Now
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Manufacturing Focus Message -->
    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border border-blue-200 rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-700 rounded-xl flex items-center justify-center">
                    <i class="fas fa-industry text-white text-2xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-xl font-bold text-gray-900 mb-2">Built for Makers, Not Offices</h4>
                <p class="text-gray-700 mb-3">Track every job, machine hour, and material gram. No more guessing where your costs go.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-check mr-1"></i> Real-time tracking
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-chart-line mr-1"></i> Cost analysis
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-cogs mr-1"></i> Machine monitoring
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function dismissOnboarding() {
    document.getElementById('onboardingWidget').style.display = 'none';
    fetch('{{ route("dashboard.dismiss-onboarding") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    });
}
</script>
@endsection