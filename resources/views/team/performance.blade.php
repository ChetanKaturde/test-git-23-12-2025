@extends('layouts.app')
@section('title', 'Team Performance')
@section('page-title', 'Team Performance Dashboard')

@section('content')
<div class="p-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-{{ $subscriptionTier === 'full_erp' ? '4' : '1' }} gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Team Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teamStats['active_members'] }}</p>
                </div>
            </div>
        </div>
        
        @if($subscriptionTier === 'full_erp')
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-tasks text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Work Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teamStats['completed_work_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Tasks</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teamStats['pending_work_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-cogs text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Machines</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teamStats['active_machines'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Team Members Performance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Team Members</h3>
            <div class="space-y-4">
                @forelse($teamMembers as $member)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-blue-600">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->getTeamDisplayName() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($subscriptionTier === 'full_erp')
                        <p class="text-sm font-medium text-gray-900">{{ $member->completed_work_orders ?? 0 }} tasks</p>
                        @endif
                        <p class="text-xs text-gray-500">
                            @if($member->is_active)
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-600">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No team members found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            @if($activity->event === 'work_order_completed')
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            @elseif($activity->event === 'work_order_started')
                                <i class="fas fa-play text-blue-600 text-xs"></i>
                            @else
                                <i class="fas fa-info text-gray-600 text-xs"></i>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent activities</p>
                @endforelse
            </div>
        </div>
    </div>

    @if($subscriptionTier === 'full_erp' && $workOrders->count() > 0)
    <!-- Work Orders Status -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Work Orders Overview</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Machine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($workOrders as $workOrder)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <a href="{{ route('work-orders.show', $workOrder) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $workOrder->wo_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $workOrder->assignedTo->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $workOrder->machine->name ?? 'N/A' }}
                        </td>
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
                                <span class="text-green-600">Completed</span>
                            @elseif($workOrder->started_at)
                                <span class="text-blue-600">In Progress</span>
                            @else
                                <span class="text-gray-500">Not Started</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection