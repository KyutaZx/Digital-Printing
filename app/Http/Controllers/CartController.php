<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display User Cart
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $cart = Cart::with([
            'items.product',
            'items.size',
            'items.material',
            'items.finishing'
        ])
        ->where('user_id', Auth::id())
        ->first();

        return view('cart.index', compact('cart'));
    }

    /*
    |--------------------------------------------------------------------------
    | Add Item to Cart
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:product_sizes,id',
            'material_id' => 'required|exists:materials,id',
            'finishing_id' => 'nullable|exists:finishing_options,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
            'size_id' => $request->size_id,
            'material_id' => $request->material_id,
            'finishing_id' => $request->finishing_id,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'design_file' => $request->design_file
        ]);

        return redirect()->back()->with('success', 'Product added to cart');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Cart Item
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Cart updated');
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Cart Item
    |--------------------------------------------------------------------------
    */

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed');
    }
}