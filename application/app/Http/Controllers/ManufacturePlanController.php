<?php

namespace App\Http\Controllers;

use index;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Cutting;
use App\Models\Product;
use App\Models\RawItem;
use App\Models\Printing;
use App\Models\Stiching;
use Barryvdh\DomPDF\PDF;
use App\Models\FinalPlan;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ManufacturePlan;
use App\Models\PlanRawMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManufacturePlanController extends Controller
{
    public function index()
    {
        $manufacturePlans = ManufacturePlan::latest()->get();
        return view('manufacture_plan.index', compact('manufacturePlans'));
    }

    public function create()
    {
        $rawItems = RawItem::with('warehouses')->orderBy('name', 'asc')->get();

        $products = Product::all();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $rawItemsQuantities = $rawItems->pluck('actual_quantity', 'id');
        $rawItemsUnits = $rawItems->mapWithKeys(function ($rawItem) {
            return [$rawItem->id => Unit::find($rawItem->itemInfos->unit_id)->unit_name];
        });
        return view('manufacture_plan.create', compact('rawItems', 'rawItemsQuantities', 'products', 'warehouses', 'rawItemsUnits', 'units'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'start_date' => 'required|date',
            'manufacture_plan_id' => 'required|unique:plans,manufacture_plan_id',
            'product_name' => 'required|exists:products,id',
            'color' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'product_quantity' => 'required|integer|min:1',
            'raw_items.*.item_id' => 'required|exists:raw_items,id',
            'raw_items.*.warehouse' => 'required|exists:warehouses,id',
            'raw_items.*.quantity' => 'required|integer|min:1',
            'raw_items.*.unit' => 'required|string|max:255',
        ]);
        $user = Auth::user();


        DB::beginTransaction();

        try {
            $planData = $request->only(['start_date', 'product_name', 'color', 'size', 'product_quantity', 'created_by', 'manufacture_plan_id']);
            $manufacturePlan = ManufacturePlan::create([
                'start_date' => $planData['start_date'],
                'product_id' => $planData['product_name'],  // Ensure product_id is set correctly
                'color' => $planData['color'],
                'size' => $planData['size'],
                'product_quantity' => $planData['product_quantity'],
                'plan_created_by' => $user->first_name,
                'manufacture_plan_id' => $planData['manufacture_plan_id'],

            ]);

            $rawItems = $request->input('raw_items', []);
            foreach ($rawItems as $rawItemData) {
                // Fetch the specific RawItem by ID
                $stock = RawItem::findOrFail($rawItemData['item_id']);
                // Update the available quantity
                $stock->actual_quantity -= $rawItemData['quantity'];
                // Save the updated quantity to the database
                $stock->save();

                $rawItem = new PlanRawMaterial([
                    'raw_item_id' => $rawItemData['item_id'],
                    'warehouse_id' => $rawItemData['warehouse'],  // Ensure warehouse_id is set correctly
                    'quantity' => $rawItemData['quantity'],
                    'unit_id' => $rawItemData['unit'],
                    'plan_id' => $manufacturePlan->id  // Set plan_id correctly
                ]);

                $rawItem->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Manufacture plan created successfully',
                'redirect_url' => route('admin.manufacture_plan.index')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create manufacture plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function show($id)
    {
        $plan = ManufacturePlan::with(['rawMaterials.rawItem', 'rawMaterials.warehouse'])->find($id);

        if (!$plan) {
            abort(404, 'Manufacture Plan not found');
        }

        return view('manufacture_plan.show', compact('plan'));
    }


    public function edit($id)
    {
        $plan = ManufacturePlan::with(['rawMaterials.rawItem', 'rawMaterials.warehouse'])->find($id);
        $warehouses = Warehouse::all();
        $plan->start_date = Carbon::parse($plan->start_date);
        $rawItems = RawItem::with('warehouses')->get();
        $rawItemsQuantities = $rawItems->pluck('actual_quantity', 'id');

        return view('manufacture_plan.edit', compact('plan',  'rawItemsQuantities', 'rawItems', 'warehouses'));
    }


    public function update(Request $request, $plan)
    {
        $manufacturePlan = ManufacturePlan::find($plan);

        if (!$manufacturePlan) {
            return response()->json([
                'message' => 'Manufacture plan not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Store the old raw items data for comparison
            $oldRawItems = $manufacturePlan->rawMaterials->keyBy('raw_item_id')->toArray();

            // Update manufacture plan details
            $manufacturePlan->update([
                'start_date' => $request->input('start_date'),
                'product_id' => $request->input('product_id'),
                'color' => $request->input('color'),
                'size' => $request->input('size'),
                'product_quantity' => $request->input('product_quantity'),
                'plan_created_by' => $request->input('plan_created_by'),
            ]);

            // Delete existing plan raw materials
            $rawItems = $request->input('raw_items', []);
            foreach ($rawItems as $rawItemData) {
                // Check if there's an existing raw material with the same raw_item_id
                $existingRawMaterial = $manufacturePlan->rawMaterials()->where('raw_item_id', $rawItemData['item_id'])->first();

                if ($existingRawMaterial) {
                    // If it exists, update its attributes
                    $existingRawMaterial->update([
                        'raw_item_id' => $rawItemData['item_id'],
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                    ]);

                    // Calculate the difference in quantity and update available quantity
                    $oldQuantity = $oldRawItems[$rawItemData['item_id']]['quantity'];
                    $quantityDifference = $rawItemData['quantity'] - $oldQuantity;
                    $stock = RawItem::findOrFail($rawItemData['item_id']);
                    $stock->actual_quantity -= $quantityDifference;
                    $stock->save();
                } else {
                    // If it doesn't exist, create a new raw material entry
                    $manufacturePlan->rawMaterials()->create([
                        'raw_item_id' => $rawItemData['item_id'],
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                    ]);

                    // Update available quantity for the new raw material
                    $stock = RawItem::findOrFail($rawItemData['item_id']);
                    $stock->actual_quantity -= $rawItemData['quantity'];
                    $stock->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Manufacture plan updated successfully',
                'redirect_url' => route('admin.manufacture_plan.index')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update manufacture plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function destroy(ManufacturePlan $manufacture_plan)
    {
        try {
            $manufacture_plan->rawMaterials()->delete();
            $manufacture_plan->cutting()->delete();
            $manufacture_plan->printing()->delete();
            $manufacture_plan->stiching()->delete();
            $manufacture_plan->final()->delete();

            // Attempt to delete the plan
            $manufacture_plan->delete();

            // Redirect with a success message
            return redirect()->route('admin.manufacture_plan.index')->with('success', 'Plan deleted successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during deletion
            return redirect()->route('admin.manufacture_plan.index')->with('error', 'An error occurred while trying to delete the plan: ' . $e->getMessage());
        }
    }

    // app/Http/Controllers/RawItemController.php



    public function getWarehouse(Request $request)
    {
        $itemId = $request->input('id');

        // Fetch the raw item with the given ID
        $rawItem = RawItem::find($itemId);

        // Fetch the warehouse associated with this raw item
        if ($rawItem) {
            $warehouse = Warehouse::find($rawItem->warehouse_id);
            $options = '<option value="' . $warehouse->id . '">' . $warehouse->name . '</option>';
        } else {
            $options = '<option value="">Select Warehouse</option>';
        }

        return response()->json($options);
    }
    public function getUnit(Request $request)
    {
        $itemId = $request->input('id');

        // Fetch the raw item with the given ID
        $rawItem = RawItem::find($itemId);

        // Initialize options variable
        $options = '';

        // If raw item found, fetch unit_name from units table based on unit_id
        if ($rawItem) {
            $unit = Unit::find($rawItem->itemInfos->unit_id);

            // If unit found, create an option with unit_name
            if ($unit) {
                $options = '<option value="' . $unit->unit_id . '">' . $unit->unit_name . '</option>';
            } else {
                $options = '<option value="">Unit Not Found</option>';
            }
        } else {
            $options = '<option value="">Raw Item Not Found</option>';
        }

        return response()->json($options);
    }
    public function fetchProductDetails(Request $request)
    {
        $productId = $request->input('id');
        $product = Product::find($productId);

        if ($product) {
            $data = [
                'color' => $product->color,
                'size' => $product->size,
            ];

            return response()->json(['success' => true, 'data' => $data]);
        }

        return response()->json(['success' => false]);
    }
    public function full_plan($id)
    {
        // Fetch the main manufacture plan with its raw materials
        $plan = ManufacturePlan::with(['rawMaterials.rawItem', 'rawMaterials.warehouse', 'rawMaterials.unit'])->find($id);

        // Fetch the final plan associated with this manufacture plan
        $final = FinalPlan::with(['rawMaterials'])->where('plan_id', $plan->id)->first();

        // Fetch the cutting details associated with this manufacture plan
        $cutting = Cutting::with(['rawMaterials'])->where('plan_id', $plan->id)->first();

        // Fetch the printing details associated with this manufacture plan
        $printing = Printing::with(['rawMaterials'])->where('plan_id', $plan->id)->first();

        // Fetch the stitching details associated with this manufacture plan
        $stiching = Stiching::with(['rawMaterials'])->where('plan_id', $plan->id)->first();

        // If manufacture plan not found, abort with 404 error
        if (!$plan) {
            abort(404, 'Manufacture Plan not found');
        }

        // Pass all fetched data to the view
        return view('manufacture_plan.full_plan', compact('plan', 'cutting', 'stiching', 'final', 'printing'));
    }
    public function downloadPdf($id)
    {
        // Fetch the main manufacture plan with its related details
        $plan = ManufacturePlan::with([
            'rawMaterials.rawItem',
            'rawMaterials.warehouse',
            'rawMaterials.unit'
        ])->find($id);

        // If manufacture plan not found, abort with 404 error
        if (!$plan) {
            abort(404, 'Manufacture Plan not found');
        }

        // Fetch associated final plan, cutting, printing, and stitching details
        $final = FinalPlan::where('plan_id', $plan->id)->first();
        $cutting = Cutting::where('plan_id', $plan->id)->first();
        $printing = Printing::where('plan_id', $plan->id)->first();
        $stitching = Stiching::where('plan_id', $plan->id)->first();

        // Initialize PDF object
        $pdf = app('dompdf.wrapper');

        // Load the 'manufacture_plan.full_plan' view with data
        $pdf->loadView('manufacture_plan.full_plan', compact('plan', 'final', 'cutting', 'printing', 'stitching'));

        // Return the PDF as a download
        return $pdf->download('manufacture_plan_' . $id . '.pdf');
    }
    public function saveRemarks(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        $manufacturePlan = ManufacturePlan::findOrFail($id);
        $manufacturePlan->remarks = $request->input('remarks');
        $manufacturePlan->save();

        return redirect()->back()->with('success', 'Remarks saved successfully!');
    }
}
