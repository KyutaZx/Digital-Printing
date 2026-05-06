<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    protected $apiUrl;
    public function __construct() { $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080'); }

    public function index(int $orderItemId)
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}/api/designs/{$orderItemId}");
            $designs = $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) { $designs = []; }
        return view('designs.index', compact('designs', 'orderItemId'));
    }

    public function upload(Request $request, int $orderItemId)
    {
        $request->validate(['file' => 'required|file|mimes:jpg,jpeg,png,pdf,ai,psd,cdr|max:10240']);
        try {
            $file = $request->file('file');
            $r = Http::timeout(30)
                ->withToken(session('token'))
                ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post("{$this->apiUrl}/api/designs/{$orderItemId}");

            if ($r->successful()) return back()->with('success', 'File desain berhasil diunggah!');
            return back()->with('error', $r->json('error') ?? $r->json('message') ?? 'Upload desain gagal.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }

    public function addReview(Request $request, int $designId)
    {
        $request->validate(['status' => 'required|in:approved,rejected', 'notes' => 'required|string']);
        try {
            $r = Http::timeout(10)->withToken(session('token'))
                ->post("{$this->apiUrl}/api/designs/{$designId}/review", $request->only(['status', 'notes']));
            if ($r->successful()) return back()->with('success', 'Review desain berhasil disimpan.');
            return back()->with('error', $r->json('message') ?? 'Gagal menyimpan review.');
        } catch (\Exception $e) { return back()->with('error', 'Koneksi ke server gagal.'); }
    }
}