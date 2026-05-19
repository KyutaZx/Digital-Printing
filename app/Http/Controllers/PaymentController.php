<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $apiUrl;
    public function __construct() { $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080'); }

    public function methods()
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}/api/payment-methods");
            $methods = $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) { $methods = []; }
        return view('payments.methods', compact('methods'));
    }

    public function uploadProof(Request $request, int $orderId)
    {
        set_time_limit(0); // Nonaktifkan timeout PHP
        $request->validate([
            'proof'            => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'amount'           => 'required|numeric',
            'method_id'        => 'required|integer',
            'transaction_code' => 'required|string',
        ]);

        try {
            $r = Http::timeout(120)
                ->withToken(session('token'))
                ->attach('payment_proof', file_get_contents($request->file('proof')->getRealPath()), $request->file('proof')->getClientOriginalName())
                ->post("{$this->apiUrl}/api/payments", [
                    'order_id'          => $orderId,
                    'amount'            => $request->amount,
                    'payment_method_id' => $request->method_id,
                    'transaction_code'  => $request->transaction_code,
                ]);

            if ($r->successful()) return redirect("/pesanan")->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi.');
            return back()->with('error', $r->json('message') ?? $r->json('error') ?? 'Upload gagal.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke server: ' . $e->getMessage());
        }
    }

    public function approve(int $id)
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/staff/payments/{$id}/approve");
            if ($r->successful()) return back()->with('success', 'Pembayaran berhasil disetujui.');
            return back()->with('error', $r->json('message') ?? 'Gagal menyetujui.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }

    public function reject(int $id)
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/staff/payments/{$id}/reject");
            if ($r->successful()) return back()->with('success', 'Pembayaran ditolak. Customer dapat upload ulang.');
            return back()->with('error', $r->json('message') ?? 'Gagal menolak.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }
}