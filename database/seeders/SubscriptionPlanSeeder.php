<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plan = \App\Models\SubscriptionPlan::create([
            'name' => 'Professional Plan',
            'price_per_user' => 799.00,
            'status' => 'active',
            'min_users' => 2,
            'max_users' => 15,
        ]);

        $features = \App\Models\Feature::all();

        foreach ($features as $feature) {
            $limit = null;
            if (str_contains($feature->name, 'quotation')) {
                $limit = 100;
            } elseif (str_contains($feature->name, 'invoice')) {
                $limit = 50;
            }

            \App\Models\PlanFeature::create([
                'plan_id' => $plan->id,
                'feature_id' => $feature->id,
                'enabled' => true,
                'quantity_limit' => $limit,
            ]);
        }
    }
}
