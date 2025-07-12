<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the patients associated with the blood type.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
