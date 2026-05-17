<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    public function index()
    {
        $items = [];
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}/api/cart");
            $items = $r->successful() ? ($r->json('items') ?? $r->json('data') ?? []) : [];
        } catch (\Exception $e) {
            Log::warning('Cart API: ' . $e->getMessage());
        }
        $apiUrl = $this->apiUrl;
        return view('cart', compact('items', 'apiUrl'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|integer', 'variant_id' => 'required|integer', 'quantity' => 'required|integer|min:1']);
        try {
            $payload = [
                'product_id' => (int) $request->product_id,
                'variant_id' => (int) $request->variant_id,
                'quantity'   => (int) $request->quantity,
                'notes'      => $request->notes ?? ''
            ];
            
            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/cart", $payload);
            if ($r->successful()) {
                if ($request->action === 'buy') {
                    return redirect('/cart');
                }
                return back()->with('success', 'Produk ditambahkan ke keranjang!');
            }
            
            return back()->with('error', $r->json('message') ?? 'Gagal menambahkan ke keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke server.');
        }
    }

    public function update(Request $request)
    {
        try {
            $payload = [
                'cart_item_id' => (int) $request->cart_item_id,
                'quantity'     => (int) $request->quantity
            ];
            Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/cart", $payload);
        } catch (\Exception $e) {}
        return back();
    }

    public function remove(int $id)
    {
        try {
            // Gunakan JSON payload 'cart_item_id' sesuai API Go
            Http::timeout(10)->withToken(session('token'))->delete("{$this->apiUrl}/api/cart", ['cart_item_id' => $id]);
        } catch (\Exception $e) {}
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}