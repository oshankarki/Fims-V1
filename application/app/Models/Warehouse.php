<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function planRawMaterials()
    {
        return $this->hasMany(PlanRawMaterial::class, 'warehouse_id', 'id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'warehouse_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function rawItems()
    {
        return $this->hasMany(RawItem::class, 'warehouse_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'warehouse_id', 'id');
    }
    public function stichings()
    {
        return $this->hasMany(Stiching::class, 'warehouse_id', 'id');
    }
}
