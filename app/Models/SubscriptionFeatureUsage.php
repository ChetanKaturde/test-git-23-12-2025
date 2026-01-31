<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatureUsage extends Model
{
    use HasFactory;

    protected $table = 'subscription_feature_usage';

    protected $fillable = [
        'subscription_id',
        'feature_name',
        'used_count',
        'limit',
    ];

    protected $casts = [
        'used_count' => 'integer',
        'limit' => 'integer',
    ];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
