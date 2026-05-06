<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    // =========================================================================
    // Show Login Page
    // =========================================================================
    public function showLogin()
    {
        if (session('token')) {
            return $this->redirectByRole(session('user.role', 'customer'));
        }
        return view('auth.login');
    }

    // =========================================================================
    // Show Register Page
    // =========================================================================
    public function showRegister()
    {
        if (session('token')) {
            return redirect('/');
        }
        return view('auth.register');
    }

    // =========================================================================
    // Login — POST ke Golang API
    // =========================================================================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        try {
            $response = Http::timeout(10)->post("{$this->apiUrl}/login", [
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Simpan JWT token & data user ke Laravel Session
                session([
                    'token' => $data['token'],
                    'user'  => [
                        'id'    => $data['user']['id'],
                        'name'  => $data['user']['name'],
                        'email' => $data['user']['email'],
                        'phone' => $data['user']['phone'] ?? '',
                        'role'  => $data['user']['role'] ?? 'customer',
                    ],
                ]);

                return $this->redirectByRole($data['user']['role'] ?? 'customer');
            }

            $message = $response->json('message') ?? 'Email atau password salah.';
            return back()->withErrors(['email' => $message])->withInput($request->except('password'));

        } catch (\Exception $e) {
            Log::error('Login API Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal terhubung ke server. Coba lagi.'])->withInput($request->except('password'));
        }
    }

    // =========================================================================
    // Register — POST ke Golang API
    // =========================================================================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'phone'    => 'required|string',
            'password' => 'required|min:8',
        ]);

        try {
            $response = Http::timeout(10)->post("{$this->apiUrl}/register", [
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
            }

            $message = $response->json('message') ?? 'Registrasi gagal. Coba lagi.';
            return back()->withErrors(['email' => $message])->withInput($request->except('password'));

        } catch (\Exception $e) {
            Log::error('Register API Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal terhubung ke server.'])->withInput($request->except('password'));
        }
    }

    // =========================================================================
    // Logout
    // =========================================================================
    public function logout(Request $request)
    {
        // Opsional: panggil API logout jika ada
        if (session('token')) {
            try {
                Http::withToken(session('token'))->post("{$this->apiUrl}/api/logout");
            } catch (\Exception $e) {
                // Silent — tidak masalah jika gagal
            }
        }

        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil keluar. Sampai jumpa!');
    }

    // =========================================================================
    // Helper: Redirect Berdasarkan Role
    // =========================================================================
    private function redirectByRole(string $role): \Illuminate\Http\RedirectResponse
    {
        return match ($role) {
            'staff'          => redirect('/staff/dashboard')->with('success', 'Selamat datang, Staff!'),
            'owner', 'admin' => redirect('/manager/dashboard')->with('success', 'Selamat datang, Manager!'),
            default          => redirect('/')->with('success', 'Selamat datang kembali!'),
        };
    }
}