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
    <div class="grid grid-cols-1 md:grid-cols-{{ count($plans) }} gap-6">
        @foreach($plans as $index => $plan)
            <div class="bg-white rounded-xl shadow-sm {{ $currentSubscription && $currentSubscription->plan_id == $plan->id ? 'border-2 border-green-500' : ($index === 1 ? 'border-2 border-indigo-500' : 'border border-gray-200') }} p-6 relative">
                @if($index === 1 && !$currentSubscription)
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                    </div>
                @endif

                @if($currentSubscription && $currentSubscription->plan_id == $plan->id)
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-medium">Current Plan</span>
                    </div>
                @endif

                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <div class="text-3xl font-bold text-indigo-600 mb-4">₹{{ number_format($plan->price_per_user, 0) }}<span class="text-sm font-normal text-gray-500">/user/month</span></div>
                    <p class="text-gray-600 mb-6">{{ $plan->min_users }}-{{ $plan->max_users }} users</p>
                </div>

                <ul class="space-y-3 mb-6">
                    @php
                        $implementedKeys = ['quotation_management', 'invoice_management', 'expense_management', 'customer_management', 'commodity_management', 'reports_analytics', 'team_management'];
                        $filteredFeatures = $plan->planFeatures->filter(function($pf) use ($implementedKeys) {
                            return in_array($pf->feature->key, $implementedKeys);
                        });
                    @endphp
                    @foreach($filteredFeatures as $feature)
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas {{ $feature->enabled ? 'fa-check text-green-500' : 'fa-times text-red-500' }} mr-3"></i>
                            <span>{{ ucwords(str_replace('_', ' ', $feature->feature->name)) }}</span>
                            @if($feature->enabled && $feature->quantity_limit)
                                <small class="text-gray-500 ml-1">(Limit: {{ $feature->quantity_limit }})</small>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if($currentSubscription && $currentSubscription->plan_id == $plan->id)
                    <button disabled class="w-full bg-green-100 text-green-800 py-2 px-4 rounded-lg cursor-not-allowed">
                        Current Plan
                    </button>
                @else
                    <button class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                        {{ $plan->price_per_user > 0 ? 'Upgrade' : 'Downgrade' }} to {{ $plan->name }}
                    </button>
                @endif
            </div>
        @endforeach
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