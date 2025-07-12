<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    public function tenant()
    {
        return $this->hasMany(Tenant::class);
    }
    protected $fillable = ['name', 'description'];
}
