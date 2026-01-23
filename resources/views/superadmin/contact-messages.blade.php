@extends('layouts.superadmin')

@section('title', 'Contact Messages - Super Admin')

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
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9375rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
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

    .search-form {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
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
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: white;
        outline: none;
    }

    .btn-search,
    .btn-clear,
    .btn-export,
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
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    }

    .btn-clear {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-clear:hover {
        background: #e2e8f0;
    }

    .btn-export {
        background: linear-gradient(135deg, var(--success-color), #34d399);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-back {
        background: white;
        color: var(--dark-bg);
        border: 2px solid var(--border-color);
    }

    .btn-back:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Bulk Actions Card */
    .bulk-actions-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .bulk-select {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .form-check-label {
        font-weight: 600;
        color: var(--dark-bg);
        cursor: pointer;
        user-select: none;
    }

    .selected-count {
        color: var(--text-light);
        font-size: 0.9375rem;
    }

    .btn-bulk-delete {
        height: 42px;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, var(--danger-color), #f87171);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: none;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-bulk-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }

    /* Messages Table Card */
    .messages-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .messages-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .messages-table thead {
        background: linear-gradient(135deg, var(--dark-bg), #1e293b);
    }

    .messages-table th {
        padding: 1.25rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        border: none;
    }

    .messages-table th:first-child {
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
        color: var(--primary-color);
    }

    .messages-table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .messages-table tbody tr:hover {
        background: #f8fafc;
    }

    .messages-table td {
        padding: 1.25rem 1.5rem;
        color: var(--dark-bg);
        font-size: 0.9375rem;
        vertical-align: middle;
    }

    .messages-table td:first-child {
        padding-left: 2rem;
    }

    .contact-name {
        font-weight: 600;
        color: var(--dark-bg);
    }

    .contact-email {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .contact-email:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .contact-phone {
        color: var(--success-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .contact-phone:hover {
        color: #059669;
    }

    .badge-business {
        background: linear-gradient(135deg, var(--info-color), #22d3ee);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-block;
    }

    .message-preview {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #64748b;
        cursor: help;
    }

    .date-info {
        color: var(--text-light);
        font-size: 0.875rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .btn-delete {
        width: 38px;
        height: 38px;
        padding: 0;
        background: #fef2f2;
        color: var(--danger-color);
        border: 1px solid #fecaca;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete:hover {
        background: var(--danger-color);
        color: white;
        border-color: var(--danger-color);
        transform: scale(1.1);
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--text-light);
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
        margin-top: 2rem;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .search-form {
            flex-direction: column;
        }

        .action-buttons {
            width: 100%;
        }

        .btn-export,
        .btn-back {
            flex: 1;
        }

        .messages-table th,
        .messages-table td {
            padding: 1rem;
        }

        .messages-table th:first-child,
        .messages-table td:first-child {
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
        .bulk-actions-card,
        .messages-card {
            padding: 1.25rem;
        }

        .bulk-actions-card {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-select {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-bulk-delete {
            width: 100%;
            justify-content: center;
        }

        .messages-table {
            font-size: 0.875rem;
        }

        .messages-table th,
        .messages-table td {
            padding: 0.875rem 0.75rem;
        }

        .message-preview {
            max-width: 150px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-export,
        .btn-back,
        .btn-search,
        .btn-clear {
            width: 100%;
            justify-content: center;
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

        .messages-table th,
        .messages-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }

        .messages-table th:first-child,
        .messages-table td:first-child {
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
                    <i class="fas fa-envelope" style="color: var(--primary-color);"></i>
                    Contact Messages
                </h1>
                <p class="page-subtitle">View and manage customer inquiries and contact requests</p>
            </div>
            <div class="badge-count">
                <i class="fas fa-inbox me-2"></i>
                {{ $contactRequests->total() }} Total Messages
            </div>
        </div>
    </div>

    <!-- Search Card -->
    <div class="search-card">
        <form method="GET" class="search-form">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search by name, email, phone, or message content..." 
                    value="{{ request('search') }}"
                >
            </div>
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i>
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('superadmin.contact-messages') }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Clear
                </a>
            @endif
        </form>
        
        <div class="action-buttons">
            <a href="{{ route('superadmin.contact-messages.export', request()->query()) }}" class="btn-export">
                <i class="fas fa-download"></i>
                Export to CSV
            </a>
            <a href="{{ route('superadmin.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form id="bulk-delete-form" method="POST" action="{{ route('superadmin.contact-messages.bulk-delete') }}">
        @csrf
        <div class="bulk-actions-card">
            <div class="bulk-select">
                <input type="checkbox" id="select-all" class="form-check-input">
                <label for="select-all" class="form-check-label">Select All Messages</label>
                <span class="selected-count" id="selected-count">0 selected</span>
            </div>
            <button type="submit" class="btn-bulk-delete" id="bulk-delete-btn" 
                    onclick="return confirm('Are you sure you want to delete the selected messages? This action cannot be undone.')">
                <i class="fas fa-trash"></i>
                Delete Selected
            </button>
        </div>

        <!-- Messages Table -->
        <div class="messages-card">
            <div class="table-wrapper">
                <table class="messages-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="select-all-header" class="form-check-input">
                            </th>
                            <th>
                                <a href="{{ route('superadmin.contact-messages', array_merge(request()->query(), ['sort_by' => 'name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="sort-link">
                                    Name
                                    @if(request('sort_by') == 'name')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('superadmin.contact-messages', array_merge(request()->query(), ['sort_by' => 'email', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="sort-link">
                                    Email
                                    @if(request('sort_by') == 'email')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Phone</th>
                            <th>Business Type</th>
                            <th>Message</th>
                            <th>
                                <a href="{{ route('superadmin.contact-messages', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="sort-link">
                                    Date
                                    @if(request('sort_by') == 'created_at')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th style="width: 80px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contactRequests as $contact)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $contact->id }}" class="form-check-input select-item">
                            </td>
                            <td>
                                <div class="contact-name">{{ $contact->name }}</div>
                            </td>
                            <td>
                                <a href="mailto:{{ $contact->email }}" class="contact-email">
                                    {{ $contact->email }}
                                </a>
                            </td>
                            <td>
                                @if($contact->mobile)
                                    <a href="tel:{{ $contact->mobile }}" class="contact-phone">
                                        <i class="fas fa-phone-alt"></i>
                                        {{ $contact->mobile }}
                                    </a>
                                @else
                                    <span style="color: var(--text-light);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($contact->business_type)
                                    <span class="badge-business">{{ $contact->business_type }}</span>
                                @else
                                    <span style="color: var(--text-light);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="message-preview" title="{{ $contact->message }}">
                                    {{ Str::limit($contact->message, 80) }}
                                </div>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span>{{ $contact->created_at->format('M d, Y') }}</span>
                                    <span>{{ $contact->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <form method="POST" action="{{ route('superadmin.contact-messages.destroy', $contact) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" 
                                            title="Delete message"
                                            onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="padding: 0; border: none;">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h3 class="empty-title">No Messages Found</h3>
                                    <p class="empty-text">
                                        @if(request('search'))
                                            No messages match your search criteria. Try adjusting your search terms.
                                        @else
                                            There are no contact messages to display at this time.
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
    </form>

    <!-- Pagination -->
    @if($contactRequests->hasPages())
        <div class="pagination-wrapper">
            {{ $contactRequests->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const selectAllHeader = document.getElementById('select-all-header');
    const checkboxes = document.querySelectorAll('.select-item');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.select-item:checked');
        const count = checkedBoxes.length;
        selectedCount.textContent = count + ' selected';
        bulkDeleteBtn.style.display = count > 0 ? 'flex' : 'none';
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        selectAllHeader.checked = this.checked;
        updateBulkActions();
    });

    selectAllHeader.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        selectAll.checked = this.checked;
        updateBulkActions();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const someChecked = Array.from(checkboxes).some(cb => cb.checked);
            selectAll.checked = allChecked;
            selectAllHeader.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
            selectAllHeader.indeterminate = someChecked && !allChecked;
            updateBulkActions();
        });
    });

    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection