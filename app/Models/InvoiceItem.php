<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'list_price',
        'discount_percentage',
        'net_price',
        'hsn_code',
        'taxable_value',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'tax_rate',
        'tax_amount',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'list_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'net_price' => 'decimal:2',
        'taxable_value' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'igst_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Calculate subtotal
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}