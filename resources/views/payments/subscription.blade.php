@extends('layouts.app')

@section('title', 'Subscription Payment - ' . config('app.name'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Complete Your Subscription Payment</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Plan Details</h5>
                            <p><strong>Plan:</strong> {{ $subscription->plan_snapshot['name'] }}</p>
                            <p><strong>Users:</strong> {{ $subscription->user_count }}</p>
                            <p><strong>Price per user:</strong> ₹{{ number_format($subscription->plan_snapshot['price_per_user'], 2) }}</p>
                            <p><strong>Duration:</strong> 1 Month</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Summary</h5>
                            <div class="border p-3 rounded">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>₹{{ number_format($subscription->amount, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span>Total:</span>
                                    <span>₹{{ number_format($subscription->amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if(config('services.razorpay.key'))
                            <!-- Razorpay Payment Form -->
                            <form action="{{ route('subscription.payment.process', $subscription) }}" method="POST" id="razorpay-form">
                                @csrf
                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ config('services.razorpay.key') }}"
                                        data-amount="{{ $subscription->amount * 100 }}"
                                        data-currency="INR"
                                        data-name="Monitorbizz"
                                        data-description="Subscription Payment"
                                        data-prefill.name="{{ auth()->user()->name }}"
                                        data-prefill.email="{{ auth()->user()->email }}"
                                        data-theme.color="#4f46e5">
                                </script>
                                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <h6>Razorpay credentials not configured</h6>
                                <p>Razorpay payment gateway is not configured. Your subscription will be activated directly.</p>
                                <form action="{{ route('subscription.payment.process', $subscription) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Activate Subscription</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(config('services.razorpay.key'))
<script>
    var options = {
        "key": "{{ config('services.razorpay.key') }}",
        "amount": "{{ $subscription->amount * 100 }}",
        "currency": "INR",
        "name": "Monitorbizz",
        "description": "Subscription Payment",
        "handler": function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay-form').submit();
        },
        "prefill": {
            "name": "{{ auth()->user()->name }}",
            "email": "{{ auth()->user()->email }}"
        },
        "theme": {
            "color": "#4f46e5"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
</script>
@endif
@endsection