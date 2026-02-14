@extends('layouts.app')

@section('page-title', 'Edit Quotation')

@section('content')
<style>
    :root {
        --clr-primary: #4f46e5;
        --clr-primary-hover: #4338ca;
        --clr-primary-light: #eef2ff;
        --clr-surface: #ffffff;
        --clr-bg: #f5f6fa;
        --clr-border: #e5e7eb;
        --clr-border-focus: #a5b4fc;
        --clr-text: #111827;
        --clr-text-muted: #6b7280;
        --clr-red: #dc2626;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
        --radius: 12px;
        --radius-sm: 8px;
    }

    .qform-page { background: var(--clr-bg); padding: 1.25rem; }
    @media (min-width: 768px) { .qform-page { padding: 1.75rem; } }

    .qform-header {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); padding: 1.375rem 1.5rem;
        box-shadow: var(--shadow-sm); margin-bottom: 1.25rem;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
    }
    .qform-title { font-size: 1.25rem; font-weight: 700; color: var(--clr-text); margin: 0 0 .25rem; }
    .qform-breadcrumb {
        display: flex; align-items: center; gap: .375rem;
        font-size: .8rem; color: var(--clr-text-muted); margin: 0;
    }
    .qform-breadcrumb a { color: var(--clr-text-muted); text-decoration: none; transition: color .15s; }
    .qform-breadcrumb a:hover { color: var(--clr-primary); }
    .qform-breadcrumb-sep { opacity: .4; font-size: .625rem; }

    .hdr-btns { display: flex; align-items: center; gap: .625rem; flex-wrap: wrap; }
    .btn-ghost {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8125rem; font-weight: 600; padding: .5rem 1rem;
        background: #f3f4f6; border: 1px solid var(--clr-border); color: #374151;
        border-radius: var(--radius-sm); text-decoration: none; transition: all .15s;
    }
    .btn-ghost:hover { background: #e5e7eb; }

    .qform-card {
        background: var(--clr-surface); border: 1px solid var(--clr-border);
        border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden;
    }

    .qsection { padding: 1.5rem; border-bottom: 1px solid #f3f4f6; }
    .qsection:last-child { border-bottom: none; }
    .qsection-title {
        font-size: .8rem; font-weight: 700; color: var(--clr-text-muted);
        text-transform: uppercase; letter-spacing: .07em;
        display: flex; align-items: center; gap: .5rem; margin: 0 0 1.125rem;
    }

    .form-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.125rem; }
    @media (min-width: 640px) { .form-grid-2 { grid-template-columns: repeat(2, 1fr); } }

    .field-label {
        display: block; font-size: .8125rem; font-weight: 600;
        color: #374151; margin-bottom: .4rem;
    }
    .field-label .req { color: var(--clr-red); margin-left: .15rem; }
    .field-input, .field-select, .field-textarea {
        width: 100%; font-size: .875rem; color: var(--clr-text);
        border: 1px solid var(--clr-border); border-radius: var(--radius-sm);
        padding: .5625rem .875rem; background: #fff;
        transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
    }
    .field-input:focus, .field-select:focus, .field-textarea:focus {
        outline: none; border-color: var(--clr-border-focus);
        box-shadow: 0 0 0 3px rgba(165,180,252,.2);
    }
    .field-select { height: 2.5rem; }
    .field-textarea { resize: vertical; min-height: 5rem; line-height: 1.6; }
    .field-error { font-size: .775rem; color: var(--clr-red); margin-top: .3rem; display: flex; align-items: center; gap: .25rem; }

    .items-head {
        padding: 1rem 1.5rem; border-bottom: 1px solid var(--clr-border);
        background: #fafafa;
        display: flex; align-items: center; justify-content: space-between;
    }
    .items-head-title { font-size: .9375rem; font-weight: 700; color: var(--clr-text); }
    .btn-add-item {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8125rem; font-weight: 600; padding: .5rem 1rem;
        background: var(--clr-primary); color: #fff; border: none;
        border-radius: var(--radius-sm); cursor: pointer;
        box-shadow: 0 2px 6px rgba(79,70,229,.2); transition: all .15s;
    }
    .btn-add-item:hover { background: var(--clr-primary-hover); }

    .items-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table.items-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    table.items-table thead th {
        padding: .75rem 1rem; text-align: left; font-size: .7rem; font-weight: 700;
        color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: .07em;
        background: #fafafa; border-bottom: 1px solid var(--clr-border); white-space: nowrap;
    }
    table.items-table thead th:last-child { text-align: center; }
    table.items-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .1s; }
    table.items-table tbody tr:last-child { border-bottom: none; }
    table.items-table tbody tr:hover { background: #fafbff; }
    table.items-table td { padding: .625rem .875rem; vertical-align: middle; }

    .tbl-input, .tbl-select {
        width: 100%; font-size: .8125rem; color: var(--clr-text);
        border: 1px solid var(--clr-border); border-radius: 6px;
        padding: .5rem .625rem; background: #fff;
        height: 2.25rem; line-height: 1.25;
        vertical-align: middle; display: block;
        transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
        appearance: auto; -webkit-appearance: auto;
    }
    .tbl-input:focus, .tbl-select:focus {
        outline: none; border-color: var(--clr-border-focus);
        box-shadow: 0 0 0 2px rgba(165,180,252,.18);
    }

    .row-total-cell { font-size: .875rem; font-weight: 600; color: var(--clr-text); white-space: nowrap; }
    .btn-remove {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.875rem; height: 1.875rem; border-radius: 6px;
        background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;
        cursor: pointer; transition: all .15s; font-size: .8rem;
    }
    .btn-remove:hover { background: #ffe4e6; }

    .totals-foot { padding: 1.25rem 1.5rem; background: #fafafa; border-top: 1px solid var(--clr-border); }
    .totals-inner { max-width: 22rem; margin-left: auto; }
    .totals-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: .5rem 0; border-bottom: 1px solid #f0f0f0; font-size: .875rem;
    }
    .totals-row:last-child { border-bottom: none; }
    .totals-row .lbl { font-weight: 500; color: var(--clr-text-muted); }
    .totals-row .val { font-weight: 600; color: var(--clr-text); }
    .totals-row.grand { padding-top: .75rem; margin-top: .25rem; border-top: 2px solid var(--clr-border); }
    .totals-row.grand .lbl { font-size: .9375rem; font-weight: 700; color: var(--clr-text); }
    .totals-row.grand .val { font-size: 1.125rem; font-weight: 700; color: var(--clr-primary); }

    .qform-actions {
        padding: 1.25rem 1.5rem; border-top: 1px solid var(--clr-border);
        display: flex; align-items: center; justify-content: flex-end; gap: .75rem; flex-wrap: wrap;
    }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8375rem; font-weight: 600; padding: .5625rem 1.25rem;
        background: #f3f4f6; border: 1px solid var(--clr-border); color: #374151;
        border-radius: var(--radius-sm); text-decoration: none; transition: all .15s;
    }
    .btn-cancel:hover { background: #e5e7eb; }
    .btn-submit {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8375rem; font-weight: 600; padding: .5625rem 1.375rem;
        background: var(--clr-primary); color: #fff; border: none;
        border-radius: var(--radius-sm); cursor: pointer;
        box-shadow: 0 2px 8px rgba(79,70,229,.22); transition: all .15s;
    }
    .btn-submit:hover { background: var(--clr-primary-hover); box-shadow: 0 4px 14px rgba(79,70,229,.3); transform: translateY(-1px); }
</style>

<div class="qform-page">
    {{-- Header --}}
    <div class="qform-header">
        <div>
            <h1 class="qform-title">Edit Quotation {{ $quotation->number }}</h1>
            <p class="qform-breadcrumb">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                <span class="qform-breadcrumb-sep">›</span>
                <a href="{{ route('quotations.index') }}">Quotations</a>
                <span class="qform-breadcrumb-sep">›</span>
                <span>Edit</span>
            </p>
        </div>
        <div class="hdr-btns">
            <a href="{{ route('quotations.show', $quotation) }}" class="btn-ghost">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('quotations.index') }}" class="btn-ghost">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="qform-card">
        <form action="{{ route('quotations.update', $quotation) }}" method="POST" id="quotationForm">
            @csrf
            @method('PUT')

            {{-- Customer & Validity --}}
            <div class="qsection">
                <p class="qsection-title"><i class="fas fa-user" style="color:#6366f1"></i> Customer & Validity</p>
                <div class="form-grid-2">
                    <div>
                        <label for="customer_id" class="field-label">Customer <span class="req">*</span></label>
                        <select name="customer_id" id="customer_id" class="field-select" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('customer_id', $quotation->customer_id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="valid_until" class="field-label">Valid Until <span class="req">*</span></label>
                        <input type="date" name="valid_until" id="valid_until" class="field-input"
                               value="{{ old('valid_until', $quotation->valid_until->format('Y-m-d')) }}" required>
                        @error('valid_until')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="qsection">
                <p class="qsection-title"><i class="fas fa-sticky-note" style="color:#f59e0b"></i> Notes</p>
                <textarea name="notes" id="notes" class="field-textarea"
                          placeholder="Additional notes, terms, or special instructions...">{{ old('notes', $quotation->notes) }}</textarea>
                @error('notes')
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Items --}}
            <div>
                <div class="items-head">
                    <span class="items-head-title">Quotation Items</span>
                    <button type="button" class="btn-add-item" onclick="addItem()">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
                <div class="items-table-wrap">
                    <table class="items-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="min-width:180px">Commodity</th>
                                <th style="min-width:160px">Description</th>
                                <th style="min-width:80px">Qty</th>
                                <th style="min-width:100px">Unit</th>
                                <th style="min-width:110px">Unit Price</th>
                                <th style="min-width:90px">Discount %</th>
                                <th style="min-width:80px">Tax %</th>
                                <th style="min-width:90px">Total</th>
                                <th style="min-width:50px;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="totals-foot">
                    <div class="totals-inner">
                        <div class="totals-row">
                            <span class="lbl">Subtotal</span>
                            <span class="val" id="subtotalDisplay">₹0.00</span>
                        </div>
                        <div class="totals-row">
                            <span class="lbl">Tax</span>
                            <span class="val" id="taxDisplay">₹0.00</span>
                        </div>
                        <div class="totals-row grand">
                            <span class="lbl">Total</span>
                            <span class="val" id="totalDisplay">₹0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="qform-actions">
                <a href="{{ route('quotations.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Quotation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = 0;
