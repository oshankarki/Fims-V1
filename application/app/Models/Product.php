<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'color',
        'size',
        'image',
        'actual_quantity',
        'warehouse_id',
        'unit_id',
        'stock_a',
        'stock_b',

        'variant',
        'price',
        'image',
        'stock',
        'type'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sales::class, "sales_products", "product_id", "sales_id");
    }

    public function warehouseTransferItems()
    {
        return $this->hasMany(WarehouseTransferItem::class, 'transferable_id', 'id');
    }

}
