<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display All Categories
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | Display Products by Category
    |--------------------------------------------------------------------------
    */

    public function show(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}