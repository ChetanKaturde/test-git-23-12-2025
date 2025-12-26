<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $fillable = [
        'customer_id', 'name', 'email', 'phone', 'role', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}