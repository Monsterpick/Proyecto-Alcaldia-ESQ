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

    protected $fillable = ['municipio_id', 'parroquia'];
}
