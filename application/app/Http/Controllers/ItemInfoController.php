<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryItem;
use App\Models\ItemInfo;
use App\Models\Unit;
use Illuminate\Http\Request;
use Log;

use App\Repositories\Interface\ItemInfoInterface;

class ItemInfoController extends Controller
{
    //

    public function __construct(
        protected ItemInfoInterface $itemInfo
    ) {
        $this->itemInfo = $itemInfo;
    }

    public function index()
    {
        $item_data = $this->itemInfo->getAllItems();
        $categories = CategoryItem::latest()->get();
        $units = Unit::all();

        return view('pages.iteminfo.wrapper', compact('item_data', 'categories', 'units'));
    }


    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'img_url' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            $file = $request->file('img_url');
            if ($file) {
                $imageName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $imageName, 'public');
            } else {
                $imageName = null;
            }

            $data = [
                'name' => $request->input('name'),
                'img_url' => $imageName,
                'color' => $request->input('color'),
                'size' => $request->input('size'),
                'item_category_id' => $request->input('item_category_id'),
                'unit_id' => $request->input('unit_id')
            ];

            $this->itemInfo->create($data);

            return redirect()->back()->with('success', 'Item created successfully');
        } catch (\Exception $err) {
            Log::error("Something wrong at ItemInfo while creating a new Items Info");
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        try {
            $item = ItemInfo::findOrFail($id);
            $categories = CategoryItem::all();
            $units = Unit::all();

            return view('pages.iteminfo.edit', compact('item', 'categories', 'units'));
        } catch (\Exception $err) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = ItemInfo::findOrFail($id);

            // $request->validate([
            //     'name' => 'required',
            // ]);

            $data = [
                'name' => $request->input('name'),
                'color' => $request->input('color'),
                'size' => $request->input('size'),
                'item_category_id' => $request->input('item_category_id'),
                'unit_id' => $request->input('unit_id')
            ];

            if ($request->hasFile('img_url')) {
                if ($item->img_url) {
                    Storage::disk('public')->delete('images/' . $item->img_url);
                }

                $file = $request->file('img_url');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $imageName, 'public');
                $data['img_url'] = $imageName;
            }

            $item->update($data);

            return redirect()->route('item.index')->with('success', 'Item updated successfully');
        } catch (\Exception $err) {
            Log::error("Something went wrong at ItemInfo while updating an Item: " . $err->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
