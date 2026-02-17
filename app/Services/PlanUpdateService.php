<?php

namespace App\Services;

use App\Models\Business;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Carbon\Carbon;

class PlanUpdateService
{
    public function validatePlanChange(Business $business, SubscriptionPlan $newPlan, string $action)
    {
        $currentSubscription = $business->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        // Continue: Plan expired, renewing same plan
        if ($action === 'continue') {
            if ($currentSubscription) {
                return ['valid' => false, 'message' => 'Cannot continue - plan is still active'];
            }
            return ['valid' => true];
        }

        // Upgrade: Plan expired, upgrading to higher plan
        if ($action === 'upgrade') {
            if ($currentSubscription) {
                return ['valid' => false, 'message' => 'Cannot upgrade while plan is active'];
            }
            
            $lastPlan = $business->subscriptions()->latest()->first();
            if ($lastPlan && $newPlan->price_per_user <= $lastPlan->plan->price_per_user) {
                return ['valid' => false, 'message' => 'Downgrade not allowed'];
            }
            return ['valid' => true];
        }

        // Advance: Active plan, paying for next month
        if ($action === 'advance') {
            if (!$currentSubscription) {
                return ['valid' => false, 'message' => 'No active plan to pay advance'];
            }
            if ($currentSubscription->plan_id !== $newPlan->id) {
                return ['valid' => false, 'message' => 'Can only pay advance for current plan'];
            }
            return ['valid' => true];
        }

        return ['valid' => false, 'message' => 'Invalid action'];
    }

    public function updatePlan(Business $business, SubscriptionPlan $plan, string $action)
    {
        $currentSubscription = $business->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        if ($action === 'continue' || $action === 'upgrade') {
            // Mark old subscription as expired
            if ($currentSubscription) {
                $currentSubscription->update(['status' => 'expired']);
            }

            // Create new subscription
            $subscription = Subscription::create([
                'business_id' => $business->id,
                'plan_id' => $plan->id,
                'user_count' => $business->users()->count(),
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'active',
                'amount' => $plan->price_per_user * $business->users()->count(),
                'sales_representative_id' => $business->sales_representative_id,
                'plan_snapshot' => $this->getPlanSnapshot($plan)
            ]);

            $business->update(['allowed_users' => $plan->max_users]);

            return [
                'success' => true,
                'message' => 'Plan ' . ($action === 'continue' ? 'renewed' : 'upgraded') . ' successfully'
            ];
        }

        if ($action === 'advance') {
            // Extend current subscription
            $currentSubscription->update([
                'end_date' => $currentSubscription->end_date->addMonth()
            ]);

            return [
                'success' => true,
                'message' => 'Advance payment successful. Plan extended by 1 month'
            ];
        }

        return ['success' => false, 'message' => 'Invalid action'];
    }

    protected function getPlanSnapshot(SubscriptionPlan $plan)
    {
        $features = [];
        foreach ($plan->planFeatures as $pf) {
            $features[$pf->feature->key] = [
                'enabled' => $pf->enabled,
                'limit' => $pf->quantity_limit
            ];
        }

        return [
            'plan_name' => $plan->name,
            'price' => $plan->price_per_user,
            'features' => $features
        ];
    }
}
