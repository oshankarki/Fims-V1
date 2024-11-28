<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RawItem;

class Unit extends Model
{

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $fillable = [
        "unit_name",
        "unit_system_default",
        "unit_time_default",
        "unit_created",
        "unit_updated",
        "unit_creatorid"
    ];

    protected $table = "units";

    protected $primaryKey = 'unit_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    // protected $guarded = ['unit_id'];
    const CREATED_AT = 'unit_created';
    const UPDATED_AT = 'unit_update';

    public function RawItems()
    {
        return $this->hasMany(RawItem::class);
    }

    public function itemInfos()
    {
        return $this->hasMany(ItemInfo::class, 'unit_id', 'id');
    }
}
