@extends('layouts.app')
@section('title', 'Edit Commodity')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h3>Edit Commodity</h3></div>
        <div class="card-body">
           <form id="materialForm" action="{{ route('materials.update', $material) }}" method="POST">
    @csrf 
    @method('PUT')
    @include('materials.partials.form', ['material' => $material])
</form>

@if(auth()->user()->business->subscription_tier !== 'billing_sales')
<!-- Vendor Linkage Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Vendor Pricing & MOQ</h4>
        <p class="text-muted mb-0">Link this commodity to vendors with pricing and minimum order quantities</p>
    </div>
    <div class="card-body">
        <div id="vendor-links">
            <!-- Existing vendor links will be loaded here -->
        </div>
        
        <div class="mt-3">
            <button type="button" class="btn btn-primary" onclick="addVendorLink()">Add Vendor</button>
        </div>
    </div>
</div>
@endif

        </div>
    </div>
</div>
<script>
@if(auth()->user()->business->subscription_tier !== 'billing_sales')
let vendorIndex = 0;
let allVendors = [];

// Load vendors and existing links on page load
document.addEventListener('DOMContentLoaded', function() {
    loadVendors();
    loadExistingVendorLinks();
});

function loadVendors() {
    fetch('/api/vendors')
        .then(res => res.json())
        .then(vendors => {
            allVendors = vendors;
        })
        .catch(err => console.error('Error loading vendors:', err));
}

function loadExistingVendorLinks() {
    fetch(`/api/materials/{{ $material->id }}/vendors`)
        .then(res => res.json())
        .then(vendors => {
            vendors.forEach(vendor => {
                addVendorLink(vendor);
            });
        })
        .catch(err => console.error('Error loading vendor links:', err));
}

function addVendorLink(existingData = null) {
    const container = document.getElementById('vendor-links');
    const index = vendorIndex++;
    
    const div = document.createElement('div');
    div.className = 'border rounded p-3 mb-3';
    div.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Vendor</label>
                <select class="form-select" name="vendors[${index}][vendor_id]" required>
                    <option value="">Select Vendor</option>
                    ${allVendors.map(v => `<option value="${v.id}" ${existingData && existingData.vendor_id == v.id ? 'selected' : ''}>${v.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Price per Unit</label>
                <input type="number" step="0.01" class="form-control" name="vendors[${index}][price_per_unit]" 
                       value="${existingData ? existingData.price_per_unit : ''}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Min Order Qty</label>
                <input type="number" class="form-control" name="vendors[${index}][min_order_qty]" 
                       value="${existingData ? existingData.min_order_qty : '1'}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Notes</label>
                <input type="text" class="form-control" name="vendors[${index}][notes]" 
                       value="${existingData ? existingData.notes || '' : ''}" placeholder="Optional notes">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeVendorLink(this)">Remove</button>
            </div>
        </div>
    `;
    
    container.appendChild(div);
}

function removeVendorLink(button) {
    button.closest('.border').remove();
}

function saveVendorLinks() {
    const formData = new FormData();
    const vendors = [];
    
    document.querySelectorAll('#vendor-links .border').forEach((div, index) => {
        const vendorId = div.querySelector('select').value;
        const pricePerUnit = div.querySelector('input[name*="price_per_unit"]').value;
        const minOrderQty = div.querySelector('input[name*="min_order_qty"]').value;
        const notes = div.querySelector('input[name*="notes"]').value;
        
        if (vendorId && pricePerUnit && minOrderQty) {
            vendors.push({
                material_id: {{ $material->id }},
                vendor_id: vendorId,
                price_per_unit: pricePerUnit,
                min_order_qty: minOrderQty,
                notes: notes
            });
        }
    });
    
    if (vendors.length === 0) {
        alert('Please add at least one vendor link');
        return;
    }
    
    fetch(`/api/vendors/{{ $material->id }}/link-materials`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ materials: vendors })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Vendor links saved successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error saving vendor links:', err);
        alert('Failed to save vendor links');
    });
}
@endif
</script>
@endsection
