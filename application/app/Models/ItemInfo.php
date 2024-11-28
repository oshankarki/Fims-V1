<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "img_url",
        "color",
        "size",
        "item_category_id",
        "unit_id"
    ];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'item_info_id', 'id');
    }
    public function item_category()
    {
        return $this->belongsTo(CategoryItem::class, 'item_category_id', 'id');
    }

    public function units() {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
