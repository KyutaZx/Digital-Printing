<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    protected $apiUrl;
    
    public function __construct() 
    { 
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080'); 
    }

    public function index()
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}/api/admin/materials");
            $materials = $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) { 
            Log::warning("Gagal fetch materials: " . $e->getMessage());
            $materials = []; 
        }
        return view('manager.material', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string', 
            'unit'  => 'required|string', 
            'stock' => 'required|numeric'
        ]);

        try {
            $payload = [
                'name'  => $request->name,
                'stock' => (float) $request->stock,
                'unit'  => $request->unit,
            ];

            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/admin/materials", $payload);
            if ($r->successful()) {
                return back()->with('success', 'Material berhasil ditambahkan.');
            }
            return back()->with('error', $r->json('error') ?? $r->json('message') ?? 'Gagal menambahkan material.');
        } catch (\Exception $e) { 
            return back()->with('error', 'Koneksi ke server gagal.'); 
        }
    }

    public function update(Request $request, int $id)
    {
        // Golang API tidak menyediakan endpoint edit nama/unit secara khusus,
        // namun kita sediakan method ini jika diperlukan atau fallback ke penyesuaian stok.
        return back()->with('error', 'Fitur edit data material tidak didukung oleh server. Silakan gunakan Update Stok.');
    }

    public function restock(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|numeric']);
        try {
            $qty = (float) $request->quantity;
            $changeType = $qty >= 0 ? 'in' : 'out';
            $absQty = abs($qty);

            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/admin/materials/{$id}/adjust", [
                'change_type' => $changeType,
                'quantity'    => $absQty,
                'reference'   => 'Restock manual oleh Owner/Admin',
            ]);

            if ($r->successful()) {
                return back()->with('success', 'Stok berhasil diperbarui.');
            }
            return back()->with('error', $r->json('error') ?? $r->json('message') ?? 'Gagal restock.');
        } catch (\Exception $e) { 
            return back()->with('error', 'Koneksi ke server gagal.'); 
        }
    }
}