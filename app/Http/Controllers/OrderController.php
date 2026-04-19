<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display User Orders
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show Order Detail
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        $order->load([
            'items.product',
            'items.size',
            'items.material',
            'items.finishing'
        ]);

        return view('orders.show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout Cart to Create Order
    |--------------------------------------------------------------------------
    */

    public function checkout()
    {
        $cart = Cart::with('items')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $totalPrice = 0;

        foreach ($cart->items as $item) {
            $totalPrice += $item->quantity * $item->product->base_price;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_code' => 'ORD-' . strtoupper(Str::random(6)),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        foreach ($cart->items as $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'size_id' => $item->size_id,
                'material_id' => $item->material_id,
                'finishing_id' => $item->finishing_id,
                'quantity' => $item->quantity,
                'price' => $item->product->base_price,
                'notes' => $item->notes,
                'design_file' => $item->design_file
            ]);
        }

        $cart->items()->delete();

        return redirect()->route('orders.show', $order->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Confirm Order Completed
    |--------------------------------------------------------------------------
    */

    public function confirmCompleted(Order $order)
    {
        $order->update([
            'status' => 'completed'
        ]);

        return redirect()->back()->with('success', 'Order confirmed as completed');
    }
}