@extends('layouts.manager')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Kelola Pengguna & Staf')

@section('content')
<div class="space-y-6 fade-in" x-data="{ modalOpen: false }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Daftar Pengguna Sistem</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola akun pelanggan, staf, serta hak akses mereka</p>
        </div>
        <div>
            <button @click="modalOpen = true" class="btn-primary !text-xs !py-2 !px-4 !bg-primary-600 hover:!bg-primary-700 shadow-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Daftarkan Staf Baru
            </button>
        </div>
    </div>

    {{-- Filter Role --}}
    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
        <a href="?role_id=" class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 {{ !$roleId ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'bg-white text-slate-500 hover:bg-slate-100' }}">
            Semua Pengguna
        </a>
        <a href="?role_id=2" class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 {{ $roleId == 2 ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'bg-white text-slate-500 hover:bg-slate-100' }}">
            Staf Toko
        </a>
        <a href="?role_id=3" class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 {{ $roleId == 3 ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'bg-white text-slate-500 hover:bg-slate-100' }}">
            Pelanggan / Customer
        </a>
    </div>

    {{-- Users Table --}}
    <div class="card border-none shadow-md overflow-hidden bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                        <th class="px-6 py-4 text-left">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Nomor Telepon</th>
                        <th class="px-6 py-4 text-left">Role</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $user['name'] }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">{{ $user['email'] }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-mono">{{ $user['phone'] ?: '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $rId = $user['role_id'] ?? 3;
                                $roleLabel = match($rId) {
                                    1 => 'Owner / Admin',
                                    2 => 'Staf Toko',
                                    default => 'Customer'
                                };
                                $roleBadge = match($rId) {
                                    1 => 'badge-red',
                                    2 => 'badge-purple',
                                    default => 'badge-blue'
                                };
                            @endphp
                            <span class="{{ $roleBadge }} !text-[9px] font-black uppercase tracking-tighter">{{ $roleLabel }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user['is_active'])
                                <span class="badge badge-green !text-[9px] font-black uppercase">Aktif</span>
                            @else
                                <span class="badge badge-red !text-[9px] font-black uppercase">Banned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($rId == 1)
                                <span class="text-xs text-slate-400 italic">Protected</span>
                            @else
                                <form method="POST" action="/manager/users/{{ $user['id'] }}/status" class="inline">
                                    @csrf
                                    <input type="hidden" name="is_active" value="{{ $user['is_active'] ? '0' : '1' }}">
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 text-xs font-bold {{ $user['is_active'] ? 'text-red-600 hover:underline' : 'text-green-600 hover:underline' }}">
                                        {{ $user['is_active'] ? 'Ban' : 'Unban' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada pengguna ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Register Staff --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Content --}}
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-3xl max-w-md w-full shadow-2xl p-6 overflow-hidden z-10">
            
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">Daftarkan Staf Toko Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="/manager/users/staff" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input text-sm" placeholder="Nama lengkap staf" required>
                </div>
                <div>
                    <label class="form-label">Email Staf</label>
                    <input type="email" name="email" class="form-input text-sm" placeholder="email@toko.com" required>
                </div>
                <div>
                    <label class="form-label">Password Sementara</label>
                    <input type="password" name="password" class="form-input text-sm" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="modalOpen = false" class="btn-secondary !text-xs">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary !text-xs !bg-primary-600 hover:!bg-primary-700">
                        Daftarkan Staf
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
