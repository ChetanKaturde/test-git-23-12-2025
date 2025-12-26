<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    // Use UUIDs instead of auto-incrementing IDs
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable fields
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
    ];

    // Casts for JSON and datetime
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Auto-generate UUID for the primary key when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Polymorphic relationship (for users, admins, etc.)
    public function notifiable()
    {
        return $this->morphTo();
    }

    // Mark notification as read
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    // Check if notification is unread
    public function isUnread()
    {
        return $this->read_at === null;
    }

    // Scope to get unread notifications
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Scope to get dashboard-type notifications
    public function scopeDashboard($query)
    {
        return $query->where('type', 'dashboard');
    }
}
