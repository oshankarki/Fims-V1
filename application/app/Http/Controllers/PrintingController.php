<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Cutting;
use App\Models\RawItem;
use App\Models\Printing;
use App\Models\Warehouse;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ManufacturePlan;
use Illuminate\Support\Facades\DB;

class PrintingController extends Controller
{
    public function index()
    {
        $printings = Printing::with(['product', 'rawMaterials', 'plans', 'cutting'])->latest()->get();
        return view('printing.index', compact('printings'));
    }
    public function edit($id)
    {
        $plan = ManufacturePlan::with(['rawMaterials.rawItem', 'rawMaterials.warehouse'])->find($id);
        $printing_raw_materials = $plan->rawMaterials->where('isPrinting', 1);
        $cutting = Cutting::where('plan_id', $plan->id)->first();

        $printing = Printing::where('plan_id', $plan->id)->first();

        $warehouses = Warehouse::all();
        $rawItems = RawItem::all();
        $departments = Department::where('type', 'printing')->get();  // Fetch departments

        // Check if the plan has raw materials and pluck the correct quantities
        if ($plan->rawMaterials->isNotEmpty()) {
            $rawItemsQuantities = $plan->rawMaterials->pluck('quantity', 'raw_item_id');
        } else {
            // Default to an empty collection if no raw materials exist
            $rawItemsQuantities = collect();
        }

        return view('printing.edit', compact('plan', 'rawItemsQuantities', 'rawItems', 'warehouses', 'printing_raw_materials', 'printing', 'cutting', 'departments'));
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
                        'raw_item_id' => $rawItemData['item_id'],
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                        'isPrinting' => 1, // Assuming this flag indicates it's for cutting
                    ]);
                } else {
                    // If it doesn't exist, create a new raw material entry
                    $manufacturePlan->rawMaterials()->create([
                        'raw_item_id' => $rawItemData['item_id'],
                        'warehouse_id' => $rawItemData['warehouse'],
                        'quantity' => $rawItemData['quantity'],
                        'unit_id' => $rawItemData['unit'],
                        'isPrinting' => 1, // Assuming this flag indicates it's for cutting
                    ]);
                }
            }

            // Create or update Cutting entry
            $printing = Printing::updateOrCreate(
                ['plan_id' => $manufacturePlan->id],
                [
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('finish_date'),
                    'product_id' => $request->input('product_id'),
                    'cutting_id' => $request->input('cutting_id'),
                    'department_id' => $request->input('department_id'), // Adjust this as per your form
                    'plan_id' => $manufacturePlan->id,
                    'created_by' => $request->input('created_by'), // Assuming you have user authentication
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Printing plan created successfully',
                'redirect_url' => route('admin.printing.index')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update printing plan',
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
        $printing = Printing::with(['product', 'rawMaterials', 'plans', 'cutting'])->find($id);

        if (!$printing) {
            abort(404, 'printing not found');
        }

        return view('printing.show', compact('printing'));
    }
    public function start(Request $request)
    {
        $printingId = $request->input('id');
        try {
            $printing = Printing::findOrFail($printingId);

            if ($printing->status == 0) {
                // Set the start date to current date
                $printing->start_date = Carbon::today()->toDateString();
                $printing->status = 1; // Change status to 1 (processing)
            } elseif ($printing->status == 1) {
                // Set the end date to current date
                $printing->end_date = Carbon::today()->toDateString();
                $printing->status = 2; // Change status to 2 (finished)
            }

            // Save the changes
            $printing->save();

            return response()->json([
                'message' => 'Printing status updated successfully.',
                'status' => $printing->status,
                'start_date' => $printing->start_date, // Assuming start_date is already in 'Y-m-d' format
                'end_date' => $printing->end_date, // Assuming end_date is already in 'Y-m-d' format
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update printing status.', 'error' => $e->getMessage()], 500);
        }
    }
    public function outputUpdate(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'id' => 'required|exists:printings,id',
                'output_name' => 'required|string|max:255',
                'output_quantity' => 'required|integer',
                'output_actual_quantity' => 'required|integer',
                'output_loss_quantity' => 'required|integer',
                'output_found_quantity' => 'required|integer',
                'output_damaged_quantity' => 'required|integer',
            ]);

            // Find the cutting record by ID
            $printing = Printing::find($request->id);

            if ($printing) {
                // Update the printing record with the new output data
                $printing->output_name = $request->output_name;
                $printing->output_quantity = $request->output_quantity;
                $printing->output_actual_quantity = $request->output_actual_quantity;
                $printing->output_loss_quantity = $request->output_loss_quantity;
                $printing->output_found_quantity = $request->output_found_quantity;
                $printing->output_damaged_quantity = $request->output_damaged_quantity;
                $printing->save();

                return response()->json(['message' => 'Output details updated successfully.']);
            } else {
                return response()->json(['notification' => ['type' => 'error', 'value' => 'Printing record not found.']], 404);
            }
        } catch (Exception $e) {
            return response()->json(['notification' => ['type' => 'error', 'value' => 'An error was encountered processing your request']], 500);
        }
    }
    public function destroy($id)
    {
        try {
            // Find the cutting record
            $printing = Printing::findOrFail($id);

            // Access the related rawMaterials and update isprinting attribute to 0
            foreach ($printing->rawMaterials as $rawMaterial) {
                $rawMaterial->isPrinting = 0;
                $rawMaterial->save();
            }

            // Delete the printing record
            $printing->delete();

            // Redirect with a success message
            return redirect()->route('admin.printing.index')->with('success', 'printing deleted successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during deletion
            return redirect()->route('admin.printing.index')->with('error', 'An error occurred while trying to delete the printing: ' . $e->getMessage());
        }
    }
}
