<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    public function tenant()
    {
        return $this->hasMany(Tenant::class);
    }

    protected $fillable = ['estado', 'iso_3166-2'];

    public function municipios()
    {
        return $this->hasMany(Municipio::class);
    }

    
}
