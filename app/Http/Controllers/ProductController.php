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
        $categories = [];

        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/products");
            if ($response->successful()) {
                $all = $response->json('data') ?? $response->json() ?? [];
                
                // Urutkan berdasarkan ID terbaru (asumsi created_at)
                usort($all, function($a, $b) {
                    return ($b['id'] ?? 0) <=> ($a['id'] ?? 0);
                });
                
                // Filter hanya produk aktif
                $allActive = array_filter($all, fn($p) => isset($p['is_active']) ? $p['is_active'] == true : true);
                
                $products = array_slice($allActive, 0, 6); // Sesuai requirement: top 6
            }
        } catch (\Exception $e) {
            Log::warning('Products API unreachable: ' . $e->getMessage());
        }

        try {
            $catResponse = Http::timeout(10)->get("{$this->apiUrl}/categories");
            if ($catResponse->successful()) {
                $allCats = $catResponse->json('data') ?? [];
                foreach ($allCats as $cat) {
                    $categories[] = [
                        'id' => $cat['id'],
                        'title' => $cat['name'],
                        'description' => $cat['description'] ?: 'Layanan cetak ' . $cat['name'] . ' dengan kualitas terbaik dan harga kompetitif.',
                        'imgSrc' => $cat['image'] ? url('/api-proxy/' . ltrim($cat['image'], '/')) : null,
                        'linkHref' => '/katalog?category=' . urlencode($cat['name']),
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Categories API unreachable: ' . $e->getMessage());
        }

        $apiUrl = $this->apiUrl;
        return view('landing', compact('products', 'categories', 'apiUrl'));
    }

    // =========================================================================
    // Katalog — Semua produk dengan filter & search
    // =========================================================================
    public function catalog(Request $request)
    {
        $products = [];
        $categories = [];
        $search   = $request->query('q', '');

        try {
            $catResponse = Http::timeout(10)->get("{$this->apiUrl}/categories");
            if ($catResponse->successful()) {
                $categories = collect($catResponse->json('data') ?? [])->pluck('name')->toArray();
            }
        } catch (\Exception $e) {
            Log::warning('Categories API unreachable: ' . $e->getMessage());
        }

        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/products");
            if ($response->successful()) {
                $all = $response->json('data') ?? $response->json() ?? [];

                // Hanya ambil produk yang aktif untuk list filter kategori
                $allActive = array_filter($all, fn($p) => isset($p['is_active']) ? $p['is_active'] == true : true);


                // Filter by search query
                if ($search) {
                    $all = array_filter($all, fn($p) =>
                        stripos($p['name'] ?? '', $search) !== false ||
                        stripos($p['description'] ?? '', $search) !== false
                    );
                }

                // Filter by category
                if ($request->filled('category')) {
                    $cat = $request->query('category');
                    $all = array_filter($all, fn($p) =>
                        strcasecmp($p['category_name'] ?? '', $cat) === 0 ||
                        stripos($p['category_name'] ?? '', $cat) !== false
                    );
                }

                $products = array_values($all);
            }
        } catch (\Exception $e) {
            Log::warning('Products API unreachable: ' . $e->getMessage());
        }

        $apiUrl = $this->apiUrl;
        return view('catalog', compact('products', 'categories', 'search', 'apiUrl'));
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

        $apiUrl = $this->apiUrl;
        return view('product-detail', compact('product', 'apiUrl'));
    }
}