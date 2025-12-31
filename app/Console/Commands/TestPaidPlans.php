<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;
use App\Services\SubscriptionService;

class TestPaidPlans extends Command
{
    protected $signature = 'test:paid-plans {business_id}';
    protected $description = 'Test paid plan functionality by simulating different plans';

    public function handle()
    {
        $businessId = $this->argument('business_id');
        $business = Business::find($businessId);
        
        if (!$business) {
            $this->error("Business not found");
            return;
        }

        $this->info("=== TESTING PAID PLANS FOR: {$business->name} ===");
        $this->newLine();

        // Test all plan combinations
        $plans = ['free', 'starter', 'professional'];
        $templates = ['service', 'manufacturing', 'trading', 'restaurant'];

        foreach ($plans as $plan) {
            foreach ($templates as $template) {
                $this->testPlanCombination($business, $plan, $template);
            }
        }
    }

    private function testPlanCombination($business, $plan, $template)
    {
        // Simulate the plan (without saving to DB)
        $business->subscription_plan = $plan;
        $business->template = $template;
        
        $price = SubscriptionService::calculatePrice($plan, $template);
        $features = SubscriptionService::getAvailableFeatures($plan, $template);
        $canCreateInvoice = SubscriptionService::isWithinLimit($business, 'invoices_per_month', 0);
        $canInviteUser = SubscriptionService::isWithinLimit($business, 'users', 2);
        
        $this->info("--- {$plan} + {$template} ---");
        $this->line("Price: ₹{$price}");
        $this->line("Can create invoice: " . ($canCreateInvoice ? 'YES' : 'NO'));
        $this->line("Can invite user: " . ($canInviteUser ? 'YES' : 'NO'));
        $this->line("Features: " . implode(', ', $features));
        $this->newLine();
    }
}