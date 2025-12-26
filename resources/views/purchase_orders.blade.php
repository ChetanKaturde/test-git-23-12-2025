@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div id="purchaseSection" class="content-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Purchase Orders</h3>
            <button class="btn btn-primary" onclick="showCreatePurchaseModal()">
                <i class="fas fa-plus"></i> New Purchase Order
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="purchaseOrdersTable">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Vendor</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="purchaseOrdersBody">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Purchase Order Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="purchaseForm" onsubmit="createPurchaseOrder(event)">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Purchase Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" name="po_number" placeholder="PO Number" required>
                    <input type="text" class="form-control mb-2" name="vendor" placeholder="Vendor" required>
                    <input type="date" class="form-control mb-2" name="date" required>
                    <textarea class="form-control mb-2" name="items" placeholder='Items (JSON format: [{"name": "Item1", "qty": 2}])' required></textarea>
                    <input type="number" step="0.01" class="form-control mb-2" name="total_amount" placeholder="Total Amount" required>
                    <select name="status" class="form-control mb-2" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", fetchPurchaseOrders);

    function fetchPurchaseOrders() {
        fetch('/api/purchase-orders')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('purchaseOrdersBody');
                tbody.innerHTML = '';
                data.forEach(order => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${order.po_number}</td>
                            <td>${order.vendor}</td>
                            <td>${order.date}</td>
                            <td><pre>${JSON.stringify(order.items, null, 2)}</pre></td>
                            <td>${order.total_amount}</td>
                            <td>${order.status}</td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="deleteOrder(${order.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            });
    }

    function showCreatePurchaseModal() {
        const modal = new bootstrap.Modal(document.getElementById('purchaseModal'));
        modal.show();
    }

    function createPurchaseOrder(event) {
        event.preventDefault();
        const form = document.getElementById('purchaseForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.items = JSON.parse(data.items); // Convert items string to array

        fetch('/api/purchase-orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        }).then(res => {
            if (res.ok) {
                bootstrap.Modal.getInstance(document.getElementById('purchaseModal')).hide();
                fetchPurchaseOrders();
                form.reset();
            } else {
                alert('Failed to create purchase order');
            }
        });
    }

    function deleteOrder(id) {
        if (!confirm('Are you sure?')) return;
        fetch(`/api/purchase-orders/${id}`, {
            method: 'DELETE'
        }).then(res => {
            if (res.ok) fetchPurchaseOrders();
            else alert('Delete failed');
        });
    }
</script>
@endsection
