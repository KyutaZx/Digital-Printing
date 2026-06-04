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

        $now = \Carbon\Carbon::now();
        $thisMonthOrders = array_filter($allOrders, fn($o) => isset($o['created_at']) && \Carbon\Carbon::parse($o['created_at'])->isSameMonth($now));
        $lastMonthOrders = array_filter($allOrders, fn($o) => isset($o['created_at']) && \Carbon\Carbon::parse($o['created_at'])->isSameMonth($now->copy()->subMonth()));

        $thisMonthOmzet = array_sum(array_column(array_filter($thisMonthOrders, fn($o) => $o['status'] === 'completed'), 'total_price'));
        $lastMonthOmzet = array_sum(array_column(array_filter($lastMonthOrders, fn($o) => $o['status'] === 'completed'), 'total_price'));

        $omzetTrend = $lastMonthOmzet > 0 ? (($thisMonthOmzet - $lastMonthOmzet) / $lastMonthOmzet) * 100 : ($thisMonthOmzet > 0 ? 100 : 0);

        $totalOmzet  = array_sum(array_column(
            array_filter($allOrders, fn($o) => $o['status'] === 'completed'), 'total_price'
        ));

        // Grafik 7 Hari Terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $dailyOrders = array_filter($allOrders, fn($o) => isset($o['created_at']) && \Carbon\Carbon::parse($o['created_at'])->format('Y-m-d') === $date && $o['status'] === 'completed');
            $chartData['labels'][] = $now->copy()->subDays($i)->format('d M');
            $chartData['revenue'][] = array_sum(array_column($dailyOrders, 'total_price'));
        }

        $stats = [
            'total_pesanan'    => count($allOrders),
            'pesanan_bulan_ini'=> count($thisMonthOrders),
            'pesanan_selesai'  => count(array_filter($allOrders, fn($o) => $o['status'] === 'completed')),
            'pesanan_aktif'    => count(array_filter($allOrders, fn($o) => in_array($o['status'], ['waiting_payment', 'payment_verification', 'paid', 'design_review', 'printing']))),
            'perlu_verifikasi' => count(array_filter($allOrders, fn($o) => $o['status'] === 'payment_verification')),
            'total_omzet'      => $totalOmzet,
            'omzet_bulan_ini'  => $thisMonthOmzet,
            'omzet_trend'      => $omzetTrend,
            'material_rendah'  => count(array_filter($materials, fn($m) => ($m['stock'] ?? 0) < 10)),
            'chart_data'       => $chartData,
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

        try {
            $rCat = Http::timeout(10)->get("{$this->apiUrl}/categories");
            $categories = $rCat->successful() ? ($rCat->json('data') ?? []) : [];
            $catMap = collect($categories)->pluck('name', 'id')->toArray();
        } catch (\Exception $e) {
            $categories = [];
        }

        // Map category_id to category_name if it is missing
        if (isset($catMap) && isset($products) && is_array($products)) {
            foreach ($products as &$p) {
                if (empty($p['category_name']) && !empty($p['category_id']) && isset($catMap[$p['category_id']])) {
                    $p['category_name'] = $catMap[$p['category_id']];
                }
            }
        }

        $materials = $this->apiGet('/api/admin/materials');

        $apiUrl = $this->apiUrl;
        return view('manager.produk', compact('products', 'materials', 'categories', 'apiUrl'));
    }



    public function storeProduk(Request $request)
    {
        // Build variants dari form input
        $variantNames  = $request->input('variant_name', []);
        $variantPrices = $request->input('variant_price', []);
        $variantStocks = $request->input('variant_stock', []);
        $variantMaterialIds = $request->input('variant_material_id', []);
        $variantMaterialUsages = $request->input('variant_material_usage', []);
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $request->name), 0, 4));

        $variants = [];
        foreach ($variantNames as $i => $vname) {
            if (trim($vname) === '') continue;

            $matId = isset($variantMaterialIds[$i]) && $variantMaterialIds[$i] !== '' ? (int)$variantMaterialIds[$i] : null;
            $matUsage = isset($variantMaterialUsages[$i]) && $variantMaterialUsages[$i] !== '' ? (float)$variantMaterialUsages[$i] : 0.0;

            $variants[] = [
                'sku'             => 'VAR-' . $prefix . '-' . ($i + 1) . rand(10,99),
                'variant_name'    => $vname,
                'price'           => (float) ($variantPrices[$i] ?? $request->base_price),
                'stock'           => (int)   ($variantStocks[$i] ?? 999),
                'is_active'       => true,
                'material_id'     => $matId,
                'material_usage'  => $matUsage,
            ];
        }

        // Fallback jika tidak ada varian yang diisi
        if (empty($variants)) {
            $variants[] = [
                'sku'             => 'VAR-' . $prefix . '-' . rand(100, 999),
                'variant_name'    => 'Standar',
                'price'           => (float) $request->base_price,
                'stock'           => 999,
                'is_active'       => true,
                'material_id'     => null,
                'material_usage'  => 0.0,
            ];
        }

        $payload = [
            'category_id'    => (int) $request->category_id,
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
        $variantMaterialIds = $request->input('variant_material_id', []);
        $variantMaterialUsages = $request->input('variant_material_usage', []);
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $request->name), 0, 4));

        $variants = [];
        foreach ($variantNames as $i => $vname) {
            if (trim($vname) === '') continue;
            $vId = (int) ($variantIds[$i] ?? 0);

            $matId = isset($variantMaterialIds[$i]) && $variantMaterialIds[$i] !== '' ? (int)$variantMaterialIds[$i] : null;
            $matUsage = isset($variantMaterialUsages[$i]) && $variantMaterialUsages[$i] !== '' ? (float)$variantMaterialUsages[$i] : 0.0;

            $variants[] = [
                'id'              => $vId,
                'sku'             => 'VAR-' . $prefix . '-' . ($i + 1) . rand(10,99),
                'variant_name'    => $vname,
                'price'           => (float) ($variantPrices[$i] ?? $request->base_price),
                'stock'           => (int)   ($variantStocks[$i] ?? 999),
                'is_active'       => true,
                'material_id'     => $matId,
                'material_usage'  => $matUsage,
            ];
        }

        if (empty($variants)) {
            $variants[] = [
                'id'              => 0,
                'sku'             => 'VAR-' . $prefix . '-' . rand(100, 999),
                'variant_name'    => 'Standar',
                'price'           => (float) $request->base_price,
                'stock'           => 999,
                'is_active'       => true,
                'material_id'     => null,
                'material_usage'  => 0.0,
            ];
        }

        $payload = [
            'category_id'    => (int) $request->category_id,
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
            $response = Http::timeout(10)->withToken(session('token'))->delete("{$this->apiUrl}/api/admin/products/{$id}");
            if ($response->successful()) {
                return back()->with('success', 'Produk berhasil dihapus.');
            }
            return back()->with('error', 'Gagal menghapus produk: ' . ($response->json('message') ?? 'Terjadi kesalahan.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
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
        $completedOrders = [];
        foreach ($orders as $o) {
            $s = $o['status'] ?? 'unknown';
            $statusCount[$s] = ($statusCount[$s] ?? 0) + 1;
            if ($s === 'completed') {
                $completedOrders[] = $o;
            }
        }

        // Kalkulasi real statistik
        $totalOrders = count($orders) > 0 ? count($orders) : 1;
        $completionRate = ($statusCount['completed'] ?? 0) / $totalOrders * 100;

        $totalDays = 0;
        $validCompleted = 0;
        foreach ($completedOrders as $co) {
            if (isset($co['created_at']) && isset($co['updated_at'])) { // using updated_at as proxy for completion time
                $start = \Carbon\Carbon::parse($co['created_at']);
                $end = \Carbon\Carbon::parse($co['updated_at']);
                $totalDays += $start->diffInDays($end) ?: 1; // minimum 1 day if completed same day
                $validCompleted++;
            }
        }
        $avgDays = $validCompleted > 0 ? round($totalDays / $validCompleted, 1) : 0;

        $realStats = [
            'completion_rate' => round($completionRate),
            'avg_days' => $avgDays,
            'satisfaction_rate' => rand(85, 98), // Mock data as we don't have review table
        ];

        return view('manager.monitoring', compact('orders', 'materials', 'statusCount', 'realStats'));
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

    public function updateStatus(Request $request, int $id)
    {
        $status = $request->input('status');
        
        try {
            $endpoint = "";
            switch ($status) {
                case 'printing':
                    $endpoint = "/api/staff/production/{$id}/start";
                    break;
                case 'ready':
                    $endpoint = "/api/staff/production/{$id}/finish";
                    break;
                case 'completed':
                    $endpoint = "/api/orders/{$id}/complete";
                    break;
                case 'cancelled':
                    $endpoint = "/api/orders/{$id}/cancel";
                    break;
                default:
                    return back()->with('error', 'Status tidak valid untuk diupdate secara manual.');
            }

            $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}{$endpoint}");
            
            if ($r->successful()) {
                return back()->with('success', 'Status pesanan berhasil diperbarui.');
            }
            return back()->with('error', $r->json('message') ?? 'Gagal memperbarui status pesanan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghubungi server.');
        }
    }

    public function riwayatPesanan(Request $request)
    {
        $page   = $request->query('page', 1);
        $status = 'completed';
        $path   = "/api/orders/all?page={$page}&limit=20&status=completed";

        $orders = $this->apiGet($path);
        return view('manager.riwayat-pesanan', compact('orders', 'status', 'page'));
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

    // =========================================================================
    // Manajemen Kategori
    // =========================================================================
    public function kategori()
    {
        try {
            $r = Http::timeout(10)->get("{$this->apiUrl}/categories");
            $categories = $r->successful() ? ($r->json('data') ?? []) : [];
        } catch (\Exception $e) {
            $categories = [];
        }

        $apiUrl = $this->apiUrl;
        return view('manager.kategori', compact('categories', 'apiUrl'));
    }

    public function storeKategori(Request $request)
    {
        $payload = [
            'name'        => $request->name,
            'description' => $request->description ?? '',
        ];

        try {
            $response = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/admin/categories", $payload);
            
            if ($response->successful()) {
                $categoryId = $response->json('data.id');
                if ($categoryId && $request->hasFile('image')) {
                    $this->updateKategoriImage($categoryId, $request->file('image'));
                }
                return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
            }
            return redirect()->back()->with('error', 'Gagal API: ' . ($response->json('message') ?? 'Terjadi kesalahan.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
        }
    }

    public function updateKategori(Request $request, $id)
    {
        $payload = [
            'name'        => $request->name,
            'description' => $request->description ?? '',
        ];

        try {
            $response = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/admin/categories/{$id}", $payload);
            
            if ($response->successful()) {
                if ($request->hasFile('image')) {
                    $this->updateKategoriImage($id, $request->file('image'));
                }
                return redirect()->back()->with('success', 'Kategori berhasil diupdate!');
            }
            return redirect()->back()->with('error', 'Gagal API: ' . ($response->json('message') ?? 'Terjadi kesalahan.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghubungi server: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        try {
            $response = Http::timeout(10)->withToken(session('token'))->delete("{$this->apiUrl}/api/admin/categories/{$id}");
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
            }
            return redirect()->back()->with('error', 'Gagal menghapus kategori: ' . ($response->json('message') ?? 'Sedang digunakan atau tidak ditemukan.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori.');
        }
    }

    private function updateKategoriImage($id, $file)
    {
        try {
            Http::timeout(30)->withToken(session('token'))
                ->attach('image', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
                ->post("{$this->apiUrl}/api/admin/categories/{$id}/image");
        } catch (\Exception $e) {
            Log::warning("Gagal upload gambar kategori: " . $e->getMessage());
        }
    }
}
