@extends('layouts.app')

@section('title', 'Work Order Details')
@section('page-title', 'Work Order #' . $workOrder->wo_number)

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Work Order {{ $workOrder->wo_number }}</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('work-orders.index') }}" class="hover:text-blue-600 transition-colors">Work Orders</a>
                    > <span class="font-medium">{{ $workOrder->wo_number }}</span>
                </nav>
                <div class="flex items-center mt-2 space-x-3">
                    <span class="text-lg font-medium text-gray-700">{{ $workOrder->product_name }}</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($workOrder->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($workOrder->status === 'in_progress') bg-blue-100 text-blue-800
                        @elseif($workOrder->status === 'completed') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                    </span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                @if($workOrder->status === 'pending')
                    <form method="POST" action="{{ route('work-orders.start', $workOrder) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                            <i class="fas fa-play mr-2"></i>
                            Start Work Order
                        </button>
                    </form>
                @elseif($workOrder->status === 'in_progress')
                    <form method="POST" action="{{ route('work-orders.complete', $workOrder) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Complete Work Order
                        </button>
                    </form>
                @endif
                <a href="{{ route('work-orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Work Order Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Work Order Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">WO Number</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $workOrder->wo_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Product Name</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $workOrder->product_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Quantity</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $workOrder->quantity }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Machine</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ $workOrder->machine->name ?? 'N/A' }}
                                @if($workOrder->machine)
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full
                                        @if($workOrder->machine->status === 'available') bg-green-100 text-green-800
                                        @elseif($workOrder->machine->status === 'in_use') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($workOrder->machine->status) }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Operator</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $workOrder->operator->name ?? 'Not assigned' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($workOrder->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $workOrder->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Timing Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Timing Details</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Started At</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ $workOrder->started_at ? $workOrder->started_at->format('M d, Y H:i') : 'Not started' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Completed At</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ $workOrder->completed_at ? $workOrder->completed_at->format('M d, Y H:i') : 'Not completed' }}
                            </dd>
                        </div>
                        @if($workOrder->started_at && $workOrder->completed_at)
                            <div>
                                <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Duration</dt>
                                <dd class="mt-1 text-xl font-bold text-blue-600">{{ $workOrder->duration }} min</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>



    <!-- Material Consumption Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Material Consumption</h3>
                @if($workOrder->status === 'in_progress' && (auth()->user()->role === 'operator' || auth()->user()->isAdmin()))
                    <button onclick="showAddMaterialForm()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add Material
                    </button>
                @endif
            </div>
            <div class="p-6">

            <!-- Add Material Form (Hidden by default) -->
            @if($workOrder->status === 'in_progress' && (auth()->user()->role === 'operator' || auth()->user()->isAdmin()))
                <div id="addMaterialForm" class="hidden bg-gray-50 p-4 rounded-lg mb-4">
                    <form method="POST" action="{{ route('work-orders.add-material', $workOrder) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                                <select name="material_id" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" required>
                                    <option value="">Select Material</option>
                                    @foreach(\App\Models\Material::where('business_id', auth()->user()->business_id)->where('is_active', true)->get() as $material)
                                        <option value="{{ $material->id }}">{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Planned Qty</label>
                                <input type="number" name="planned_quantity" step="0.01" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Actual Qty</label>
                                <input type="number" name="actual_quantity" step="0.01" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waste Qty</label>
                                <input type="number" name="waste_quantity" step="0.01" value="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Add
                                </button>
                                <button type="button" onclick="hideAddMaterialForm()" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @if($workOrder->materialConsumptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Planned</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waste</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waste %</th>
                                @if($workOrder->status === 'in_progress' && (auth()->user()->role === 'operator' || auth()->user()->isAdmin()))
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($workOrder->materialConsumptions as $consumption)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $consumption->material->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $consumption->planned_quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $consumption->actual_quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $consumption->waste_quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($consumption->waste_percentage, 2) }}%
                                </td>
                                @if($workOrder->status === 'in_progress' && (auth()->user()->role === 'operator' || auth()->user()->isAdmin()))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form method="POST" action="{{ route('work-orders.remove-material', [$workOrder, $consumption]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Remove this material consumption?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <i class="fas fa-boxes text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No materials recorded yet</h3>
                    @if($workOrder->status === 'in_progress' && (auth()->user()->role === 'operator' || auth()->user()->isAdmin()))
                        <p class="text-gray-500 mb-6">Click "Add Material" to record material consumption for this work order.</p>
                    @else
                        <p class="text-gray-500">Material consumption will be recorded during work order execution.</p>
                    @endif
                </div>
            @endif
            </div>
        </div>
    </div>

        <script>
        function showAddMaterialForm() {
            document.getElementById('addMaterialForm').classList.remove('hidden');
        }
        function hideAddMaterialForm() {
            document.getElementById('addMaterialForm').classList.add('hidden');
        }
        </script>
    </div>
</div>
@endsection