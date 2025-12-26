<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;

class MaterialConsumption extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'work_order_id',
        'material_id',
        'planned_quantity',
        'actual_quantity',
        'waste_quantity',
        'notes',
        'business_id',
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
        'waste_quantity' => 'decimal:2',
    ];

    // Relationships
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Calculate waste percentage
    public function getWastePercentageAttribute()
    {
        if ($this->actual_quantity > 0) {
            return round(($this->waste_quantity / $this->actual_quantity) * 100, 2);
        }
        return 0;
    }

    // Calculate efficiency
    public function getEfficiencyAttribute()
    {
        if ($this->planned_quantity > 0) {
            return round(($this->actual_quantity / $this->planned_quantity) * 100, 2);
        }
        return 0;
    }
}