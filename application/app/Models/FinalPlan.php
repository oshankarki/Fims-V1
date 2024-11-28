<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalPlan extends Model
{
    use HasFactory;
    protected $table = "finals";
    protected $fillable = [
        'start_date',
        'end_date',
        'product_id',
        'department_id',
        'plan_id',
        'stiching_id',
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
    public function stiching()
    {
        return $this->belongsTo(Stiching::class, 'stiching_id');
    }
}
