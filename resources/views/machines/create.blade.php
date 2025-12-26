@extends('layouts.app')

@section('title', 'Add Machine')
@section('page-title', 'Add Machine')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('machines.store') }}">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Machine Name</label>
                    <input type="text" name="name" id="name" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., CNC Machine 01">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Machine Type</label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="cnc">CNC Machine</option>
                        <option value="lathe">Lathe</option>
                        <option value="welding">Welding Equipment</option>
                        <option value="cutting">Cutting Machine</option>
                        <option value="drilling">Drilling Machine</option>
                        <option value="milling">Milling Machine</option>
                        <option value="other">Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model (Optional)</label>
                    <input type="text" name="model" id="model"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Haas VF-2">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="available">Available</option>
                        <option value="in_use">In Use</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="broken">Broken</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('machines.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Add Machine
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection