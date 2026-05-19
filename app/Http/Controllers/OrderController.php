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
        
        $activeOrders = array_filter($orders, function($order) {
            return !in_array($order['status'] ?? '', ['completed', 'cancelled']);
        });

        return view('orders.index', ['orders' => $activeOrders]);
    }

    public function history()
    {
        $orders = $this->api('get', '/api/orders') ?? [];
        if (!is_array($orders) || isset($orders['id'])) $orders = [$orders];
        
        $historyOrders = array_filter($orders, function($order) {
            return in_array($order['status'] ?? '', ['completed', 'cancelled']);
        });

        return view('orders.history', ['orders' => $historyOrders]);
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

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $payload = [
            'items' => [
                [
                    'product_id' => (int) $request->product_id,
                    'variant_id' => (int) $request->variant_id,
                    'quantity' => (int) $request->quantity,
                    'notes' => $request->notes ?? ''
                ]
            ]
        ];

        // Memanggil API CreateOrder (manual, bypass keranjang)
        $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/orders", $payload);
        if ($r->successful()) {
            $data = $r->json('data') ?? $r->json();
            // Asumsi API mengembalikan ID pesanan di response message atau data.
            // Karena di golang-api handler Create mengembalikan message saja, mungkin kita perlu menyesuaikan golang-api agar mengembalikan order_id, atau kita asumsikan getMyOrders terbaru adalah order tersebut.
            // Untuk amannya, kita panggil getMyOrders dan ambil yang pertama.
            $orders = $this->api('get', '/api/orders') ?? [];
            if (!empty($orders)) {
                $latestOrder = $orders[0];
                return redirect("/pesanan/{$latestOrder['id']}/upload-desain")->with('success', 'Pesanan berhasil dibuat! Silakan upload desain Anda.');
            }
            return redirect('/pesanan')->with('success', 'Pesanan berhasil dibuat!');
        }

        return back()->with('error', $r->json('message') ?? 'Gagal membuat pesanan.');
    }

    public function checkout(Request $request)
    {
        $result = $this->api('post', '/api/checkout', []);
        if ($result && isset($result['order_id'])) {
            return redirect("/pesanan/{$result['order_id']}/upload-desain")->with('success', 'Checkout berhasil! Silakan upload desain untuk pesanan Anda.');
        } elseif ($result) {
            // Fallback if order_id is not returned directly
            $orders = $this->api('get', '/api/orders') ?? [];
            if (!empty($orders)) {
                $latestOrder = $orders[0];
                return redirect("/pesanan/{$latestOrder['id']}/upload-desain")->with('success', 'Checkout berhasil! Silakan upload desain Anda.');
            }
            return redirect('/pesanan')->with('success', 'Checkout berhasil! Silakan upload desain Anda.');
        }
        return back()->with('error', 'Checkout gagal. Pastikan keranjang Anda tidak kosong.');
    }

    public function showUploadDesign(int $id)
    {
        $order = $this->api('get', "/api/orders/{$id}");
        if (!$order) abort(404);
        
        // Cek kepemilikan
        if ((session('user.role') === 'customer') && ($order['customer_id'] ?? $order['user_id'] ?? null) != session('user.id')) {
            abort(403);
        }

        return view('orders.upload_design', compact('order'));
    }

    public function showPayment(int $id)
    {
        $order = $this->api('get', "/api/orders/{$id}");
        if (!$order) abort(404);
        
        // Cek kepemilikan
        if ((session('user.role') === 'customer') && ($order['customer_id'] ?? $order['user_id'] ?? null) != session('user.id')) {
            abort(403);
        }

        // Ambil list metode pembayaran
        $methods = $this->api('get', '/api/payments/methods') ?? [];
        
        return view('orders.payment', compact('order', 'methods'));
    }

    public function confirmCompleted(int $id)
    {
        $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/orders/{$id}/complete");
        if ($r->successful()) return back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi selesai.');
        return back()->with('error', $r->json('message') ?? 'Gagal mengkonfirmasi pesanan.');
    }

    public function cancel(int $id)
    {
        $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/orders/{$id}/cancel");
        if ($r->successful()) return back()->with('success', 'Pesanan berhasil dibatalkan.');
        return back()->with('error', $r->json('message') ?? 'Gagal membatalkan pesanan.');
    }

    public function viewInvoice(int $id)
    {
        $order = $this->api('get', "/api/orders/{$id}");
        if (!$order) abort(404, 'Invoice tidak ditemukan.');
        return view('orders.invoice', compact('order', 'id'));
    }

    public function streamInvoicePDF(int $id)
    {
        try {
            $response = Http::timeout(30)->withToken(session('token'))->get("{$this->apiUrl}/api/orders/{$id}/invoice/pdf");
            
            if ($response->successful()) {
                if (ob_get_length()) ob_clean(); 
                
                // DEBUG: Return as plain text to see if it's valid
                $body = $response->body();
                if (empty($body)) {
                    return response('PDF KOSONG DARI GOLANG API!', 500);
                }
                
                return response($body, 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => "inline; filename=Invoice-{$id}.pdf",
                ]);
            }
            return response('Gagal memuat invoice dari API. Status: ' . $response->status(), 500);
        } catch (\Exception $e) {
            return response('Error saat memuat invoice: ' . $e->getMessage(), 500);
        }
    }

    public function downloadInvoice(int $id)
    {
        try {
            $r = Http::timeout(30)->withToken(session('token'))->get("{$this->apiUrl}/api/orders/{$id}/invoice/pdf");
            if ($r->successful()) {
                if (ob_get_length()) ob_clean(); // Prevent whitespace corruption
                return response($r->body(), 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=Invoice-{$id}.pdf",
                ]);
            }
            return back()->with('error', 'Gagal mengunduh invoice. API Status: ' . $r->status() . ' Body: ' . $r->body());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunduh invoice. Error: ' . $e->getMessage());
        }
    }
}