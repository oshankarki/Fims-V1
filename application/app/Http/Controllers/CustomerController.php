<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

use Log;

class CustomerController extends Controller
{
    //

    public function index()
    {
        $customers = Customer::all();

        return view('pages.customers.wrapper', compact('customers'));
    }

    public function create(Request $request)
    {
        try {

            $customer = new Customer;
            $customer->customer_name = $request->customer_name;
            $customer->contact_name = $request->contact_name;
            $customer->contact_email = $request->contact_email;
            $customer->contact_address = $request->contact_address;
            $customer->contact_phone = $request->contact_phone;

            $customer->save();

            return redirect()->back()->with('success', 'Customer created successfully.');
        } catch (\Exception $err) {
            Log::error("Something unexpected happened " . $err->getMessage());
            return redirect()->route('customer.index')->with('error', 'Something unexpected happened.');
        }
    }

    public function edit($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            return view('pages.customers.edit', compact('customer'));
        } catch (\Exception $error) {
            Log::error("Something unexpected happened " . $error->getMessage());
            return redirect()->route('customer.index')->with('error', 'Something unexpected happened.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->customer_name = $request->customer_name;
            $customer->contact_name = $request->contact_name;
            $customer->contact_email = $request->contact_email;
            $customer->contact_address = $request->contact_address;
            $customer->contact_phone = $request->contact_phone;

            $customer->save();

            return redirect()->route('customer.index')->with('success', 'Customer updated successfully.');
        } catch (\Exception $err) {
            Log::error("Something unexpected happened " . $err->getMessage());
            return redirect()->route('customer.index')->with('error', 'Something unexpected happened.');
        }
    }

    public function delete($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return redirect()->back()->with('success', 'Customer deleted successfully.');
        } catch (\Exception $err) {
            Log::error("Can't Delete, Something unexpected happened " . $err->getMessage());
            return redirect()->back()->with('error', 'Something unexpected happened.');
        }
    }


}
