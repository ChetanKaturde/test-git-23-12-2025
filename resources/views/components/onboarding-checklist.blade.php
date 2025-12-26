@php
    $business = auth()->user()->business;
    $tier = $business->subscription_tier ?? 'full_erp';
    
    // Check completion status
    $hasProfile = $business && $business->name && $business->email;
    $hasCustomers = \App\Models\Customer::where('business_id', $business->id)->count() > 0;
    $hasQuotations = \App\Models\Quotation::where('business_id', $business->id)->count() > 0;
    $hasMaterials = \App\Models\Material::where('business_id', $business->id)->count() > 0;
    $hasWorkOrders = \App\Models\WorkOrder::where('business_id', $business->id)->count() > 0;
    
    // Determine if we should show the widget
    $showWidget = !$hasProfile || !$hasCustomers || ($tier === 'full_erp' && (!$hasMaterials || !$hasWorkOrders)) || ($tier === 'billing_sales' && !$hasQuotations);
@endphp

@if($showWidget)
<div class="col-lg-4 mb-4">
    <div class="card border-indigo-500">
        <div class="card-header bg-indigo-50">
            <h5 class="mb-0 text-indigo-700">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Get Started
            </h5>
        </div>
        <div class="card-body">
            <p class="text-sm text-gray-600 mb-4">Complete these steps to get the most out of Monitorbizz:</p>
            
            <div class="space-y-3">
                <!-- Complete Business Profile -->
                <div class="flex items-start">
                    @if($hasProfile)
                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-500 line-through">Complete Business Profile</span>
                    @else
                        <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <a href="{{ route('business.profile') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Complete Business Profile</a>
                    @endif
                </div>

                @if($tier === 'full_erp')
                    <!-- Add Materials -->
                    <div class="flex items-start">
                        @if($hasMaterials)
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-500 line-through">Add your first Item</span>
                        @else
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <a href="{{ route('materials.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Add your first Item (product or service)</a>
                        @endif
                    </div>
                @endif

                <!-- Add Customers -->
                <div class="flex items-start">
                    @if($hasCustomers)
                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-500 line-through">Add Customers</span>
                    @else
                        <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <a href="{{ route('customers.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Add First Customer</a>
                    @endif
                </div>

                @if($tier === 'billing_sales')
                    <!-- Create First Quotation -->
                    <div class="flex items-start">
                        @if($hasQuotations)
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-500 line-through">Create First Quotation</span>
                        @else
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <a href="{{ route('quotations.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Create First Quotation</a>
                        @endif
                    </div>
                @else
                    <!-- Create First Work Order -->
                    <div class="flex items-start">
                        @if($hasWorkOrders)
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-500 line-through">Create First Work Order</span>
                        @else
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <a href="{{ route('work-orders.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Create First Work Order</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
