<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ManufactureProduct;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesProduct;
use Illuminate\Http\Request;
use DB;
use Log;

class SalesController extends Controller
{
    //

    public function index()
    {
        $sales = Sales::all();
        $customers = Customer::all();
        $manufactureproducts = ManufactureProduct::all();
        $products = Product::all();

        return view(
            "pages.sales.wrapper",
            compact("sales", "customers", "manufactureproducts", "products")
        );
    }

    public function create(Request $request)
    {
        $staff_id = auth()->user()->id;
        DB::beginTransaction();
        try {
            $sale = Sales::create([
                "customer_id" => $request->input("customer_id"),
                "sales_date" => $request->input("sales_date"),
                "staff_id" => $staff_id,
            ]);

            foreach ($request->products as $index => $productId) {
                $quantity = $request->quantities[$index];
                $stockType = $request->stock_types[$index];

                $product = Product::findOrFail($productId);

                SalesProduct::create([
                    'sales_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'stock_type' => $stockType,
                ]);

                if ($stockType === "stock_a") {
                    $product->stock_a -= $quantity;
                } elseif ($stockType === "stock_b") {
                    $product->stock_b -= $quantity;
                }

                $product->save();
            }

            DB::commit();
            return redirect()
                ->route("sales.index")
                ->with("success", "Purchase created successfully.");
        } catch (\Exception $e) {
            return redirect()
                ->route("sales.index")
                ->with("error", $e->getMessage());
        }
    }

    public function fetchSingleProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            "id" => $product->id,
            "stock_a" => $product->stock_a,
            "stock_b" => $product->stock_b,
        ]);
    }

    public function createPage()
    {
        $sales = Sales::all();
        $customers = Customer::all();
        $manufactureproducts = ManufactureProduct::all();
        $products = Product::all();

        return view(
            "pages.sales.create",
            compact("customers", "manufactureproducts", "products")
        );
    }

    public function delete($id)
    {
        $sales = Sales::find($id);
        $sales->delete();

        return redirect()->back()->with("success", "Successfully Deleted");
    }

    public function view($id)
    {
        try {
            $sale = Sales::with(['customers', 'products'])->findOrFail($id);
            return view("pages.sales.view", compact('sale'));
        } catch (\Exception $err) {
            Log::error("Something went wrong at Sales Controller View Function: " . $err->getMessage());
            return redirect()->back()->with("error", "Something went wrong");
        }
    }
}
