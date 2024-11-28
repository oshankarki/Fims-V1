<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\RawItem;
use App\Models\Printing;
use App\Models\Stiching;
use App\Models\Warehouse;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ManufacturePlan;
use Illuminate\Support\Facades\DB;

class StitchingController extends Controller
{
    public function index()
    {
        $stichings = Stiching::with(['product', 'rawMaterials', 'plans', 'printing'])->latest()->get();
        $warehouses = Warehouse::where('name', 'Stiching Warehouse')->get();
        return view('stiching.index', compact('stichings', 'warehouses'));
    }

    public function edit($id)
    {
        $plan = ManufacturePlan::with(['rawMaterials.rawItem', 'rawMaterials.warehouse'])->find($id);
        $stiching_raw_materials = $plan->rawMaterials->where('isStiching', 1);
        $printing = Printing::where('plan_id', $plan->id)->first();

        $stiching = Stiching::where('plan_id', $plan->id)->first();

        $warehouses = Warehouse::all();
        $rawItems = RawItem::all();
        $departments = Department::where('type', 'stiching')->get();  // Fetch departments


        // Check if the plan has raw materials and pluck the correct quantities
        if ($plan->rawMaterials->isNotEmpty()) {
            $rawItemsQuantities = $plan->rawMaterials->pluck('quantity', 'raw_item_id');
        } else {
            // Default to an empty collection if no raw materials exist
            $rawItemsQuantities = collect();
        }

        return view('stiching.edit', compact('plan', 'rawItemsQuantities', 'rawItems', 'warehouses', 'stiching_raw_materials', 'stiching', 'printing', 'departments'));
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
                        'isStiching' => 1, // Assuming this flag indicates it's for cutting
                    ]);
                } else {
                    // If it doesn't exist, create a new raw material entry
                    $manufacturePlan->rawMaterials()->create([
                        'raw_item_id' => $rawItemData['item_id'],
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                        'isStiching' => 1, // Assuming this flag indicates it's for cutting
                    ]);
                }
            }

            // Create or update Cutting entry
            $stiching = Stiching::updateOrCreate(
                ['plan_id' => $manufacturePlan->id],
                [
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('finish_date'),
                    'product_id' => $request->input('product_id'),
                    'printing_id' => $request->input('printing_id'),
                    'department_id' => $request->input('department_id'), // Adjust this as per your form
                    'plan_id' => $manufacturePlan->id,
                    'created_by' => $request->input('created_by'), // Assuming you have user authentication
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Stiching plan created successfully',
                'redirect_url' => route('admin.stiching.index')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update stiching plan',
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
        $stiching = Stiching::with(['product', 'rawMaterials', 'plans', 'printing'])->find($id);

        if (!$stiching) {
            abort(404, 'Stiching not found');
        }

        return view('stiching.show', compact('stiching'));
    }
    public function start(Request $request)
    {
        $stichingId = $request->input('id');
        try {
            $stiching = Stiching::findOrFail($stichingId);

            if ($stiching->status == 0) {
                // Set the start date to current date
                $stiching->start_date = Carbon::today()->toDateString();
                $stiching->status = 1; // Change status to 1 (processing)
            } elseif ($stiching->status == 1) {
                // Set the end date to current date
                $stiching->end_date = Carbon::today()->toDateString();
                $stiching->status = 2; // Change status to 2 (finished)
            }

            // Save the changes
            $stiching->save();

            return response()->json([
                'message' => 'Stiching status updated successfully.',
                'status' => $stiching->status,
                'start_date' => $stiching->start_date, // Assuming start_date is already in 'Y-m-d' format
                'end_date' => $stiching->end_date, // Assuming end_date is already in 'Y-m-d' format
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update stiching status.', 'error' => $e->getMessage()], 500);
        }
    }
    public function outputUpdate(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'id' => 'required|exists:stichings,id',
                'output_name' => 'required|string|max:255',
                'output_quantity' => 'required|integer',
                'output_actual_quantity' => 'required|integer',
                'output_loss_quantity' => 'required|integer',
                'output_found_quantity' => 'required|integer',
                'output_damaged_quantity' => 'required|integer',
            ]);

            // Find the cutting record by ID
            $stiching = Stiching::find($request->id);

            if ($stiching) {
                // Update the stiching record with the new output data
                $stiching->output_name = $request->output_name;
                $stiching->output_quantity = $request->output_quantity;
                $stiching->output_actual_quantity = $request->output_actual_quantity;
                $stiching->output_loss_quantity = $request->output_loss_quantity;
                $stiching->output_found_quantity = $request->output_found_quantity;
                $stiching->output_damaged_quantity = $request->output_damaged_quantity;
                $stiching->save();

                return response()->json(['message' => 'Output details updated successfully.']);
            } else {
                return response()->json(['notification' => ['type' => 'error', 'value' => 'Stiching record not found.']], 404);
            }
        } catch (Exception $e) {
            return response()->json(['notification' => ['type' => 'error', 'value' => 'An error was encountered processing your request']], 500);
        }
    }
    public function destroy($id)
    {
        try {
            // Find the cutting record
            $stiching = Stiching::findOrFail($id);

            // Access the related rawMaterials and update isprinting attribute to 0
            foreach ($stiching->rawMaterials as $rawMaterial) {
                $rawMaterial->isStiching = 0;
                $rawMaterial->save();
            }

            // Delete the printing record
            $stiching->delete();

            // Redirect with a success message
            return redirect()->route('admin.stiching.index')->with('success', 'Stiching deleted successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during deletion
            return redirect()->route('admin.stiching.index')->with('error', 'An error occurred while trying to delete the stiching: ' . $e->getMessage());
        }
    }
    public function warehouseUpdate(Request $request)
    {
        try {
            // Find the cutting record by ID
            $stiching = Stiching::find($request->id);
            if ($stiching) {
                // Update the stiching record with the new output data
                $stiching->warehouse_id = $request->warehouse_id;

                $stiching->save();

                return response()->json(['message' => 'Stored to warehouse .']);
            } else {
                return response()->json(['notification' => ['type' => 'error', 'value' => 'Stiching record not found.']], 404);
            }
        } catch (Exception $e) {
            return response()->json(['notification' => ['type' => 'error', 'value' => 'An error was encountered processing your request']], 500);
        }
    }
}
