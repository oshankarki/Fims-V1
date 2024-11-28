<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stiching extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'end_date',
        'product_id',
        'warehouse_id',
        'department_id',
        'plan_id',
        'printing_id',
        'status',
        'created_by'
    ];

    public function rawItem()
    {
        return $this->belongsTo(RawItem::class, 'raw_item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function rawMaterials()
    {
        return $this->hasManyThrough(PlanRawMaterial::class, ManufacturePlan::class, 'id', 'plan_id', 'plan_id', 'id');
    }

    public function plans()
    {
        return $this->belongsTo(ManufacturePlan::class, 'plan_id');
    }
    public function printing()
    {
        return $this->belongsTo(Printing::class, 'printing_id');
    }
    public function final()
    {
        return $this->hasOne(FinalPlan::class, 'plan_id', 'plan_id');
    }
}
