<?php

// app/Models/ReportLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_name',
        'report_type',
        'filters_applied',
        'date_from',
        'date_to',
        'file_path',
        'status',
        'generated_by',
        'completed_at'
    ];

    protected $casts = [
        'filters_applied' => 'array',
        'date_from' => 'date',
        'date_to' => 'date',
        'completed_at' => 'datetime'
    ];

    // Relationships
    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
