<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToBusiness;
use App\Traits\HasFinancialYearNumbering;

class Quotation extends Model
{
    use SoftDeletes, BelongsToBusiness, HasFinancialYearNumbering;

    protected $fillable = [
        'business_id',
        'customer_id',
        'number',
        'status',
        'valid_until',
        'notes',
        'sent_at',
        'converted_at',
        'subtotal',
        'tax_amount',
        'total',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'sent_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function markAsSent()
    {
        $this->update(['sent_at' => now()]);
    }

    public function isSent()
    {
        return $this->sent_at !== null;
    }
}