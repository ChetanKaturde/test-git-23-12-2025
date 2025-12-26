<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBusiness;

class Invitation extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'email',
        'role',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function getRoleDisplayName()
    {
        $roleDisplayNames = [
            'manager' => 'Manager',
            'inventory_manager' => 'Inventory Manager',
            'purchase_team' => 'Purchase Team',
            'operator' => 'Machine Operator',
            'viewer' => 'Viewer',
        ];

        return $roleDisplayNames[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
    }
}
