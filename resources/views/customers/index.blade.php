@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<style>
    /* ── Base Variables ── */
    :root {
        --clr-primary: #4f46e5;
        --clr-primary-hover: #4338ca;
        --clr-primary-light: #eef2ff;
        --clr-green: #059669;
        --clr-green-light: #d1fae5;
        --clr-purple: #7c3aed;
        --clr-purple-light: #ede9fe;
        --clr-orange: #d97706;
        --clr-orange-light: #fef3c7;
        --clr-surface: #ffffff;
        --clr-bg: #f5f6fa;
        --clr-border: #e5e7eb;
        --clr-text: #111827;
        --clr-text-muted: #6b7280;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md: 0 4px 16px rgba(0,0,0,.08);
        --radius: 14px;
        --radius-sm: 8px;
    }

    /* ── Page Wrapper ── */
    .cust-page { background: var(--clr-bg); padding: 1.5rem; }
    @media (min-width: 768px) { .cust-page { padding: 2rem; } }

    /* ── Header Card ── */
    .cust-header {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius);
        padding: 1.75rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
    }
    .cust-header-inner {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    @media (min-width: 768px) {
        .cust-header-inner { flex-direction: row; align-items: center; justify-content: space-between; }
    }
    .cust-header-title { font-size: 1.625rem; font-weight: 700; color: var(--clr-text); letter-spacing: -0.02em; margin: 0 0 .25rem; }
    .cust-header-sub { font-size: .875rem; color: var(--clr-text-muted); margin: 0; }
    .cust-breadcrumb {
        display: flex; align-items: center; gap: .375rem;
        font-size: .8125rem; color: var(--clr-text-muted); margin-bottom: .375rem;
    }
    .cust-breadcrumb a { color: var(--clr-text-muted); text-decoration: none; transition: color .15s; }
    .cust-breadcrumb a:hover { color: var(--clr-primary); }
    .cust-breadcrumb-sep { font-size: .625rem; opacity: .5; }

    /* ── Header Actions ── */
    .cust-header-actions { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
    .cust-badge-count {
        display: inline-flex; align-items: center; gap: .5rem;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        color: var(--clr-green); font-size: .8125rem; font-weight: 600;
        padding: .5rem .875rem; border-radius: 100px;
    }
    .cust-badge-count .dot {
        width: 7px; height: 7px; background: #34d399;
        border-radius: 50%; animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.3)} }

    .btn-primary {
        display: inline-flex; align-items: center; gap: .5rem;
        background: var(--clr-primary); color: #fff; font-size: .875rem; font-weight: 600;
        padding: .625rem 1.25rem; border-radius: var(--radius-sm); border: none;
        cursor: pointer; text-decoration: none; transition: background .15s, transform .1s, box-shadow .15s;
        box-shadow: 0 2px 8px rgba(79,70,229,.25);
    }
    .btn-primary:hover { background: var(--clr-primary-hover); box-shadow: 0 4px 14px rgba(79,70,229,.35); transform: translateY(-1px); }

    /* ── Stats Grid ── */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius);
        padding: 1.375rem 1.5rem;
        box-shadow: var(--shadow-sm);
        display: flex; align-items: center; justify-content: space-between;
        transition: box-shadow .2s, transform .2s;
        position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        border-radius: var(--radius) var(--radius) 0 0;
    }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .stat-card.blue::before { background: linear-gradient(90deg, #6366f1, #818cf8); }
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    .stat-info { flex: 1; }
    .stat-label { font-size: .8rem; font-weight: 600; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: .06em; margin: 0 0 .375rem; }
    .stat-value { font-size: 2rem; font-weight: 700; color: var(--clr-text); line-height: 1; margin: 0 0 .5rem; letter-spacing: -.03em; }
    .stat-meta { display: flex; align-items: center; gap: .375rem; font-size: .75rem; font-weight: 500; }
    .stat-meta.blue { color: #6366f1; }
    .stat-meta.green { color: var(--clr-green); }
    .stat-meta.purple { color: var(--clr-purple); }
    .stat-meta.orange { color: var(--clr-orange); }

    .stat-icon {
        width: 3rem; height: 3rem; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 1.125rem;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4f46e5; }
    .stat-icon.green { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
    .stat-icon.purple { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
    .stat-icon.orange { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }

    /* ── Table Card ── */
    .table-card {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .table-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--clr-border);
        background: #fafafa;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem;
    }
    .table-card-title { font-size: 1rem; font-weight: 700; color: var(--clr-text); margin: 0; }
    .table-filters { display: flex; align-items: center; gap: .75rem; }
    .filter-chip {
        display: inline-flex; align-items: center; gap: .375rem;
        font-size: .75rem; color: var(--clr-text-muted); font-weight: 500;
        background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 100px;
        padding: .3rem .75rem;
    }

    /* ── Responsive Table Wrapper ── */
    .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table.cust-table { width: 100%; border-collapse: collapse; min-width: 720px; }
    table.cust-table thead tr { border-bottom: 1px solid var(--clr-border); }
    table.cust-table thead th {
        padding: .875rem 1.25rem;
        text-align: left; font-size: .75rem; font-weight: 700;
        color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: .07em;
        background: #fafafa; white-space: nowrap;
    }
    table.cust-table thead th:last-child { text-align: right; }
    table.cust-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background .12s;
    }
    table.cust-table tbody tr:last-child { border-bottom: none; }
    table.cust-table tbody tr:hover { background: #f9fafb; }
    table.cust-table td { padding: 1rem 1.25rem; vertical-align: middle; }

    /* ── Customer Avatar Cell ── */
    .cust-avatar {
        width: 2.75rem; height: 2.75rem; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: .875rem; font-weight: 800; letter-spacing: -.01em;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4338ca;
    }
    .cust-name-cell { display: flex; align-items: center; gap: .875rem; }
    .cust-name { font-size: .9rem; font-weight: 700; color: var(--clr-text); margin: 0 0 .2rem; }
    .cust-sub { font-size: .75rem; color: var(--clr-text-muted); margin: 0; display: flex; align-items: center; gap: .25rem; }
    .cust-id-tag {
        display: inline-block; font-size: .6875rem; font-weight: 600;
        color: #9ca3af; background: #f3f4f6; border-radius: 4px;
        padding: .1rem .375rem; margin-top: .25rem;
    }

    /* ── Contact Cell ── */
    .contact-item { display: flex; align-items: center; gap: .5rem; font-size: .8375rem; color: var(--clr-text); margin-bottom: .25rem; }
    .contact-item:last-child { margin-bottom: 0; }
    .contact-item i { width: 1rem; text-align: center; color: #9ca3af; font-size: .75rem; }
    .contact-email { color: var(--clr-text-muted); font-size: .8rem; }

    /* ── Location Cell ── */
    .loc-city { font-size: .875rem; font-weight: 600; color: var(--clr-text); display: flex; align-items: center; gap: .375rem; }
    .loc-state { font-size: .75rem; color: var(--clr-text-muted); padding-left: 1.125rem; margin-top: .15rem; }
    .loc-none { font-size: .8125rem; color: #d1d5db; }

    /* ── Business Cell ── */
    .biz-type { font-size: .875rem; font-weight: 600; color: var(--clr-text); margin-bottom: .3rem; }

    /* ── Badges ── */
    .badge {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .7rem; font-weight: 700; letter-spacing: .04em;
        padding: .275rem .625rem; border-radius: 100px;
    }
    .badge-active { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-inactive { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }
    .badge-gst { background: #f0fdf4; color: #16a34a; border: 1px solid #86efac; }
    .badge-no-gst { background: #f9fafb; color: #9ca3af; border: 1px solid #e5e7eb; }
    .badge .dot { width: 5px; height: 5px; border-radius: 50%; }
    .badge-active .dot { background: #22c55e; animation: pulse 2s infinite; }
    .badge-inactive .dot { background: #9ca3af; }

    /* ── Action Buttons ── */
    .action-cell { display: flex; align-items: center; justify-content: flex-end; gap: .5rem; }
    .btn-action {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .75rem; font-weight: 600; padding: .4rem .75rem;
        border-radius: 6px; border: 1px solid; cursor: pointer; text-decoration: none;
        transition: all .15s;
    }
    .btn-view { background: #fff; border-color: #e5e7eb; color: #374151; }
    .btn-view:hover { background: #f9fafb; border-color: #d1d5db; }
    .btn-edit { background: var(--clr-primary-light); border-color: #c7d2fe; color: var(--clr-primary); }
    .btn-edit:hover { background: #e0e7ff; border-color: #a5b4fc; }

    /* ── Pagination ── */
    .table-pagination {
        padding: 1rem 1.5rem; border-top: 1px solid var(--clr-border); background: #fafafa;
    }

    /* ── Empty State ── */
    .empty-state {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); box-shadow: var(--shadow-sm);
        text-align: center; padding: 4rem 2rem;
    }
    .empty-icon-wrap {
        width: 5rem; height: 5rem; margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        border-radius: 20px; display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; color: #4f46e5;
    }
    .empty-title { font-size: 1.375rem; font-weight: 800; color: var(--clr-text); margin: 0 0 .625rem; }
    .empty-body { font-size: .9rem; color: var(--clr-text-muted); max-width: 28rem; margin: 0 auto 1.75rem; line-height: 1.6; }
    .empty-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }

    /* .btn-secondary — unused (sample data button commented out)
    .btn-secondary {
        display: inline-flex; align-items: center; gap: .5rem;
        background: linear-gradient(135deg, #059669, #10b981); color: #fff;
        font-size: .875rem; font-weight: 600; padding: .75rem 1.375rem;
        border-radius: var(--radius-sm); border: none; cursor: pointer; text-decoration: none;
        transition: filter .15s, transform .1s; box-shadow: 0 2px 8px rgba(5,150,105,.25);
    }
    .btn-secondary:hover { filter: brightness(1.08); transform: translateY(-1px); }
    */
</style>

<div class="cust-page">

    {{-- ── Header ── --}}
    <div class="cust-header">
        <div class="cust-header-inner">
            <div>
                <nav class="cust-breadcrumb">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                    <span class="cust-breadcrumb-sep">›</span>
                    <span>Customers</span>
                </nav>
                <h1 class="cust-header-title">Customers</h1>
                <p class="cust-header-sub">Manage your customer relationships and contacts</p>
            </div>
            <div class="cust-header-actions">
                <span class="cust-badge-count">
                    <span class="dot"></span>
                    {{ $customers->total() }} Total
                </span>
                <a href="{{ route('customers.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    New Customer
                </a>
            </div>
        </div>
    </div>

    @if($customers->count() > 0)

        {{-- ── Stats ── --}}
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-info">
                    <p class="stat-label">Total Customers</p>
                    <p class="stat-value">{{ $customers->total() }}</p>
                    <div class="stat-meta blue"><i class="fas fa-users"></i> All contacts</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            </div>

            <div class="stat-card green">
                <div class="stat-info">
                    <p class="stat-label">Active</p>
                    <p class="stat-value">{{ $customers->where('is_active', true)->count() }}</p>
                    <div class="stat-meta green"><i class="fas fa-check-circle"></i> Currently active</div>
                </div>
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            </div>

            <div class="stat-card purple">
                <div class="stat-info">
                    <p class="stat-label">With GST</p>
                    <p class="stat-value">{{ $customers->whereNotNull('gstin')->count() }}</p>
                    <div class="stat-meta purple"><i class="fas fa-file-invoice"></i> GST registered</div>
                </div>
                <div class="stat-icon purple"><i class="fas fa-file-invoice"></i></div>
            </div>

            <div class="stat-card orange">
                <div class="stat-info">
                    <p class="stat-label">Business Type</p>
                    <p class="stat-value">{{ $customers->where('customer_type', 'business')->count() }}</p>
                    <div class="stat-meta orange"><i class="fas fa-building"></i> B2B customers</div>
                </div>
                <div class="stat-icon orange"><i class="fas fa-building"></i></div>
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="table-card">
            <div class="table-card-head">
                <h3 class="table-card-title">Customer Directory</h3>
                <div class="table-filters">
                    <span class="filter-chip"><i class="fas fa-filter"></i> All customers</span>
                    <span class="filter-chip"><i class="fas fa-sort-alpha-down"></i> A–Z</span>
                </div>
            </div>

            <div class="table-scroll">
                <table class="cust-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact Info</th>
                            <th>Location</th>
                            <th>Business</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            {{-- Customer col --}}
                            <td>
                                <div class="cust-name-cell">
                                    <div class="cust-avatar">{{ strtoupper(substr($customer->name, 0, 2)) }}</div>
                                    <div>
                                        <p class="cust-name">{{ $customer->name }}</p>
                                        @if($customer->contact_person)
                                            <p class="cust-sub">
                                                <i class="fas fa-user" style="font-size:.65rem;color:#9ca3af"></i>
                                                {{ $customer->contact_person }}
                                            </p>
                                        @endif
                                        <span class="cust-id-tag">#{{ $customer->id }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Contact col --}}
                            <td>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    {{ $customer->phone }}
                                </div>
                                @if($customer->email)
                                <div class="contact-item contact-email">
                                    <i class="fas fa-envelope"></i>
                                    {{ $customer->email }}
                                </div>
                                @endif
                            </td>

                            {{-- Location col --}}
                            <td>
                                @if($customer->city || $customer->state)
                                    @if($customer->city)
                                        <div class="loc-city"><i class="fas fa-map-marker-alt" style="color:#9ca3af;font-size:.75rem"></i> {{ $customer->city }}</div>
                                    @endif
                                    @if($customer->state)
                                        <div class="loc-state">{{ $customer->state }}</div>
                                    @endif
                                @else
                                    <span class="loc-none">—</span>
                                @endif
                            </td>

                            {{-- Business col --}}
                            <td>
                                <div class="biz-type">{{ ucfirst($customer->customer_type ?? 'business') }}</div>
                                @if($customer->gstin)
                                    <span class="badge badge-gst">
                                        <i class="fas fa-certificate" style="font-size:.6rem"></i>
                                        {{ substr($customer->gstin, 0, 6) }}…
                                    </span>
                                @else
                                    <span class="badge badge-no-gst">No GST</span>
                                @endif
                            </td>

                            {{-- Status col --}}
                            <td>
                                @if($customer->is_active)
                                    <span class="badge badge-active"><span class="dot"></span> Active</span>
                                @else
                                    <span class="badge badge-inactive"><span class="dot"></span> Inactive</span>
                                @endif
                            </td>

                            {{-- Actions col --}}
                            <td>
                                <div class="action-cell">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn-action btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn-action btn-edit">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
            <div class="table-pagination">
                {{ $customers->links() }}
            </div>
            @endif
        </div>

    @else
        {{-- ── Empty State ── --}}
        <div class="empty-state">
            <div class="empty-icon-wrap"><i class="fas fa-users"></i></div>
            <h3 class="empty-title">No Customers Yet</h3>
            <p class="empty-body">
                Start building your customer base. Add your first customer to begin sending quotes and invoices.
            </p>
            <div class="empty-actions">
                {{-- @php $customerCount = \App\Models\Customer::where('business_id', auth()->user()->business_id)->count(); @endphp
                @if($customerCount < 2)
                    <form action="{{ route('business.load-sample-data') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-secondary">
                            <i class="fas fa-magic"></i> Add Sample Data
                        </button>
                    </form>
                @endif --}}
                <a href="{{ route('customers.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Add Your First Customer
                </a>
            </div>
        </div>
    @endif

</div>
@endsection