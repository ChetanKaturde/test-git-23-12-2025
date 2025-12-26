<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;
protected $table = 'purchase_order_items'; // only if not default

   protected $fillable = [
    'purchase_order_id',
    'item_name',
    'description',
    'unit_price',
    'quantity',
    'total_price',
];

// Removed material relationship since material_id doesn't exist in database


public function purchaseOrder()
{
    return $this->belongsTo(\App\Models\PurchaseOrder::class, 'purchase_order_id');
}


}
