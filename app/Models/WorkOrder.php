<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;

class WorkOrder extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'wo_number',
        'machine_id',
        'operator_id',
        'assigned_to',
        'customer_id',
        'product_name',
        'quantity',
        'quoted_rate',
        'status',
        'started_at',
        'completed_at',
        'notes',
        'business_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'quantity' => 'integer',
        'quoted_rate' => 'decimal:2',
    ];

    // Relationships
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function materialConsumptions()
    {
        return $this->hasMany(MaterialConsumption::class);
    }

    // Status helpers
    public function isActive()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // Calculate duration
    public function getDurationAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }
        return null;
    }

    // Calculate total cost
    public function calculateCost()
    {
        $materialCost = $this->materialConsumptions->sum(function($consumption) {
            return ($consumption->actual_quantity ?? 0) * ($consumption->unit_cost ?? $consumption->material->unit_price ?? 0);
        });
        
        $laborCost = $this->duration ? ($this->duration / 60) * 100 : 0; // ₹100/hour
        $quotedAmount = $this->quoted_rate ? ($this->quantity * $this->quoted_rate) : 0;
        
        return max($materialCost + $laborCost, $quotedAmount);
    }

    // Get invoice for this work order
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}