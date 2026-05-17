<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    protected $apiUrl;
    public function __construct() { $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080'); }

    public function start(int $orderId)
    {
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(10)->withToken(session('token'))
                ->put("{$this->apiUrl}/api/staff/production/{$orderId}/start");
            if ($r->successful()) return back()->with('success', 'Produksi berhasil dimulai.');
            return back()->with('error', $r->json('message') ?? 'Gagal memulai produksi.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }

    public function finish(int $orderId)
    {
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(10)->withToken(session('token'))
                ->put("{$this->apiUrl}/api/staff/production/{$orderId}/finish");
            if ($r->successful()) return back()->with('success', 'Produksi berhasil diselesaikan.');
            return back()->with('error', $r->json('message') ?? 'Gagal menyelesaikan produksi.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }
}