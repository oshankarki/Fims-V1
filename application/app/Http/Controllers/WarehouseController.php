<?php

namespace App\Http\Controllers;

use App\Models\ManufactureProduct;
use App\Models\PlanRawMaterial;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\PurchaseItem;
use App\Models\RawItem;
use App\Models\Stiching;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\WarehouseRepository;
use Log;
use DB;

class WarehouseController extends Controller
{
    /**
     * Warehouse Repository Instance
     */
    protected $warehouseRepo;

    /**
     * 
     * Construct 
     */
    public function __construct(WarehouseRepository $warehouseRepo)
    {
        // 
        $this->warehouseRepo = $warehouseRepo;
    }


    public function index()
    {
        $warehouses = $this->warehouseRepo->get();

        return view("pages.warehouse.wrapper", compact("warehouses"));
    }

    public function create(Request $request)
    {
        $warehouse = $this->warehouseRepo->create($request);
        $warehouses = $this->warehouseRepo->get();

        return redirect()->back()->with("success", "Warehouse created successfully");
    }

    public function edit($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            return view('pages.warehouse.edit', compact('warehouse'));
        } catch (\Exception $e) {
            return redirect()->route('warehouse.index')->with("error", "Warehouse not found");
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->name = $request->name;
            $warehouse->save();

            return redirect()->route("warehouse.index")->with("success", "Warehouse updated successfully");
        } catch (\Exception $e) {
            return redirect()->route('warehouse.index')->with("error", "Warehouse not found");
        }
    }

    public function viewData($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return view('pages.warehouse.view', compact('warehouse'));
    }

    public function removeWarehouse($id)
    {
        DB::beginTransaction();
        try {
            $purchaseDetailHasWarehouse = PurchaseItem::where("warehouse_id", $id)->count();
            $rawItemsHasWarehouse = RawItem::where('warehouse_id', $id)->count();
            $productsHasWarehouse = Product::where('warehouse_id', $id)->count();
            $planRawMaterialsHasWarehouse = PlanRawMaterial::where('warehouse_id', $id)->count();
            $stichingHasWarehouse = Stiching::where('warehouse_id', $id)->count();
            $manufacturedProductsHasWarehouse = ManufactureProduct::where('warehouse_id', $id)->count();

            if (
                $purchaseDetailHasWarehouse == 0 &&
                $rawItemsHasWarehouse == 0 &&
                $productsHasWarehouse == 0 &&
                $planRawMaterialsHasWarehouse == 0 &&
                $stichingHasWarehouse == 0 &&
                $manufacturedProductsHasWarehouse == 0
            ) {
                $this->warehouseRepo->delete($id);
                DB::commit();
                return redirect()->back()->with("success", "Successfully Removed the Warehouse");
            } else {
                DB::rollBack();
                return redirect()->back()->with("error", "The warehouse is used somewhere");
            }

        } catch (\Exception $err) {
            return redirect()->back()->with("error", "Something went wrong");
        }
    }

    public function items()
    {
        $products = Product::with('warehouse')->get();
        return view('warehouse_item.index', compact('products'));
    }

    public function ProductsJson($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);

            $products = $warehouse->products;

            return response()->json($products);
        } catch (\Exception $error) {
            Log::error("Something went wrong at ProductsJson in WarehouseController " . $error->getMessage());
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }
    }

    public function RawItemsJson($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);

            $rawitems = $warehouse->rawItems;

            return response()->json($rawitems);
        } catch (\Exception $error) {
            Log::error("Something went wrong at RawItemsJson in WarehouseController " . $error->getMessage());
            return response()->json([
                'error' => 'Something went wrong',
            ]);
        }
    }
}
