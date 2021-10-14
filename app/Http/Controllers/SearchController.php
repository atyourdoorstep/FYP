<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchSeller(Request $request)
    {
        return [
            'search'=> $request->search,
            'result'=>Seller::where('user_name', 'LIKE',"%". $request->search . "%")->get(),
        ];
    }
    public function searchCat(Request $request)
    {
        return [
            'result'=>Category::where('name', 'LIKE',"%". $request->search . "%")->get(),
        ];
    }
    public function searchItem(Request $request)
    {
        return [
            'result'=>Item::where('name', 'LIKE',  "%".$request->search . "%")->get(),
        ];
    }
}
