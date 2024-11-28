<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "actual_quantity",
        "size",
        "color",
        // "unit_id",
        "purchase_id",
        "warehouse_id",
        "iteminfo_id"
    ];

    public function purchases()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function planRawMaterials()
    {
        return $this->hasMany(PlanRawMaterial::class, 'raw_item_id', 'id');
    }
    public function itemInfos()
    {
        return $this->belongsTo(ItemInfo::class, 'iteminfo_id', 'id');
    }

    public function warehouseTransferItems()
    {
        return $this->hasMany(WarehouseTransferItem::class, 'transferable_id', 'id');
    }

}
