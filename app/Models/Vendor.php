<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToBusiness;

class Vendor extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'phone',
        'business_id',

        // Company address
        'company_address',
        'company_state',
        'company_city',
        'company_pincode',
        'company_country',

        // Warehouse address
        'warehouse_address',
        'warehouse_state',
        'warehouse_city',
        'warehouse_pincode',
        'warehouse_country',

        // Bank info
        'bank_holder_name',
        'branch_name',
        'bank_name',
        'account_number',
        'ifsc_code',
    ];

    /**
     * Get the purchase orders associated with the vendor.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_vendor', 'vendor_id', 'material_id')
            ->withPivot('price_per_unit', 'min_order_qty', 'business_id', 'notes')
            ->withTimestamps();
    }
}
