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
        return \request()->all();
        $data = \request()->validate(
            [
                'name' => 'required',
                'description' => 'nullable',
                'category_id' => 'nullable',
            ]
        );
        Category::create($data);
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
        return $this->generateCategories(Category::all()->whereNull('category_id'));
    }

    public function generateCategories($categories)
    {
//        dd($categories);
        $view = '';
        foreach ($categories as $category) {
            $view = $view . '<li>' . $category->name . '</li>';
            if (count($category->children) > 0) {
                $view = $view . '<ul>';
//                $view=$view. '<li>';
                $view = $view . $this->generateCategories($category->children);
//                $view=$view. '</li>';
                $view = $view . '</ul>';
            }
        }
        return $view;
    }
}
