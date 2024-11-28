<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Log;

class UnitController extends Controller
{
    //
    public function index()
    {
        $units = Unit::all();

        return view("pages.units.wrapper", compact("units"));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'unit_name' => 'required|string|max:255|unique:units,unit_name',
            'unit_system_default' => 'nullable|string|in:yes,no',
            'unit_time_default' => 'nullable|string|in:yes,no',
        ]);
        try {

            $unit = new Unit();

            $unit->unit_name = $validatedData["unit_name"];
            $unit->unit_system_default = $validatedData["unit_system_default"] ?? "yes";
            $unit->unit_time_default = $validatedData["unit_time_default"] ?? "yes";

            $unit->save();
            // $unit = Unit::create($request->all());


            return redirect()->back()->with("success", "Unit created successfully");
        } catch (Exception $err) {
            return response()->json(['message' => 'Failed to create unit', 'error' => $err->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        // 
        try {
            $unit = Unit::findOrFail($id);
            return view("pages.units.edit", compact("unit"));
        } catch (Exception $err) {
            Log::error("Error while editing unit: " . $err->getMessage());
            return redirect()->route('unit.index')->with("error", "Something went wrong. Try again later.");
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->unit_name = $request->input("unit_name");
            $unit->unit_system_default = $request->input("unit_system_default") ?? "yes";
            $unit->unit_time_default = $request->input("unit_time_default") ?? "yes";
            $unit->save();
            return redirect()->route('unit.index')->with("success", "Unit updated successfully");
        } catch (Exception $e) {
            Log::error("Error while updating unit: " . $e->getMessage());
            return redirect()->route('unit.index')->with("error", "Something went wrong.");
        }
    }

    public function delete($id)
    {
        // 
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            return redirect()->back()->with("success", "Successfully deleted unit");
        } catch (Exception $err) {
            Log::error("Error while deleting unit: " . $err->getMessage());
            return redirect()->back()->with("error", "Something went wrong. Try again later.");
        }
    }
}
