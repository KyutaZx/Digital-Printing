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
        $request->validate([
            'proof'            => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'amount'           => 'required|numeric',
            'method_id'        => 'required|integer',
            'transaction_code' => 'required|string',
        ]);

        try {
            $r = Http::timeout(30)
                ->withToken(session('token'))
                ->attach('proof', file_get_contents($request->file('proof')->getRealPath()), $request->file('proof')->getClientOriginalName())
                ->post("{$this->apiUrl}/api/orders/{$orderId}/payment", [
                    'amount'           => $request->amount,
                    'method_id'        => $request->method_id,
                    'transaction_code' => $request->transaction_code,
                ]);

            if ($r->successful()) return redirect("/pesanan/{$orderId}")->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi.');
            return back()->with('error', $r->json('message') ?? 'Upload gagal.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke server.');
        }
    }

    public function approve(int $id)
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/payments/{$id}/approve");
            if ($r->successful()) return back()->with('success', 'Pembayaran berhasil disetujui.');
            return back()->with('error', $r->json('message') ?? 'Gagal menyetujui.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }

    public function reject(int $id)
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/payments/{$id}/reject");
            if ($r->successful()) return back()->with('success', 'Pembayaran ditolak. Customer dapat upload ulang.');
            return back()->with('error', $r->json('message') ?? 'Gagal menolak.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }
}