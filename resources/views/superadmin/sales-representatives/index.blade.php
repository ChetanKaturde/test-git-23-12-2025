@extends('layouts.superadmin')

@section('title', 'Sales Representatives - Super Admin')

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
    .btn-back,
    .btn-create {
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

    .btn-create {
        background: linear-gradient(135deg, var(--info-color), #22d3ee);
        color: white;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
    }

    /* Table Card */
    .table-card {
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

    .representatives-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .representatives-table thead {
        background: linear-gradient(135deg, var(--dark-bg), #1e293b);
    }

    .representatives-table th {
        padding: 1.25rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        border: none;
    }

    .representatives-table th:first-child {
        padding-left: 2rem;
    }

    .representatives-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .representatives-table tbody tr:hover {
        background: #f8fafc;
    }

    .representatives-table td {
        padding: 1.25rem 1.5rem;
        color: var(--dark-bg);
        font-size: 0.9375rem;
        vertical-align: middle;
    }

    .representatives-table td:first-child {
        padding-left: 2rem;
    }

    .representative-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .representative-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--info-color), #22d3ee);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    }

    .representative-details {
        flex: 1;
        min-width: 0;
    }

    .representative-name {
        font-weight: 600;
        color: var(--dark-bg);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .representative-id {
        font-size: 0.8125rem;
        color: var(--text-light);
        font-family: 'Courier New', monospace;
    }

    .representative-email {
        color: var(--success-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .representative-email:hover {
        color: #059669;
        text-decoration: underline;
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

    .business-count {
        background: linear-gradient(135deg, var(--warning-color), #fbbf24);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-block;
    }

    .date-info {
        color: var(--text-light);
        font-size: 0.875rem;
    }

    .actions-column {
        white-space: nowrap;
    }

    .btn-action {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        margin-right: 0.5rem;
    }

    .btn-view {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-view:hover {
        background: #e2e8f0;
        color: #475569;
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
        .search-form-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            flex-direction: column;
        }

        .btn-search,
        .btn-clear,
        .btn-back,
        .btn-create {
            width: 100%;
            justify-content: center;
        }

        .representatives-table th,
        .representatives-table td {
            padding: 1rem;
        }

        .representatives-table th:first-child,
        .representatives-table td:first-child {
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

        .search-card,
        .table-card {
            padding: 1.25rem;
        }

        .representatives-table {
            font-size: 0.875rem;
        }

        .representatives-table th,
        .representatives-table td {
            padding: 0.875rem 0.75rem;
        }

        .representative-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .representative-info {
            gap: 0.75rem;
        }

        .btn-action {
            padding: 0.375rem 0.625rem;
            font-size: 0.75rem;
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

        .representatives-table th,
        .representatives-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }

        .representatives-table th:first-child,
        .representatives-table td:first-child {
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
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-user-tie" style="color: var(--info-color);"></i>
                    Sales Representatives
                </h1>
                <p class="page-subtitle">Manage 3rd-party sales representatives and track their business onboarding</p>
            </div>
            <div class="badge-count">
                <i class="fas fa-users me-2"></i>
                {{ $representatives->total() }} Total Representatives
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
                        placeholder="Search by name, email, phone, or representative ID..."
                        value="{{ request('search') }}"
                    >
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('superadmin.sales-representatives.index') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                @endif
            </form>

            <div class="d-flex gap-2">
                <a href="{{ route('superadmin.sales-representatives.create') }}" class="btn-create">
                    <i class="fas fa-plus"></i>
                    Add Representative
                </a>
                <a href="{{ route('superadmin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Representatives Table -->
    <div class="table-card">
        <div class="table-wrapper">
            <table class="representatives-table">
                <thead>
                    <tr>
                        <th>Representative</th>
                        <th>Contact Info</th>
                        <th>Status</th>
                        <th>Businesses</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($representatives as $representative)
                        <tr>
                            <td>
                                <div class="representative-info">
                                    <div class="representative-avatar">
                                        {{ strtoupper(substr($representative->full_name, 0, 1)) }}
                                    </div>
                                    <div class="representative-details">
                                        <div class="representative-name">{{ $representative->full_name }}</div>
                                        <div class="representative-id">{{ $representative->representative_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <a href="mailto:{{ $representative->email }}" class="representative-email">
                                        <i class="fas fa-envelope me-1"></i>{{ $representative->email }}
                                    </a>
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $representative->phone }}
                                </div>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <span class="business-count">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $representative->businesses()->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    {{ $representative->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="actions-column">
                                <a href="{{ route('superadmin.sales-representatives.show', $representative) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="empty-title">No Sales Representatives Found</div>
                                    <div class="empty-text">
                                        @if(request('search'))
                                            No representatives match your search criteria. Try adjusting your search terms.
                                        @else
                                            Get started by adding your first sales representative to the platform.
                                        @endif
                                    </div>
                                    @if(!request('search'))
                                        <a href="{{ route('superadmin.sales-representatives.create') }}" class="btn-create" style="margin-top: 1rem;">
                                            <i class="fas fa-plus"></i>
                                            Add First Representative
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($representatives->hasPages())
        <div class="pagination-wrapper">
            {{ $representatives->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection