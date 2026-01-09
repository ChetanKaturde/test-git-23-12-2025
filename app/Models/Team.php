<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'business_id',
    ];

    /**
     * Team belongs to a business
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Team has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
