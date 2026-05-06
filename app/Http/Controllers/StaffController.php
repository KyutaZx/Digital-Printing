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
            $response = Http::timeout(10)
                ->withToken(session('token'))
                ->get("{$this->apiUrl}{$path}");
            return $response->successful() ? ($response->json('data') ?? $response->json() ?? []) : [];
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
        $orders = $this->apiGet('/api/orders/all?status=paid&limit=50');
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
