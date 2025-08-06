<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'type',
        'serie',
        'correlative',
        'date',
        'warehouse_id',
        'total',
        'discount',
        'tax',
        'observation',
        'reason_id',
    ];

    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }
}
