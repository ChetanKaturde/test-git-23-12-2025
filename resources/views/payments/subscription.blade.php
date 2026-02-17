@extends('layouts.app')

@section('title', 'Subscription Payment')
@section('page-title', 'Complete Payment')

@section('content')
<div class="p-4 md:p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Subscription Payment</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Plan Details</h3>
                    <div class="space-y-2">
                        <p class="text-gray-600"><strong>Plan:</strong> {{ $plan->name }}</p>
                        <p class="text-gray-600"><strong>Users:</strong> {{ auth()->user()->business->users()->count() }}</p>
                        <p class="text-gray-600"><strong>Price per user:</strong> ₹{{ number_format($plan->price_per_user, 2) }}</p>
                        <p class="text-gray-600"><strong>Duration:</strong> 1 Month</p>
                        <p class="text-gray-600"><strong>Action:</strong> {{ ucfirst($action) }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Summary</h3>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">₹{{ number_format($amount, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between">
                            <span class="text-gray-900 font-semibold">Total:</span>
                            <span class="text-gray-900 font-bold text-xl">₹{{ number_format($amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $razorpayKey = config('services.razorpay.key');
                $razorpaySecret = config('services.razorpay.secret');
            @endphp

            @if(!empty($razorpayKey) && !empty($razorpaySecret))
                <div class="mt-6">
                    <button id="rzp-button" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Pay ₹{{ number_format($amount, 2) }} with Razorpay
                    </button>
                </div>
                
                <form action="{{ route('subscription.payment.process') }}" method="POST" id="razorpay-form" class="hidden">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="action" value="{{ $action }}">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                </form>
            @else
                <div class="mt-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Razorpay credentials not found</h3>
                                <p class="mt-2 text-sm text-yellow-700">
                                    Payment gateway is not configured. Your subscription will be activated directly.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('subscription.payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="action" value="{{ $action }}">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                            Activate Subscription
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@if(!empty($razorpayKey) && !empty($razorpaySecret))
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('rzp-button').onclick = function(e) {
        e.preventDefault();
        var options = {
            "key": "{{ $razorpayKey }}",
            "amount": "{{ $amount * 100 }}",
            "currency": "INR",
            "name": "Monitorbizz",
            "description": "{{ ucfirst($action) }} - {{ $plan->name }}",
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
    };
</script>
@endif
@endsection