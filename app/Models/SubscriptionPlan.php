<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_user',
        'status',
        'min_users',
        'max_users',
    ];

    protected $casts = [
        'price_per_user' => 'decimal:2',
        'min_users' => 'integer',
        'max_users' => 'integer',
    ];

    // Relationships
    public function planFeatures()
    {
        return $this->hasMany(PlanFeature::class, 'plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
