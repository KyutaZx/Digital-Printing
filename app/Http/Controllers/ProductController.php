<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display All Products
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $products = Product::where('is_active', true)
                    ->with('category')
                    ->paginate(12);

        return view('products.index', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show Product Detail
    |--------------------------------------------------------------------------
    */

    public function show(Product $product)
    {
        $product->load([
            'sizes',
            'materials.material',
            'finishing.finishing'
        ]);

        return view('products.show', compact('product'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show Products by Category
    |--------------------------------------------------------------------------
    */

    public function byCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $products = Product::where('category_id', $categoryId)
                    ->where('is_active', true)
                    ->paginate(12);

        return view('products.category', compact('products', 'category'));
    }
}