<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'image_url'];

    /* public function getImageUrlAttribute($value)
    {
        return asset('storage/' . $value);
    } */
}