const materials = {!! json_encode($materials->map(function($m) {
    return [
        'id' => $m->id,
        'name' => $m->name,
        'code' => $m->code,
        'unit_price' => $m->unit_price,
        'gst_rate' => $m->gst_rate,
        'unit' => $m->unit,
        'description' => $m->description ?? $m->name
    ];
})) !!};

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    const idx = itemIndex;
    row.setAttribute('data-idx', idx);
    row.innerHTML = `
        <td>
            <select name="items[${idx}][material_id]" class="tbl-select" required onchange="updateDescription(${idx})">
                <option value="">Choose commodity</option>
                ${materials.map(m => `<option value="${m.id}" data-price="${m.unit_price}" data-gst="${m.gst_rate}" data-unit="${m.unit}" data-description="${m.description}" data-name="${m.name}">${m.name} (${m.code})</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="text" name="items[${idx}][description]" class="tbl-input" required>
        </td>
        <td>
            <input type="number" name="items[${idx}][quantity]" class="tbl-input" step="0.01" min="0.01" required onchange="calculateRowTotal(${idx})">
        </td>
        <td>
            <select name="items[${idx}][unit]" class="tbl-select" required>
                <option value="piece">Piece</option>
                <option value="kg">Kg</option>
                <option value="hour">Hour</option>
                <option value="meter">Meter</option>
                <option value="liter">Liter</option>
                <option value="box">Box</option>
                <option value="pack">Pack</option>
            </select>
        </td>
        <td>
            <input type="number" name="items[${idx}][unit_price]" class="tbl-input" step="0.01" min="0" required onchange="calculateRowTotal(${idx})">
        </td>
        <td>
            <input type="number" name="items[${idx}][discount_percentage]" class="tbl-input" step="0.01" min="0" max="100" value="0" onchange="calculateRowTotal(${idx})">
        </td>
        <td>
            <input type="number" name="items[${idx}][tax_rate]" class="tbl-input" step="0.01" min="0" max="100" value="18" required onchange="calculateRowTotal(${idx})">
        </td>
        <td>
            <span class="row-total-cell" id="row-total-${idx}">₹0.00</span>
        </td>
        <td style="text-align:center">
            <button type="button" class="btn-remove" onclick="removeItem(this)" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    itemIndex++;
}

