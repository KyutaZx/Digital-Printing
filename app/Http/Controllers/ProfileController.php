<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    public function show()
    {
        // Coba ambil data terbaru dari API (sekarang sudah return data lengkap)
        try {
            $r = Http::timeout(5)->withToken(session('token'))->get("{$this->apiUrl}/api/profile");
            if ($r->successful()) {
                $apiData = $r->json('data') ?? [];
                // Merge API data dengan session (role ada di session, not in API yet)
                $profile = array_merge(session('user', []), $apiData);
                // Mapping role_id ke role name
                if (!isset($profile['role']) && isset($profile['role_id'])) {
                    $roleMap = [1 => 'owner', 2 => 'staff', 3 => 'customer'];
                    $profile['role'] = $roleMap[$profile['role_id']] ?? 'customer';
                }
                return view('profile.show', compact('profile'));
            }
        } catch (\Exception $e) { /* silent */ }

        // Fallback ke session
        $profile = session('user', []);
        return view('profile.show', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|min:2|max:100',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min'      => 'Nama minimal 2 karakter.',
        ]);

        try {
            // Field: name dan phone_number (sesuai backend Golang)
            $r = Http::timeout(10)->withToken(session('token'))->put("{$this->apiUrl}/api/profile", [
                'name'         => $request->name,
                'phone_number' => $request->phone ?? '',
            ]);

            if ($r->successful()) {
                // Update session user data
                $user = session('user', []);
                $user['name']  = $request->name;
                $user['phone'] = $request->phone;
                session(['user' => $user]);

                return back()->with('success', 'Profil berhasil diperbarui!');
            }

            return back()->with('error', $r->json('message') ?? 'Gagal memperbarui profil.');
        } catch (\Exception $e) {
            Log::error('Profile update: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}

