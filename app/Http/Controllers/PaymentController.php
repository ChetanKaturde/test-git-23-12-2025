<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->currentSubscription() || !auth()->user()->currentSubscription()->isFeatureEnabled('invoice_management')) {
                abort(403, 'Invoice management feature is not enabled for your subscription plan.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Admin bypass - admins always have access without requiring explicit permission
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('view_payment_receipts')) {
            abort(403);
        }

        $businessId = auth()->user()->business_id;
        $query = Payment::where('business_id', $businessId)->with(['invoice', 'createdBy']);

        if ($invoice = request('invoice')) {
            $query->whereHas('invoice', function($q) use ($invoice) {
                $q->where('invoice_number', 'like', '%' . $invoice . '%');
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function record()
    {
        // Check permission for recording payments
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('manage_invoices')) {
            abort(403, 'You do not have permission to record payments.');
        }

        $businessId = auth()->user()->business_id;
        $invoices = Invoice::where('business_id', $businessId)
            ->where('status', '!=', 'paid')
            ->whereRaw('(total_amount - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.invoice_id = invoices.id)) > 0')
            ->orderBy('issue_date', 'desc')
            ->paginate(15);

        return view('payments.record', compact('invoices'));
    }

    public function store(Request $request)
    {
        // Check permission for recording payments
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('manage_invoices')) {
            abort(403, 'You do not have permission to record payments.');
        }

        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $invoice = Invoice::where('business_id', auth()->user()->business_id)
                         ->findOrFail($request->invoice_id);

        if ($request->amount > $invoice->balance()) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed invoice balance.']);
        }

        Payment::create([
            'invoice_id' => $invoice->id,
            'business_id' => auth()->user()->business_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        // Update invoice status if fully paid
        if ($invoice->isFullyPaid()) {
            $invoice->update([
                'status' => 'paid',
                'paid_date' => now()
            ]);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function subscriptionPayment(Request $request)
    {
        $planId = $request->get('plan');
        $action = $request->get('action');
        
        $plan = \App\Models\SubscriptionPlan::findOrFail($planId);
        $business = auth()->user()->business;
        $amount = $plan->price_per_user * $business->users()->count();

        return view('payments.subscription', compact('plan', 'action', 'amount'));
    }

    public function processSubscriptionPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'action' => 'required|in:continue,upgrade,advance',
            'razorpay_payment_id' => 'nullable|string'
        ]);

        $business = auth()->user()->business;
        $plan = \App\Models\SubscriptionPlan::findOrFail($request->plan_id);
        
        $planUpdateService = app(\App\Services\PlanUpdateService::class);
        $result = $planUpdateService->updatePlan($business, $plan, $request->action);

        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        }
        
        return back()->with('error', $result['message']);
    }
}