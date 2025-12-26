@extends('layouts.app')

@section('page-title', 'Activity Log')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Activity Log</h1>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $activity->created_at->format('M d, Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $activity->created_at->format('h:i A') }}</span>
                                    <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($activity->causer)
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-600">
                                                {{ substr($activity->causer->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $activity->causer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $activity->causer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($activity->description == 'created') bg-green-100 text-green-800
                                    @elseif($activity->description == 'updated') bg-blue-100 text-blue-800
                                    @elseif($activity->description == 'deleted') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($activity->description) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($activity->subject)
                                    <div>
                                        <div class="font-medium">{{ class_basename($activity->subject_type) }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $activity->subject_id }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    @if($activity->subject && method_exists($activity->subject, 'name'))
                                        {{ $activity->description }} {{ strtolower(class_basename($activity->subject_type)) }}: 
                                        <span class="font-medium">{{ $activity->subject->name }}</span>
                                    @elseif($activity->properties && $activity->properties->has('attributes'))
                                        {{ $activity->description }} {{ strtolower(class_basename($activity->subject_type)) }}
                                    @else
                                        {{ ucfirst($activity->description) }} {{ strtolower(class_basename($activity->subject_type ?? 'item')) }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">No activity logs found</p>
                                    <p class="text-sm">Activity will appear here as users interact with the system</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection