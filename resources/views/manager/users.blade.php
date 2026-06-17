@extends('layouts.manager')

@section('title', 'Pengguna Sistem')
@section('page_title', 'Pengguna Sistem')
@section('page_description', 'Kelola akun pelanggan, staf, serta hak akses mereka')

@php
    $userList = is_array($users) ? $users : [];
    $totalUsers = count($userList);
    $staffCount = collect($userList)->filter(fn ($u) => ($u['role_id'] ?? 3) == 2)->count();
    $customerCount = collect($userList)->filter(fn ($u) => ($u['role_id'] ?? 3) == 3)->count();
    $adminCount = collect($userList)->filter(fn ($u) => ($u['role_id'] ?? 3) == 1)->count();
    $activeCount = collect($userList)->filter(fn ($u) => !empty($u['is_active']))->count();
    $bannedCount = $totalUsers - $activeCount;

    $roleFilterLabel = match((int) $roleId) {
        2 => 'Staf Toko',
        3 => 'Pelanggan',
        default => 'Semua Pengguna',
    };
@endphp

@section('page_actions')
<button @click="$dispatch('open-add-modal')"
        class="btn-primary !text-sm !py-2.5 !px-5 shrink-0 self-start lg:self-auto">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
    Daftarkan Staf Baru
</button>
@endsection

@section('content')

