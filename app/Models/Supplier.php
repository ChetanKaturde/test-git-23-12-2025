<?php
// app/Models/Supplier.php
namespace App\Models;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name']; // Add columns as needed



public function supplier()
{
    return $this->belongsTo(Supplier::class, 'supplier_id'); // Adjust key if needed
}
}