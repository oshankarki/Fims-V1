<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Log;

class VendorController extends Controller
{
    //
    public function index()
    {
        $vendors = Vendor::all();

        return view("pages.vendor.wrapper", compact("vendors"));
    }

    public function create(Request $request)
    {
        $vendor = Vendor::create($request->all());

        return redirect()->back()->with("success", "Vendor created successfully");

    }

    public function edit($id)
    {
        $vendor = Vendor::find($id);

        return view("pages.vendor.edit", compact("vendor"));
    }

    public function update(Request $request, $id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                throw new \Exception("Vendor not found");
            }
            $vendor->update($request->all());

            return redirect()->route('vendor.index')->with("success", "Vendor updated successfully");
        } catch (\Exception $e) {
            Log::error("Something went wrong at vendor update  " . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


    public function delete($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return redirect()->back()->with("error", "Vendor not found");
        }

        $vendor->delete();

        return redirect()->back()->with("success", "Vendor deleted successfully");

    }
}
