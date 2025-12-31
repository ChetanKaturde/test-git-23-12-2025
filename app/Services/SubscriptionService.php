<?php

namespace App\Services;

class SubscriptionService
{
    public static function getBasePlans(): array
    {
        return [
            'free' => [
                'name' => 'Free',
                'base_price' => 0,
                'billing' => 'forever',
                'limits' => [
                    'invoices_per_month' => 50,
                    'users' => 2,
                    'storage_gb' => 1,
                ],
                'core_features' => ['customers', 'quotations', 'invoices', 'basic_reports']
            ],
            'starter' => [
                'name' => 'Starter',
                'base_price' => 999,
                'billing' => 'monthly',
                'limits' => [
                    'invoices_per_month' => 500,
                    'users' => 5,
                    'storage_gb' => 10,
                ],
                'core_features' => ['customers', 'quotations', 'invoices', 'basic_reports', 'team_management']
            ],
            'professional' => [
                'name' => 'Professional',
                'base_price' => 2999,
                'billing' => 'monthly',
                'limits' => [
                    'invoices_per_month' => -1,
                    'users' => 20,
                    'storage_gb' => 50,
                ],
                'core_features' => ['customers', 'quotations', 'invoices', 'advanced_reports', 'team_management', 'api_access']
            ]
        ];
    }

    public static function getTemplateAddons(): array
    {
        return [
            'manufacturing' => [
                'name' => 'Manufacturing ERP',
                'addon_price' => [
                    'free' => 0, // Not available
                    'starter' => 500,
                    'professional' => 300, // Discount for higher plans
                ],
                'features' => ['materials', 'inventory', 'machines', 'work_orders', 'purchase_orders'],
                'tier' => 'full_erp'
            ],
            'service' => [
                'name' => 'Service Business',
                'addon_price' => [
                    'free' => 0,
                    'starter' => 200,
                    'professional' => 100,
                ],
                'features' => ['project_management', 'time_tracking'],
                'tier' => 'billing_sales'
            ],
            'trading' => [
                'name' => 'Trading & Distribution',
                'addon_price' => [
                    'free' => 0,
                    'starter' => 400,
                    'professional' => 200,
                ],
                'features' => ['inventory', 'vendors', 'purchase_orders', 'batch_tracking'],
                'tier' => 'full_erp'
            ],
            'restaurant' => [
                'name' => 'Restaurant POS',
                'addon_price' => [
                    'free' => 0,
                    'starter' => 300,
                    'professional' => 150,
                ],
                'features' => ['menu_management', 'table_management', 'kitchen_orders'],
                'tier' => 'billing_sales'
            ]
        ];
    }

    public static function calculatePrice(string $plan, string $template): int
    {
        $basePlans = self::getBasePlans();
        $templateAddons = self::getTemplateAddons();
        
        $basePrice = $basePlans[$plan]['base_price'] ?? 0;
        $addonPrice = $templateAddons[$template]['addon_price'][$plan] ?? 0;
        
        return $basePrice + $addonPrice;
    }

    public static function getAvailableFeatures(string $plan, string $template): array
    {
        $basePlans = self::getBasePlans();
        $templateAddons = self::getTemplateAddons();
        
        $coreFeatures = $basePlans[$plan]['core_features'] ?? [];
        $templateFeatures = $templateAddons[$template]['features'] ?? [];
        
        return array_merge($coreFeatures, $templateFeatures);
    }

    public static function getPlan(string $planName): ?array
    {
        return self::getBasePlans()[$planName] ?? null;
    }

    public static function getTemplate(string $templateName): ?array
    {
        return self::getTemplateAddons()[$templateName] ?? null;
    }

    public static function canAccessFeature($business, string $feature): bool
    {
        $availableFeatures = self::getAvailableFeatures(
            $business->subscription_plan ?? 'free',
            $business->template ?? 'service'
        );
        
        return in_array($feature, $availableFeatures);
    }

    public static function isWithinLimit($business, string $limitType, int $currentUsage): bool
    {
        $plan = self::getPlan($business->subscription_plan ?? 'free');
        $limit = $plan['limits'][$limitType] ?? 0;
        
        if ($limit === -1) return true; // unlimited
        return $currentUsage < $limit;
    }

    public static function getModulesForTier(string $tier): array
    {
        return match ($tier) {
            'billing_sales' => ['customers', 'quotations', 'invoices', 'reports'],
            'full_erp' => ['materials', 'machines', 'work_orders', 'inventory', 'vendors', 'purchase_orders', 'customers', 'quotations', 'invoices', 'reports'],
            default => ['customers', 'quotations', 'invoices']
        };
    }

    public static function getPricingTable(): array
    {
        $basePlans = self::getBasePlans();
        $templates = self::getTemplateAddons();
        $pricing = [];
        
        foreach ($basePlans as $planKey => $plan) {
            foreach ($templates as $templateKey => $template) {
                $pricing[$planKey][$templateKey] = [
                    'total_price' => self::calculatePrice($planKey, $templateKey),
                    'base_price' => $plan['base_price'],
                    'addon_price' => $template['addon_price'][$planKey] ?? 0,
                    'features' => self::getAvailableFeatures($planKey, $templateKey)
                ];
            }
        }
        
        return $pricing;
    }
}