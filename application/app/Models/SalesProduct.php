<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
    use HasFactory;

    protected $fillable = ['sales_id', 'product_id', 'quantity', 'stock_type'];

    public function sales() {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }

    public function products() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
