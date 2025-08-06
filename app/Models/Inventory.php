<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'detail',
        'quantity_in',
        'cost_in',
        'total_in',
        'quantity_out',
        'cost_out',
        'total_out',
        'quantity_balance',
        'cost_balance',
        'total_balance',
        'inventoryable_id',
        'inventoryable_type',
    ];

    //Relacion uno a muchos inversa
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //Relacion uno a muchos inversa
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    //Relacion polimorfica
    public function inventoryable()
    {
        return $this->morphTo();
    }
}
