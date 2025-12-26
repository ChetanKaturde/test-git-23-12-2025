@extends('layouts.app')

@section('title', 'User Activities')
@section('page-title', 'User Activities')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Activity Log for {{ $user->name }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $user->getRoleDisplayName() }} • {{ $user->email }}</p>
            </div>
            <a href="{{ route('team.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Team
            </a>
        </div>
    </div>

    <!-- Activities -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900">Recent Activities</h4>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($activities as $activity)
                <div class="px-6 py-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-activity text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $activity->description ?? 'Activity performed' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $activity->created_at->format('M j, Y g:i A') }} • {{ $activity->created_at->diffForHumans() }}
                            </div>
                            @if(isset($activity->properties) && count($activity->properties))
                                <div class="mt-2 text-xs text-gray-600 bg-gray-50 p-2 rounded">
                                    <strong>Details:</strong>
                                    @foreach($activity->properties as $key => $value)
                                        <br>{{ ucfirst($key) }}: {{ is_array($value) ? json_encode($value) : $value }}
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-xs text-gray-400">
                            {{ $activity->log_name ?? 'system' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center">
                    <i class="fas fa-history text-gray-400 text-3xl mb-4"></i>
                    <p class="text-gray-500">No activities recorded for this user yet.</p>
                </div>
            @endforelse
        </div>
        
        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection