<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseSlot extends Model
{
    protected $fillable = ['block_id', 'row', 'column', 'status', 'batch_id'];

    public function block()
    {
        return $this->belongsTo(WarehouseBlock::class, 'block_id');
    }

    public function batch()
    {
        return $this->belongsTo(InventoryBatch::class);
    }
}
