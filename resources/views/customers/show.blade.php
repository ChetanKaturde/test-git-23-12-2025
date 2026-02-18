@extends('layouts.app')
@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
<style>
    /* ── Variables (same palette as index) ── */
    :root {
        --clr-primary: #4f46e5;
        --clr-primary-hover: #4338ca;
        --clr-primary-light: #eef2ff;
        --clr-green: #059669;
        --clr-green-light: #d1fae5;
        --clr-purple: #7c3aed;
        --clr-orange: #d97706;
        --clr-red: #dc2626;
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

    .show-page { background: var(--clr-bg); padding: 1.5rem; }
    @media (min-width: 768px) { .show-page { padding: 2rem; } }

    /* ── Breadcrumb / Header ── */
    .show-header {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius);
        padding: 1.75rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
    }
    .show-header-top {
        display: flex; flex-direction: column; gap: 1.25rem;
    }
    @media (min-width: 1024px) {
        .show-header-top { flex-direction: row; align-items: flex-start; justify-content: space-between; }
    }

    .breadcrumb { display: flex; align-items: center; gap: .375rem; font-size: .8125rem; color: var(--clr-text-muted); margin-bottom: .625rem; }
    .breadcrumb a { color: var(--clr-text-muted); text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: var(--clr-primary); }
    .breadcrumb-sep { font-size: .625rem; opacity: .45; }

    .customer-identity { display: flex; align-items: center; gap: 1.125rem; }
    .customer-avatar-lg {
        width: 4rem; height: 4rem; border-radius: 16px; flex-shrink: 0;
        background: linear-gradient(135deg, #c7d2fe, #a5b4fc);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; font-weight: 900; color: #3730a3; letter-spacing: -.02em;
        box-shadow: 0 4px 12px rgba(99,102,241,.2);
    }
    .customer-name-h1 { font-size: 1.625rem; font-weight: 700; color: var(--clr-text); margin: 0 0 .2rem; letter-spacing: -.02em; }
    .customer-tagline { font-size: .875rem; color: var(--clr-text-muted); margin: 0; }

    .tag-row { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .875rem; }
    .tag {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .75rem; font-weight: 700; letter-spacing: .04em;
        padding: .325rem .75rem; border-radius: 100px;
    }
    .tag-active { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .tag-inactive { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }
    .tag-gst { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
    .tag-type { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .tag .dot { width: 6px; height: 6px; border-radius: 50%; }
    .tag-active .dot { background: #22c55e; animation: pulse 2s infinite; }

    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.35)} }

    /* ── Header Buttons ── */
    .hdr-actions { display: flex; flex-wrap: wrap; gap: .625rem; align-items: flex-start; }
    .btn {
        display: inline-flex; align-items: center; gap: .5rem;
        font-size: .8125rem; font-weight: 600; padding: .5625rem 1.125rem;
        border-radius: var(--radius-sm); text-decoration: none; cursor: pointer;
        border: 1px solid; transition: all .15s;
    }
    .btn-back { background: #fff; border-color: var(--clr-border); color: #374151; }
    .btn-back:hover { background: #f9fafb; }
    .btn-edit { background: var(--clr-primary); border-color: var(--clr-primary); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.2); }
    .btn-edit:hover { background: var(--clr-primary-hover); box-shadow: 0 4px 14px rgba(79,70,229,.3); transform: translateY(-1px); }
    .btn-quote { background: #059669; border-color: #059669; color: #fff; box-shadow: 0 2px 8px rgba(5,150,105,.2); }
    .btn-quote:hover { background: #047857; transform: translateY(-1px); }

    /* ── Stats Grid ── */
    .stats-grid {
        display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); padding: 1.375rem 1.5rem;
        box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between;
        transition: box-shadow .2s, transform .2s; position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--radius) var(--radius) 0 0;
    }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .stat-card.blue::before  { background: linear-gradient(90deg, #6366f1, #818cf8); }
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card.purple::before{ background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .stat-card.orange::before{ background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .stat-info { flex: 1; }
    .stat-label { font-size: .75rem; font-weight: 700; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: .06em; margin: 0 0 .35rem; }
    .stat-value { font-size: 1.875rem; font-weight: 700; color: var(--clr-text); line-height: 1; margin: 0 0 .45rem; letter-spacing: -.03em; }
    .stat-meta { font-size: .725rem; font-weight: 600; display: flex; align-items: center; gap: .35rem; }
    .stat-meta.blue   { color: #6366f1; } .stat-meta.green  { color: var(--clr-green); }
    .stat-meta.purple { color: var(--clr-purple); } .stat-meta.orange { color: var(--clr-orange); }
    .stat-icon { width: 2.875rem; height: 2.875rem; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1rem; }
    .stat-icon.blue   { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4f46e5; }
    .stat-icon.green  { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
    .stat-icon.purple { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
    .stat-icon.orange { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }

    /* ── Two-column layout ── */
    .content-grid {
        display: grid; grid-template-columns: 1fr; gap: 1.5rem;
    }
    @media (min-width: 1024px) {
        .content-grid { grid-template-columns: 1fr 340px; }
    }

    /* ── Section Cards ── */
    .section-card {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden;
    }
    .section-card + .section-card { margin-top: 1.25rem; }
    .section-head {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--clr-border);
        display: flex; align-items: center; justify-content: space-between;
        background: #fafafa;
    }
    .section-title { font-size: .9625rem; font-weight: 700; color: var(--clr-text); margin: 0; }
    .section-body { padding: 1.5rem; }
    .section-icon { font-size: 1.125rem; color: #d1d5db; }

    /* ── Financial Summary ── */
    .fin-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;
    }
    @media (min-width: 768px) { .fin-grid { grid-template-columns: repeat(4, 1fr); } }
    .fin-block {
        border-radius: 10px; padding: 1rem 1.125rem; border: 1px solid;
    }
    .fin-block.g { background: #f0fdf4; border-color: #bbf7d0; }
    .fin-block.o { background: #fffbeb; border-color: #fcd34d; }
    .fin-block.b { background: #eff6ff; border-color: #bfdbfe; }
    .fin-block.p { background: #f5f3ff; border-color: #ddd6fe; }
    .fin-val { font-size: 1.5rem; font-weight: 900; margin: 0 0 .25rem; letter-spacing: -.02em; }
    .fin-block.g .fin-val { color: #065f46; }
    .fin-block.o .fin-val { color: #92400e; }
    .fin-block.b .fin-val { color: #1e3a8a; }
    .fin-block.p .fin-val { color: #4c1d95; }
    .fin-lbl { font-size: .775rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
    .fin-block.g .fin-lbl { color: #059669; }
    .fin-block.o .fin-lbl { color: #b45309; }
    .fin-block.b .fin-lbl { color: #2563eb; }
    .fin-block.p .fin-lbl { color: #7c3aed; }

    /* Outstanding invoice rows */
    .overdue-title { font-size: .8375rem; font-weight: 700; color: var(--clr-text); margin-bottom: .75rem; }
    .overdue-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: .75rem 1rem; background: #fafafa; border: 1px solid #f0f0f0;
        border-radius: 10px; margin-bottom: .5rem;
    }
    .overdue-row:last-child { margin-bottom: 0; }
    .overdue-num { font-size: .875rem; font-weight: 700; color: var(--clr-text); }
    .overdue-due { font-size: .75rem; color: var(--clr-text-muted); margin-top: .2rem; }
    .badge-overdue { font-size: .675rem; font-weight: 700; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 100px; padding: .2rem .5rem; }
    .overdue-amount { font-size: .875rem; font-weight: 800; color: #d97706; }

    /* ── Detail Grid (contact / business) ── */
    .detail-two-col {
        display: grid; grid-template-columns: 1fr; gap: 2rem;
    }
    @media (min-width: 640px) { .detail-two-col { grid-template-columns: repeat(2, 1fr); } }

    .detail-group-title {
        font-size: .8rem; font-weight: 700; color: var(--clr-text-muted);
        text-transform: uppercase; letter-spacing: .07em;
        display: flex; align-items: center; gap: .5rem;
        margin: 0 0 1rem; padding-bottom: .625rem; border-bottom: 1px solid var(--clr-border);
    }
    .detail-group-title i { font-size: .875rem; }

    .detail-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: .625rem 0; border-bottom: 1px solid #f3f4f6;
        gap: .75rem;
    }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-lbl { font-size: .8125rem; color: var(--clr-text-muted); display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
    .detail-lbl i { width: 1rem; text-align: center; font-size: .75rem; color: #9ca3af; }
    .detail-val { font-size: .8375rem; font-weight: 700; color: var(--clr-text); text-align: right; word-break: break-word; }

    .status-pill {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .725rem; font-weight: 700; padding: .3rem .7rem; border-radius: 100px;
    }
    .status-pill.active { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .status-pill.inactive { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }
    .status-pill .dot { width: 5px; height: 5px; border-radius: 50%; }
    .status-pill.active .dot { background: #22c55e; animation: pulse 2s infinite; }
    .status-pill.inactive .dot { background: #9ca3af; }

    /* ── Address Block ── */
    .address-divider { border-top: 1px solid var(--clr-border); margin-top: 1.5rem; padding-top: 1.5rem; }
    .address-label {
        font-size: .8rem; font-weight: 700; color: var(--clr-text-muted);
        text-transform: uppercase; letter-spacing: .07em;
        display: flex; align-items: center; gap: .5rem; margin-bottom: .875rem;
    }
    .address-box {
        background: #f9fafb; border: 1px solid var(--clr-border);
        border-radius: 10px; padding: 1rem 1.25rem;
        font-size: .875rem; color: var(--clr-text); line-height: 1.7;
    }

    /* ── Sidebar ── */
    .sidebar-stack { display: flex; flex-direction: column; gap: 1.25rem; }

    /* Quick actions sidebar */
    .quick-actions { display: flex; flex-direction: column; gap: .625rem; }
    .btn-qa {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        font-size: .8375rem; font-weight: 700; padding: .6875rem 1rem;
        border-radius: var(--radius-sm); text-decoration: none; border: none;
        cursor: pointer; transition: all .15s;
    }
    .btn-qa.primary { background: var(--clr-primary); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.2); }
    .btn-qa.primary:hover { background: var(--clr-primary-hover); transform: translateY(-1px); }

    /* Activity list (work orders / invoices) */
    .activity-list { display: flex; flex-direction: column; gap: .625rem; }
    .activity-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: .75rem 1rem; background: #fafafa; border: 1px solid #f0f0f0;
        border-radius: 10px; gap: .75rem;
    }
    .activity-main { flex: 1; min-width: 0; }
    .activity-title { font-size: .8375rem; font-weight: 700; color: var(--clr-text); margin: 0 0 .2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .activity-sub { font-size: .75rem; color: var(--clr-text-muted); margin: 0; }
    .activity-sub2 { font-size: .7rem; color: #9ca3af; margin: .15rem 0 0; }
    .activity-badge {
        font-size: .675rem; font-weight: 700; padding: .275rem .575rem; border-radius: 100px;
        white-space: nowrap; flex-shrink: 0;
    }
    .ab-completed { background: #dcfce7; color: #15803d; }
    .ab-in_progress{ background: #dbeafe; color: #1d4ed8; }
    .ab-pending { background: #fef9c3; color: #92400e; }
    .ab-paid { background: #dcfce7; color: #15803d; }
    .ab-sent { background: #dbeafe; color: #1d4ed8; }
    .ab-draft { background: #fef9c3; color: #92400e; }

    .see-all-link {
        display: flex; align-items: center; justify-content: center; gap: .35rem;
        font-size: .8rem; font-weight: 700; color: var(--clr-primary);
        text-decoration: none; padding: .75rem; margin-top: .25rem;
        border-top: 1px solid var(--clr-border);
    }
    .see-all-link:hover { color: var(--clr-primary-hover); }

    .sidebar-count { font-size: .8rem; font-weight: 600; color: var(--clr-text-muted); background: #f3f4f6; border-radius: 100px; padding: .2rem .6rem; }
</style>

<div class="show-page">

    {{-- ── Header ── --}}
    <div class="show-header">
        <div class="show-header-top">
            <div>
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                    <span class="breadcrumb-sep">›</span>
                    <a href="{{ route('customers.index') }}">Customers</a>
                    <span class="breadcrumb-sep">›</span>
                    <span>{{ $customer->name }}</span>
                </nav>
                <div class="customer-identity">
                    <div class="customer-avatar-lg">{{ strtoupper(substr($customer->name, 0, 2)) }}</div>
                    <div>
                        <h1 class="customer-name-h1">{{ $customer->name }}</h1>
                        <p class="customer-tagline">Customer information and business activity</p>
                    </div>
                </div>
                <div class="tag-row">
                    @if($customer->is_active)
                        <span class="tag tag-active"><span class="dot"></span> Active</span>
                    @else
                        <span class="tag tag-inactive"><span class="dot"></span> Inactive</span>
                    @endif
                    @if($customer->gstin)
                        <span class="tag tag-gst"><i class="fas fa-certificate" style="font-size:.6rem"></i> GST Registered</span>
                    @endif
                    <span class="tag tag-type"><i class="fas fa-user" style="font-size:.6rem"></i> {{ ucfirst($customer->customer_type ?? 'business') }}</span>
                </div>
            </div>
            <div class="hdr-actions">
                <a href="{{ route('customers.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
                @if(auth()->user()->canViewModule('quotations'))
                    <a href="{{ route('quotations.create', ['customer' => $customer->id]) }}" class="btn btn-quote">
                        <i class="fas fa-plus"></i> Create Quote
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Overview Stats ── --}}
    <div class="stats-grid">
        @if(auth()->user()->business->subscription_tier === 'full_erp')
        {{-- <div class="stat-card blue">
            <div class="stat-info">
                <p class="stat-label">Work Orders</p>
                <p class="stat-value">{{ $workOrders->count() }}</p>
                <div class="stat-meta blue"><i class="fas fa-clipboard-list"></i> Total projects</div>
            </div>
            <div class="stat-icon blue"><i class="fas fa-clipboard-list"></i></div>
        </div> --}}
        @endif
        <div class="stat-card green">
            <div class="stat-info">
                <p class="stat-label">Invoices</p>
                <p class="stat-value">{{ $invoices->count() }}</p>
                <div class="stat-meta green"><i class="fas fa-file-invoice"></i> Billing history</div>
            </div>
            <div class="stat-icon green"><i class="fas fa-file-invoice"></i></div>
        </div>
        <div class="stat-card purple">
            <div class="stat-info">
                <p class="stat-label">Total Revenue</p>
                <p class="stat-value">₹{{ number_format($invoices->sum('total_amount'), 0) }}</p>
                <div class="stat-meta purple"><i class="fas fa-chart-line"></i> Lifetime value</div>
            </div>
            <div class="stat-icon purple"><i class="fas fa-chart-line"></i></div>
        </div>
        <div class="stat-card orange">
            <div class="stat-info">
                <p class="stat-label">Outstanding</p>
                <p class="stat-value">₹{{ number_format($invoices->where('status', '!=', 'paid')->sum('total_amount'), 0) }}</p>
                <div class="stat-meta orange"><i class="fas fa-clock"></i> Pending payment</div>
            </div>
            <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    {{-- ── Financial Summary ── --}}
    <div class="section-card" style="margin-bottom:1.5rem">
        <div class="section-head">
            <h3 class="section-title">Financial Summary</h3>
            <i class="fas fa-chart-bar section-icon"></i>
        </div>
        <div class="section-body">
            <div class="fin-grid">
                <div class="fin-block g">
                    <div class="fin-val">₹{{ number_format($invoices->where('status', 'paid')->sum('total_amount'), 0) }}</div>
                    <div class="fin-lbl">Total Paid</div>
                </div>
                <div class="fin-block o">
                    <div class="fin-val">₹{{ number_format($invoices->where('status', '!=', 'paid')->sum('total_amount'), 0) }}</div>
                    <div class="fin-lbl">Outstanding</div>
                </div>
                <div class="fin-block b">
                    @php
                        $paidInvoices = $invoices->where('status', 'paid')->whereNotNull('paid_date')->whereNotNull('issue_date');
                        $avgDays = 0;
                        if ($paidInvoices->count() > 0) {
                            $totalDays = $paidInvoices->sum(fn($i) => $i->paid_date ? $i->paid_date->diffInDays($i->issue_date) : 0);
                            $avgDays = round($totalDays / $paidInvoices->count());
                        }
                    @endphp
                    <div class="fin-val">{{ $avgDays }}</div>
                    <div class="fin-lbl">Avg. Pay Days</div>
                </div>
                <div class="fin-block p">
                    <div class="fin-val">{{ $invoices->where('issue_date', '>=', now()->subDays(30))->count() }}</div>
                    <div class="fin-lbl">Recent Invoices (30d)</div>
                </div>
            </div>

            @if($invoices->where('status', '!=', 'paid')->count() > 0)
                <div class="overdue-title">Outstanding Invoices</div>
                @foreach($invoices->where('status', '!=', 'paid')->take(5) as $invoice)
                    <div class="overdue-row">
                        <div>
                            <div class="overdue-num">
                                {{ $invoice->invoice_number }}
                                @if($invoice->due_date && $invoice->due_date->isPast())
                                    <span class="badge-overdue" style="margin-left:.35rem">{{ $invoice->due_date->diffInDays(now()) }}d overdue</span>
                                @endif
                            </div>
                            <div class="overdue-due">Due: {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'No due date' }}</div>
                        </div>
                        <span class="overdue-amount">₹{{ number_format($invoice->total_amount, 0) }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ── Main Two-Col Grid ── --}}
    <div class="content-grid">
        {{-- Left: Customer Info --}}
        <div>
            <div class="section-card">
                <div class="section-head">
                    <h3 class="section-title">Customer Information</h3>
                    <i class="fas fa-user-circle section-icon"></i>
                </div>
                <div class="section-body">
                    <div class="detail-two-col">
                        {{-- Contact Details --}}
                        <div>
                            <p class="detail-group-title">
                                <i class="fas fa-address-book" style="color:#6366f1"></i> Contact Details
                            </p>
                            <div class="detail-row">
                                <span class="detail-lbl"><i class="fas fa-phone"></i> Phone</span>
                                <span class="detail-val">{{ $customer->phone }}</span>
                            </div>
                            @if($customer->email)
                            <div class="detail-row">
                                <span class="detail-lbl"><i class="fas fa-envelope"></i> Email</span>
                                <span class="detail-val">{{ $customer->email }}</span>
                            </div>
                            @endif
                            @if($customer->contact_person)
                            <div class="detail-row">
                                <span class="detail-lbl"><i class="fas fa-user"></i> Contact Person</span>
                                <span class="detail-val">{{ $customer->contact_person }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Business Details --}}
                        <div>
                            <p class="detail-group-title">
                                <i class="fas fa-building" style="color:#059669"></i> Business Details
                            </p>
                            <div class="detail-row">
                                <span class="detail-lbl">Customer Type</span>
                                <span class="detail-val">{{ ucfirst($customer->customer_type ?? 'business') }}</span>
                            </div>
                            @if($customer->gstin)
                            <div class="detail-row">
                                <span class="detail-lbl">GSTIN</span>
                                <span class="detail-val">{{ $customer->gstin }}</span>
                            </div>
                            @endif
                            <div class="detail-row">
                                <span class="detail-lbl">Payment Terms</span>
                                <span class="detail-val">{{ ucfirst(str_replace('_', ' ', $customer->payment_terms ?? 'due_on_receipt')) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-lbl">Status</span>
                                <span>
                                    @if($customer->is_active)
                                        <span class="status-pill active"><span class="dot"></span> Active</span>
                                    @else
                                        <span class="status-pill inactive"><span class="dot"></span> Inactive</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($customer->address || $customer->city || $customer->state || $customer->pincode)
                    <div class="address-divider">
                        <p class="address-label"><i class="fas fa-map-marker-alt" style="color:#dc2626"></i> Address</p>
                        <div class="address-box">
                            @if($customer->address){{ $customer->address }}@endif
                            @if($customer->city){{ $customer->address ? ', ' : '' }}{{ $customer->city }}@endif
                            @if($customer->state){{ ($customer->address || $customer->city) ? ', ' : '' }}{{ $customer->state }}@endif
                            @if($customer->pincode){{ ($customer->address || $customer->city || $customer->state) ? ' – ' : '' }}{{ $customer->pincode }}@endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Sidebar --}}
        <div class="sidebar-stack">
            {{-- Quick Actions --}}
            <div class="section-card">
                <div class="section-head">
                    <h3 class="section-title">Quick Actions</h3>
                    <i class="fas fa-bolt" style="color:#f59e0b;font-size:1rem"></i>
                </div>
                <div class="section-body" style="padding:1.125rem 1.25rem">
                    <div class="quick-actions">
                        <a href="{{ route('quotations.create') }}?customer_id={{ $customer->id }}" class="btn-qa primary">
                            <i class="fas fa-file-alt"></i> Create Quotation
                        </a>
                    </div>
                </div>
            </div>

            @if($workOrders->count() > 0 && auth()->user()->business->subscription_tier === 'full_erp')
            {{-- Recent Work Orders --}}
            <div class="section-card">
                <div class="section-head">
                    <h3 class="section-title">Work Orders</h3>
                    <span class="sidebar-count">{{ $workOrders->count() }}</span>
                </div>
                <div class="section-body" style="padding:1rem 1.25rem">
                    <div class="activity-list">
                        @foreach($workOrders->take(5) as $wo)
                        <div class="activity-row">
                            <div class="activity-main">
                                <p class="activity-title">{{ $wo->work_order_number ?? 'WO-'.$wo->id }}</p>
                                <p class="activity-sub">{{ $wo->product_name ?? '—' }}</p>
                                <p class="activity-sub2">{{ $wo->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="activity-badge ab-{{ $wo->status }}">{{ ucfirst($wo->status) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @if($workOrders->count() > 5)
                    <a href="{{ route('work-orders.index') }}?customer={{ $customer->id }}" class="see-all-link">
                        View all {{ $workOrders->count() }} <i class="fas fa-arrow-right" style="font-size:.7rem"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if($invoices->count() > 0)
            {{-- Recent Invoices --}}
            <div class="section-card">
                <div class="section-head">
                    <h3 class="section-title">Recent Invoices</h3>
                    <span class="sidebar-count">{{ $invoices->count() }}</span>
                </div>
                <div class="section-body" style="padding:1rem 1.25rem">
                    <div class="activity-list">
                        @foreach($invoices->take(5) as $invoice)
                        <div class="activity-row">
                            <div class="activity-main">
                                <p class="activity-title">{{ $invoice->invoice_number }}</p>
                                <p class="activity-sub">₹{{ number_format($invoice->total_amount, 2) }}</p>
                                <p class="activity-sub2">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'No date' }}</p>
                            </div>
                            <span class="activity-badge ab-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @if($invoices->count() > 5)
                    <a href="{{ route('invoices.index') }}?customer={{ $customer->id }}" class="see-all-link">
                        View all {{ $invoices->count() }} <i class="fas fa-arrow-right" style="font-size:.7rem"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection