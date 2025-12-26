<?php

// app/Models/Returns.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_number',
        'dispatch_id',
        'batch_id',
        'material_id',
        'quantity',
        'unit',
        'return_date',
        'return_time',
        'return_type',
        'reason',
        'action_taken',
        'returned_by',
        'approved_by',
        'status'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'return_date' => 'date',
        'return_time' => 'time'
    ];

    // Relationships
    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function returner()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }
}