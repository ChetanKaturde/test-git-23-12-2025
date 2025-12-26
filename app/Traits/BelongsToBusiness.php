<?php

namespace App\Traits;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToBusiness
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToBusiness()
    {
        // Auto-assign business_id when creating
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->business_id && !$model->business_id) {
                $model->business_id = Auth::user()->business_id;
            }
        });

        // Global scope to filter by business
        static::addGlobalScope('business', function (Builder $builder) {
            if (Auth::check() && Auth::user()->business_id) {
                $builder->where('business_id', Auth::user()->business_id);
            }
        });
    }

    /**
     * Relationship to business
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Scope to get records for specific business
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}