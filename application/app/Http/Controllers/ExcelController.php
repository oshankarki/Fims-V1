<?php

namespace App\Http\Controllers;

use App\Models\RawItem;
use Illuminate\Http\Request;
use App\Exports\ExportRawItem;
use App\Imports\RawItemImport;

class ExcelController extends Controller
{
    //

    public function addExcel(Request $request)
    {
        \Excel::import(new RawItemImport, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportExcelSheet(Request $request)
    {
        return \Excel::download(new ExportRawItem, 'rawitems.xlsx');
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $results = RawItem::where('name', 'LIKE', '%' . $searchTerm . '%')->get();

        return view('pages.rawitem.search_results', compact('results', 'searchTerm'));
    }
}
