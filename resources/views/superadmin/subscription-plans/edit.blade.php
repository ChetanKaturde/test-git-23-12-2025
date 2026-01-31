@extends('layouts.superadmin')

@section('title', 'Edit Subscription Plan - Super Admin')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Subscription Plan</h1>
                <p class="page-subtitle">Modify plan details and feature access</p>
            </div>
            <a href="{{ route('superadmin.subscription-plans.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Plans
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('superadmin.subscription-plans.update', $plan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price_per_user" class="form-label">Price per User per Month (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price_per_user') is-invalid @enderror"
                                   id="price_per_user" name="price_per_user" value="{{ old('price_per_user', $plan->price_per_user) }}" required>
                            @error('price_per_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="min_users" class="form-label">Minimum Users <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_users') is-invalid @enderror"
                                   id="min_users" name="min_users" value="{{ old('min_users', $plan->min_users) }}" min="1" required>
                            @error('min_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="max_users" class="form-label">Maximum Users <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_users') is-invalid @enderror"
                                   id="max_users" name="max_users" value="{{ old('max_users', $plan->max_users) }}" min="1" required>
                            @error('max_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $plan->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $plan->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Feature Access Control</h5>
                <p class="text-muted">Enable/disable features for this plan and set quantity limits where applicable.</p>

                <div class="row">
                    @foreach($features as $feature)
                        @if(in_array($feature->key, ['quotation_management', 'invoice_management', 'expense_management', 'customer_management', 'commodity_management', 'reports_analytics', 'team_management']))
                        @php
                            $planFeature = $plan->planFeatures->where('feature_id', $feature->id)->first();
                            $isEnabled = old("features.{$feature->id}.enabled", $planFeature ? $planFeature->enabled : false);
                            $limit = old("features.{$feature->id}.quantity_limit", $planFeature ? $planFeature->quantity_limit : null);
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input feature-checkbox" type="checkbox"
                                               id="feature_{{ $feature->id }}" name="features[{{ $feature->id }}][enabled]"
                                               value="1" {{ $isEnabled ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="feature_{{ $feature->id }}">
                                            {{ ucwords(str_replace('_', ' ', $feature->name)) }}
                                        </label>
                                    </div>
                                    <p class="text-muted small mb-2">{{ $feature->description }}</p>

                                    <div class="feature-limits" style="display: {{ $isEnabled ? 'block' : 'none' }};">
                                        <label class="form-label small">Quantity Limit (optional)</label>
                                        <input type="number" class="form-control form-control-sm"
                                               name="features[{{ $feature->id }}][quantity_limit]"
                                               value="{{ $limit }}"
                                               placeholder="Unlimited" min="0">
                                        <small class="text-muted">Leave empty for unlimited usage</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('superadmin.subscription-plans.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide quantity limits based on checkbox state
    document.querySelectorAll('.feature-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const limitsDiv = this.closest('.card-body').querySelector('.feature-limits');
            if (this.checked) {
                limitsDiv.style.display = 'block';
            } else {
                limitsDiv.style.display = 'none';
            }
        });
    });
});
</script>
@endsection