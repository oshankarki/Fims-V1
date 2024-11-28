<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $units = Unit::all();
        $warehouses = Warehouse::all();

        return view('products.create', compact('units', 'warehouses'));
    }

    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'quantity' => ['nullable', 'string', 'max:255'],
        //     'color' => ['nullable', 'string', 'max:255'],
        //     'size' => ['nullable', 'string', 'max:255'],
        //     'image' => ['nullable', 'string', 'max:255'],
        //     'actual_quantity' => ['nullable', 'numeric', 'max:255', 'default:0'],
        //     'stock_a' => ['nullable', 'numeric', 'max:255', 'default:0'],
        //     'stock_b' => ['nullable', 'numeric', 'max:255', 'default:0'],
        // ]);

        $data = $request->all();

        $data['actual_quantity'] = $request->input('actual_quantity') ?? 0;
        $data['stock_a'] = $request->input('stock_a') ?? 0;
        $data['stock_b'] = $request->input('stock_b') ?? 0;

        // Create product
        $product = Product::create($data);

        // $product = Product::create($request->input('stock_a'));

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'redirect_url' => route('admin.products.index') // Redirect URL after successful creation
            ]);
        }

        // Redirect for non-AJAX requests
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $units = Unit::all();
        $warehouses = Warehouse::all();

        return view('products.edit', compact('product', 'units', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->name = $request->input('name');
            $product->unit_id = $request->input('unit');
            $product->color = $request->input('color');
            $product->size = $request->input('size');
            
            $product->update();
    
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
        } catch (\Exception $err) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $results = Product::where('name', 'LIKE', '%' . $searchTerm . '%')->get();

        return view('products.search_results', compact('results', 'searchTerm'));
    }
}
