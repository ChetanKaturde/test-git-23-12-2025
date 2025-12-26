@extends('layouts.app')
@section('title', 'Material Details')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h3>Material Details</h3></div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $material->name }}</p>
            <p><strong>Code:</strong> {{ $material->code }}</p>
            <p><strong>Description:</strong><br>{{ $material->description ?? 'N/A' }}</p>
            <p><strong>Unit:</strong> {{ $material->unit }}</p>
            <p><strong>Unit Price:</strong> {{ number_format($material->unit_price, 2) }}</p>
            <p><strong>GST Rate:</strong> {{ $material->gst_rate }}%</p>
            <p><strong>Category:</strong> {{ $material->category ?? 'N/A' }}</p>
            <p><strong>Available:</strong> 
                <span class="badge bg-{{ $material->is_active ? 'success' : 'danger' }}">
                    {{ $material->is_available ? 'Yes' : 'No' }}
                </span>
            </p>
            <p><strong>Created At:</strong> {{ $material->created_at->format('M d, Y h:i A') }}</p>
            <p><strong>Updated At:</strong> {{ $material->updated_at->format('M d, Y h:i A') }}</p>

            <a href="{{ route('materials.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
