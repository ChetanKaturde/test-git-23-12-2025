<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBusiness;

class InventoryBatch extends Model
{
    use BelongsToBusiness;
    
    protected $table = 'inventory_batches';

 protected $fillable = [
    'batch_number',
    'supplier_batch_number',
    'purchase_order_id',
    'material_id',
    'warehouse_id',
    'received_quantity',
    'current_quantity',
    'remaining_quantity',
    'ordered_quantity',
    'unit_price',
    'quality_grade',
    'received_by',
    'received_date',
    'expiry_date',
    'status',
    'notes',
    'business_id',
];

protected $casts = [
    'received_date' => 'date',
    'expiry_date' => 'date',
    'unit_price' => 'decimal:2',
    'received_quantity' => 'integer',
    'current_quantity' => 'integer',
    'remaining_quantity' => 'decimal:2',  // ✅ Keep this as decimal if needed
    'ordered_quantity' => 'integer',
];


  protected static function booted()
{
    static::creating(function ($model) {
        // Set remaining_quantity ONLY if not provided
        if (is_null($model->remaining_quantity)) {
            $model->remaining_quantity = $model->received_quantity ?? 0;
        }
    });
}

   public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
  
  public function batch()
{
    return $this->belongsTo(InventoryBatch::class, 'batch_id');
}
    // Optional: If you store po_items separately
    public function poItems()
    {
        return $this->hasMany(InventoryPoItem::class, 'inventory_batch_id');
    }

    // Relationships
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'batch_id');
    }
  public function getInitialQuantityAttribute()
{
    return $this->received_quantity;
}

public function getInitialWeightAttribute()
{
    return $this->received_weight;
}
// In InventoryBatch.php
public function supplier()
{
    return $this->belongsTo(Supplier::class); // Make sure Supplier model exists
}

}