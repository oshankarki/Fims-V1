<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "sales_date",
        "staff_id",
        "sales_quantity",
        "contact_phone",
    ];

    public function customers()
    {
        return $this->belongsTo(Customer::class, "customer_id");
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "sales_products", "sales_id", "product_id")->withPivot('quantity', 'stock_type');
    }
}
