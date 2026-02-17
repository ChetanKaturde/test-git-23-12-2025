<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Services\PlanUpdateService;

class PricingController extends Controller
{
    protected $planUpdateService;

    public function __construct(PlanUpdateService $planUpdateService)
    {
        $this->planUpdateService = $planUpdateService;
    }

    public function index()
    {
        $business = auth()->user()->business;
        $plans = SubscriptionPlan::active()->orderBy('price_per_user')->get();
        
        $currentSubscription = $business->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        $expiredSubscription = null;
        if (!$currentSubscription) {
            $expiredSubscription = $business->subscriptions()
                ->where('status', 'expired')
                ->orWhere('end_date', '<', now())
                ->latest()
                ->first();
        }

        return view('pricing', compact('plans', 'currentSubscription', 'expiredSubscription'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'action' => 'required|in:continue,upgrade,advance'
        ]);

        $business = auth()->user()->business;
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Validation
        $validation = $this->planUpdateService->validatePlanChange($business, $plan, $request->action);
        if (!$validation['valid']) {
            return back()->with('error', $validation['message']);
        }

        // Check Razorpay credentials
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        if (empty($razorpayKey) || empty($razorpaySecret)) {
            // Direct processing without payment gateway
            $result = $this->planUpdateService->updatePlan($business, $plan, $request->action);
            
            if ($result['success']) {
                return redirect()->route('dashboard')->with('success', $result['message']);
            }
            return back()->with('error', $result['message']);
        }

        // Redirect to Razorpay (implement payment gateway logic here)
        return redirect()->route('subscription.payment', ['plan' => $plan->id, 'action' => $request->action]);
    }
}
