<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'material_id',
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
        'total',
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
        'total' => 'decimal:2',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}