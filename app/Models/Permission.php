<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
  
   protected $fillable = [
        'user_id', 
        'module_id', 
        'can_view', 
        'can_edit', 
        'can_create', 
        'can_delete', 
        'can_assign'
    ];

    /**
     * Permission belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Permission belongs to a module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Cast boolean fields
    protected $casts = [
        'can_view' => 'boolean',
        'can_edit' => 'boolean', 
        'can_create' => 'boolean',
        'can_delete' => 'boolean',
        'can_assign' => 'boolean',
    ];
}