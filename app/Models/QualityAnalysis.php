<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'purchase_order_item_id',
        'product_name',
        'product_category',
        'expected_volumetric_data',
        'expected_weight',
        'other_analysis_parameters',
        'actual_volumetric_data',
        'actual_weight',
        'vendor_id',
        'quantity_received',
        'quality_status',
        'remarks',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'barcode',
        'sku_id',
        'batch_number',
        'expiry_date',
        'manufacturing_date',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'expiry_date' => 'date',
        'manufacturing_date' => 'date',
        'expected_volumetric_data' => 'decimal:2',
        'actual_volumetric_data' => 'decimal:2',
        'expected_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'quantity_received' => 'decimal:2'
    ];

    // Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

  public function inventoryBatch()
{
    return $this->belongsTo(InventoryBatch::class);
}

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('quality_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('quality_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('quality_status', 'rejected');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];

        return $badges[$this->quality_status] ?? 'secondary';
    }

    // Generate SKU ID
    public function generateSkuId()
    {
        $prefix = strtoupper(substr($this->product_name, 0, 3));
        $category = strtoupper(substr($this->product_category, 0, 2));
        $vendor = $this->vendor ? strtoupper(substr($this->vendor->name, 0, 3)) : 'VEN';
        $id = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        
        return $prefix . $category . $vendor . $id;
    }

    // Generate Barcode
    public function generateBarcode()
    {
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $id = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        
        return $date . $id . $random;
    }
}