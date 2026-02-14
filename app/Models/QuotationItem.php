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
        'discount_percentage',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
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