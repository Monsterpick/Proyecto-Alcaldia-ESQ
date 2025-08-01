<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'name',
        'description',
        'is_public',
        'is_tenant_editable',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_tenant_editable' => 'boolean',
    ];

    // Obtener el valor con el tipo correcto
    public function getTypedValueAttribute()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    // Método estático para obtener una configuración por su clave
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->typed_value;
    }

    // Método estático para establecer una configuración
    public static function set(string $key, $value)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }

        $setting->value = is_array($value) ? json_encode($value) : $value;
        return $setting->save();
    }

    
}