<div class="space-y-6 fade-in pb-8"
     x-data="{
        modalOpen: false,
        searchQuery: '',
        noSearchResults: false,
        checkVisible: function() {
            this.$nextTick(() => {
                const rows = this.$refs.tableBody ? this.$refs.tableBody.querySelectorAll('[data-user-row]') : [];
                this.noSearchResults = this.searchQuery.trim() !== '' &&
                    Array.from(rows).every(r => r.offsetParent === null);
            });
        }
     }"
     @open-add-modal.window="modalOpen = true"
     x-init="checkVisible()">

    @include('manager.partials.flash')


    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pengguna</p>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $totalUsers }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">{{ $roleFilterLabel }}</p>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-purple-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Staf Toko</p>
            <h3 class="text-2xl font-black text-purple-600 mt-2">{{ $staffCount }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-primary-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan</p>
            <h3 class="text-2xl font-black text-primary-600 mt-2">{{ $customerCount }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Akun</p>
            <div class="flex items-baseline gap-2 mt-2">
                <h3 class="text-2xl font-black text-emerald-600">{{ $activeCount }}</h3>
                <span class="text-xs text-slate-400 font-bold">aktif</span>
                @if($bannedCount > 0)
                <span class="text-xs text-red-500 font-bold">/ {{ $bannedCount }} deactivated</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Toolbar: Search + Role Filter --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="relative flex-1 max-w-md">
                <input type="text"
                       x-model="searchQuery"
                       @input.debounce.150ms="checkVisible()"
                       placeholder="Cari nama atau email..."
                       class="form-input !text-sm !py-2.5 !pl-10 w-full bg-slate-50 border-slate-100 focus:bg-white">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <button x-show="searchQuery" @click="searchQuery = ''; checkVisible()"
                        class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest shrink-0">
                <span x-text="searchQuery ? 'Hasil pencarian' : '{{ $totalUsers }} pengguna'"></span>
            </span>
        </div>

        <div class="px-5 pb-5">
            <div class="bg-slate-50 p-1.5 rounded-2xl flex gap-1 overflow-x-auto scrollbar-hide">
                <a href="?role_id="
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ !$roleId ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Semua Pengguna
                </a>
                <a href="?role_id=2"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $roleId == 2 ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Staf Toko
                </a>
                <a href="?role_id=3"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $roleId == 3 ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Pelanggan
                </a>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-black text-slate-900 text-sm">Daftar Akun Terdaftar</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Klik Deactivate/Activate untuk mengelola akses pengguna</p>
            </div>
            @if($adminCount > 0)
            <span class="shrink-0 px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase">{{ $adminCount }} Admin</span>
            @endif
        </div>

        @if(count($userList) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 text-left">Pengguna</th>
                        <th class="px-6 py-4 text-left">Kontak</th>
                        <th class="px-6 py-4 text-left">Role</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" x-ref="tableBody">
                    @foreach($userList as $user)
                    @php
                        $rId = $user['role_id'] ?? 3;
                        $roleLabel = match($rId) {
                            1 => 'Owner / Admin',
                            2 => 'Staf Toko',
                            default => 'Customer',
                        };
                        $avatarBg = match($rId) {
                            1 => 'from-red-500 to-rose-600',
                            2 => 'from-purple-500 to-violet-600',
                            default => 'from-primary-500 to-blue-600',
                        };
                        $roleBadge = match($rId) {
                            1 => 'bg-red-50 text-red-700 ring-red-100',
                            2 => 'bg-purple-50 text-purple-700 ring-purple-100',
                            default => 'bg-blue-50 text-blue-700 ring-blue-100',
                        };
                        $searchName = strtolower($user['name'] ?? '');
                        $searchEmail = strtolower($user['email'] ?? '');
                    @endphp
                    <tr data-user-row
                        class="hover:bg-slate-50/80 transition-colors"
                        x-show='searchQuery === "" || @js($searchName).includes(searchQuery.toLowerCase()) || @js($searchEmail).includes(searchQuery.toLowerCase())'>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br {{ $avatarBg }} flex items-center justify-center text-white text-sm font-black shrink-0 shadow-sm">
                                    {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $user['name'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">ID #{{ $user['id'] ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-medium text-slate-700">{{ $user['email'] }}</p>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $user['phone'] ?: '—' }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight ring-1 {{ $roleBadge }}">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user['is_active'])
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase ring-1 ring-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-[10px] font-black tracking-wider uppercase ring-1 ring-red-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    Deactivated
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($rId == 1)
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Protected
                                </span>
                            @else
                                <form method="POST" action="/manager/users/{{ $user['id'] }}/status" class="inline"
                                      onsubmit="return confirm('{{ $user['is_active'] ? 'Yakin ingin menonaktifkan akun ini?' : 'Yakin ingin mengaktifkan kembali akun ini?' }}')">
                                    @csrf
                                    <input type="hidden" name="is_active" value="{{ $user['is_active'] ? '0' : '1' }}">
                                    @if($user['is_active'])
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-[10px] font-black uppercase transition-colors ring-1 ring-red-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        Deactivate
                                    </button>
                                    @else
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-[10px] font-black uppercase transition-colors ring-1 ring-emerald-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Activate
                                    </button>
                                    @endif
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- No search results --}}
        <div x-show="noSearchResults" x-cloak class="px-6 py-16 flex flex-col items-center justify-center text-center border-t border-slate-50">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Pengguna Tidak Ditemukan</h4>
            <p class="text-xs text-slate-400 mt-1">Tidak ada hasil untuk "<span x-text="searchQuery" class="font-bold text-slate-600"></span>"</p>
            <button @click="searchQuery = ''; checkVisible()" class="mt-4 text-xs font-bold text-primary-600 hover:underline">Hapus pencarian</button>
        </div>

        @else
        <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Belum Ada Pengguna</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-xs">Tidak ada data pada filter <strong>{{ $roleFilterLabel }}</strong>.</p>
        </div>
        @endif
    </div>

    {{-- Modal Register Staff --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden z-10">

            <div class="px-6 py-5 bg-gradient-to-r from-primary-600 to-primary-500 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-sm">Daftarkan Staf Baru</h3>
                            <p class="text-[10px] text-primary-100 mt-0.5">Akun dengan akses Staf Toko</p>
                        </div>
                    </div>
                    <button @click="modalOpen = false" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form method="POST" action="/manager/users/staff" class="p-6 space-y-4">
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
                    <p class="text-[10px] text-slate-400 mt-1">Staf dapat mengganti password setelah login pertama.</p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="modalOpen = false" class="btn-secondary !text-sm">Batal</button>
                    <button type="submit" class="btn-primary !text-sm">Daftarkan Staf</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
