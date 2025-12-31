@extends('layouts.app')
@section('title', 'Commodities')
@section('page-title', 'Commodities')

@section('content')
<div class="p-6">
    <!-- Onboarding Message for New Users -->
    @if($materials->count() == 0)
    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border border-blue-200 rounded-xl p-6 mb-6">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Welcome to Commodities Management!</h3>
                <p class="text-gray-700 mb-4">Commodities are the building blocks of your business - raw materials, finished goods, or services you offer. Here's how to get started:</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-sm">1</span>
                            </div>
                            <h4 class="font-semibold text-gray-900">Add Materials</h4>
                        </div>
                        <p class="text-sm text-gray-600">Start with raw materials like steel, wood, or components you purchase.</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-green-600 font-bold text-sm">2</span>
                            </div>
                            <h4 class="font-semibold text-gray-900">Set Pricing</h4>
                        </div>
                        <p class="text-sm text-gray-600">Define unit costs and selling prices for accurate profit calculations.</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <span class="text-purple-600 font-bold text-sm">3</span>
                            </div>
                            <h4 class="font-semibold text-gray-900">Track Stock</h4>
                        </div>
                        <p class="text-sm text-gray-600">Monitor inventory levels and set reorder points to avoid stockouts.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if(auth()->user()->canCreateInModule('materials'))
                        <a href="{{ route('materials.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors"
                           data-tooltip="Create your first commodity to start tracking inventory">
                            <i class="fas fa-plus"></i>
                            <span>Add First Commodity</span>
                        </a>
                    @endif
                    <button onclick="loadSampleData()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors"
                            data-tooltip="Load sample data to explore features quickly">
                        <i class="fas fa-download"></i>
                        <span>Load Sample Data</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" data-hint="dashboard-stats">
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow" 
             data-tooltip="Total number of commodities in your system">
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
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow" 
             data-tooltip="Commodities currently available for use">
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
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow" 
             data-tooltip="Items below reorder point - need restocking soon">
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
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow" 
             data-tooltip="Number of suppliers you work with">
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
            <div class="flex items-center space-x-3">
                <!-- Search and Filter -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition inline-flex items-center"
                            data-tooltip="Filter commodities by type, status, or stock level">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Items</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Raw Materials</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Finished Goods</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Services</a>
                            <div class="border-t border-gray-100"></div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Low Stock</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Out of Stock</a>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->canCreateInModule('materials'))
                    <a href="{{ route('materials.create') }}" 
                       class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition inline-flex items-center"
                       data-tooltip="Add a new commodity to your inventory">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Commodity
                    </a>
                @endif
            </div>
        </div>

        @if($materials->count() > 0)
            <!-- Quick Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-info text-blue-600 text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Pro Tips</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Use consistent SKU naming for better organization (e.g., MS-001 for Mild Steel)</li>
                            <li>• Set reorder points to avoid stockouts - typically 20-30% of average monthly usage</li>
                            <li>• HSN codes are required for GST compliance - use 8-digit codes for accuracy</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Commodity</span>
                                    <i class="fas fa-info-circle text-gray-400 cursor-help" 
                                       data-tooltip="Commodity name, type, and unique identifier (SKU)"></i>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Supplier</span>
                                    <i class="fas fa-info-circle text-gray-400 cursor-help" 
                                       data-tooltip="Primary supplier for this commodity"></i>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Stock</span>
                                    <i class="fas fa-info-circle text-gray-400 cursor-help" 
                                       data-tooltip="Current available quantity in inventory"></i>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Reorder Point</span>
                                    <i class="fas fa-info-circle text-gray-400 cursor-help" 
                                       data-tooltip="Minimum stock level before reordering - prevents stockouts"></i>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Unit Cost</span>
                                    <i class="fas fa-info-circle text-gray-400 cursor-help" 
                                       data-tooltip="Cost per unit - used for inventory valuation and costing"></i>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($materials as $material)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                        @if($material->item_type === 'service')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                                  data-tooltip="Service item - no physical inventory tracking">
                                                💼 Service
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                                  data-tooltip="Physical good - tracked in inventory">
                                                📦 Good
                                            </span>
                                        @endif
                                    </div>
                                    @if($material->sku && $material->item_type !== 'service')
                                        <div class="text-sm text-gray-500" data-tooltip="Stock Keeping Unit - unique identifier">SKU: {{ $material->sku }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Primary Supplier</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($material->item_type === 'service')
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Service</span>
                                    <div class="text-sm text-gray-500 mt-1">N/A</div>
                                @else
                                    @php
                                        $currentStock = $material->getCurrentStock();
                                        $isLowStock = $currentStock <= 50; // Assuming 50 as reorder point
                                    @endphp
                                    @if($currentStock > 0)
                                        <span class="px-2 py-1 text-xs font-medium {{ $isLowStock ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} rounded-full"
                                              data-tooltip="{{ $isLowStock ? 'Stock is below reorder point' : 'Stock level is healthy' }}">
                                            {{ $isLowStock ? 'Low Stock' : 'In Stock' }}
                                        </span>
                                        <div class="text-sm text-gray-500 mt-1">{{ number_format($currentStock, 2) }} {{ $material->unit }}</div>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full"
                                              data-tooltip="Item is out of stock - immediate reorder needed">
                                            Out of Stock
                                        </span>
                                        <div class="text-sm text-gray-500 mt-1">0 {{ $material->unit }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">50 {{ $material->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($material->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}"
                                      data-tooltip="{{ $material->is_active ? 'Available for use in orders' : 'Disabled - not available for new orders' }}">
                                    {{ $material->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('materials.show', $material) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors"
                                   data-tooltip="View commodity details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->canEditInModule('materials'))
                                    <a href="{{ route('materials.edit', $material) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                       data-tooltip="Edit commodity information">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if(auth()->user()->canDeleteInModule('materials'))
                                    <form action="{{ route('materials.destroy', $material) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition-colors" 
                                                onclick="return confirm('Are you sure you want to delete this commodity? This action cannot be undone.')"
                                                data-tooltip="Delete commodity permanently">
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
                @if(auth()->user()->canCreateInModule('materials'))
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

<script>
function loadSampleData() {
    if (confirm('This will add sample commodities to help you explore the features. Continue?')) {
        fetch('{{ route("materials.load-sample") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Sample data loaded successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Failed to load sample data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while loading sample data', 'error');
        });
    }
}
</script>
@endsection
