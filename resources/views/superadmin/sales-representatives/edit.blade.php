@extends('layouts.superadmin')

@section('title', 'Edit Sales Representative - Super Admin')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        color: var(--text-light);
        font-size: 1rem;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-bg);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }

    .form-control {
        width: 100%;
        height: 48px;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        border-color: var(--success-color);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .form-textarea {
        height: 120px;
        resize: vertical;
        padding: 0.75rem 1rem;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .btn-submit,
    .btn-cancel {
        height: 48px;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--success-color), #34d399);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-cancel {
        background: white;
        color: var(--dark-bg);
        border: 2px solid var(--border-color);
        margin-left: 1rem;
    }

    .btn-cancel:hover {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .required-asterisk {
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .info-text {
        color: var(--text-light);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .readonly-field {
        background: #f1f5f9 !important;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .form-card {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-section-title {
            font-size: 1.125rem;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            justify-content: center;
            margin-left: 0;
            margin-top: 0.5rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user-edit" style="color: var(--warning-color);"></i>
                    Edit Sales Representative
                </h1>
                <p class="page-subtitle">Update representative information for {{ $representative->full_name }}</p>
            </div>
            <a href="{{ route('superadmin.sales-representatives.index') }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('superadmin.sales-representatives.update', $representative) }}">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-user" style="color: var(--info-color);"></i>
                    Personal Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            Full Name <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $representative->full_name) }}"
                            required
                        >
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $representative->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            Phone Number <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $representative->phone) }}"
                            required
                        >
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">
                            Date of Birth <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            class="form-control @error('date_of_birth') is-invalid @enderror"
                            value="{{ old('date_of_birth', $representative->date_of_birth ? $representative->date_of_birth->format('Y-m-d') : '') }}"
                            required
                        >
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-briefcase" style="color: var(--warning-color);"></i>
                    Professional Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="company_name" class="form-label">
                            Company Name <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            class="form-control @error('company_name') is-invalid @enderror"
                            value="{{ old('company_name', $representative->company_name) }}"
                            required
                        >
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="territory_region" class="form-label">
                            Territory/Region <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="text"
                            id="territory_region"
                            name="territory_region"
                            class="form-control @error('territory_region') is-invalid @enderror"
                            value="{{ old('territory_region', $representative->territory_region) }}"
                            required
                        >
                        @error('territory_region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">
                            Status <span class="required-asterisk">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="form-control @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Status</option>
                            <option value="Active" {{ old('status', $representative->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $representative->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="On Leave" {{ old('status', $representative->status) === 'On Leave' ? 'selected' : '' }}>On Leave</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="languages_spoken" class="form-label">
                            Languages Spoken <span class="required-asterisk">*</span>
                        </label>
                        <input
                            type="text"
                            id="languages_spoken"
                            name="languages_spoken"
                            class="form-control @error('languages_spoken') is-invalid @enderror"
                            value="{{ old('languages_spoken', $representative->languages_spoken) }}"
                            placeholder="e.g., English, Hindi, Gujarati"
                            required
                        >
                        @error('languages_spoken')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-map-marker-alt" style="color: var(--danger-color);"></i>
                    Address Information
                </h3>
                <div class="form-grid">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="current_address" class="form-label">
                            Current Address <span class="required-asterisk">*</span>
                        </label>
                        <textarea
                            id="current_address"
                            name="current_address"
                            class="form-control form-textarea @error('current_address') is-invalid @enderror"
                            rows="4"
                            required
                        >{{ old('current_address', $representative->current_address) }}</textarea>
                        @error('current_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Representative ID (Read-only) -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-id-card" style="color: var(--success-color);"></i>
                    Representative ID
                </h3>
                <div class="form-grid">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="representative_id" class="form-label">
                            Representative ID
                        </label>
                        <input
                            type="text"
                            id="representative_id"
                            class="form-control readonly-field"
                            value="{{ $representative->representative_id }}"
                            readonly
                        >
                        <div class="info-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Representative ID is auto-generated and cannot be modified.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end flex-wrap gap-2">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Update Representative
                </button>
                <a href="{{ route('superadmin.sales-representatives.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection