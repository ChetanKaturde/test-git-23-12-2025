<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SalesRepresentative extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'representative_id',
        'date_of_birth',
        'company_name',
        'territory_region',
        'status',
        'current_address',
        'languages_spoken',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->representative_id)) {
                do {
                    $id = 'MNBZ' . strtoupper(Str::random(8));
                } while (self::where('representative_id', $id)->exists());
                $model->representative_id = $id;
            }
        });
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'sales_representative_id', 'representative_id');
    }
}
