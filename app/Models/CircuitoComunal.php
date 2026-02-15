<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CircuitoComunal extends Model
{
    protected $fillable = [
        'parroquia_id',
        'nombre',
        'codigo',
        'descripcion',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con Parroquia
     */
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    /**
     * Scope para circuitos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para filtrar por parroquia
     */
    public function scopeByParroquia($query, $parroquiaId)
    {
        return $query->where('parroquia_id', $parroquiaId);
    }

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('welcome_form_data'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('welcome_form_data'));
    }
}
