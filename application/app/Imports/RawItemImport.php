<?php

namespace App\Imports;

use App\Models\ItemInfo;
use App\Models\RawItem;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RawItemImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = ItemInfo::findOrFail($row['iteminfoid'])->name;

        return new RawItem([
            // 
            'actual_quantity' => $row['actual_quantity'],
            'size' => $row['size'],
            'color' => $row['color'],
            'unit_id' => $row['unitid'],
            'purchase_id' => $row['purchaseid'],
            'iteminfo_id' => $row['iteminfoid'],
            'warehouse_id' => $row['warehouseid'],
            'name' => $name,

        ]);
    }
}
