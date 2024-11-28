<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturePlan extends Model
{
    use HasFactory;

    protected $table = 'plans';


    protected $fillable = [
        'start_date',
        'product_id',       // Added for foreign key reference
        'manufacture_plan_id',
        'color',
        'size',
        'product_quantity',
        'plan_created_by',
    ];

    public function rawMaterials()
    {
        return $this->hasMany(PlanRawMaterial::class, 'plan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function cutting()
    {
        return $this->hasOne(Cutting::class, 'plan_id');
    }
    public function printing()
    {
        return $this->hasOne(Printing::class, 'plan_id');
    }
    public function stiching()
    {
        return $this->hasOne(Stiching::class, 'plan_id');
    }
    public function final()
    {
        return $this->hasOne(FinalPlan::class, 'plan_id');
    }
}
