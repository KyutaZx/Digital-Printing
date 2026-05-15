<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    private function apiGet(string $path): array
    {
        try {
            $response = Http::timeout(15)
                ->withToken(session('token'))
                ->get("{$this->apiUrl}{$path}");

            if (!$response->successful()) {
                Log::warning("API GET {$path} returned HTTP {$response->status()}");
                return [];
            }

            $body = $response->json();
            // Golang API membungkus data dalam key 'data', ambil isinya
            if (isset($body['data']) && is_array($body['data'])) {
                return $body['data'];
            }
            // Fallback jika response langsung array
            return is_array($body) ? $body : [];
        } catch (\Exception $e) {
            Log::warning("API GET {$path} failed: " . $e->getMessage());
            return [];
        }
    }

    // =========================================================================
    // Staff Dashboard
    // =========================================================================
    public function dashboard()
    {
        // Ambil semua pesanan (status: payment_verification, printing, paid)
        $orders = $this->apiGet('/api/orders/all?limit=50');

        $stats = [
            'menunggu_verifikasi' => count(array_filter($orders, fn($o) => $o['status'] === 'payment_verification')),
            'sedang_cetak'        => count(array_filter($orders, fn($o) => $o['status'] === 'printing')),
            'siap_ambil'          => count(array_filter($orders, fn($o) => $o['status'] === 'ready')),
            'selesai_hari_ini'    => count(array_filter($orders, fn($o) => $o['status'] === 'completed')),
        ];

        $recentOrders = array_slice($orders, 0, 10);

        return view('staff.dashboard', compact('stats', 'recentOrders'));
    }

    // =========================================================================
    // Verifikasi Pembayaran
    // =========================================================================
    public function verifikasi()
    {
        $orders = $this->apiGet('/api/orders/all?status=payment_verification&limit=50');
        return view('staff.verifikasi', compact('orders'));
    }

    public function verifikasiDetail(int $id)
    {
        $order = $this->apiGet("/api/orders/{$id}");
        if (empty($order)) abort(404);
        return view('staff.verifikasi-detail', compact('order'));
    }

    // =========================================================================
    // Daftar Desain yang Perlu Di-review
    // =========================================================================
    public function desainList()
    {
        // Ambil semua pesanan ringkas terlebih dahulu
        $allOrders = $this->apiGet('/api/orders/all?limit=100');

        Log::info('[DesainList] Total orders dari API:', ['count' => count($allOrders), 'statuses' => array_column($allOrders, 'status')]);

        // Filter pesanan yang statusnya butuh review desain
        // Termasuk semua status yang relevan
        $validStatuses = ['paid', 'payment_verification', 'printing', 'waiting_payment', 'design_review'];
        $filtered = array_filter($allOrders, fn($o) =>
            in_array($o['status'] ?? '', $validStatuses)
        );

        Log::info('[DesainList] Filtered orders:', ['count' => count($filtered)]);

        // Ambil detail lengkap (termasuk items + designs) untuk setiap pesanan
        $orders = [];
        foreach ($filtered as $order) {
            $detail = $this->apiGet("/api/orders/{$order['id']}");
            if (!empty($detail)) {
                // Pastikan key 'items' ada dan 'designs' ada di setiap item
                if (!isset($detail['items'])) {
                    $detail['items'] = [];
                }
                foreach ($detail['items'] as &$item) {
                    if (!isset($item['designs'])) {
                        $item['designs'] = [];
                    }
                }
                unset($item);
                Log::info("[DesainList] Order {$order['id']} items:", [
                    'item_count'   => count($detail['items']),
                    'designs_per_item' => array_map(fn($i) => count($i['designs'] ?? []), $detail['items'])
                ]);
                $orders[] = $detail;
            } else {
                Log::warning("[DesainList] Detail kosong untuk order ID: {$order['id']}");
            }
        }

        return view('staff.desain', compact('orders'));
    }

    // =========================================================================
    // Antrean Produksi
    // =========================================================================
    public function produksi()
    {
        $orders = $this->apiGet('/api/orders/all?limit=100');

        $antrian = array_filter($orders, fn($o) => in_array($o['status'], ['paid', 'printing', 'ready']));
        $antrian = array_values($antrian);

        return view('staff.produksi', compact('antrian'));
    }
}
