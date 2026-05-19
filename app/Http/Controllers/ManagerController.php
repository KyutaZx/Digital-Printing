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
        $materials = $this->apiGet('/api/admin/materials');

        $totalOmzet  = array_sum(array_column(
            array_filter($allOrders, fn($o) => $o['status'] === 'completed'), 'total_price'
        ));

        $stats = [
            'total_pesanan'    => count($allOrders),
            'pesanan_selesai'  => count(array_filter($allOrders, fn($o) => $o['status'] === 'completed')),
            'pesanan_aktif'    => count(array_filter($allOrders, fn($o) => in_array($o['status'], ['waiting_payment', 'payment_verification', 'paid', 'printing']))),
            'perlu_verifikasi' => count(array_filter($allOrders, fn($o) => $o['status'] === 'payment_verification')),
            'total_omzet'      => $totalOmzet,
            'material_rendah'  => count(array_filter($materials, fn($m) => ($m['stock'] ?? 0) < 10)),
        ];

        // Pesanan terbaru
        $recentOrders = array_slice($allOrders, 0, 8);

        return view('manager.dashboard', compact('stats', 'recentOrders'));
    }

    // =========================================================================
    // Verifikasi Pembayaran
    // =========================================================================
    public function verifikasi()
    {
        $allOrders = $this->apiGet('/api/orders/all?limit=100');
        
        $pending = array_values(array_filter($allOrders, fn($o) => $o['status'] === 'payment_verification'));
        $history = array_values(array_filter($allOrders, fn($o) => in_array($o['status'] ?? '', ['paid', 'design_review', 'printing', 'ready', 'completed'])));
        
        // Batasi history maksimal 20 terbaru
        $history = array_slice($history, 0, 20);

        return view('manager.verifikasi', compact('pending', 'history'));
    }

    public function verifikasiDetail(int $id)
    {
        $order = $this->apiGet("/api/orders/{$id}");
        if (empty($order)) return redirect('/manager/verifikasi')->with('error', 'Pesanan tidak ditemukan.');
        return view('manager.verifikasi-detail', compact('order'));
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

        $apiUrl = $this->apiUrl;
        return view('manager.produk', compact('products', 'apiUrl'));
    }

    private function mapCategoryNameToId($name)
    {
        $map = [
            'Printing Digital'      => 1,
            'Printing'              => 1,   // backward compat
            'Poster & Brosur'       => 9,
            'Cetak Buku & Majalah'  => 2,
            'Banner & Spanduk'      => 3,
            'Sticker & Label'       => 4,
            'Kartu & Undangan'      => 5,
            'Kaos & Merchandise'    => 6,
            'Outdoor Advertising'   => 7,
            'Packaging & Dus'       => 8,
        ];
        return $map[$name] ?? 1;  // Default ke Printing Digital
    }

    public function storeProduk(Request $request)
    {
        // Build variants dari form input
        $variantNames  = $request->input('variant_name', []);
        $variantPrices = $request->input('variant_price', []);
        $variantStocks = $request->input('variant_stock', []);
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $request->name), 0, 4));

        $variants = [];
        foreach ($variantNames as $i => $vname) {
            if (trim($vname) === '') continue;
            $variants[] = [
                'sku'          => 'VAR-' . $prefix . '-' . ($i + 1) . rand(10,99),
                'variant_name' => $vname,
                'price'        => (float) ($variantPrices[$i] ?? $request->base_price),
                'stock'        => (int)   ($variantStocks[$i] ?? 999),
                'is_active'    => true,
            ];
        }

        // Fallback jika tidak ada varian yang diisi
        if (empty($variants)) {
            $variants[] = [
                'sku'          => 'VAR-' . $prefix . '-' . rand(100, 999),
                'variant_name' => 'Standar',
                'price'        => (float) $request->base_price,
                'stock'        => 999,
                'is_active'    => true,
            ];
        }

        $payload = [
            'category_id'    => $this->mapCategoryNameToId($request->category_name ?? 'Printing Digital'),
            'name'           => $request->name,
            'description'    => $request->description ?? '',
            'base_price'     => (float) $request->base_price,
            'estimated_days' => (int) ($request->estimated_days ?? 1),
            'is_active'      => true,
            'variants'       => $variants,
        ];

        try {
            $response = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/admin/products", $payload);
            
            if ($response->successful()) {
                $productId = $response->json('data.id');

                // 🔥 Jika ada upload foto, kirim ke endpoint khusus image
                if ($request->hasFile('image') && $productId) {
                    $image = $request->file('image');
                    Http::timeout(30)->withToken(session('token'))
                        ->attach('image', fopen($image->getRealPath(), 'r'), $image->getClientOriginalName())
                        ->post("{$this->apiUrl}/api/admin/products/{$productId}/image");
                }

                return back()->with('success', 'Produk berhasil ditambahkan.');
            }
            return back()->with('error', 'Gagal API: ' . ($response->json('message') ?? 'Cek format data.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
        }
    }

    public function updateProduk(Request $request, int $id)
    {
        $request->validate(['name' => 'required', 'base_price' => 'required|numeric']);

        $variantIds    = $request->input('variant_id', []);
        $variantNames  = $request->input('variant_name', []);
        $variantPrices = $request->input('variant_price', []);
        $variantStocks = $request->input('variant_stock', []);
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $request->name), 0, 4));

        $variants = [];
        foreach ($variantNames as $i => $vname) {
            if (trim($vname) === '') continue;
            $vId = (int) ($variantIds[$i] ?? 0);
            $variants[] = [
                'id'           => $vId,
                'sku'          => 'VAR-' . $prefix . '-' . ($i + 1) . rand(10,99),
                'variant_name' => $vname,
                'price'        => (float) ($variantPrices[$i] ?? $request->base_price),
                'stock'        => (int)   ($variantStocks[$i] ?? 999),
                'is_active'    => true,
            ];
        }

        if (empty($variants)) {
            $variants[] = [
                'id'           => 0,
                'sku'          => 'VAR-' . $prefix . '-' . rand(100, 999),
                'variant_name' => 'Standar',
                'price'        => (float) $request->base_price,
                'stock'        => 999,
                'is_active'    => true,
            ];
        }

        $payload = [
            'category_id'    => $this->mapCategoryNameToId($request->category_name ?? 'Printing Digital'),
            'name'           => $request->name,
            'description'    => $request->description ?? '',
            'base_price'     => (float) $request->base_price,
            'estimated_days' => (int) ($request->estimated_days ?? 1),
            'is_active'      => true,
            'variants'       => $variants,
        ];

        try {
            $response = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/admin/products/{$id}", $payload);
            if ($response->successful()) {
                // 🔥 Jika ada upload foto baru saat update
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    Http::timeout(30)->withToken(session('token'))
                        ->attach('image', fopen($image->getRealPath(), 'r'), $image->getClientOriginalName())
                        ->post("{$this->apiUrl}/api/admin/products/{$id}/image");
                }
                return back()->with('success', 'Produk berhasil diperbarui.');
            }
            return back()->with('error', 'Gagal API: ' . ($response->json('message') ?? 'Cek format data.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
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
        $materials = $this->apiGet('/api/admin/materials');

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

    public function detailPesanan(int $id)
    {
        try {
            $response = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}/api/orders/{$id}");
            if ($response->successful()) {
                return response()->json($response->json('data') ?? $response->json());
            }
            return response()->json(['error' => 'Gagal mengambil detail pesanan dari API.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // Manajemen Pengguna (Owner Only)
    // =========================================================================
    public function users(Request $request)
    {
        $roleId = $request->query('role_id', '');
        $path   = '/api/admin/users' . ($roleId ? "?role_id={$roleId}" : '');
        $users  = $this->apiGet($path);

        return view('manager.users', compact('users', 'roleId'));
    }

    public function registerStaff(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $response = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/admin/staff", [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Staff baru berhasil didaftarkan.');
            }
            return back()->with('error', 'Gagal API: ' . ($response->json('message') ?? 'Cek data input.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
        }
    }

    public function updateUserStatus(Request $request, int $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $response = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/admin/users/{$id}/status", [
                'is_active' => (bool) $request->is_active,
            ]);

            if ($response->successful()) {
                return back()->with('success', $response->json('message') ?? 'Status pengguna berhasil diperbarui.');
            }
            return back()->with('error', 'Gagal API: ' . ($response->json('message') ?? 'Gagal memperbarui status.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
        }
    }
}