function updateDescription(index) {
    const select = document.querySelector(`select[name="items[${index}][material_id]"]`);
    const descInput = document.querySelector(`input[name="items[${index}][description]"]`);
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    const taxInput = document.querySelector(`input[name="items[${index}][tax_rate]"]`);
    const unitSelect = document.querySelector(`select[name="items[${index}][unit]"]`);
    if (select.value) {
        const option = select.selectedOptions[0];
        descInput.value = option.dataset.description || option.dataset.name;
        priceInput.value = option.dataset.price;
        taxInput.value = option.dataset.gst || 18;
        if (option.dataset.unit && unitSelect) unitSelect.value = option.dataset.unit;
        calculateRowTotal(index);
    }
}

function calculateRowTotal(index) {
    const qty = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value) || 0;
    const discountPercent = parseFloat(document.querySelector(`input[name="items[${index}][discount_percentage]"]`).value) || 0;
    const taxRate = parseFloat(document.querySelector(`input[name="items[${index}][tax_rate]"]`).value) || 0;
    const subtotal = qty * price;
    const discountAmount = (subtotal * discountPercent) / 100;
    const taxableAmount = subtotal - discountAmount;
    const tax = (taxableAmount * taxRate) / 100;
    const total = taxableAmount + tax;
    const cell = document.getElementById(`row-total-${index}`);
    if (cell) cell.textContent = `₹${total.toFixed(2)}`;
    updateTotals();
}

