<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PlanRawMaterial;
use App\Models\Product;
use App\Models\RawItem;
use App\Models\Warehouse;
use App\Models\WarehouseTransfer;
use App\Models\WarehouseTransferItem;
use Illuminate\Http\Request;
use Log;
use DB;

class HelperController extends Controller
{
    //

    public function transfer_index()
    {
        $warehouses = Warehouse::all();

        return view('pages.warehouse.transfer', compact('warehouses'));
    }

    public function transfer_warehouse(Request $request)
    {
        DB::beginTransaction();
        try {
            $oldWarehouseId = $request->input('old_warehouse_id');
            $newWarehouseId = $request->input('new_warehouse_id');
            $rawItemsTransferQuantity = $request->input('raw_item_transfer_quantity', []);
            $productsTransferQuantity = $request->input('product_transfer_quantity', []);

            $warehouseTransfer = WarehouseTransfer::create([
                'from_warehouse_id' => $oldWarehouseId,
                'to_warehouse_id' => $newWarehouseId,
            ]);

            foreach ($rawItemsTransferQuantity as $itemId => $quantity) {
                if ($quantity > 0) {
                    $rawItem = RawItem::findOrFail($itemId);
                    if ($rawItem->warehouse_id == $oldWarehouseId && $rawItem->actual_quantity >= $quantity) {
                        $a = RawItem::create([
                            'name' => $rawItem->name,
                            'warehouse_id' => $newWarehouseId,
                            'actual_quantity' => $quantity,
                            'unit_id' => $rawItem->unit_id,
                            'iteminfo_id' => $rawItem->iteminfo_id,
                        ]);

                        $rawItem->actual_quantity -= $quantity;

                        if ($rawItem->actual_quantity == 0) {
                            // $rawItem->destroy($rawItem->id);
                            $rawItem->delete();
                        } else {
                            $rawItem->save();
                        }

                        $warehouseTransfer->transferItems()->create([
                            'transferable_type' => RawItem::class,
                            'transferable_id' => $a->id,
                            'quantity' => $quantity,
                        ]);
                    }
                }
            }

            foreach ($productsTransferQuantity as $productId => $quantity) {
                if ($quantity > 0) {
                    $product = Product::findOrFail($productId);
                    if ($product->warehouse_id == $oldWarehouseId && $product->actual_quantity >= $quantity) {
                        $newProduct = Product::create([
                            'name' => $product->name,
                            'description' => $product->description,
                            'warehouse_id' => $newWarehouseId,
                            'actual_quantity' => $quantity,
                            'unit_id' => $product->unit_id,
                        ]);

                        $product->actual_quantity -= $quantity;
                        if ($product->actual_quantity == 0) {
                            // $product->destroy($product->id);
                            $product->delete();
                        } else {
                            $product->save();
                        }

                        // b
                        $warehouseTransfer->transferItems()->create([
                            'transferable_type' => Product::class,
                            'transferable_id' => $newProduct->id,
                            'quantity' => $quantity,
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('warehouse.index')->with('success', 'Successfully transferred');
        } catch (\Exception $error) {
            DB::rollBack();
            Log::error("Something is wrong with transfering warehouse " . $error->getMessage());
            return redirect()->route('warehouse.index')->with('error', 'Something went wrong');
        }
    }

    public function transfer_page()
    {
        $transfers = WarehouseTransfer::with(['fromWarehouse', 'toWarehouse'])->withSum('transferItems', 'quantity')->orderBy('created_at', 'desc')->get();

        return view('pages.warehouse.transfer.index', compact('transfers'));
    }

    public function transfer_view($id)
    {
        try {
            $transfer = WarehouseTransfer::with([
                'fromWarehouse',
                'toWarehouse',
                'transferItems.transferable'
            ])->findOrFail($id);

            $rawItems = $transfer->transferItems->where('transferable_type', RawItem::class);
            $products = $transfer->transferItems->where('transferable_type', Product::class);

            return view('pages.warehouse.transfer.show', compact('transfer', 'rawItems', 'products'));
        } catch (\Exception $error) {
            Log::error("Error occurred at transfer_view in HelperController: " . $error->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }



}
