<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_name', 'contact_email', 'contact_phone', 'address'];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'vendor_id', 'id');
    }
}
