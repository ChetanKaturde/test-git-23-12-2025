@extends('layouts.superadmin')

@section('title', 'View Subscription Plan - Super Admin')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">{{ $plan->name }}</h1>
                <p class="page-subtitle">Plan details and feature configuration</p>
            </div>
            <div>
                <a href="{{ route('superadmin.subscription-plans.edit', $plan) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Edit Plan
                </a>
                <a href="{{ route('superadmin.subscription-plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Plans
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Plan Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Plan Name</label>
                                <p class="mb-0">{{ $plan->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($plan->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Price per User/Month</label>
                                <p class="mb-0">₹{{ number_format($plan->price_per_user, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Minimum Users</label>
                                <p class="mb-0">{{ $plan->min_users }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Maximum Users</label>
                                <p class="mb-0">{{ $plan->max_users }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Feature Access</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $implementedKeys = ['quotation_management', 'invoice_management', 'expense_management', 'customer_management', 'commodity_management', 'reports_analytics', 'team_management'];
                            $filteredFeatures = $plan->planFeatures->filter(function($pf) use ($implementedKeys) {
                                return in_array($pf->feature->key, $implementedKeys);
                            });
                        @endphp
                        @forelse($filteredFeatures as $planFeature)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 {{ $planFeature->enabled ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">
                                                {{ ucwords(str_replace('_', ' ', $planFeature->feature->name)) }}
                                            </h6>
                                            @if($planFeature->enabled)
                                                <span class="badge bg-success">Enabled</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-2">{{ $planFeature->feature->description }}</p>
                                        @if($planFeature->enabled && $planFeature->quantity_limit)
                                            <div class="alert alert-info py-1 px-2 small">
                                                <strong>Limit:</strong> {{ $planFeature->quantity_limit }} per month
                                            </div>
                                        @elseif($planFeature->enabled)
                                            <div class="alert alert-success py-1 px-2 small">
                                                Unlimited usage
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No features configured for this plan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Active Subscriptions</label>
                        <p class="mb-0 h4">{{ $plan->subscriptions()->active()->count() }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Subscriptions</label>
                        <p class="mb-0 h4">{{ $plan->subscriptions()->count() }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monthly Revenue</label>
                        <p class="mb-0 h4">₹{{ number_format($plan->subscriptions()->active()->sum(\DB::raw('amount')), 2) }}</p>
                    </div>
                </div>
            </div>

            @if($plan->subscriptions()->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Subscriptions</h5>
                    </div>
                    <div class="card-body">
                        @foreach($plan->subscriptions()->latest()->take(5) as $subscription)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <small class="fw-bold">{{ $subscription->business->name }}</small><br>
                                    <small class="text-muted">{{ $subscription->created_at->format('M d, Y') }}</small>
                                </div>
                                <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection