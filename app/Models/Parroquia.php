<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    public function tenant()
    {
        return $this->hasMany(Tenant::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function circuitosComunales()
    {
        return $this->hasMany(CircuitoComunal::class);
    }

    protected $fillable = ['municipio_id', 'parroquia'];

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('welcome_form_data'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('welcome_form_data'));
    }
}
