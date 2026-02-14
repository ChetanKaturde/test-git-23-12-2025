<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'legal_name',
        'slug',
        'email',
        'phone',
        'address',
        'is_active',
        'subscription_plan',
        'subscription_tier',
        'subscription_expires_at',
        'logo_path',
        'city',
        'state',
        'business_state',
        'business_city',
        'pin_code',
        'country',
        'gstin',
        'pan',
        'hsn_prefix',
        'currency',
        'financial_year_start',
        'payment_terms',
        'terms_and_conditions',
        'timezone',
        'sales_representative_id',
        'allowed_users',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'financial_year_start' => 'date',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function salesRepresentative()
    {
        return $this->belongsTo(SalesRepresentative::class, 'sales_representative_id', 'representative_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Helper methods
    public function getSubdomainAttribute()
    {
        return $this->slug . '.monitorbizz.com';
    }

    public function isActive()
    {
        return $this->is_active && ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture());
    }

    /**
     * ✅ FIXED: Check if business has a specific feature enabled
     * This method is required by User::businessHasFeature()
     */
    public function hasFeature($featureName)
    {
        // First check if there's an active subscription
        $subscription = $this->subscriptions()->active()->first();
        
        if ($subscription) {
            return $subscription->isFeatureEnabled($featureName);
        }
        
        // Fallback: Check subscription_tier for basic feature access
        if ($this->subscription_tier) {
            $tierFeatures = match ($this->subscription_tier) {
                'billing_sales' => ['quotation_management', 'invoice_management', 'customer_management'],
                'full_erp' => ['quotation_management', 'invoice_management', 'customer_management', 'inventory_management', 'purchase_order_management', 'vendor_management', 'machine_management', 'work_order_management'],
                default => ['quotation_management', 'invoice_management', 'customer_management', 'inventory_management', 'purchase_order_management', 'vendor_management', 'machine_management', 'work_order_management'],
            };
            
            return in_array($featureName, $tierFeatures);
        }
        
        // Default: no features available
        return false;
    }

    // Free Plan Limits
    public function canCreateInvoice()
    {
        if ($this->subscription_plan !== 'free') {
            return true;
        }

        return \Cache::remember("business_{$this->id}_invoice_count", 3600, function () {
            return $this->invoices()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
        }) < 50;
    }

    /**
     * ✅ TEAM MEMBER LIMIT ENFORCEMENT
     * Check if a new team member can be invited based on allowed_users limit
     * 
     * Rules:
     * - If allowed_users is null, allow unlimited users (fallback)
     * - If current_users < allowed_users, allow invitation
     * - If current_users >= allowed_users, block invitation
     */
    public function canInviteUser()
    {
        // If no allowed_users limit set, allow unlimited (fallback for existing businesses)
        if (empty($this->allowed_users)) {
            return true;
        }
        
        $currentUserCount = $this->users()->count();
        
        // Allow if current count is less than allowed users
        return $currentUserCount < $this->allowed_users;
    }

    /**
     * Get the allowed users limit for this business
     * Returns the value entered during registration
     */
    public function getAllowedUsers()
    {
        return $this->allowed_users;
    }

    /**
     * Check if the business has reached their user limit
     */
    public function hasReachedUserLimit()
    {
        // If no limit set, haven't reached limit
        if (empty($this->allowed_users)) {
            return false;
        }
        
        return $this->users()->count() >= $this->allowed_users;
    }

    /**
     * Get remaining users that can be added
     */
    public function getRemainingUserSlots()
    {
        if (empty($this->allowed_users)) {
            return PHP_INT_MAX; // Unlimited
        }
        
        $remaining = $this->allowed_users - $this->users()->count();
        return max(0, $remaining);
    }

    public function getInvoiceCount()
    {
        return \Cache::remember("business_{$this->id}_invoice_count", 3600, function () {
            return $this->invoices()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
        });
    }

    public function getActiveUserCount()
    {
        return \Cache::remember("business_{$this->id}_user_count", 3600, function () {
            return $this->users()->where('is_active', true)->count();
        });
    }
}