<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\RawItemImport;
use App\Models\PurchaseDetail;
use App\Models\RawItem;
use App\Models\Warehouse;
use App\Models\ItemInfo;
use App\Models\Unit;
use Illuminate\Http\Request;

class RawItemController extends Controller
{
    //
    public function index()
    {
        $raw_items = RawItem::orderBy('name', 'asc')->get();
        $purchases = PurchaseDetail::all();
        $warehouses = Warehouse::all();
        $itemInfos = ItemInfo::all();
        $units = Unit::all();

        return view("pages.rawitem.wrapper", compact("raw_items", "purchases", "warehouses", "units", "itemInfos"));
    }


    public function create(Request $request)
    {
        $raw_item = new RawItem();
        $raw_item->actual_quantity = $request->input("actual_quantity");
        $raw_item->color = $request->input('color');
        $raw_item->size = $request->input('size');
        // $raw_item->unit_id = $request->input("unit_id");
        $raw_item->purchase_id = $request->input("purchase_id");
        $raw_item->iteminfo_id = $request->input("iteminfo_id");
        $raw_item->warehouse_id = $request->input("warehouse_id");
        $raw_item->name = ItemInfo::findOrFail($raw_item->iteminfo_id)->name;

        $raw_item->save();

        return redirect()->back()->with("success", "Successfully added new Raw Item");
    }

    public function destroy($id)
    {
        $raw_items = RawItem::findOrFail($id);
        if (!$raw_items) {
            return redirect()->back()->with("error", "Item not found");
        }

        $raw_items->delete();
        return redirect()->back()->with("success", "Item deleted successfully.");
    }
}
