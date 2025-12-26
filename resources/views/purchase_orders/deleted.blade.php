@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="alert alert-danger text-center">
        <h4>Order Not Found</h4>
        <p>Purchase Order #<strong>{{ $order_id }}</strong> has been deleted or does not exist.</p>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-arrow-left me-1"></i>Back to Orders
        </a>
    </div>
</div>
@endsection
