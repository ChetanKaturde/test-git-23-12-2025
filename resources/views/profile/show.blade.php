@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->getTeamDisplayName() }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </a>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->phone ? '+91 ' . $user->phone : 'Not provided' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Team</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->getTeamDisplayName() }}</p>
            </div>
        </div>
    </div>

    @if($user->isAdmin())
    <!-- Company Address -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Address</h3>
        <div class="space-y-2">
            @if($user->company_address)
                <p class="text-sm text-gray-900">{{ $user->company_address }}</p>
                <p class="text-sm text-gray-900">
                    {{ $user->company_city }}{{ $user->company_state ? ', ' . $user->company_state : '' }}{{ $user->company_pincode ? ' - ' . $user->company_pincode : '' }}
                </p>
                <p class="text-sm text-gray-900">{{ $user->company_country }}</p>
            @else
                <p class="text-sm text-gray-500 italic">Company address not provided</p>
            @endif
        </div>
    </div>

    <!-- Warehouse Address -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Warehouse Address</h3>
        <div class="space-y-2">
            @if($user->warehouse_same_as_company)
                <p class="text-sm text-gray-500 italic">Same as company address</p>
            @elseif($user->warehouse_address)
                <p class="text-sm text-gray-900">{{ $user->warehouse_address }}</p>
                <p class="text-sm text-gray-900">
                    {{ $user->warehouse_city }}{{ $user->warehouse_state ? ', ' . $user->warehouse_state : '' }}{{ $user->warehouse_pincode ? ' - ' . $user->warehouse_pincode : '' }}
                </p>
                <p class="text-sm text-gray-900">{{ $user->warehouse_country }}</p>
            @else
                <p class="text-sm text-gray-500 italic">Warehouse address not provided</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Profile History -->
    @if($history->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Changes</h3>
        <div class="space-y-3">
            @foreach($history as $change)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <i class="fas fa-history text-gray-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">
                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $change->field_name)) }}</span> 
                        changed from 
                        <span class="text-red-600">"{{ $change->old_value ?: 'empty' }}"</span> 
                        to 
                        <span class="text-green-600">"{{ $change->new_value ?: 'empty' }}"</span>
                    </p>
                    <p class="text-xs text-gray-500">
                        by {{ $change->changedBy->name }} • {{ $change->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection