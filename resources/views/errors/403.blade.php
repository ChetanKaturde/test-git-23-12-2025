@extends('layouts.app')
@section('title', 'Access Denied')
@section('page-title', 'Access Denied')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-24 w-24 text-red-500 mb-4">
                <i class="fas fa-lock text-6xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Access Denied</h2>
            <p class="mt-2 text-sm text-gray-600">
                You don't have permission to access this resource.
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Need Access?
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Contact your administrator to request access to this module.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-home mr-2"></i>Go to Dashboard
                </a>
                <button onclick="history.back()" 
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Go Back
                </button>
            </div>
        </div>
    </div>
</div>
@endsection