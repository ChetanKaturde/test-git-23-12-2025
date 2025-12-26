<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'batch_number', 'purchase_order_id', 'material_id',
        'received_weight', 'received_quantity', 'current_weight',
        'current_quantity', 'storage_location', 'received_date',
        'expiry_date', 'status', 'notes'
    ];

    protected $casts = [
        'received_weight' => 'decimal:3',
        'current_weight' => 'decimal:3',
        'received_date' => 'date',
        'expiry_date' => 'date'
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'batch_id');
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class, 'batch_id');
    }

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function (self $model): void {
            $todayCount = static::query()
                ->whereDate('created_at', today())
                ->count();
            
            $model->batch_number = 'BT' . now()->format('Ymd') . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}