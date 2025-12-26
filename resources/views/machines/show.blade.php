@extends('layouts.app')

@section('title', 'Machine Details')
@section('page-title', 'Machine Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $machine->name }}</h3>
                <p class="text-sm text-gray-500">Code: {{ $machine->code }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('machines.edit', $machine) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Edit
                </a>
                <a href="{{ route('machines.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Machine Information</h4>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500">Type</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ ucfirst($machine->type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="text-sm font-medium">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($machine->status === 'available') bg-green-100 text-green-800
                                @elseif($machine->status === 'in_use') bg-blue-100 text-blue-800
                                @elseif($machine->status === 'maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $machine->status)) }}
                            </span>
                        </dd>
                    </div>
                    @if($machine->location)
                    <div>
                        <dt class="text-sm text-gray-500">Location</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $machine->location }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            @if($machine->description)
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Description</h4>
                <p class="text-sm text-gray-600">{{ $machine->description }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection