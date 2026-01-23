@extends('layouts.superadmin')

@section('title', 'Sales Representative Details - Super Admin')

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

    .badge-status {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
    }

    .badge-active {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-inactive {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-on-leave {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .btn-action {
        height: 40px;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        margin-right: 0.5rem;
    }

    .btn-edit {
        background: #ecfdf5;
        color: #065f46;
    }

    .btn-edit:hover {
        background: #d1fae5;
        color: #047857;
    }

    .btn-toggle-active {
        background: #ecfdf5;
        color: #065f46;
    }

    .btn-toggle-active:hover {
        background: #d1fae5;
        color: #047857;
    }

    .btn-toggle-inactive {
        background: #fef2f2;
        color: #991b1b;
    }

    .btn-toggle-inactive:hover {
        background: #fecaca;
        color: #dc2626;
    }

    .btn-back {
        background: white;
        color: var(--dark-bg);
        border: 2px solid var(--border-color);
    }

    .btn-back:hover {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    /* Details Card */
    .details-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .details-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .representative-avatar {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--info-color), #22d3ee);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
    }

    .representative-info h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
    }

    .representative-id {
        font-size: 1rem;
        color: var(--text-light);
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark-bg);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-label {
        font-weight: 600;
        color: var(--dark-bg);
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .detail-value {
        color: var(--text-light);
        font-size: 0.9375rem;
        line-height: 1.5;
    }

    .detail-link {
        color: var(--success-color);
        text-decoration: none;
        font-weight: 500;
    }

    .detail-link:hover {
        text-decoration: underline;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .stat-card.success {
        --card-color: #10b981;
        --card-color-light: #34d399;
    }

    .stat-card.info {
        --card-color: #06b6d4;
        --card-color-light: #22d3ee;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--dark-bg);
        line-height: 1;
    }

    .stat-label {
        color: var(--text-light);
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    /* Businesses Table */
    .businesses-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .businesses-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid var(--border-color);
    }

    .businesses-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-bg);
        margin: 0;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .businesses-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .businesses-table thead {
        background: linear-gradient(135deg, var(--dark-bg), #1e293b);
    }

    .businesses-table th {
        padding: 1rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        border: none;
    }

    .businesses-table th:first-child {
        padding-left: 2rem;
    }

    .businesses-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .businesses-table tbody tr:hover {
        background: #f8fafc;
    }

    .businesses-table td {
        padding: 1rem 1.5rem;
        color: var(--dark-bg);
        font-size: 0.9375rem;
        vertical-align: middle;
    }

    .businesses-table td:first-child {
        padding-left: 2rem;
    }

    .business-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .business-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--success-color), #34d399);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .business-details {
        flex: 1;
        min-width: 0;
    }

    .business-name {
        font-weight: 600;
        color: var(--dark-bg);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .business-email {
        font-size: 0.8125rem;
        color: var(--text-light);
    }

    .business-owner {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .owner-name {
        font-weight: 600;
        color: var(--dark-bg);
    }

    .owner-email {
        font-size: 0.875rem;
        color: var(--text-light);
    }

    .date-info {
        color: var(--text-light);
        font-size: 0.875rem;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--success-color);
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--text-light);
        font-size: 1rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .details-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .businesses-table th,
        .businesses-table td {
            padding: 1rem;
        }

        .businesses-table th:first-child,
        .businesses-table td:first-child {
            padding-left: 1rem;
        }
    }

    @media (max-width: 767.98px) {
        .page-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .details-card,
        .businesses-card {
            padding: 1.25rem;
        }

        .representative-avatar {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }

        .representative-info h2 {
            font-size: 1.5rem;
        }

        .businesses-table {
            font-size: 0.875rem;
        }

        .businesses-table th,
        .businesses-table td {
            padding: 0.875rem 0.75rem;
        }

        .business-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 575.98px) {
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .details-card,
        .businesses-card {
            padding: 1rem;
        }

        .businesses-table th,
        .businesses-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }

        .businesses-table th:first-child,
        .businesses-table td:first-child {
            padding-left: 0.75rem;
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            font-size: 2rem;
        }

        .empty-title {
            font-size: 1.25rem;
        }

        .empty-text {
            font-size: 0.9375rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user-tie" style="color: var(--info-color);"></i>
                    Sales Representative Details
                </h1>
                <p class="page-subtitle">View complete information and business connections</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('superadmin.sales-representatives.edit', $representative) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
                <form method="POST" action="{{ route('superadmin.sales-representatives.toggle-status', $representative) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-action {{ $representative->status === 'Active' ? 'btn-toggle-inactive' : 'btn-toggle-active' }}">
                        <i class="fas fa-{{ $representative->status === 'Active' ? 'ban' : 'check' }}"></i>
                        {{ $representative->status === 'Active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <a href="{{ route('superadmin.sales-representatives.index') }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $businesses->total() }}</div>
                    <div class="stat-label">Total Businesses</div>
                </div>
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $representative->created_at->format('M Y') }}</div>
                    <div class="stat-label">Joined</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Representative Details -->
    <div class="details-card">
        <div class="details-header">
            <div class="representative-avatar">
                {{ strtoupper(substr($representative->full_name, 0, 1)) }}
            </div>
            <div class="representative-info">
                <h2>{{ $representative->full_name }}</h2>
                <div class="representative-id">{{ $representative->representative_id }}</div>
                @if($representative->status === 'Active')
                    <span class="badge-status badge-active">
                        <i class="fas fa-check-circle"></i>
                        Active
                    </span>
                @elseif($representative->status === 'Inactive')
                    <span class="badge-status badge-inactive">
                        <i class="fas fa-times-circle"></i>
                        Inactive
                    </span>
                @else
                    <span class="badge-status badge-on-leave">
                        <i class="fas fa-clock"></i>
                        On Leave
                    </span>
                @endif
            </div>
        </div>

        <div class="details-grid">
            <!-- Personal Information -->
            <div class="detail-section">
                <h3 class="detail-section-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h3>
                <div class="detail-item">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">
                        <a href="mailto:{{ $representative->email }}" class="detail-link">{{ $representative->email }}</a>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">{{ $representative->phone }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date of Birth</div>
                    <div class="detail-value">{{ $representative->date_of_birth ? $representative->date_of_birth->format('M d, Y') : 'Not provided' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Languages Spoken</div>
                    <div class="detail-value">{{ $representative->languages_spoken }}</div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="detail-section">
                <h3 class="detail-section-title">
                    <i class="fas fa-briefcase"></i>
                    Professional Information
                </h3>
                <div class="detail-item">
                    <div class="detail-label">Company Name</div>
                    <div class="detail-value">{{ $representative->company_name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Territory/Region</div>
                    <div class="detail-value">{{ $representative->territory_region }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @if($representative->status === 'Active')
                            <span class="badge-status badge-active">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        @elseif($representative->status === 'Inactive')
                            <span class="badge-status badge-inactive">
                                <i class="fas fa-times-circle"></i>
                                Inactive
                            </span>
                        @else
                            <span class="badge-status badge-on-leave">
                                <i class="fas fa-clock"></i>
                                On Leave
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="detail-section" style="grid-column: 1 / -1;">
                <h3 class="detail-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Address Information
                </h3>
                <div class="detail-item">
                    <div class="detail-label">Current Address</div>
                    <div class="detail-value">{{ $representative->current_address }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Connected Businesses -->
    <div class="businesses-card">
        <div class="businesses-header">
            <h3 class="businesses-title">
                <i class="fas fa-building me-2"></i>
                Connected Businesses ({{ $businesses->total() }})
            </h3>
        </div>

        <div class="table-wrapper">
            <table class="businesses-table">
                <thead>
                    <tr>
                        <th>Business</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($businesses as $business)
                        <tr>
                            <td>
                                <div class="business-info">
                                    <div class="business-avatar">
                                        {{ strtoupper(substr($business->name, 0, 1)) }}
                                    </div>
                                    <div class="business-details">
                                        <div class="business-name">{{ $business->name }}</div>
                                        <div class="business-email">{{ $business->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="business-owner">
                                    @php
                                        $adminUser = $business->users()->where('role', 'admin')->first();
                                    @endphp
                                    @if($adminUser)
                                        <div class="owner-name">{{ $adminUser->name }}</div>
                                        <div class="owner-email">{{ $adminUser->email }}</div>
                                    @else
                                        <div class="owner-name">No admin user</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <a href="mailto:{{ $business->email }}" class="detail-link">
                                        <i class="fas fa-envelope me-1"></i>{{ $business->email }}
                                    </a>
                                </div>
                                @if($business->phone)
                                    <div class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $business->phone }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="date-info">
                                    {{ $business->created_at->format('M d, Y') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="empty-title">No Connected Businesses</div>
                                    <div class="empty-text">
                                        This sales representative hasn't onboarded any businesses yet.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($businesses->hasPages())
        <div class="pagination-wrapper">
            {{ $businesses->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection