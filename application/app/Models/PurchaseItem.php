<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'item_info_id', 'item_quantity', 'total_price', 'unit_id'];


    public function purchases()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_id');
    }

    public function itemInfos()
    {
        return $this->belongsTo(ItemInfo::class, 'item_info_id');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

}
