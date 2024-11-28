<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_id',
        'plan_id',
        'warehouse_id',
        'product_id',
        'category_id',
    ];
}
