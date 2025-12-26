<?php

namespace App\Models;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;

class PurchaseOrder extends Model
{
    use HasFactory; // BelongsToBusiness temporarily disabled

    protected $fillable = [
        'vendor_id',
        'po_date',
        'po_number',
        'status',
        'notes',
        'total_amount',
        'business_id',
    ];
  
  protected $casts = [
    'po_date' => 'datetime',
];


public function items()
{
    return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
}


public function vendor()
{
    return $this->belongsTo(\App\Models\Vendor::class);
}

  public function canBeEdited(): bool
{
    // Adjust the logic based on your business rules
    return $this->status === 'pending';
}
public function qualityAnalysis()
{
    return $this->hasOne(QualityAnalysis::class);
}

  
   /**
     * Check if this purchase order has a quality analysis
     */
    public function hasQualityAnalysis(): bool
    {
        return $this->qualityAnalysis()->exists();
    }

    /**
     * Scope to get purchase orders without quality analysis
     */
    public function scopeWithoutQualityAnalysis($query)
    {
        return $query->whereDoesntHave('qualityAnalysis');
    }

    /**
     * Scope to get purchase orders with quality analysis
     */
    public function scopeWithQualityAnalysis($query)
    {
        return $query->whereHas('qualityAnalysis');
    }

  protected static function booted()
{
    static::deleting(function ($order) {
        $url = route('purchase-orders.show', $order->id);

        // Delete notifications pointing to this PO
        \DB::table('notifications')
            ->where('type', 'dashboard')
            ->whereJsonContains('data->url', $url)
            ->delete();

        // Optional: Log who deleted it
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['po_id' => $order->id])
            ->log("Purchase Order #{$order->po_number} was deleted by " . auth()->user()->name);
    });
}

  public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

    /**
     * Work orders that used materials from this PO
     */
    public function workOrders()
    {
        return $this->hasManyThrough(
            WorkOrder::class,
            MaterialConsumption::class,
            'material_id', // Foreign key on material_consumptions table
            'id', // Foreign key on work_orders table
            'id', // Local key on purchase_orders table
            'work_order_id' // Local key on material_consumptions table
        );
    }

    /**
     * Get available materials from this PO for work orders
     */
    public function getAvailableMaterials()
    {
        return $this->items()->with('material')->get()->map(function($item) {
            return [
                'material_id' => $item->material_id,
                'material_name' => $item->material->name ?? $item->item_name,
                'available_quantity' => $item->quantity, // From inventory batches
                'unit' => $item->material->unit ?? 'pcs',
            ];
        });
    }


}
