<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('pages.department.wrapper', compact('departments'));
    }


    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'type' => 'required|in:cutting,printing,stiching,final_production',
        ]);

        $department = new Department();

        $department->name = $request->input('name');
        $department->type = $request->input('type');

        $department->save();

        return redirect()->back()->with('success', 'Department created successfully');
    }

    public function deleteDepartment($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return redirect()->back()->with('success', 'Department deleted successfully');
        } catch (\Exception $err) {
            Log::error("DepartmentController::deleteDepartment() - Error: " . $err->getMessage());
            return redirect()->back()->with('error', 'Department deletion failed');
        }

    }
}
