@extends('layouts.app')
@section('title', 'Quotations')
@section('page-title', 'Quotations')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Quotations</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a> 
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">Quotations</span>
                </nav>
                <p class="text-gray-600 mt-1">Manage customer quotes and proposals</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex items-center space-x-2 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span>{{ $quotations->count() }} Total</span>
                </div>
                @if(auth()->user()->hasPermission('create_quotation'))
                    <a href="{{ route('quotations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-plus w-5 h-5 mr-2"></i>
                        New Quotation
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($quotations->count() > 0)
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Quotations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $quotations->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-blue-600">
                            <i class="fas fa-file-alt mr-1"></i>
                            <span>All time</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Draft</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $quotations->where('status', 'draft')->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-yellow-600">
                            <i class="fas fa-edit mr-1"></i>
                            <span>In progress</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-yellow-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Sent</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $quotations->filter(function($q) { return $q->isSent(); })->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-blue-600">
                            <i class="fas fa-paper-plane mr-1"></i>
                            <span>Awaiting response</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-paper-plane text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Converted</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $quotations->where('status', 'converted')->count() }}</p>
                        <div class="flex items-center mt-2 text-xs text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span>To invoices</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quotations Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Quotations</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Filter:</label>
                            <select id="statusFilter" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="accepted">Accepted</option>
                                <option value="converted">Converted</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-sort mr-1"></i>
                            <span>Sorted by date</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quotation</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Valid Until</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quotations as $quotation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-alt text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $quotation->number }}</div>
                                            <div class="text-xs text-gray-500">{{ $quotation->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600">{{ substr($quotation->customer->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $quotation->customer->name }}</div>
                                            @if($quotation->customer->phone)
                                                <div class="text-xs text-gray-500">{{ $quotation->customer->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $quotation->status === 'converted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 
                                               ($quotation->status === 'accepted' ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-amber-100 text-amber-800 border border-amber-300') }}">
                                            @if($quotation->status === 'converted')
                                                <i class="fas fa-check-circle mr-1"></i>
                                            @elseif($quotation->status === 'accepted')
                                                <i class="fas fa-thumbs-up mr-1"></i>
                                            @else
                                                <i class="fas fa-edit mr-1"></i>
                                            @endif
                                            {{ ucfirst($quotation->status) }}
                                        </span>
                                        @if($quotation->isSent())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                                <i class="fas fa-paper-plane mr-1"></i>
                                                Sent
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $quotation->valid_until->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($quotation->valid_until->isPast())
                                            <span class="text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>Expired</span>
                                        @else
                                            {{ $quotation->valid_until->diffForHumans() }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">₹{{ number_format($quotation->total, 2) }}</div>
                                    <div class="text-xs text-gray-500">{{ $quotation->items->count() }} items</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('quotations.show', $quotation) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                                            <i class="fas fa-eye w-4 h-4 mr-1"></i>
                                            View
                                        </a>
                                        @if($quotation->status !== 'converted')
                                            <form action="{{ route('quotations.convert', $quotation) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md hover:bg-emerald-200 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-300 transition-colors" 
                                                        onclick="return confirm('Convert to invoice?')">
                                                    <i class="fas fa-exchange-alt w-4 h-4 mr-1"></i>
                                                    Convert
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
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="text-center py-12 px-6">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">No Quotations Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Start creating professional quotations for your customers. Track proposals and convert them to invoices seamlessly.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if(auth()->user()->hasPermission('create_quotation'))
                        <a href="{{ route('quotations.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="fas fa-plus w-5 h-5 mr-2"></i>
                            Create Quote
                        </a>
                    @endif
                    <a href="{{ route('customers.index') }}" 
                       class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                        <i class="fas fa-users w-5 h-5 mr-2"></i>
                        Add Customer
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const statusElement = row.querySelector('.inline-flex');
        if (!statusElement) return;
        
        const statusText = statusElement.textContent.trim().toLowerCase();
        const isVisible = selectedStatus === '' || statusText.includes(selectedStatus);
        row.style.display = isVisible ? '' : 'none';
    });
});
</script>
@endsection