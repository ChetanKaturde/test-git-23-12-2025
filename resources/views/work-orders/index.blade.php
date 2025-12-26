@extends('layouts.app')

@section('title', 'Work Orders')
@section('page-title', 'Work Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="workOrdersApp()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                @if(auth()->user()->role === 'operator')
                    My Tasks
                @else
                    Work Orders
                @endif
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a> 
                > <span class="font-medium">
                    @if(auth()->user()->role === 'operator')
                        My Tasks
                    @else
                        Work Orders
                    @endif
                </span>
            </nav>
        </div>
        <div>
            @if(auth()->user()->canCreateInModule('work_orders'))
                <a href="{{ route('work-orders.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Work Order
                </a>
            @endif
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-lg font-semibold text-gray-900" x-text="stats.pending">{{ $workOrders->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-play text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">In Progress</p>
                    <p class="text-lg font-semibold text-gray-900" x-text="stats.inProgress">{{ $workOrders->where('status', 'in_progress')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-lg font-semibold text-gray-900" x-text="stats.completed">{{ $workOrders->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <i class="fas fa-list text-gray-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total</p>
                    <p class="text-lg font-semibold text-gray-900" x-text="stats.total">{{ $workOrders->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WO Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($workOrders as $workOrder)
                <tr x-data="{ loading: false }">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $workOrder->wo_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full mr-2 
                                @if($workOrder->machine && $workOrder->machine->status === 'available') bg-green-400
                                @elseif($workOrder->machine && $workOrder->machine->status === 'in_use') bg-blue-400
                                @else bg-gray-400 @endif"></span>
                            {{ $workOrder->machine->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $workOrder->product_name }}
                        <div class="text-xs text-gray-400">Qty: {{ $workOrder->quantity }}</div>
                    </td>
                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($workOrder->assignedTo)
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    {{ $workOrder->assignedTo->name }}
                                </div>
                            @else
                                <form method="POST" action="{{ route('work-orders.assign', $workOrder) }}" class="flex items-center space-x-2">
                                    @csrf
                                    <select name="assigned_to" class="text-xs border-gray-300 rounded" onchange="this.form.submit()">
                                        <option value="">Assign to...</option>
                                        @foreach($operators as $operator)
                                            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($workOrder->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($workOrder->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($workOrder->status === 'completed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($workOrder->started_at && $workOrder->completed_at)
                            {{ $workOrder->started_at->diffInMinutes($workOrder->completed_at) }} min
                        @elseif($workOrder->started_at)
                            <span class="text-blue-600">{{ $workOrder->started_at->diffForHumans() }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('work-orders.show', $workOrder) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                        @if($workOrder->status === 'pending')
                            <form method="POST" action="{{ route('work-orders.start', $workOrder) }}" class="inline" 
                                  x-on:submit="loading = true">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 disabled:opacity-50" 
                                        x-bind:disabled="loading">
                                    <span x-show="!loading">Start</span>
                                    <span x-show="loading" class="flex items-center">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Starting...
                                    </span>
                                </button>
                            </form>
                        @elseif($workOrder->status === 'in_progress')
                            <form method="POST" action="{{ route('work-orders.complete', $workOrder) }}" class="inline"
                                  x-on:submit="loading = true">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-900 disabled:opacity-50"
                                        x-bind:disabled="loading">
                                    <span x-show="!loading">Complete</span>
                                    <span x-show="loading" class="flex items-center">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Completing...
                                    </span>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium mb-2">No work orders found</p>
                            <p class="text-sm mb-4">Get started by creating your first work order</p>
                            <a href="{{ route('work-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>Create Work Order
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function workOrdersApp() {
    return {
        stats: {
            pending: {{ $workOrders->where('status', 'pending')->count() }},
            inProgress: {{ $workOrders->where('status', 'in_progress')->count() }},
            completed: {{ $workOrders->where('status', 'completed')->count() }},
            total: {{ $workOrders->count() }}
        },
        
        init() {
            // Auto-refresh stats every 30 seconds
            setInterval(() => {
                this.refreshStats();
            }, 30000);
        },
        
        refreshStats() {
            // In a real app, this would fetch from an API endpoint
            console.log('Refreshing work order stats...');
        }
    }
}
</script>
@endsection