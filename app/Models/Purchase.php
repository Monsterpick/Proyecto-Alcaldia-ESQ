<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'purchase_order_id',
        'supplier_id',
        'warehouse_id',
        'total',
        'discount',
        'tax',
        'observation',
    ];

    //Relacion uno a muchos inversa
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }

    //Relacion uno a muchos inversa
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    //Relacion uno a muchos inversa
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
