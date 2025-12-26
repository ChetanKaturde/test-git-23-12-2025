<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBusiness;

class Customer extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'gstin',
        'contact_person',
        'is_active',
        'billing_address',
        'shipping_address',
        'customer_type',
        'payment_terms',
        'default_currency',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function invoices()
    {
        // Since invoices don't have customer_id, we'll match by phone number
        return $this->hasMany(Invoice::class, 'customer_phone', 'phone');
    }
    
    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }
    
    public function primaryContact()
    {
        return $this->hasOne(CustomerContact::class)->where('is_primary', true);
    }
    
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->name . ($this->gstin ? " (GST: {$this->gstin})" : '');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->address, $this->city, $this->state, $this->pincode]);
        return implode(', ', $parts);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}