@extends('layouts.app')

@section('page-title', 'Quotation Details')

@section('content')
<style>
    :root {
        --clr-primary: #4f46e5;
        --clr-primary-hover: #4338ca;
        --clr-primary-light: #eef2ff;
        --clr-surface: #ffffff;
        --clr-bg: #f5f6fa;
        --clr-border: #e5e7eb;
        --clr-text: #111827;
        --clr-text-muted: #6b7280;
        --clr-text-light: #9ca3af;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
        --shadow-md: 0 4px 16px rgba(0,0,0,.07);
        --radius: 12px;
        --radius-sm: 8px;
    }

    .qshow-page { background: var(--clr-bg); padding: 1.25rem; }
    @media (min-width: 768px) { .qshow-page { padding: 1.75rem; } }

    /* ── Header Card ── */
    .qshow-header {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); padding: 1.5rem;
        box-shadow: var(--shadow-sm); margin-bottom: 1.25rem;
    }
    .qshow-header-inner {
        display: flex; flex-direction: column; gap: 1.125rem;
    }
    @media (min-width: 1024px) {
        .qshow-header-inner { flex-direction: row; align-items: flex-start; justify-content: space-between; }
    }

    .breadcrumb { display: flex; align-items: center; gap: .35rem; font-size: .8rem; color: var(--clr-text-muted); margin-bottom: .5rem; }
    .breadcrumb a { color: var(--clr-text-muted); text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: var(--clr-primary); }
    .breadcrumb-sep { opacity: .4; font-size: .6rem; }

    .qshow-identity { display: flex; align-items: center; gap: .875rem; margin-bottom: .875rem; }
    .qshow-icon {
        width: 2.875rem; height: 2.875rem; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.125rem; color: #4338ca;
    }
    .qshow-number { font-size: 1.375rem; font-weight: 700; color: var(--clr-text); margin: 0; letter-spacing: -.015em; }

    .qshow-meta { display: flex; align-items: center; flex-wrap: wrap; gap: .75rem; }
    .cust-chip {
        display: flex; align-items: center; gap: .5rem;
    }
    .cust-chip-avatar {
        width: 1.75rem; height: 1.75rem; border-radius: 6px;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        display: flex; align-items: center; justify-content: center;
        font-size: .6875rem; font-weight: 700; color: #4338ca;
    }
    .cust-chip-name { font-size: .9rem; font-weight: 600; color: var(--clr-text); }

    /* Status badges */
    .badge {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .7125rem; font-weight: 600; padding: .3rem .7rem; border-radius: 100px;
    }
    .badge-converted { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-accepted  { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-draft, .badge-pending { background: #fef9c3; color: #92400e; border: 1px solid #fde68a; }
    .badge-sent { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }

    /* ── Header action buttons ── */
    .hdr-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: flex-start; }
    .btn {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8rem; font-weight: 600; padding: .5rem .9375rem;
        border-radius: var(--radius-sm); text-decoration: none; cursor: pointer;
        border: 1px solid; transition: all .15s; white-space: nowrap;
    }
    .btn-ghost { background: #f3f4f6; border-color: var(--clr-border); color: #374151; }
    .btn-ghost:hover { background: #e5e7eb; }
    .btn-indigo { background: var(--clr-primary); border-color: var(--clr-primary); color: #fff; box-shadow: 0 2px 6px rgba(79,70,229,.2); }
    .btn-indigo:hover { background: var(--clr-primary-hover); box-shadow: 0 3px 10px rgba(79,70,229,.28); transform: translateY(-1px); }
    .btn-red { background: #dc2626; border-color: #dc2626; color: #fff; box-shadow: 0 2px 6px rgba(220,38,38,.2); }
    .btn-red:hover { background: #b91c1c; transform: translateY(-1px); }
    .btn-green { background: #059669; border-color: #059669; color: #fff; box-shadow: 0 2px 6px rgba(5,150,105,.2); }
    .btn-green:hover { background: #047857; transform: translateY(-1px); }
    .btn-disabled { background: #f3f4f6; border-color: #e5e7eb; color: #9ca3af; cursor: not-allowed; }

    /* ── Content Grid ── */
    .qshow-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
    @media (min-width: 1024px) { .qshow-grid { grid-template-columns: 1fr 300px; } }

    /* ── Cards ── */
    .qcard {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden;
    }
    .qcard + .qcard { margin-top: 1.125rem; }
    .qcard-head {
        padding: .9375rem 1.375rem; border-bottom: 1px solid var(--clr-border);
        background: #fafafa; display: flex; align-items: center; justify-content: space-between;
    }
    .qcard-title { font-size: .9rem; font-weight: 700; color: var(--clr-text); margin: 0; }
    .qcard-meta { font-size: .775rem; color: var(--clr-text-muted); }
    .qcard-body { padding: 0; }

    /* ── Items Table ── */
    .items-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table.view-table { width: 100%; border-collapse: collapse; min-width: 640px; }
    table.view-table thead th {
        padding: .75rem 1.25rem; text-align: left; font-size: .7rem; font-weight: 700;
        color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: .07em;
        background: #fafafa; border-bottom: 1px solid var(--clr-border); white-space: nowrap;
    }
    table.view-table thead th.right { text-align: right; }
    table.view-table thead th.center { text-align: center; }
    table.view-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .1s; }
    table.view-table tbody tr:last-child { border-bottom: none; }
    table.view-table tbody tr:hover { background: #fafbff; }
    table.view-table td { padding: .9375rem 1.25rem; vertical-align: middle; }
    table.view-table td.center { text-align: center; }
    table.view-table td.right { text-align: right; }

    .item-icon {
        width: 2rem; height: 2rem; border-radius: 7px; flex-shrink: 0;
        background: #eff6ff; display: flex; align-items: center; justify-content: center;
        color: #3b82f6; font-size: .7rem;
    }
    .item-cell { display: flex; align-items: center; gap: .75rem; }
    .item-name { font-size: .875rem; font-weight: 600; color: var(--clr-text); }
    .item-desc { font-size: .75rem; color: var(--clr-text-muted); margin-top: .1rem; }
    .item-sku  { font-size: .7rem; color: var(--clr-text-light); margin-top: .1rem; }

    .pill {
        display: inline-flex; align-items: center;
        font-size: .725rem; font-weight: 600; padding: .225rem .6rem; border-radius: 100px;
    }
    .pill-qty    { background: #f3f4f6; color: #374151; }
    .pill-unit   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .pill-disc   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .pill-tax    { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

    .price-cell { font-size: .875rem; font-weight: 600; color: var(--clr-text); }
    .total-cell { font-size: .9rem; font-weight: 700; color: var(--clr-text); }

    /* ── Totals footer ── */
    .totals-section { padding: 1.125rem 1.375rem; border-top: 1px solid var(--clr-border); background: #fafafa; }
    .totals-wrap { max-width: 20rem; margin-left: auto; }
    .totals-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: .45rem 0; border-bottom: 1px solid #f0f0f0; font-size: .875rem;
    }
    .totals-row:last-child { border-bottom: none; }
    .totals-row .lbl { color: var(--clr-text-muted); font-weight: 500; }
    .totals-row .val { font-weight: 600; color: var(--clr-text); }
    .totals-row.discount .val { color: #059669; }
    .totals-row.grand {
        padding-top: .75rem; margin-top: .25rem;
        border-top: 2px solid var(--clr-border); border-bottom: none;
    }
    .totals-row.grand .lbl { font-size: .9375rem; font-weight: 700; color: var(--clr-text); }
    .totals-row.grand .val { font-size: 1.1875rem; font-weight: 700; color: var(--clr-primary); }

    /* ── Sidebar ── */
    .sidebar-stack { display: flex; flex-direction: column; gap: 1.125rem; }

    .detail-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: .5625rem 0; border-bottom: 1px solid #f3f4f6; gap: .75rem; font-size: .875rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-lbl { color: var(--clr-text-muted); font-weight: 500; flex-shrink: 0; }
    .detail-val { font-weight: 600; color: var(--clr-text); text-align: right; }

    .total-highlight {
        background: var(--clr-primary-light); border: 1px solid #c7d2fe;
        border-radius: 10px; padding: 1rem 1.125rem;
        display: flex; align-items: center; justify-content: space-between;
        margin-top: .875rem;
    }
    .total-highlight-lbl { font-size: .875rem; font-weight: 600; color: #3730a3; }
    .total-highlight-val { font-size: 1.25rem; font-weight: 700; color: var(--clr-primary); }

    /* Customer sidebar */
    .cust-profile { display: flex; align-items: center; gap: .875rem; margin-bottom: 1rem; }
    .cust-avatar-md {
        width: 2.5rem; height: 2.5rem; border-radius: 9px; flex-shrink: 0;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        display: flex; align-items: center; justify-content: center;
        font-size: .875rem; font-weight: 700; color: #3730a3;
    }
    .cust-profile-name { font-size: .9rem; font-weight: 700; color: var(--clr-text); margin: 0 0 .15rem; }
    .cust-profile-phone { font-size: .775rem; color: var(--clr-text-muted); margin: 0; }

    .cust-detail-row { display: flex; align-items: center; gap: .5rem; font-size: .8125rem; color: var(--clr-text-muted); margin-bottom: .4rem; }
    .cust-detail-row:last-of-type { margin-bottom: 0; }
    .cust-detail-row i { width: .875rem; text-align: center; font-size: .7rem; color: var(--clr-text-light); }

    .view-cust-link {
        display: flex; align-items: center; justify-content: center; gap: .35rem;
        font-size: .8125rem; font-weight: 600; color: var(--clr-primary); text-decoration: none;
        padding: .625rem; margin-top: .75rem; border-top: 1px solid var(--clr-border);
        transition: color .15s;
    }
    .view-cust-link:hover { color: var(--clr-primary-hover); }

    /* Notes card */
    .notes-body {
        padding: 1rem 1.375rem; background: #fafafa;
        border-radius: 0 0 var(--radius) var(--radius);
        font-size: .875rem; color: #374151; line-height: 1.65;
    }
</style>

<div class="qshow-page">
    @php $business = auth()->user()->business; @endphp

    {{-- ── Header ── --}}
    <div class="qshow-header">
        <div class="qshow-header-inner">
            <div>
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                    <span class="breadcrumb-sep">›</span>
                    <a href="{{ route('quotations.index') }}">Quotations</a>
                    <span class="breadcrumb-sep">›</span>
                    <span>{{ $quotation->number }}</span>
                </nav>
                <div class="qshow-identity">
                    <div class="qshow-icon"><i class="fas fa-file-alt"></i></div>
                    <h1 class="qshow-number">{{ $quotation->number }}</h1>
                </div>
                <div class="qshow-meta">
                    <div class="cust-chip">
                        <div class="cust-chip-avatar">{{ strtoupper(substr($quotation->customer->name, 0, 2)) }}</div>
                        <span class="cust-chip-name">{{ $quotation->customer->name }}</span>
                    </div>
                    {{-- Status badge --}}
                    @php $st = $quotation->status; @endphp
                    <span class="badge badge-{{ $st }}">
                        @if($st === 'converted')  <i class="fas fa-check-circle"></i>
                        @elseif($st === 'accepted') <i class="fas fa-thumbs-up"></i>
                        @else <i class="fas fa-edit"></i>
                        @endif
                        {{ ucfirst($st) }}
                    </span>
                    @if($quotation->isSent())
                        <span class="badge badge-sent">
                            <i class="fas fa-paper-plane"></i> Sent {{ $quotation->sent_at->format('M d') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="hdr-actions">
                <a href="{{ route('quotations.index') }}" class="btn btn-ghost">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                @if(!$quotation->isSent() && $quotation->status !== 'converted')
                    <form action="{{ route('quotations.mark-sent', $quotation) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-indigo">
                            <i class="fas fa-paper-plane"></i> Mark as Sent
                        </button>
                    </form>
                @endif

                <a href="{{ route('quotations.pdf', $quotation) }}" class="btn btn-red">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>

                @if($quotation->status !== 'converted' && (auth()->user()->isAdmin() || auth()->user()->hasPermission('edit_quotation')))
                    <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-ghost">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                @endif

                @if($quotation->status !== 'converted')
                    @if(auth()->user()->isAdmin() || ($canCreateInvoice && auth()->user()->hasPermission('convert_quotation_to_invoice')))
                        <form action="{{ route('quotations.convert', $quotation) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-green"
                                    onclick="return confirm('Convert to invoice?')">
                                <i class="fas fa-file-invoice"></i> Convert to Invoice
                            </button>
                        </form>
                    @else
                        <button disabled class="btn btn-disabled" title="{{ !$canCreateInvoice ? 'Upgrade to create more invoices' : 'No permission' }}">
                            <i class="fas fa-file-invoice"></i> Convert to Invoice
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- ── Main Grid ── --}}
    <div class="qshow-grid">
        {{-- Left: Items Table --}}
        <div>
            <div class="qcard">
                <div class="qcard-head">
                    <h3 class="qcard-title">Quotation Items</h3>
                    <span class="qcard-meta">{{ $quotation->items->count() }} items</span>
                </div>
                <div class="qcard-body">
                    <div class="items-scroll">
                        <table class="view-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="center">Qty</th>
                                    <th class="center">Unit</th>
                                    <th class="right">Unit Price</th>
                                    <th class="center">Disc.</th>
                                    <th class="center">Tax</th>
                                    <th class="right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->items as $item)
                                <tr>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-icon"><i class="fas fa-box"></i></div>
                                            <div>
                                                <div class="item-name">{{ $item->material->name ?? 'Item' }}</div>
                                                @if($item->description && $item->description !== ($item->material->name ?? ''))
                                                    <div class="item-desc">{{ $item->description }}</div>
                                                @endif
                                                @if($item->material)
                                                    <div class="item-sku">SKU: {{ $item->material->sku ?? 'N/A' }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <span class="pill pill-qty">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="center">
                                        <span class="pill pill-unit">{{ ucfirst($item->unit ?? 'piece') }}</span>
                                    </td>
                                    <td class="right">
                                        <span class="price-cell">₹{{ number_format($item->unit_price, 2) }}</span>
                                    </td>
                                    <td class="center">
                                        <span class="pill pill-disc">{{ $item->discount_percentage ?? 0 }}%</span>
                                    </td>
                                    <td class="center">
                                        <span class="pill pill-tax">{{ $item->tax_rate }}%</span>
                                    </td>
                                    <td class="right">
                                        <span class="total-cell">₹{{ number_format($item->total, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="totals-section">
                        <div class="totals-wrap">
                            <div class="totals-row">
                                <span class="lbl">Subtotal</span>
                                <span class="val">₹{{ number_format($quotation->subtotal, 2) }}</span>
                            </div>
                            @if($quotation->discount_amount > 0)
                            <div class="totals-row discount">
                                <span class="lbl">Discount</span>
                                <span class="val">−₹{{ number_format($quotation->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="totals-row">
                                <span class="lbl">Tax</span>
                                <span class="val">₹{{ number_format($quotation->tax_amount, 2) }}</span>
                            </div>
                            <div class="totals-row grand">
                                <span class="lbl">Total</span>
                                <span class="val">₹{{ number_format($quotation->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Sidebar --}}
        <div class="sidebar-stack">
            {{-- Details card --}}
            <div class="qcard">
                <div class="qcard-head">
                    <h3 class="qcard-title">Details</h3>
                    <i class="fas fa-info-circle" style="color:#9ca3af;font-size:.9rem"></i>
                </div>
                <div style="padding:.875rem 1.375rem">
                    <div class="detail-row">
                        <span class="detail-lbl">Valid Until</span>
                        <span class="detail-val">{{ $quotation->valid_until->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-lbl">Created</span>
                        <span class="detail-val">{{ $quotation->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($quotation->isSent())
                    <div class="detail-row">
                        <span class="detail-lbl">Sent Date</span>
                        <span class="detail-val">{{ $quotation->sent_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-lbl">Items</span>
                        <span class="detail-val">{{ $quotation->items->count() }}</span>
                    </div>
                    <div class="total-highlight">
                        <span class="total-highlight-lbl">Total Amount</span>
                        <span class="total-highlight-val">₹{{ number_format($quotation->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Customer card --}}
            <div class="qcard">
                <div class="qcard-head">
                    <h3 class="qcard-title">Customer</h3>
                    <i class="fas fa-user" style="color:#9ca3af;font-size:.9rem"></i>
                </div>
                <div style="padding:.875rem 1.375rem">
                    <div class="cust-profile">
                        <div class="cust-avatar-md">{{ strtoupper(substr($quotation->customer->name, 0, 2)) }}</div>
                        <div>
                            <p class="cust-profile-name">{{ $quotation->customer->name }}</p>
                            @if($quotation->customer->phone)
                                <p class="cust-profile-phone">{{ $quotation->customer->phone }}</p>
                            @endif
                        </div>
                    </div>
                    @if($quotation->customer->email)
                        <div class="cust-detail-row">
                            <i class="fas fa-envelope"></i>
                            {{ $quotation->customer->email }}
                        </div>
                    @endif
                    @if($quotation->customer->city || $quotation->customer->state)
                        <div class="cust-detail-row">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $quotation->customer->city }}{{ $quotation->customer->city && $quotation->customer->state ? ', ' : '' }}{{ $quotation->customer->state }}
                        </div>
                    @endif
                    <a href="{{ route('customers.show', $quotation->customer) }}" class="view-cust-link">
                        View Customer <i class="fas fa-arrow-right" style="font-size:.65rem"></i>
                    </a>
                </div>
            </div>

            {{-- Notes card --}}
            @if($quotation->notes)
            <div class="qcard">
                <div class="qcard-head">
                    <h3 class="qcard-title">Notes</h3>
                    <i class="fas fa-sticky-note" style="color:#9ca3af;font-size:.9rem"></i>
                </div>
                <div class="notes-body">{{ $quotation->notes }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection