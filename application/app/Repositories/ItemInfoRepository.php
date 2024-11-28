<?php

namespace App\Repositories;

use App\Repositories\Interface\ItemInfoInterface;
use App\Models\ItemInfo;

class ItemInfoRepository implements ItemInfoInterface {

    public function getAllItems()
    {
        return ItemInfo::all();
    }

    public function create(array $data)
    {
        return ItemInfo::create($data);
    }
    
}

