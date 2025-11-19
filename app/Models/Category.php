<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_active',
    ];

    //Relacion uno a muchos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
