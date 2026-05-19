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
        $orders = $this->apiGet('/api/orders/all?limit=50');
        
        $stats = [
            'design_review' => count(array_filter($orders, fn($o) => in_array($o['status'], ['paid', 'design_review']))),
            'printing'      => count(array_filter($orders, fn($o) => $o['status'] === 'printing')),
            'ready'         => count(array_filter($orders, fn($o) => $o['status'] === 'ready')),
            'completed'     => count(array_filter($orders, fn($o) => $o['status'] === 'completed')),
        ];

        $recentOrders = array_slice($orders, 0, 10);

        return view('staff.dashboard', compact('stats', 'recentOrders'));
    }

    // =========================================================================
    // Daftar Desain yang Perlu Di-review
    // =========================================================================
    public function desainList()
    {
        // Ambil semua pesanan ringkas terlebih dahulu
        $allOrders = $this->apiGet('/api/orders/all?limit=100');

        Log::info('[DesainList] Total orders dari API:', ['count' => count($allOrders), 'statuses' => array_column($allOrders, 'status')]);

        // Filter pesanan yang statusnya butuh review desain (sudah bayar dan lunas)
        // Filter pesanan yang statusnya butuh review desain (sudah bayar dan lunas)
        $pendingFiltered = array_filter($allOrders, fn($o) => in_array($o['status'] ?? '', ['paid', 'design_review']));
        
        // Filter pesanan yang sudah selesai direview (masuk produksi dst)
        $historyFiltered = array_filter($allOrders, fn($o) => in_array($o['status'] ?? '', ['printing', 'ready', 'completed']));
        $historyFiltered = array_slice($historyFiltered, 0, 15); // Batasi history 15 saja agar tidak lambat
        
        $pending = [];
        foreach ($pendingFiltered as $order) {
            $detail = $this->apiGet("/api/orders/{$order['id']}");
            if (!empty($detail)) {
                if (!isset($detail['items'])) $detail['items'] = [];
                foreach ($detail['items'] as &$item) {
                    if (!isset($item['designs'])) $item['designs'] = [];
                }
                unset($item);
                $pending[] = $detail;
            }
        }

        $history = [];
        foreach ($historyFiltered as $order) {
            $detail = $this->apiGet("/api/orders/{$order['id']}");
            if (!empty($detail)) {
                if (!isset($detail['items'])) $detail['items'] = [];
                foreach ($detail['items'] as &$item) {
                    if (!isset($item['designs'])) $item['designs'] = [];
                }
                unset($item);
                $history[] = $detail;
            }
        }

        return view('staff.desain', compact('pending', 'history'));
    }

    // =========================================================================
    // Antrean Produksi
    // =========================================================================
    public function produksi(Request $request)
    {
        $statusFilter = $request->query('status', '');
        
        $path = '/api/orders/all?limit=200';
        if ($statusFilter) {
            $path .= "&status={$statusFilter}";
        }
        
        $orders = $this->apiGet($path);

        if ($statusFilter) {
            $antrian = array_filter($orders, fn($o) => $o['status'] === $statusFilter);
        } else {
            $antrian = array_filter($orders, fn($o) => in_array($o['status'], ['paid', 'printing', 'ready']));
        }
        
        $antrian = array_values($antrian);

        return view('staff.produksi', compact('antrian'));
    }
}
