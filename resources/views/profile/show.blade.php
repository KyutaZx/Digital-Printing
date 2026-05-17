@extends('layouts.app')

@section('title', 'Profil Saya - Jaya Mandiri')

@section('content')
<div class="min-h-screen bg-slate-50">
<div class="max-w-3xl mx-auto py-8 px-4">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Profil Saya</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola informasi akun dan keamanan Anda</p>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm font-medium">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm font-medium">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="space-y-6">

        {{-- ===== CARD: Avatar + Info ===== --}}
        @php
            $roleLabels = ['owner' => 'Owner', 'admin' => 'Admin', 'staff' => 'Staff', 'customer' => 'Customer'];
            $role = $profile['role'] ?? session('user.role', 'customer');
            $initial = strtoupper(substr($profile['name'] ?? 'U', 0, 1));

            // Validasi created_at — tolak jika null, kosong, atau tahun < 2000 (Go zero time)
            $createdAt = $profile['created_at'] ?? null;
            $showCreatedAt = false;
            if ($createdAt) {
                try {
                    $parsedDate = \Carbon\Carbon::parse($createdAt);
                    $showCreatedAt = $parsedDate->year >= 2000;
                } catch (\Exception $e) {}
            }
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            {{-- Banner --}}
            <div class="h-32 rounded-t-2xl" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #10b981 100%);"></div>
            {{-- Avatar overlapping banner --}}
            <div class="px-6 pb-6">
                <div class="flex items-end justify-between" style="margin-top: -3rem;">
                    <div class="w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center font-black text-primary-600 text-3xl shrink-0">
                        {{ $initial }}
                    </div>
                    <div class="mb-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                            {{ $roleLabels[$role] ?? ucfirst($role) }}
                        </span>
                    </div>
                </div>
                <div class="mt-3">
                    <h2 class="text-xl font-bold text-slate-900">{{ $profile['name'] ?? '-' }}</h2>
                    <p class="text-slate-500 text-sm mt-0.5">{{ $profile['email'] ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- ===== CARD: Edit Profil ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Informasi Pribadi</h3>
                        <p class="text-xs text-slate-400">Perbarui nama dan nomor HP Anda</p>
                    </div>
                </div>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $profile['name'] ?? '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                        placeholder="Masukkan nama lengkap" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input type="email"
                        value="{{ $profile['email'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 text-sm cursor-not-allowed"
                        disabled>
                    <p class="text-xs text-slate-400 mt-1">Email tidak dapat diubah.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">+62</span>
                        <input type="text" name="phone" id="phone"
                            value="{{ old('phone', ltrim($profile['phone'] ?? '', '+62')) }}"
                            class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                            placeholder="812-3456-7890">
                    </div>
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200 text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- ===== CARD: Info Akun ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Info Akun</h3>
                        <p class="text-xs text-slate-400">Informasi teknis akun Anda</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 divide-y divide-slate-50">
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-slate-500">ID Pengguna</span>
                    <span class="text-sm font-semibold text-slate-800 font-mono">{{ $profile['id'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-slate-500">Role</span>
                    <span class="text-sm font-semibold text-blue-600">{{ ucfirst($role) }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-slate-500">Status Akun</span>
                    @if($profile['is_active'] ?? true)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Non-Aktif</span>
                    @endif
                </div>
                @if($showCreatedAt)
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-slate-500">Bergabung Sejak</span>
                    <span class="text-sm font-semibold text-slate-800">
                        {{ $parsedDate->locale('id')->isoFormat('D MMMM YYYY') }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="flex gap-4">
            <a href="{{ route('orders.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-xl border border-slate-200 transition-all duration-200 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan Saya
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-xl border border-red-200 transition-all duration-200 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Akun
                </button>
            </form>
        </div>

    </div>
</div>
</div>
@endsection
