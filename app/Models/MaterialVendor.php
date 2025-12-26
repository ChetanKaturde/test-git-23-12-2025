<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBusiness;

class MaterialVendor extends Model
{
    use BelongsToBusiness;
    
    protected $table = 'material_vendor';

    protected $fillable = [
        'vendor_id',
        'material_id',
        'business_id',
        'price_per_unit',
        'min_order_qty',
        'notes',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'min_order_qty' => 'integer',
    ];

    // Handle both old and new column names for backward compatibility
    public function getPricePerUnitAttribute($value)
    {
        return $value ?? $this->attributes['unit_price'] ?? null;
    }

    public function getMinOrderQtyAttribute($value)
    {
        return $value ?? $this->attributes['quantity'] ?? 1;
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
