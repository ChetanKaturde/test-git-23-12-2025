<?php

// app/Models/Setting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_editable'
    ];

    protected $casts = [
        'is_editable' => 'boolean'
    ];

    // Helper methods
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(['key' => $key], ['value' => $value]);
        return $setting;
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}
