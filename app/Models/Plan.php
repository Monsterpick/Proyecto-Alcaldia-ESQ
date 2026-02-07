<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Plan extends Model
{
    public function tenant()
    {
        return $this->hasMany(Tenant::class);
    }

    protected $fillable = ['name', 'description', 'price', 'trial_period_days', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected function activeText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->active ? 'Activa' : 'Inactiva',
        );
    }
}
