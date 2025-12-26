<?php

// app/Models/Batch.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'purchase_order_id',
        'material_id',
        'vendor_id',
        'received_date',
        'manufacturing_date',
        'expiry_date',
        'received_quantity',
        'current_quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'status',
        'location',
        'quality_notes',
        'received_by'
    ];

    protected $casts = [
        'received_date' => 'date',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'received_quantity' => 'decimal:3',
        'current_quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    // Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }

    public function returns()
    {
        return $this->hasMany(Returns::class);
    }

    public function qualityChecks()
    {
        return $this->hasMany(QualityCheck::class);
    }

    public function barcodes()
    {
        return $this->morphMany(Barcode::class, 'barcodeable');
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isNearExpiry($days = 30)
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days;
    }
}