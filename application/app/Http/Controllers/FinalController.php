<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\RawItem;
use App\Models\Stiching;
use App\Models\FinalPlan;
use App\Models\Warehouse;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ManufacturePlan;
use Illuminate\Support\Facades\DB;

class FinalController extends Controller
{
    public function index()
    {
        $finals = FinalPlan::with(['product', 'rawMaterials', 'plans', 'stiching'])->latest()->get();
        return view('final.index', compact('finals'));
    }

    public function edit($id)
    {
        $plan = ManufacturePlan::with(['rawMaterials.rawItem', 'rawMaterials.warehouse'])->find($id);
        $final_raw_materials = $plan->rawMaterials->where('isFinal', 1);
        $stiching = Stiching::where('plan_id', $plan->id)->first();


        $final = FinalPlan::where('plan_id', $plan->id)->first();

        $warehouses = Warehouse::all();
        $rawItems = RawItem::all();
        $departments = Department::where('type', 'final_production')->get();  // Fetch departments


        // Check if the plan has raw materials and pluck the correct quantities
        if ($plan->rawMaterials->isNotEmpty()) {
            $rawItemsQuantities = $plan->rawMaterials->pluck('quantity', 'raw_item_id');
        } else {
            // Default to an empty collection if no raw materials exist
            $rawItemsQuantities = collect();
        }

        return view('final.edit', compact('plan', 'rawItemsQuantities', 'rawItems', 'warehouses', 'final_raw_materials', 'final', 'stiching', 'departments'));
    }




    public function update(Request $request, $plan)
    {

        $manufacturePlan = ManufacturePlan::findOrFail($plan);

        DB::beginTransaction();

        try {
            // Delete existing plan raw materials
            // Add updated plan raw materials
            $rawItems = $request->input('raw_items', []);
            foreach ($rawItems as $rawItemData) {
                // Check if there's an existing raw material with the same raw_item_id
                $existingRawMaterial = $manufacturePlan->rawMaterials()->where('raw_item_id', $rawItemData['item_id'])->first();

                if ($existingRawMaterial) {
                    // If it exists, update its attributes
                    $existingRawMaterial->update([
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                        'isFinal' => 1, // Assuming this flag indicates it's for cutting
                    ]);
                } else {
                    // If it doesn't exist, create a new raw material entry
                    $manufacturePlan->rawMaterials()->create([
                        'raw_item_id' => $rawItemData['item_id'],
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                        'isFinal' => 1, // Assuming this flag indicates it's for cutting
                    ]);
                }
            }
            $stichingId = $request->input('stiching_id');
            if ($stichingId) {
                $stiching = Stiching::find($stichingId);
                if ($stiching) {
                    $stiching->update(['warehouse_id' => null]);
                }
            }
            // Create or update Cutting entry
            $final = FinalPlan::updateOrCreate(
                ['plan_id' => $manufacturePlan->id],
                [
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('finish_date'),
                    'product_id' => $request->input('product_id'),
                    'stiching_id' => $request->input('stiching_id'),
                    'department_id' => $request->input('department_id'), // Adjust this as per your form
                    'plan_id' => $manufacturePlan->id,
                    'created_by' => $request->input('created_by'), // Assuming you have user authentication
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Final plan created successfully',
                'redirect_url' => route('admin.final.index')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update final plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
    public function show($id)
    {
        $final = FinalPlan::with(['product', 'rawMaterials', 'plans', 'stiching'])->find($id);

        if (!$final) {
            abort(404, 'Final not found');
        }

        return view('final.show', compact('final'));
    }
    public function start(Request $request)
    {
        $finalId = $request->input('id');
        try {
            $final = FinalPlan::findOrFail($finalId);

            if ($final->status == 0) {
                // Set the start date to current date
                $final->start_date = Carbon::today()->toDateString();
                $final->status = 1; // Change status to 1 (processing)
            } elseif ($final->status == 1) {
                // Set the end date to current date
                $final->end_date = Carbon::today()->toDateString();
                $final->status = 2; // Change status to 2 (finished)
            }

            // Save the changes
            $final->save();

            return response()->json([
                'message' => 'Final plan status updated successfully.',
                'status' => $final->status,
                'start_date' => $final->start_date, // Assuming start_date is already in 'Y-m-d' format
                'end_date' => $final->end_date, // Assuming end_date is already in 'Y-m-d' format
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update final status.', 'error' => $e->getMessage()], 500);
        }
    }
    public function outputUpdate(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'id' => 'required|exists:finals,id',
                'output_name' => 'required|string|max:255',
                'output_quantity' => 'required|integer',
                'output_actual_quantity' => 'required|integer',
                'output_loss_quantity' => 'required|integer',
                'output_found_quantity' => 'required|integer',
                'output_damaged_quantity' => 'required|integer',
            ]);

            // Find the cutting record by ID
            $final = FinalPlan::find($request->id);

            if ($final) {
                // Update the final record with the new output data
                $final->output_name = $request->output_name;
                $final->output_quantity = $request->output_quantity;
                $final->output_actual_quantity = $request->output_actual_quantity;
                $final->output_loss_quantity = $request->output_loss_quantity;
                $final->output_found_quantity = $request->output_found_quantity;
                $final->output_damaged_quantity = $request->output_damaged_quantity;
                $final->actual_quality_A = $request->actual_quality_A;
                $final->actual_quality_B = $request->actual_quality_B;
                $final->quality_A_price = $request->quality_A_price;
                $final->quality_B_price = $request->quality_B_price;
                $product = $final->product;
                if ($product) {
                    $product->stock_a = $product->stock_a + $request->actual_quality_A;
                    $product->stock_b = $product->stock_b + $request->actual_quality_B;
                    $product->save();
                } else {
                    return response()->json(['notification' => ['type' => 'error', 'value' => 'Product record not found.']], 404);
                }
                $final->save();
                return response()->json(['message' => 'Output details updated successfully.']);
            } else {
                return response()->json(['notification' => ['type' => 'error', 'value' => 'Final record not found.']], 404);
            }
        } catch (Exception $e) {
            return response()->json(['notification' => ['type' => 'error', 'value' => 'An error was encountered processing your request']], 500);
        }
    }
    public function destroy($id)
    {
        try {
            // Find the cutting record
            $final = FinalPlan::findOrFail($id);

            // Access the related rawMaterials and update isFinal attribute to 0
            foreach ($final->rawMaterials as $rawMaterial) {
                $rawMaterial->isFinal = 0;
                $rawMaterial->save();
            }

            // Delete the final record
            $final->delete();

            // Redirect with a success message
            return redirect()->route('admin.final.index')->with('success', 'Final deleted successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during deletion
            return redirect()->route('admin.final.index')->with('error', 'An error occurred while trying to delete the finals: ' . $e->getMessage());
        }
    }
}