function removeItem(button) {
    button.closest('tr').remove();
    updateTotals();
}

function updateTotals() {
    let subtotal = 0, totalDiscount = 0, totalTax = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
        const discountPercent = parseFloat(row.querySelector('input[name*="[discount_percentage]"]')?.value) || 0;
        const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]')?.value) || 0;
        const itemSubtotal = qty * price;
        const itemDiscount = (itemSubtotal * discountPercent) / 100;
        const taxableAmount = itemSubtotal - itemDiscount;
        const itemTax = (taxableAmount * taxRate) / 100;
        subtotal += itemSubtotal;
        totalDiscount += itemDiscount;
        totalTax += itemTax;
    });
    document.getElementById('subtotalDisplay').textContent = `₹${subtotal.toFixed(2)}`;
    document.getElementById('taxDisplay').textContent = `₹${totalTax.toFixed(2)}`;
    document.getElementById('totalDisplay').textContent = `₹${(subtotal - totalDiscount + totalTax).toFixed(2)}`;
}

document.addEventListener('DOMContentLoaded', function() {
    @if(isset($quotation) && $quotation->items->count() > 0)
        @foreach($quotation->items as $index => $item)
            addItem();
            setTimeout(() => {
                const row = document.querySelector(`#itemsBody tr:nth-child({{ $index + 1 }})`);
                if (row) {
                    const materialSelect = row.querySelector('select[name*="[material_id]"]');
                    const descInput = row.querySelector('input[name*="[description]"]');
                    const qtyInput = row.querySelector('input[name*="[quantity]"]');
                    const unitSelect = row.querySelector('select[name*="[unit]"]');
                    const priceInput = row.querySelector('input[name*="[unit_price]"]');
                    const discountInput = row.querySelector('input[name*="[discount_percentage]"]');
                    const taxInput = row.querySelector('input[name*="[tax_rate]"]');
                    if (materialSelect) materialSelect.value = '{{ $item->material_id }}';
                    if (descInput) descInput.value = '{{ addslashes($item->description) }}';
                    if (qtyInput) qtyInput.value = '{{ $item->quantity }}';
                    if (unitSelect) unitSelect.value = '{{ $item->unit ?? "piece" }}';
                    if (priceInput) priceInput.value = '{{ $item->unit_price }}';
                    if (discountInput) discountInput.value = '{{ $item->discount_percentage ?? 0 }}';
                    if (taxInput) taxInput.value = '{{ $item->tax_rate ?? 18 }}';
                    calculateRowTotal({{ $index }});
                }
            }, 100);
        @endforeach
    @else
        addItem();
    @endif
});
</script>
@endsection