<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'module_id', 'can_view', 'can_create', 'can_edit', 'can_delete'];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean', 
        'can_edit' => 'boolean',
        'can_delete' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}