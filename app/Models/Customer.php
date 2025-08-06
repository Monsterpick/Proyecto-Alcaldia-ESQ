<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'identity_id',
        'document_number',
        'name',
        'address',
        'phone',
        'email',
    ];

    //Relacion uno a muchos inversa
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    //Relacion uno a muchos
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    //Relacion uno a muchos
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
