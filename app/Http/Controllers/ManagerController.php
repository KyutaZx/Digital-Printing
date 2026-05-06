<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    private function apiGet(string $path): array
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}{$path}");
            return $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) {
            Log::warning("API GET {$path} failed: " . $e->getMessage());
            return [];
        }
    }

    // =========================================================================
    // Manager Dashboard — Statistik Bisnis
    // =========================================================================
    public function dashboard()
    {
        $allOrders = $this->apiGet('/api/orders/all?limit=200');
        $materials = $this->apiGet('/api/materials');

        $totalOmzet  = array_sum(array_column(
            array_filter($allOrders, fn($o) => $o['status'] === 'completed'), 'total_price'
        ));

        $stats = [
            'total_pesanan'    => count($allOrders),
            'pesanan_selesai'  => count(array_filter($allOrders, fn($o) => $o['status'] === 'completed')),
            'pesanan_aktif'    => count(array_filter($allOrders, fn($o) => in_array($o['status'], ['waiting_payment', 'payment_verification', 'paid', 'printing']))),
            'total_omzet'      => $totalOmzet,
            'material_rendah'  => count(array_filter($materials, fn($m) => ($m['stock'] ?? 0) < 10)),
        ];

        // Pesanan terbaru
        $recentOrders = array_slice($allOrders, 0, 8);

        return view('manager.dashboard', compact('stats', 'recentOrders'));
    }

    // =========================================================================
    // Manajemen Produk
    // =========================================================================
    public function produk()
    {
        try {
            $r = Http::timeout(10)->get("{$this->apiUrl}/products");
            $products = $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) {
            $products = [];
        }

        return view('manager.produk', compact('products'));
    }

    public function storeProduk(Request $request)
    {
        $request->validate(['name' => 'required', 'base_price' => 'required|numeric']);

        try {
            Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/products", $request->all());
            return back()->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan produk.');
        }
    }

    public function updateProduk(Request $request, int $id)
    {
        try {
            Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/products/{$id}", $request->all());
            return back()->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui produk.');
        }
    }

    public function deleteProduk(int $id)
    {
        try {
            Http::timeout(10)->withToken(session('token'))->delete("{$this->apiUrl}/api/products/{$id}");
            return back()->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk.');
        }
    }

    // =========================================================================
    // Monitoring & Laporan
    // =========================================================================
    public function monitoring()
    {
        $orders    = $this->apiGet('/api/orders/all?limit=200');
        $materials = $this->apiGet('/api/materials');

        // Hitung distribusi status
        $statusCount = [];
        foreach ($orders as $o) {
            $s = $o['status'] ?? 'unknown';
            $statusCount[$s] = ($statusCount[$s] ?? 0) + 1;
        }

        return view('manager.monitoring', compact('orders', 'materials', 'statusCount'));
    }

    // =========================================================================
    // Semua Pesanan
    // =========================================================================
    public function pesanan(Request $request)
    {
        $page   = $request->query('page', 1);
        $status = $request->query('status', '');
        $path   = "/api/orders/all?page={$page}&limit=20" . ($status ? "&status={$status}" : '');

        $orders = $this->apiGet($path);
        return view('manager.pesanan', compact('orders', 'status', 'page'));
    }
}
