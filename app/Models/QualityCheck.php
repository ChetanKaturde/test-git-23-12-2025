<?php


// app/Models/QualityCheck.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'qc_number',
        'batch_id',
        'material_id',
        'check_date',
        'status',
        'test_parameters',
        'sample_quantity',
        'observations',
        'remarks',
        'checked_by',
        'approved_by',
        'approved_at',
        'certificate_path'
    ];

    protected $casts = [
        'check_date' => 'date',
        'test_parameters' => 'array',
        'sample_quantity' => 'decimal:3',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
