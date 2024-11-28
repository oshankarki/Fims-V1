<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'purchase_date',
        'purchased_by',
        'total_price',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function rawitems()
    {
        return $this->hasMany(RawItem::class, 'purchase_id', 'id');
    }

    public function itemInfos()
    {
        return $this->hasManyThrough(ItemInfo::class, PurchaseItem::class, 'purchase_id', 'id', 'id', 'item_info_id');
    }

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
    
    public function purchasedItems() {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }

}
