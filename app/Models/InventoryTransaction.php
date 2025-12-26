<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'transaction_id', 
        'batch_id', 
        'type', 
        'weight', 
        'quantity',
        'unit_price',           // <-- ADD THIS
        'total_value',          // <-- ADD THIS
        'reference_number',
      'dispatch_to',
    'purpose',
        'reason', 
        'metadata', 
        'transaction_date',
        'notes'                 // <-- ADD THIS (since you're passing notes too)
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'unit_price' => 'decimal:2',      // <-- ADD (optional but recommended)
        'total_value' => 'decimal:2',     // <-- ADD (optional but recommended)
        'metadata' => 'array',
        'transaction_date' => 'datetime'
    ];

    public function batch()
    {
        return $this->belongsTo(InventoryBatch::class, 'batch_id');
    }
public static function generateTransactionId()
{
    return 'TXN-' . \Illuminate\Support\Str::uuid()->toString();
}
  
  //  protected static function boot()
  //  {
    //    parent::boot();
        
      //  static::creating(function ($model) {
      //      $model->transaction_id = 'TXN' . date('Ymd') . str_pad(static::whereDate('created_at', today())->count() + 1, 6, '0', STR_PAD_LEFT);
     //   });
   // }
}
