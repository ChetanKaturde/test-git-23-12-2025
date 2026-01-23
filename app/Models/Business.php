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

    // Helper methods
    public function getSubdomainAttribute()
    {
        return $this->slug . '.monitorbizz.com';
    }

    public function isActive()
    {
        return $this->is_active && ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture());
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

    public function canInviteUser()
    {
        if ($this->subscription_plan !== 'free') {
            return true;
        }

        return \Cache::remember("business_{$this->id}_user_count", 3600, function () {
            return $this->users()->where('is_active', true)->count();
        }) < 2;
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