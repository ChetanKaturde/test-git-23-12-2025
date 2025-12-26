@extends('layouts.app')

@section('title', $feature . ' - Upgrade Required')
@section('page-title', $feature)

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>

            <!-- Title -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $feature }} is Premium</h2>
                <p class="text-sm text-gray-600 mb-6">{{ $description ?? 'This feature is available on paid plans.' }}</p>
            </div>

            <!-- Features List -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">What you get with paid plans:</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Complete activity tracking
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Advanced reporting features
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Manufacturing modules
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Priority support
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="window.open('https://monitorbizz.com/pricing', '_blank')" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Upgrade to Paid Plan
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                    Back to Dashboard
                </a>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Need help? 
                    <a href="mailto:support@monitorbizz.com" class="text-indigo-600 hover:text-indigo-500">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection