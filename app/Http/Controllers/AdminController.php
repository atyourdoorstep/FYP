<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{




    public function catIndex()
    {
//        return ValidateUserSession(view('admin.index', ['data' => Organization::paginate(10)]), 'canView');
        return (view('admin.catIndex', ['data' => Category::with('children')->whereNull('category_id')->paginate(10)]));
    }
}
