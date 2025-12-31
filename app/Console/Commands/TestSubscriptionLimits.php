<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;
use App\Services\SubscriptionService;

class TestSubscriptionLimits extends Command
{
    protected $signature = 'test:limits {business_id}';
    protected $description = 'Test subscription limits and edge cases';

    public function handle()
    {
        $businessId = $this->argument('business_id');
        $business = Business::find($businessId);
        
        if (!$business) {
            $this->error("Business not found");
            return;
        }

        $this->info("=== TESTING SUBSCRIPTION LIMITS ===");
        $this->newLine();

        // Test 1: Free Plan Limits
        $this->testFreePlanLimits($business);
        
        // Test 2: Starter Plan Limits  
        $this->testStarterPlanLimits($business);
        
        // Test 3: Professional Plan (Unlimited)
        $this->testProfessionalPlanLimits($business);
        
        // Test 4: Feature Access by Template
        $this->testFeatureAccess($business);
    }

    private function testFreePlanLimits($business)
    {
        $this->info("--- FREE PLAN LIMITS ---");
        $business->subscription_plan = 'free';
        
        // Test invoice limits
        $this->line("Invoice Limits:");
        for ($i = 0; $i <= 52; $i += 10) {
            $canCreate = SubscriptionService::isWithinLimit($business, 'invoices_per_month', $i);
            $status = $canCreate ? '✅' : '❌';
            $this->line("  {$i} invoices: {$status}");
        }
        
        // Test user limits
        $this->line("User Limits:");
        for ($i = 0; $i <= 4; $i++) {
            $canInvite = SubscriptionService::isWithinLimit($business, 'users', $i);
            $status = $canInvite ? '✅' : '❌';
            $this->line("  {$i} users: {$status}");
        }
        $this->newLine();
    }

    private function testStarterPlanLimits($business)
    {
        $this->info("--- STARTER PLAN LIMITS ---");
        $business->subscription_plan = 'starter';
        
        // Test invoice limits (500/month)
        $testCounts = [0, 100, 300, 499, 500, 501];
        $this->line("Invoice Limits (500/month):");
        foreach ($testCounts as $count) {
            $canCreate = SubscriptionService::isWithinLimit($business, 'invoices_per_month', $count);
            $status = $canCreate ? '✅' : '❌';
            $this->line("  {$count} invoices: {$status}");
        }
        
        // Test user limits (5 users)
        $this->line("User Limits (5 users):");
        for ($i = 0; $i <= 7; $i++) {
            $canInvite = SubscriptionService::isWithinLimit($business, 'users', $i);
            $status = $canInvite ? '✅' : '❌';
            $this->line("  {$i} users: {$status}");
        }
        $this->newLine();
    }

    private function testProfessionalPlanLimits($business)
    {
        $this->info("--- PROFESSIONAL PLAN LIMITS ---");
        $business->subscription_plan = 'professional';
        
        // Test unlimited invoices
        $testCounts = [0, 1000, 5000, 10000];
        $this->line("Invoice Limits (Unlimited):");
        foreach ($testCounts as $count) {
            $canCreate = SubscriptionService::isWithinLimit($business, 'invoices_per_month', $count);
            $status = $canCreate ? '✅' : '❌';
            $this->line("  {$count} invoices: {$status}");
        }
        
        // Test user limits (20 users)
        $testCounts = [0, 10, 19, 20, 21];
        $this->line("User Limits (20 users):");
        foreach ($testCounts as $count) {
            $canInvite = SubscriptionService::isWithinLimit($business, 'users', $count);
            $status = $canInvite ? '✅' : '❌';
            $this->line("  {$count} users: {$status}");
        }
        $this->newLine();
    }

    private function testFeatureAccess($business)
    {
        $this->info("--- FEATURE ACCESS BY TEMPLATE ---");
        
        $templates = ['service', 'manufacturing', 'trading', 'restaurant'];
        $testFeatures = ['materials', 'inventory', 'machines', 'work_orders', 'customers', 'invoices'];
        
        foreach ($templates as $template) {
            $business->template = $template;
            $this->line(strtoupper($template) . ":");
            
            foreach ($testFeatures as $feature) {
                $hasAccess = SubscriptionService::canAccessFeature($business, $feature);
                $status = $hasAccess ? '✅' : '❌';
                $this->line("  {$feature}: {$status}");
            }
            $this->newLine();
        }
    }
}