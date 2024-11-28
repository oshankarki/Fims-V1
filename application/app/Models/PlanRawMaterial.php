<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanRawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'raw_item_id',
        'warehouse_id',
        'quantity',
        'unit_id',
        'isCutting',
        'isPrinting',
        'isStiching',
        'isFinal'
    ];

    public function rawItem()
    {
        return $this->belongsTo(RawItem::class, 'raw_item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function plans()
    {
        return $this->hasMany(ManufacturePlan::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
