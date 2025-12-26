<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToBusiness;

class Payment extends Model
{
    use SoftDeletes, BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_no',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}