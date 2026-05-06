<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    protected $apiUrl;
    public function __construct() { $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080'); }

    public function index()
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}/api/materials");
            $materials = $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) { $materials = []; }
        return view('manager.material', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string', 'unit' => 'required|string', 'stock' => 'required|numeric']);
        try {
            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/materials", $request->all());
            if ($r->successful()) return back()->with('success', 'Material berhasil ditambahkan.');
            return back()->with('error', $r->json('message') ?? 'Gagal menambahkan material.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }

    public function update(Request $request, int $id)
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/materials/{$id}", $request->all());
            if ($r->successful()) return back()->with('success', 'Material berhasil diperbarui.');
            return back()->with('error', $r->json('message') ?? 'Gagal memperbarui.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }

    public function restock(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|numeric|min:1']);
        try {
            $r = Http::timeout(10)->withToken(session('token'))->post("{$this->apiUrl}/api/materials/{$id}/restock", ['quantity' => $request->quantity]);
            if ($r->successful()) return back()->with('success', 'Stok berhasil ditambahkan.');
            return back()->with('error', $r->json('message') ?? 'Gagal restock.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }
}