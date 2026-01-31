<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Feature;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'plan_id',
        'user_count',
        'start_date',
        'end_date',
        'status',
        'plan_snapshot',
        'sales_representative_id',
        'amount',
        'type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'plan_snapshot' => 'array',
        'amount' => 'decimal:2',
        'user_count' => 'integer',
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function salesRepresentative()
    {
        return $this->belongsTo(SalesRepresentative::class, 'sales_representative_id');
    }

    public function featureUsages()
    {
        return $this->hasMany(SubscriptionFeatureUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('end_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')->orWhere('end_date', '<', now());
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    public function getFeatureLimit($featureName)
    {
        $usage = $this->featureUsages()->where('feature_name', $featureName)->first();
        return $usage ? $usage->limit : null;
    }

    public function getFeatureUsage($featureName)
    {
        $feature = Feature::where('key', $featureName)->first();
        if ($feature && $feature->is_quantity_based) {
            // For quantity-based features, aggregate usage across all subscriptions for the business
            return SubscriptionFeatureUsage::whereHas('subscription', function ($query) {
                $query->where('business_id', $this->business_id);
            })->where('feature_name', $featureName)->sum('used_count');
        } else {
            // For non-quantity features, use per-subscription usage
            $usage = $this->featureUsages()->where('feature_name', $featureName)->first();
            return $usage ? $usage->used_count : 0;
        }
    }

    public function canUseFeature($featureName, $increment = 0)
    {
        // Check if feature is enabled in plan using updated method
        if (!$this->isFeatureEnabled($featureName)) {
            return false;
        }

        $limit = $this->getFeatureLimit($featureName);
        if ($limit === null) return true; // No limit
        $current = $this->getFeatureUsage($featureName);
        return ($current + $increment) <= $limit;
    }

    public function isFeatureEnabled($featureName)
    {
        $features = $this->plan_snapshot['features'] ?? [];
        
        // Direct match first
        if (isset($features[$featureName])) {
            return $features[$featureName]['enabled'] ?? false;
        }
        
        // Handle common key variations
        $keyMap = [
            'invoice_management' => 'Invoice Management',
            'quotation_management' => 'Quotation Management',
            'customer_management' => 'Customer Management',
            'commodity_management' => 'Commodity Management',
            'expense_management' => 'Expense Management',
            'team_management' => 'Team Management',
        ];
        
        // Try mapped key
        if (isset($keyMap[$featureName]) && isset($features[$keyMap[$featureName]])) {
            return $features[$keyMap[$featureName]]['enabled'] ?? false;
        }
        
        return false;
    }

    public function incrementFeatureUsage($featureName, $amount = 1)
    {
        $feature = Feature::where('key', $featureName)->first();
        if ($feature && $feature->is_quantity_based) {
            // For quantity-based features, increment on the current subscription
            // Usage will be aggregated across all subscriptions when checking limits
            $usage = $this->featureUsages()->where('feature_name', $featureName)->first();
            if ($usage) {
                $usage->increment('used_count', $amount);
            } else {
                $this->featureUsages()->create([
                    'feature_name' => $featureName,
                    'used_count' => $amount,
                    'limit' => $this->plan_snapshot['features'][$featureName]['limit'] ?? null,
                ]);
            }
        } else {
            // For non-quantity features, use per-subscription usage
            $usage = $this->featureUsages()->where('feature_name', $featureName)->first();
            if ($usage) {
                $usage->increment('used_count', $amount);
            } else {
                $this->featureUsages()->create([
                    'feature_name' => $featureName,
                    'used_count' => $amount,
                    'limit' => $this->plan_snapshot['features'][$featureName]['limit'] ?? null,
                ]);
            }
        }
    }
}
