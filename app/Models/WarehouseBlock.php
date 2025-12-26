<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseBlock extends Model
{
    protected $fillable = ['warehouse_id', 'name', 'rows', 'columns'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function slots()
    {
        return $this->hasMany(WarehouseSlot::class, 'block_id');
    }
}
