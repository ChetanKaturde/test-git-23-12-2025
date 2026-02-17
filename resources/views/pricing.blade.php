@extends('layouts.app')

@section('title', 'Upgrade Your Plan')
@section('page-title', 'Pricing Plans')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Upgrade to unlock unlimited invoices, team members, and advanced features for your manufacturing business.
        </p>
    </div>

    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <p class="text-yellow-700">{{ session('warning') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-{{ count($plans) }} gap-6">
        @foreach($plans as $plan)
            @php
                $isCurrentPlan = $currentSubscription && $currentSubscription->plan_id == $plan->id;
                $isExpiredPlan = $expiredSubscription && $expiredSubscription->plan_id == $plan->id;
                $isPlanActive = $currentSubscription && !$isCurrentPlan;
                
                // Determine visibility
                $showPlan = true;
                if ($currentSubscription) {
                    // Active plan: hide lower plans
                    if ($plan->price_per_user < $currentSubscription->plan->price_per_user) {
                        $showPlan = false;
                    }
                } elseif ($expiredSubscription) {
                    // Expired plan: hide lower plans
                    if ($plan->price_per_user < $expiredSubscription->plan->price_per_user) {
                        $showPlan = false;
                    }
                }
                
                // Button logic
                $buttonText = 'Select Plan';
                $buttonAction = 'upgrade';
                $buttonDisabled = false;
                
                if ($isCurrentPlan) {
                    if ($currentSubscription->end_date >= now()) {
                        $buttonText = 'Pay Next Month Advance';
                        $buttonAction = 'advance';
                    }
                } elseif ($isExpiredPlan) {
                    $buttonText = 'Continue with ' . $plan->name;
                    $buttonAction = 'continue';
                } elseif ($isPlanActive && $plan->price_per_user > $currentSubscription->plan->price_per_user) {
                    $buttonText = 'Upgrade available after expiry';
                    $buttonDisabled = true;
                } elseif ($currentSubscription && $plan->price_per_user > $currentSubscription->plan->price_per_user) {
                    $buttonText = 'Upgrade to ' . $plan->name;
                    $buttonAction = 'upgrade';
                }
            @endphp

            @if($showPlan)
            <div class="bg-white rounded-xl shadow-sm {{ $isCurrentPlan ? 'border-2 border-green-500' : 'border border-gray-200' }} p-6 relative">
                @if($isCurrentPlan)
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-medium">
                            {{ $currentSubscription->end_date >= now() ? 'Active' : 'Expired' }}
                        </span>
                    </div>
                @endif

                @if($isExpiredPlan)
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Expired</span>
                    </div>
                @endif

                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <div class="text-3xl font-bold text-indigo-600 mb-4">
                        ₹{{ number_format($plan->price_per_user, 0) }}
                        <span class="text-sm font-normal text-gray-500">/user/month</span>
                    </div>
                    <p class="text-gray-600 mb-2">{{ $plan->min_users }}-{{ $plan->max_users }} users</p>
                    @if($isCurrentPlan && $currentSubscription->end_date)
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Expires: {{ $currentSubscription->end_date->format('d M Y') }}
                        </p>
                    @endif
                </div>

                <ul class="space-y-3 mb-6 mt-4">
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

                <form method="POST" action="{{ route('pricing.process') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="action" value="{{ $buttonAction }}">
                    <button type="submit" 
                            class="w-full py-2 px-4 rounded-lg transition-colors {{ $buttonDisabled ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}"
                            {{ $buttonDisabled ? 'disabled' : '' }}>
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
            @endif
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Frequently Asked Questions</h3>
        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Can I downgrade my plan?</h4>
                <p class="text-gray-600 text-sm">No, downgrades are not allowed to maintain data integrity.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Can I upgrade during an active plan?</h4>
                <p class="text-gray-600 text-sm">Upgrades are available after your current plan expires.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">What happens when my plan expires?</h4>
                <p class="text-gray-600 text-sm">Only the business owner can login. Team members will be blocked until renewal.</p>
            </div>
        </div>
    </div>
</div>
@endsection