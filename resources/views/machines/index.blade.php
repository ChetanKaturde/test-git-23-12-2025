@extends('layouts.app')

@section('title', 'Machines')
@section('page-title', 'Machines')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Machines</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Home</a> 
                    > <span class="font-medium">Machines</span>
                </nav>
            </div>
            <div>
                @if(auth()->user()->canCreateInModule('machines'))
                    <a href="{{ route('machines.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Machine
                    </a>
                @endif
            </div>
        </div>

        @if($machines->count() > 0)
            <!-- Status Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm text-green-800 font-medium">Available</p>
                            <p class="text-lg font-bold text-green-900">{{ $machines->where('status', 'available')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm text-blue-800 font-medium">In Use</p>
                            <p class="text-lg font-bold text-blue-900">{{ $machines->where('status', 'in_use')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Maintenance</p>
                            <p class="text-lg font-bold text-yellow-900">{{ $machines->where('status', 'maintenance')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm text-red-800 font-medium">Broken</p>
                            <p class="text-lg font-bold text-red-900">{{ $machines->where('status', 'broken')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($machines as $machine)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            @if($machine->type === 'cnc')
                                                <i class="fas fa-microchip text-blue-600"></i>
                                            @elseif($machine->type === 'lathe')
                                                <i class="fas fa-circle-notch text-green-600"></i>
                                            @elseif($machine->type === 'welding')
                                                <i class="fas fa-fire text-orange-600"></i>
                                            @else
                                                <i class="fas fa-cog text-gray-600"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                        <div class="text-sm text-gray-500">Code: {{ $machine->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">{{ $machine->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $machine->location ?? 'Not set' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($machine->status === 'available') bg-green-100 text-green-800
                                    @elseif($machine->status === 'in_use') bg-blue-100 text-blue-800
                                    @elseif($machine->status === 'maintenance') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $machine->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('machines.show', $machine) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->canEditInModule('machines'))
                                    <a href="{{ route('machines.edit', $machine) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if(auth()->user()->role === 'operator' || auth()->user()->isAdmin())
                                    <div class="inline-block relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-green-600 hover:text-green-900" title="Update Status">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                            @foreach(['available', 'in_use', 'maintenance', 'broken'] as $status)
                                                @if($status !== $machine->status)
                                                    <form method="POST" action="{{ route('machines.update-status', $machine) }}" class="block">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <span class="w-2 h-2 rounded-full inline-block mr-2 
                                                                @if($status === 'available') bg-green-500
                                                                @elseif($status === 'in_use') bg-blue-500
                                                                @elseif($status === 'maintenance') bg-yellow-500
                                                                @else bg-red-500 @endif"></span>
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if(auth()->user()->canDeleteInModule('machines'))
                                    <form action="{{ route('machines.destroy', $machine) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
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
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                    <i class="fas fa-cogs text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No machines yet</h3>
                <p class="text-gray-500 mb-6">Get started by adding your first machine to the workshop.</p>
                <a href="{{ route('machines.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add First Machine</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection