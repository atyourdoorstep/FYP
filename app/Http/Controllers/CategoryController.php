<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index()
    {
        return view
        (
            'category.index',
            [
                'data' => Category::with('children')->
                whereNull('category_id')->get()
            ]
        );
    }

    public function create()//regCategory
    {
        $data = \request()->validate(
            [
                'name' => 'required',
                'description' => 'nullable',
                'category_id' => 'nullable',
            ]
        );
        Category::create($data);
        return redirect(route('category.list'));
    }

    public function edit($id)
    {
        return view(
            'category.index',
            [
                'data' => Category::with('children')->whereNull('category_id')->get(),
                'info' => Category::find($id)
            ]
        );
    }

    public function categoryTree($parent_id = 0, $sub_mark = '')
    {
        return view
        (
            'category.tree',
            [
                'data' => (Category::all()->whereNull('category_id'))
            ]
        );
    }
    public function update($id)
    {

        $data = \request()->validate(
            [
                'name' => 'required',
                'description' => 'nullable',
                'category_id' => 'nullable',
            ]
        );
        //dd($data);
        Category::where('id', $id)->update($data);
        return redirect('/catList/'.$data['category_id']);

    }
}
