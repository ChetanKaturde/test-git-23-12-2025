@extends('layouts.superadmin')

@section('title', 'Subscription Plans - Super Admin')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Subscription Plans</h1>
                <p class="page-subtitle">Manage subscription plans and their features</p>
            </div>
            <a href="{{ route('superadmin.subscription-plans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Plan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price/User/Month</th>
                            <th>User Limits</th>
                            <th>Status</th>
                            <th>Active Subscriptions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>₹{{ number_format($plan->price_per_user, 2) }}</td>
                                <td>{{ $plan->min_users }} - {{ $plan->max_users }}</td>
                                <td>
                                    <span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($plan->status) }}
                                    </span>
                                </td>
                                <td>{{ $plan->subscriptions()->active()->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('superadmin.subscription-plans.show', $plan) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('superadmin.subscription-plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('superadmin.subscription-plans.toggle-status', $plan) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-{{ $plan->status === 'active' ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($plan->subscriptions()->count() === 0)
                                            <form action="{{ route('superadmin.subscription-plans.destroy', $plan) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>No subscription plans found.</p>
                                        <a href="{{ route('superadmin.subscription-plans.create') }}" class="btn btn-primary btn-sm">
                                            Create First Plan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection