@extends('layouts.app')
@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <span class="text-xl font-bold text-blue-700">{{ substr($customer->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
                        <nav class="text-sm text-gray-500 flex items-center space-x-2 mt-1">
                            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                                <i class="fas fa-home mr-1"></i>Home
                            </a> 
                            <i class="fas fa-chevron-right text-xs"></i>
                            <a href="{{ route('customers.index') }}" class="hover:text-blue-600 transition-colors">Customers</a>
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span class="font-medium text-gray-700">{{ $customer->name }}</span>
                        </nav>
                        <p class="text-gray-600 mt-1">Customer information and business activity</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    @if($customer->is_active)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <div class="w-2 h-2 mr-2 bg-green-400 rounded-full animate-pulse"></div>
                            Active Customer
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                            <div class="w-2 h-2 mr-2 bg-gray-400 rounded-full"></div>
                            Inactive
                        </span>
                    @endif
                    
                    @if($customer->gstin)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                            <i class="fas fa-certificate mr-2"></i>
                            GST Registered
                        </span>
                    @endif
                    
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        <i class="fas fa-user mr-2"></i>
                        {{ ucfirst($customer->customer_type ?? 'business') }}
                    </span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('customers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Customers
                </a>
                <a href="{{ route('customers.edit', $customer) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Customer
                </a>
                @if(auth()->user()->canViewModule('quotations'))
                    <a href="{{ route('quotations.create', ['customer' => $customer->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Create Quote
                    </a>
                @endif
                @if(auth()->user()->canViewModule('invoices') && auth()->user()->isAdmin())
                    <a href="{{ route('invoices.create', ['customer' => $customer->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Create Invoice
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @if(auth()->user()->business->subscription_tier === 'full_erp')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Work Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $workOrders->count() }}</p>
                    <div class="flex items-center mt-2 text-xs text-blue-600">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        <span>Total projects</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $invoices->count() }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-file-invoice mr-1"></i>
                        <span>Billing history</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($invoices->sum('total_amount'), 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-purple-600">
                        <i class="fas fa-rupee-sign mr-1"></i>
                        <span>Lifetime value</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Outstanding</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($invoices->where('status', '!=', 'paid')->sum('total_amount'), 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-orange-600">
                        <i class="fas fa-clock mr-1"></i>
                        <span>Pending payment</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Financial Summary</h3>
                <p class="text-sm text-gray-500 mt-1">Customer payment history and aging</p>
            </div>
            <i class="fas fa-chart-bar text-gray-400 text-xl"></i>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                <div class="text-2xl font-bold text-green-700 mb-1">₹{{ number_format($invoices->where('status', 'paid')->sum('total_amount'), 0) }}</div>
                <div class="text-sm font-medium text-green-600">Total Paid</div>
            </div>
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                <div class="text-2xl font-bold text-orange-700 mb-1">₹{{ number_format($invoices->where('status', '!=', 'paid')->sum('total_amount'), 0) }}</div>
                <div class="text-sm font-medium text-orange-600">Outstanding</div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                <div class="text-2xl font-bold text-blue-700 mb-1">
                    @php
                        $paidInvoices = $invoices->where('status', 'paid')->whereNotNull('paid_date')->whereNotNull('issue_date');
                        $avgDays = 0;
                        if ($paidInvoices->count() > 0) {
                            $totalDays = $paidInvoices->sum(function($invoice) {
                                return $invoice->paid_date ? $invoice->paid_date->diffInDays($invoice->issue_date) : 0;
                            });
                            $avgDays = round($totalDays / $paidInvoices->count());
                        }
                    @endphp
                    {{ $avgDays }}
                </div>
                <div class="text-sm font-medium text-blue-600">Avg. Payment Days</div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                <div class="text-2xl font-bold text-purple-700 mb-1">{{ $invoices->where('issue_date', '>=', now()->subDays(30))->count() }}</div>
                <div class="text-sm font-medium text-purple-600">Recent Invoices</div>
            </div>
        </div>
        
        @if($invoices->where('status', '!=', 'paid')->count() > 0)
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Outstanding Invoices</h4>
            <div class="space-y-2">
                @foreach($invoices->where('status', '!=', 'paid')->take(5) as $invoice)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</span>
                            @if($invoice->due_date && $invoice->due_date->isPast())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $invoice->due_date->diffInDays(now()) }} days overdue
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Due: {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'No due date' }}
                        </div>
                    </div>
                    <span class="text-sm font-bold text-orange-600">₹{{ number_format($invoice->total_amount, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    <i class="fas fa-user-circle text-gray-400 text-xl"></i>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Contact Details -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-address-book mr-2 text-blue-600"></i>
                            Contact Details
                        </h4>
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-600 flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                                    Phone
                                </dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $customer->phone }}</dd>
                            </div>
                            @if($customer->email)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                                        Email
                                    </dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $customer->email }}</dd>
                                </div>
                            @endif
                            @if($customer->contact_person)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-user text-gray-400 mr-2 w-4"></i>
                                        Contact Person
                                    </dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $customer->contact_person }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                    
                    <!-- Business Details -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-building mr-2 text-green-600"></i>
                            Business Details
                        </h4>
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-600">Customer Type</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($customer->customer_type ?? 'business') }}</dd>
                            </div>
                            @if($customer->gstin)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600">GSTIN</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $customer->gstin }}</dd>
                                </div>
                            @endif
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-600">Payment Terms</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $customer->payment_terms ?? 'due_on_receipt')) }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-sm font-medium text-gray-600">Status</dt>
                                <dd>
                                    @if($customer->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <div class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></div>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            <div class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></div>
                                            Inactive
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                @if($customer->address || $customer->city || $customer->state || $customer->pincode)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-red-600"></i>
                            Address Information
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm text-gray-700 leading-relaxed">
                                @if($customer->address){{ $customer->address }}@endif
                                @if($customer->city){{ $customer->address ? ', ' : '' }}{{ $customer->city }}@endif
                                @if($customer->state){{ ($customer->address || $customer->city) ? ', ' : '' }}{{ $customer->state }}@endif
                                @if($customer->pincode){{ ($customer->address || $customer->city || $customer->state) ? ' - ' : '' }}{{ $customer->pincode }}@endif
                            </p>
                        </div>
                    </div>
                @endif
                
                <!-- Contact Persons -->
                @if($customer->contacts->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                Contact Persons
                            </h4>
                            <button onclick="openAddContactModal()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-plus mr-1"></i>Add Contact
                            </button>
                        </div>
                        <div class="space-y-3">
                            @foreach($customer->contacts as $contact)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-900">{{ $contact->name }}</span>
                                            @if($contact->is_primary)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Primary
                                                </span>
                                            @endif
                                        </div>
                                        @if($contact->role)
                                            <div class="text-xs text-gray-500 mt-1">{{ $contact->role }}</div>
                                        @endif
                                        <div class="text-xs text-gray-600 mt-1">
                                            @if($contact->phone)
                                                <span class="mr-3"><i class="fas fa-phone mr-1"></i>{{ $contact->phone }}</span>
                                            @endif
                                            @if($contact->email)
                                                <span><i class="fas fa-envelope mr-1"></i>{{ $contact->email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editContact({{ $contact->id }})" class="text-gray-400 hover:text-blue-600">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteContact({{ $contact->id }})" class="text-gray-400 hover:text-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                Contact Persons
                            </h4>
                            <button onclick="openAddContactModal()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-plus mr-1"></i>Add Contact
                            </button>
                        </div>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-plus text-3xl mb-3"></i>
                            <p class="text-sm">No contact persons added yet</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Activity Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    <i class="fas fa-bolt text-yellow-500"></i>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('quotations.create') }}?customer_id={{ $customer->id }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        Create Quotation
                    </a>
                    <a href="{{ route('invoices.create') }}?customer_id={{ $customer->id }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Create Invoice
                    </a>
                    @if(auth()->user()->business->subscription_tier === 'full_erp')
                    <a href="{{ route('work-orders.create') }}?customer_id={{ $customer->id }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        New Work Order
                    </a>
                    @endif
                </div>
            </div>
            
            @if($workOrders->count() > 0 && auth()->user()->business->subscription_tier === 'full_erp')
                <!-- Recent Work Orders -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Work Orders</h3>
                        <span class="text-sm text-gray-500">{{ $workOrders->count() }} total</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($workOrders->take(5) as $workOrder)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        {{ $workOrder->work_order_number ?? 'WO-' . $workOrder->id }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $workOrder->product_name ?? 'No product specified' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $workOrder->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $workOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($workOrder->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($workOrder->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if($workOrders->count() > 5)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('work-orders.index') }}?customer={{ $customer->id }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all {{ $workOrders->count() }} work orders →
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            
            @if($invoices->count() > 0)
                <!-- Recent Invoices -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                        <span class="text-sm text-gray-500">{{ $invoices->count() }} total</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($invoices->take(5) as $invoice)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $invoice->invoice_number }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        ₹{{ number_format($invoice->total_amount, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'No date' }}
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if($invoices->count() > 5)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('invoices.index') }}?customer={{ $customer->id }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all {{ $invoices->count() }} invoices →
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection