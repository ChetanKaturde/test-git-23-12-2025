@extends('layouts.app')

@section('title', 'Upgrade Your Plan')
@section('page-title', 'Pricing Plans')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Upgrade to unlock unlimited invoices, team members, and advanced features for your manufacturing business.
        </p>
    </div>

    <!-- Pricing Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Free Plan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Free Plan</h3>
                <div class="text-3xl font-bold text-gray-900 mb-4">₹0<span class="text-sm font-normal text-gray-500">/month</span></div>
                <p class="text-gray-600 mb-6">Perfect for getting started</p>
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    50 invoices per month
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    2 team members
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Basic reporting
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Email support
                </li>
            </ul>
            
            <button disabled class="w-full bg-gray-100 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed">
                Current Plan
            </button>
        </div>

        <!-- Pro Plan -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-indigo-500 p-6 relative">
            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
            </div>
            
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pro Plan</h3>
                <div class="text-3xl font-bold text-indigo-600 mb-4">₹999<span class="text-sm font-normal text-gray-500">/month</span></div>
                <p class="text-gray-600 mb-6">For growing businesses</p>
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Unlimited invoices
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Unlimited team members
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Advanced reporting
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Priority support
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Manufacturing features
                </li>
            </ul>
            
            <button class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                Upgrade to Pro
            </button>
        </div>

        <!-- Enterprise Plan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Enterprise</h3>
                <div class="text-3xl font-bold text-gray-900 mb-4">₹2999<span class="text-sm font-normal text-gray-500">/month</span></div>
                <p class="text-gray-600 mb-6">For large operations</p>
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Everything in Pro
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Custom integrations
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    Dedicated support
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    On-premise deployment
                </li>
            </ul>
            
            <button class="w-full bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-800 transition-colors">
                Contact Sales
            </button>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Frequently Asked Questions</h3>
        
        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">What happens when I reach my Free Plan limits?</h4>
                <p class="text-gray-600 text-sm">You'll see a notification and won't be able to create more invoices or invite more team members until you upgrade or wait for the next month (for invoice limits).</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Can I downgrade my plan?</h4>
                <p class="text-gray-600 text-sm">Yes, you can downgrade at any time. Your data will be preserved, but you'll be subject to the new plan's limits.</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Is there a setup fee?</h4>
                <p class="text-gray-600 text-sm">No, there are no setup fees. You only pay the monthly subscription cost.</p>
            </div>
        </div>
    </div>
</div>
@endsection