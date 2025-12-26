<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'contact_phone',
        'contact_email',
        'type',
        'is_default',
        'is_active',
       'capacity'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'warehouse_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state}";
    }

    public function getTypeDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->type));
    }

    // Methods
    public function assignUser($userId, $role = 'view_only')
    {
        return $this->users()->syncWithoutDetaching([$userId => ['role' => $role]]);
    }

    public function removeUser($userId)
    {
        return $this->users()->detach($userId);
    }

    public static function getTypes()
    {
        return [
            'main' => 'Main Warehouse',
            'cold_storage' => 'Cold Storage',
            'transit' => 'Transit Hub',
            'distribution' => 'Distribution Center',
            'temporary' => 'Temporary Storage'
        ];
    }

    public static function getRoles()
    {
        return [
            'view_only' => 'View Only',
            'editor' => 'Editor',
            'manager' => 'Manager',
            'admin' => 'Admin'
        ];
    }
  
  public function blocks()
{
    return $this->hasMany(\App\Models\WarehouseBlock::class);
}

  
}