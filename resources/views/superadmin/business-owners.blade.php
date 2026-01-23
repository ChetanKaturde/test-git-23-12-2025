@extends('layouts.superadmin')

@section('title', 'Business Owners - Super Admin')

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

    .badge-count {
        background: linear-gradient(135deg, var(--success-color), #34d399);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9375rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Search Card */
    .search-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .search-form-wrapper {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-form {
        flex: 1;
        display: flex;
        gap: 0.75rem;
    }

    .search-input-group {
        flex: 1;
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        height: 48px;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .search-input:focus {
        border-color: var(--success-color);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
    }

    .btn-search,
    .btn-clear,
    .btn-back {
        height: 48px;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn-search {
        background: linear-gradient(135deg, var(--success-color), #34d399);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-clear {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-clear:hover {
        background: #e2e8f0;
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

    /* Business Table Card */
    .business-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .business-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .business-table thead {
        background: linear-gradient(135deg, var(--dark-bg), #1e293b);
    }

    .business-table th {
        padding: 1.25rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        border: none;
    }

    .business-table th:first-child {
        padding-left: 2rem;
    }

    .sort-link {
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s ease;
    }

    .sort-link:hover {
        color: var(--success-color);
    }

    .business-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .business-table tbody tr:hover {
        background: #f8fafc;
    }

    .business-table td {
        padding: 1.25rem 1.5rem;
        color: var(--dark-bg);
        font-size: 0.9375rem;
        vertical-align: middle;
    }

    .business-table td:first-child {
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

    .business-id {
        font-size: 0.8125rem;
        color: var(--text-light);
    }

    .owner-info {
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

    .business-email {
        color: var(--success-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .business-email:hover {
        color: #059669;
        text-decoration: underline;
    }

    .business-phone {
        color: var(--success-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .business-phone:hover {
        color: #059669;
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

    .badge-subscription {
        background: linear-gradient(135deg, var(--info-color), #22d3ee);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-block;
    }

    .badge-free {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-block;
    }

    .date-info {
        color: var(--text-light);
        font-size: 0.875rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
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

    /* Summary Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .summary-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .summary-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
    }

    .summary-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .summary-stat-card.success {
        --card-color: #10b981;
        --card-color-light: #34d399;
    }

    .summary-stat-card.danger {
        --card-color: #ef4444;
        --card-color-light: #f87171;
    }

    .summary-stat-card.info {
        --card-color: #06b6d4;
        --card-color-light: #22d3ee;
    }

    .summary-stat-card.warning {
        --card-color: #f59e0b;
        --card-color-light: #fbbf24;
    }

    .summary-stat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .summary-stat-icon {
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

    .summary-stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--dark-bg);
        line-height: 1;
    }

    .summary-stat-label {
        color: var(--text-light);
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .search-form-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            flex-direction: column;
        }

        .btn-search,
        .btn-clear,
        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .business-table th,
        .business-table td {
            padding: 1rem;
        }

        .business-table th:first-child,
        .business-table td:first-child {
            padding-left: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .page-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .search-card,
        .business-card,
        .summary-stat-card {
            padding: 1.25rem;
        }

        .business-table {
            font-size: 0.875rem;
        }

        .business-table th,
        .business-table td {
            padding: 0.875rem 0.75rem;
        }

        .business-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .business-info {
            gap: 0.75rem;
        }

        .summary-stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .summary-stat-value {
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

        .badge-count {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .business-table th,
        .business-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }

        .business-table th:first-child,
        .business-table td:first-child {
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
                    <i class="fas fa-building" style="color: var(--success-color);"></i>
                    Business Owners
                </h1>
                <p class="page-subtitle">Monitor and manage all registered businesses on the platform</p>
            </div>
            <div class="badge-count">
                <i class="fas fa-briefcase me-2"></i>
                {{ $businesses->total() }} Total Businesses
            </div>
        </div>
    </div>

    <!-- Search Card -->
    <div class="search-card">
        <div class="search-form-wrapper">
            <form method="GET" class="search-form">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input 
                        type="text" 
                        name="search" 
                        class="search-input" 
                        placeholder="Search by business name, email, owner name, or phone..." 
                        value="{{ request('search') }}"
                    >
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('superadmin.business-owners') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                @endif
            </form>
            
            <a href="{{ route('superadmin.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Business Table -->
    <div class="business-card">
        <div class="table-wrapper">
            <table class="business-table">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route('superadmin.business-owners', array_merge(request()->query(), ['sort_by' => 'name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="sort-link">
                                Business Name
                                @if(request('sort_by') == 'name')
                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Owner Details</th>
                        <th>
                            <a href="{{ route('superadmin.business-owners', array_merge(request()->query(), ['sort_by' => 'email', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="sort-link">
                                Business Email
                                @if(request('sort_by') == 'email')
                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Subscription</th>
                        <th>
                            <a href="{{ route('superadmin.business-owners', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="sort-link">
                                Registered
                                @if(request('sort_by') == 'created_at')
                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($businesses as $business)
                    <tr>
                        <td>
                            <div class="business-info">
                                <div class="business-avatar">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="business-details">
                                    <div class="business-name">{{ $business->name }}</div>
                                    <div class="business-id">ID: {{ $business->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($business->users->first())
                                <div class="owner-info">
                                    <div class="owner-name">{{ $business->users->first()->name }}</div>
                                    <div class="owner-email">{{ $business->users->first()->email }}</div>
                                </div>
                            @else
                                <span style="color: var(--text-light);">No owner assigned</span>
                            @endif
                        </td>
                        <td>
                            <a href="mailto:{{ $business->email }}" class="business-email">
                                {{ $business->email }}
                            </a>
                        </td>
                        <td>
                            @if($business->phone)
                                <a href="tel:{{ $business->phone }}" class="business-phone">
                                    <i class="fas fa-phone"></i>
                                    {{ $business->phone }}
                                </a>
                            @else
                                <span style="color: var(--text-light);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($business->is_active)
                                <span class="badge-status badge-active">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="fas fa-times-circle"></i>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($business->subscription_tier)
                                <span class="badge-subscription">{{ ucfirst(str_replace('_', ' ', $business->subscription_tier)) }}</span>
                            @else
                                <span class="badge-free">Free</span>
                            @endif
                        </td>
                        <td>
                            <div class="date-info">
                                <span>{{ $business->created_at->format('M d, Y') }}</span>
                                <span>{{ $business->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 0; border: none;">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h3 class="empty-title">No Businesses Found</h3>
                                <p class="empty-text">
                                    @if(request('search'))
                                        No businesses match your search criteria. Try adjusting your search terms.
                                    @else
                                        There are no registered businesses to display at this time.
                                    @endif
                                </p>
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

    <!-- Summary Statistics -->
    <div class="stats-grid">
        <div class="summary-stat-card success">
            <div class="summary-stat-header">
                <div class="summary-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="summary-stat-value">{{ $businesses->where('is_active', true)->count() }}</div>
                    <div class="summary-stat-label">Active Businesses</div>
                </div>
            </div>
        </div>

        <div class="summary-stat-card danger">
            <div class="summary-stat-header">
                <div class="summary-stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="summary-stat-value">{{ $businesses->where('is_active', false)->count() }}</div>
                    <div class="summary-stat-label">Inactive Businesses</div>
                </div>
            </div>
        </div>

        <div class="summary-stat-card info">
            <div class="summary-stat-header">
                <div class="summary-stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <div class="summary-stat-value">{{ $businesses->where('subscription_tier', 'billing_sales')->count() }}</div>
                    <div class="summary-stat-label">Premium Users</div>
                </div>
            </div>
        </div>

        <div class="summary-stat-card warning">
            <div class="summary-stat-header">
                <div class="summary-stat-icon">
                    <i class="fas fa-sparkles"></i>
                </div>
                <div>
                    <div class="summary-stat-value">{{ $businesses->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                    <div class="summary-stat-label">New (30 days)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection