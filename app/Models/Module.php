<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'display_name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }
}