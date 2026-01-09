@extends('layouts.app')

@section('title', 'Edit Team')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Team</h1>

            <form method="POST" action="{{ route('teams.update', $team) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="team_name" class="block text-sm font-medium text-gray-700 mb-2">Team Name</label>
                    <input type="text" name="team_name" id="team_name" value="{{ old('team_name', $team->team_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('team_name') border-red-500 @enderror"
                           required>
                    @error('team_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('teams.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection