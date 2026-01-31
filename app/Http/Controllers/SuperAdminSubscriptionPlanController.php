<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Feature;
use App\Models\PlanFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminSubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::with('planFeatures.feature')->get();
        return view('superadmin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        $features = Feature::all();
        return view('superadmin.subscription-plans.create', compact('features'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_user' => 'required|numeric|min:0',
            'min_users' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1|gte:min_users',
            'status' => 'required|in:active,inactive',
            'features' => 'array',
            'features.*.enabled' => 'boolean',
            'features.*.quantity_limit' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $plan = SubscriptionPlan::create($request->only(['name', 'price_per_user', 'min_users', 'max_users', 'status']));

            if ($request->has('features')) {
                foreach ($request->features as $featureId => $featureData) {
                    PlanFeature::create([
                        'plan_id' => $plan->id,
                        'feature_id' => $featureId,
                        'enabled' => $featureData['enabled'] ?? false,
                        'quantity_limit' => $featureData['quantity_limit'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('superadmin.subscription-plans.index')->with('success', 'Plan created successfully.');
    }

    public function show(SubscriptionPlan $subscription_plan)
    {
        $plan = $subscription_plan->load('planFeatures.feature');
        return view('superadmin.subscription-plans.show', compact('plan'));
    }

    public function edit(SubscriptionPlan $subscription_plan)
    {
        $plan = $subscription_plan->load('planFeatures');
        $features = Feature::all();
        return view('superadmin.subscription-plans.edit', compact('plan', 'features'));
    }

    public function update(Request $request, SubscriptionPlan $subscription_plan)
    {
        $plan = $subscription_plan;
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_user' => 'required|numeric|min:0',
            'min_users' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1|gte:min_users',
            'status' => 'required|in:active,inactive',
            'features' => 'array',
            'features.*.enabled' => 'boolean',
            'features.*.quantity_limit' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $plan) {
            $plan->update($request->only(['name', 'price_per_user', 'min_users', 'max_users', 'status']));

            // Remove existing features
            $plan->planFeatures()->delete();

            // Add updated features
            if ($request->has('features')) {
                foreach ($request->features as $featureId => $featureData) {
                    PlanFeature::create([
                        'plan_id' => $plan->id,
                        'feature_id' => $featureId,
                        'enabled' => $featureData['enabled'] ?? false,
                        'quantity_limit' => $featureData['quantity_limit'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('superadmin.subscription-plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscription_plan)
    {
        // Check if plan has active subscriptions
        if ($subscription_plan->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $subscription_plan->delete();
        return redirect()->route('superadmin.subscription-plans.index')->with('success', 'Plan deleted successfully.');
    }

    public function toggleStatus(SubscriptionPlan $plan)
    {
        $plan->update(['status' => $plan->status === 'active' ? 'inactive' : 'active']);
        return redirect()->back()->with('success', 'Plan status updated.');
    }
}
