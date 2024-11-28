<?php

namespace App\Repositories\Interface;

use App\Models\ItemInfo;

interface ItemInfoInterface
{
    public function getAllItems();
    public function create(array $data);

    // public function getItemById(ItemInfo $itemInfo);
    // public function deleteItem(ItemInfo $itemInfo);
    // public function addItemInfo(array $attributes);
    // public function updateItemInfo(ItemInfo $itemInfo, array $attributes);
}