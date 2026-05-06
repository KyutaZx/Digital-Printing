<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $apiUrl;
    public function __construct() { $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080'); }

    private function api(string $method, string $path, array $data = []): ?array
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->{$method}("{$this->apiUrl}{$path}", $data);
            return $r->successful() ? ($r->json('data') ?? $r->json()) : null;
        } catch (\Exception $e) { Log::warning("Order API {$path}: ".$e->getMessage()); return null; }
    }

    public function index()
    {
        $orders = $this->api('get', '/api/orders') ?? [];
        if (!is_array($orders) || isset($orders['id'])) $orders = [$orders];
        return view('orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = $this->api('get', "/api/orders/{$id}");
        if (!$order) abort(404);
        // Cek kepemilikan
        if ((session('user.role') === 'customer') && ($order['customer_id'] ?? $order['user_id'] ?? null) != session('user.id')) {
            abort(403);
        }
        return view('orders.detail', compact('order'));
    }

    public function checkout(Request $request)
    {
        $result = $this->api('post', '/api/checkout', []);
        if ($result) return redirect('/pesanan')->with('success', 'Checkout berhasil! Silakan upload bukti pembayaran.');
        return back()->with('error', 'Checkout gagal. Pastikan keranjang Anda tidak kosong.');
    }

    public function confirmCompleted(int $id)
    {
        $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/orders/{$id}/complete");
        if ($r->successful()) return back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi selesai.');
        return back()->with('error', $r->json('message') ?? 'Gagal mengkonfirmasi pesanan.');
    }

    public function downloadInvoice(int $id)
    {
        try {
            $r = Http::timeout(30)->withToken(session('token'))->get("{$this->apiUrl}/api/orders/{$id}/invoice/pdf");
            if ($r->successful()) {
                return response($r->body(), 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=Invoice-{$id}.pdf",
                ]);
            }
        } catch (\Exception $e) {}
        return back()->with('error', 'Gagal mengunduh invoice.');
    }
}