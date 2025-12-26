<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'requested_by',
        'resolved',
    ];

    /**
     * Get the user who requested the material.
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
