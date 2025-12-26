@extends('layouts.app')

@section('title', 'Edit Machine')
@section('page-title', 'Edit Machine')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('machines.update', $machine) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Machine Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $machine->name) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Machine Type</label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="cnc" {{ old('type', $machine->type) == 'cnc' ? 'selected' : '' }}>CNC Machine</option>
                        <option value="lathe" {{ old('type', $machine->type) == 'lathe' ? 'selected' : '' }}>Lathe</option>
                        <option value="welding" {{ old('type', $machine->type) == 'welding' ? 'selected' : '' }}>Welding Equipment</option>
                        <option value="cutting" {{ old('type', $machine->type) == 'cutting' ? 'selected' : '' }}>Cutting Machine</option>
                        <option value="drilling" {{ old('type', $machine->type) == 'drilling' ? 'selected' : '' }}>Drilling Machine</option>
                        <option value="milling" {{ old('type', $machine->type) == 'milling' ? 'selected' : '' }}>Milling Machine</option>
                        <option value="other" {{ old('type', $machine->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="available" {{ old('status', $machine->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="in_use" {{ old('status', $machine->status) == 'in_use' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ old('status', $machine->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="broken" {{ old('status', $machine->status) == 'broken' ? 'selected' : '' }}>Broken</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $machine->location) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $machine->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('machines.show', $machine) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Update Machine
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection