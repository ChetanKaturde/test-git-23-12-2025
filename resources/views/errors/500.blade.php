@extends('layouts.app')
@section('title', 'Server Error')
@section('page-title', 'Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-24 w-24 text-red-500 mb-4">
                <i class="fas fa-exclamation-triangle text-6xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Server Error</h2>
            <p class="mt-2 text-sm text-gray-600">
                Something went wrong on our end. We're working to fix it.
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bug text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            What happened?
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>An unexpected error occurred. Our team has been notified and is investigating.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-home mr-2"></i>Go to Dashboard
                </a>
                <button onclick="location.reload()" 
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    <i class="fas fa-redo mr-2"></i>Try Again
                </button>
            </div>
        </div>
    </div>
</div>
@endsection