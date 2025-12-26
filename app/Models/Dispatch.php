<?php

// app/Models/Dispatch.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_number',
        'batch_id',
        'material_id',
        'quantity',
        'unit',
        'dispatch_to',
        'purpose',
        'dispatch_date',
        'dispatch_time',
        'vehicle_number',
        'driver_name',
        'notes',
        'dispatched_by',
        'status'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'dispatch_date' => 'date',
        'dispatch_time' => 'time'
    ];

    // Relationships
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    public function returns()
    {
        return $this->hasMany(Returns::class);
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', 'dispatched');
    }
}