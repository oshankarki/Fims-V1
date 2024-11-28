<?php

namespace App\Exports;

use App\Models\RawItem;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportRawItem implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RawItem::select(
            "name", 
            "actual_quantity", 
            "size", 
            "color", 
            "unit_id",
            "purchase_id",
            "warehouse_id",
            "iteminfo_id"
        )->get();
    }
}
