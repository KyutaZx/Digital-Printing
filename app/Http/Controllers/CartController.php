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
            $items = $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) {
            Log::warning('Cart API: ' . $e->getMessage());
        }
        return view('cart', compact('items'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|integer', 'variant_id' => 'required|integer', 'quantity' => 'required|integer|min:1']);
        try {
            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/cart", $request->only(['product_id', 'variant_id', 'quantity', 'notes']));
            if ($r->successful()) return back()->with('success', 'Produk ditambahkan ke keranjang!');
            return back()->with('error', $r->json('message') ?? 'Gagal menambahkan ke keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke server.');
        }
    }

    public function update(Request $request)
    {
        try {
            Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/cart", $request->only(['product_id', 'variant_id', 'quantity']));
        } catch (\Exception $e) {}
        return back();
    }

    public function remove(int $id)
    {
        try {
            Http::timeout(10)->withToken(session('token'))->delete("{$this->apiUrl}/api/cart", ['product_id' => $id]);
        } catch (\Exception $e) {}
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}