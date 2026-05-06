<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    // =========================================================================
    // Landing Page — Tampilkan produk unggulan dari Golang API
    // =========================================================================
    public function index(Request $request)
    {
        $products = [];

        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/products");
            if ($response->successful()) {
                $all      = $response->json('data') ?? $response->json() ?? [];
                $products = array_slice($all, 0, 8); // Tampilkan max 8 di landing
            }
        } catch (\Exception $e) {
            Log::warning('Products API unreachable: ' . $e->getMessage());
        }

        return view('landing', compact('products'));
    }

    // =========================================================================
    // Katalog — Semua produk dengan filter & search
    // =========================================================================
    public function catalog(Request $request)
    {
        $products = [];
        $search   = $request->query('q', '');

        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/products");
            if ($response->successful()) {
                $all = $response->json('data') ?? $response->json() ?? [];

                // Filter by search query
                if ($search) {
                    $all = array_filter($all, fn($p) =>
                        stripos($p['name'] ?? '', $search) !== false ||
                        stripos($p['description'] ?? '', $search) !== false
                    );
                }

                $products = array_values($all);
            }
        } catch (\Exception $e) {
            Log::warning('Products API unreachable: ' . $e->getMessage());
        }

        return view('catalog', compact('products', 'search'));
    }

    // =========================================================================
    // Product Detail
    // =========================================================================
    public function show(int $id)
    {
        $product = null;

        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/products/{$id}");
            if ($response->successful()) {
                $product = $response->json('data') ?? $response->json();
            }
        } catch (\Exception $e) {
            Log::warning("Product {$id} API unreachable: " . $e->getMessage());
        }

        if (!$product) {
            abort(404, 'Produk tidak ditemukan.');
        }

        return view('product-detail', compact('product'));
    }
}