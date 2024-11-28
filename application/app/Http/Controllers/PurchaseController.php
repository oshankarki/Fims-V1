<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ItemInfo;
use App\Models\PurchaseDetail;
use App\Models\PurchaseItem;
use App\Models\RawItem;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class PurchaseController extends Controller
{
    //

    public function index()
    {
        $purchases = PurchaseDetail::all();
        $vendors = Vendor::all();
        $iteminfos = ItemInfo::all();
        $warehouses = Warehouse::all();

        return view("pages.purchase.wrapper", compact("purchases", "vendors", "iteminfos", "warehouses"));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'warehouses' => 'array|required|exists:warehouses,id',
            'purchase_date' => 'required|date',
            'purchased_by' => 'required|string',
            'total_price' => 'required|numeric',
            'item_infos' => 'required|array|exists:item_infos,id',
            'item_quantities' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $newPurchase = new PurchaseDetail();
            $newPurchase->vendor_id = $request->input('vendor_id');
            $newPurchase->purchase_date = $request->input('purchase_date');
            $newPurchase->purchased_by = $request->input('purchased_by');
            $newPurchase->total_price = $request->input('total_price');

            $newPurchase->save();

            $itemInfos = $request->item_infos;
            $itemQuantities = $request->item_quantities;
            $warehouses = $request->warehouses;

            if (!is_array($itemInfos) || !is_array($itemQuantities) || !is_array($warehouses)) {
                throw new \Exception("Invalid Input Data");
            }

            foreach ($itemInfos as $index => $item_info_id) {
                $purchase = new PurchaseItem();
                $purchase->purchase_id = $newPurchase->id;
                $purchase->item_info_id = $item_info_id;
                $purchase->item_quantity = $request->item_quantities[$index];
                $purchase->warehouse_id = $request->warehouses[$index];

                $purchase->save();

                $rawItem = RawItem::where('iteminfo_id', $item_info_id)->where('warehouse_id', $request->warehouses[$index])->first();

                if ($rawItem) {
                    $rawItem->actual_quantity += $request->item_quantities[$index];
                    $rawItem->update();
                } else {
                    RawItem::create([
                        'iteminfo_id' => $item_info_id,
                        'actual_quantity' => $request->item_quantities[$index],
                        'warehouse_id' => $request->warehouses[$index],
                        'purchase_id' => $purchase->purchase_id,
                        'name' => ItemInfo::find($item_info_id)->name,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Purchase created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $purchase = PurchaseDetail::findOrFail($id);
            $vendors = Vendor::all();
            $purchases = PurchaseDetail::all();
            $iteminfos = ItemInfo::all();
            $warehouses = Warehouse::all();
            $units = Unit::all();

            return view("pages.purchase.edit", compact('purchase', 'vendors', 'warehouses', 'purchases', 'iteminfos', 'units'));
        } catch (\Exception $err) {
            return redirect()->back()->with("errors", "Something went wrong");
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'warehouses' => 'array|required|exists:warehouses,id',
            'purchase_date' => 'required|date',
            'purchased_by' => 'required|string',
            'total_price' => 'required|numeric',
            'item_infos' => 'required|array|exists:item_infos,id',
            'item_quantities' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $purchase = PurchaseDetail::findOrFail($id);
            $purchase->vendor_id = $request->input('vendor_id');
            $purchase->purchase_date = $request->input('purchase_date');
            $purchase->purchased_by = $request->input('purchased_by');
            $purchase->total_price = $request->input('total_price');

            $purchase->save();

            $itemInfos = $request->item_infos;
            $itemQuantities = $request->item_quantities;
            $warehouses = $request->warehouses;

            if (!is_array($itemInfos) || !is_array($itemQuantities) || !is_array($warehouses)) {
                throw new \Exception("Invalid Input Data");
            }

            $oldPurchaseItems = PurchaseItem::where('purchase_id', $id)->get();
            foreach ($oldPurchaseItems as $oldItem) {
                $rawItem = RawItem::where('iteminfo_id', $oldItem->item_info_id)
                    ->where('warehouse_id', $oldItem->warehouse_id)
                    ->first();
                if ($rawItem) {
                    $rawItem->actual_quantity -= $oldItem->item_quantity;
                    $rawItem->update();
                }
                $oldItem->delete();
            }

            foreach ($itemInfos as $index => $item_info_id) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->item_info_id = $item_info_id;
                $purchaseItem->item_quantity = $request->item_quantities[$index];
                $purchaseItem->warehouse_id = $request->warehouses[$index];

                $purchaseItem->save();

                $rawItem = RawItem::where('iteminfo_id', $item_info_id)
                    ->where('warehouse_id', $request->warehouses[$index])
                    ->first();

                if ($rawItem) {
                    $rawItem->actual_quantity += $request->item_quantities[$index];
                    $rawItem->update();
                } else {
                    RawItem::create([
                        'iteminfo_id' => $item_info_id,
                        'actual_quantity' => $request->item_quantities[$index],
                        'warehouse_id' => $request->warehouses[$index],
                        'purchase_id' => $purchase->id,
                        'name' => ItemInfo::find($item_info_id)->name,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        $purchase = PurchaseDetail::find($id);
        if (!$purchase) {
            return redirect()->back()->with("error", "Vendor not found");
        }

        $purchase->delete();

        return redirect()->back()->with("success", "Vendor deleted successfully");
    }

    public function gotoPurchaseCreate()
    {

        $purchases = PurchaseDetail::all();
        $vendors = Vendor::all();
        $iteminfos = ItemInfo::all();
        $warehouses = Warehouse::all();
        $units = Unit::all();

        return view('pages.purchase.create', compact('purchases', 'vendors', 'iteminfos', 'warehouses', 'units'));
    }

    public function show($id)
    {
        try {
            $purchase = PurchaseDetail::findOrFail($id);

            $items = PurchaseItem::where('purchase_id', $purchase->id)->get();

            return view('pages.purchase.view', compact('purchase', 'items'));
        } catch (\Exception $err) {
            Log::error("Error at PurchaseController Show Function " . $err->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
