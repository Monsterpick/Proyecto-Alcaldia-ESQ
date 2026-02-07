<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    public function tenant()
    {
        return $this->hasMany(Tenant::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    protected $fillable = ['estado_id', 'municipio'];

    public function parroquias()
    {
        return $this->hasMany(Parroquia::class);
    }
}
