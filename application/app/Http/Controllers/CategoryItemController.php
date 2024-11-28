<?php

namespace App\Http\Controllers;

use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryItemController extends Controller
{
    public function index()
    {
        $categories = CategoryItem::latest()->get();
        return view('category_item.index', compact('categories'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:item_categories,name'],
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category already exists.',
            'name.max' => 'The category name must not exceed 255 characters.',
        ]);

        $categoryItem = CategoryItem::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('admin.category_item.index')->with('success', 'Category Item created successfully.');
    }
    public function destroy($category_item)
    {
        $item = CategoryItem::findOrFail($category_item);
        $item->delete();
        return redirect()->route('admin.category_item.index')->with('success', 'Category Item deleted successfully.');
    }
    public function edit($category_item)
    {
        $item = CategoryItem::find($category_item);
        return view('category_item.edit', compact('item'));
    }
    public function update(Request $request, $category_item)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('item_categories')->ignore($category_item)],
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists.',
            'name.max' => 'The category name must not exceed 255 characters.',
        ]);
        $item = CategoryItem::findOrFail($category_item);
        $item->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('admin.category_item.index')->with('success', 'Category Item updated successfully.');
    }
}
