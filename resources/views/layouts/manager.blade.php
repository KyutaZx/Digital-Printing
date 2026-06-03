<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manager Panel') â€” Jaya Mandiri</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via CDN (agar jalan tanpa Vite/Node.js) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;

            /* Primary: Blue */
            --color-primary-50: #eff6ff;
            --color-primary-100: #dbeafe;
            --color-primary-200: #bfdbfe;
            --color-primary-300: #93c5fd;
            --color-primary-400: #60a5fa;
            --color-primary-500: #3b82f6;
            --color-primary-600: #2563eb;
            --color-primary-700: #1d4ed8;
            --color-primary-800: #1e40af;
            --color-primary-900: #1e3a8a;

            /* Secondary: Emerald */
            --color-secondary-50: #ecfdf5;
            --color-secondary-100: #d1fae5;
            --color-secondary-200: #a7f3d0;
            --color-secondary-300: #6ee7b7;
            --color-secondary-400: #34d399;
            --color-secondary-500: #10b981;
            --color-secondary-600: #059669;
            --color-secondary-700: #047857;
        }

        @layer base {
            * { @apply scroll-smooth; }
            body { @apply antialiased text-slate-700; }
        }

        @layer components {
            .btn-primary { @apply inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-95 cursor-pointer; }
            .btn-secondary { @apply inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-xl border border-slate-200 transition-all duration-200 shadow-sm cursor-pointer; }
            .btn-outline { @apply inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-transparent hover:bg-white/10 text-white font-semibold rounded-xl border border-white/30 transition-all duration-200 cursor-pointer; }
            .card { @apply bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden; }
            .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
            .badge-blue { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700; }
            .badge-green { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700; }
            .badge-yellow { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700; }
            .badge-red { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700; }
            .badge-gray { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600; }
            .badge-purple { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700; }

            .form-input { @apply w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all; }
            .form-label { @apply block text-sm font-semibold text-slate-700 mb-1.5; }
            .sidebar-link { @apply flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 font-medium hover:bg-primary-50 hover:text-primary-700 transition-all duration-150; }
            .sidebar-link.active { @apply bg-primary-600 text-white shadow-sm; }
            
            .fade-in { animation: fadeIn 0.5s ease-out forwards; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes progress {
                from { width: 100%; }
                to { width: 0%; }
            }
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; } [x-cloak] { display: none !important; }
    /* Hide ALL scrollbars on sidebar completely */
    aside::-webkit-scrollbar { display: none; }
    aside *::-webkit-scrollbar { display: none; }
    aside { -ms-overflow-style: none; scrollbar-width: none; }
    aside * { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#F8F9FA]">

<div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">
    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-slate-200 flex flex-col transition-all duration-300 shrink-0 z-20">
        {{-- Logo --}}
        <div class="h-16 flex items-center px-4 border-b border-slate-100">
            <div class="flex items-center gap-2 overflow-hidden">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/logo-j.svg') }}" alt="Logo" class="w-full h-full object-contain drop-shadow-sm">
                </div>
                <div x-show="sidebarOpen">
                    <p class="font-bold text-blue-600 text-lg whitespace-nowrap leading-none tracking-tight">Jaya Mandiri</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="/manager/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/dashboard*') || request()->is('manager') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/dashboard*') || request()->is('manager') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>

            {{-- Divider: Manajemen Pesanan --}}
            <div x-show="sidebarOpen" class="pt-5 pb-1">
                <p class="text-[11px] font-semibold text-slate-400 px-3">E-commerce</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-100"></div>

            <a href="/manager/pesanan" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/pesanan') || request()->is('manager/pesanan?*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/pesanan') || request()->is('manager/pesanan?*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div x-show="sidebarOpen" class="flex items-center justify-between flex-1">
                    <span class="whitespace-nowrap">Semua Pesanan</span>
                </div>
            </a>

            <a href="/manager/riwayat-pesanan" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/riwayat-pesanan*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/riwayat-pesanan*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Riwayat Pesanan</span>
            </a>

            <a href="/manager/verifikasi" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/verifikasi*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="relative {{ request()->is('manager/verifikasi*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if(isset($pendingVerifikasiCount) && $pendingVerifikasiCount > 0)
                    <span x-show="!sidebarOpen" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                    @endif
                </div>
                <div x-show="sidebarOpen" class="flex items-center justify-between flex-1">
                    <span class="whitespace-nowrap">Verifikasi Pembayaran</span>
                    @if(isset($pendingVerifikasiCount) && $pendingVerifikasiCount > 0)
                    <span class="bg-red-50 text-red-600 border border-red-200 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingVerifikasiCount }}</span>
                    @endif
                </div>
            </a>

            {{-- Divider: Produk & Inventori --}}
            <div x-show="sidebarOpen" class="pt-5 pb-1">
                <p class="text-[11px] font-semibold text-slate-400 px-3">Produk & Inventori</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-100"></div>

            <a href="/manager/produk" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/produk*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/produk*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Produk</span>
            </a>

            <a href="/manager/material" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/material*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/material*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Material Bahan</span>
            </a>

            {{-- Divider: Laporan & Analitik --}}
            <div x-show="sidebarOpen" class="pt-5 pb-1">
                <p class="text-[11px] font-semibold text-slate-400 px-3">Laporan & Analitik</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-100"></div>

            <a href="/manager/monitoring" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/monitoring*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/monitoring*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Monitoring Transaksi</span>
            </a>

            <a href="/manager/laporan" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/laporan*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/laporan*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Laporan & Audit Logs</span>
            </a>

            {{-- Divider: Pengaturan --}}
            <div x-show="sidebarOpen" class="pt-5 pb-1">
                <p class="text-[11px] font-semibold text-slate-400 px-3">Pengaturan</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-100"></div>

            <a href="/manager/users" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ request()->is('manager/users*') ? 'bg-blue-50/80 text-blue-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="{{ request()->is('manager/users*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500' }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Pengguna</span>
            </a>

        </nav>

        {{-- Footer --}}
        <div class="p-3 border-t border-slate-100">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 text-sm font-medium transition-all duration-150">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 shrink-0 z-10">
            <div class="flex items-center gap-4 flex-1">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 text-slate-400 hover:bg-slate-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                
                {{-- Mock Search --}}
                <div class="hidden md:flex items-center bg-slate-50 rounded-lg px-3 py-2 w-72 border border-slate-100 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                    <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search data..." class="bg-transparent border-none outline-none text-sm w-full text-slate-700 placeholder-slate-400">
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 text-sm font-medium text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ date('d M, y') }}</span>
                </div>
                
                <div class="h-6 w-px bg-slate-200"></div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr(session('user.name', 'M'), 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-slate-800 leading-tight">{{ session('user.name', 'Manager') }}</p>
                        <p class="text-[10px] text-slate-500 capitalize leading-tight">{{ session('user.role', 'owner') }}</p>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.duration.500ms x-cloak class="fixed top-20 right-6 z-[9999] flex items-center gap-4 bg-white border border-slate-100 p-4 rounded-2xl shadow-2xl shadow-slate-200/50 max-w-md w-full fade-in overflow-hidden">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-slate-900">Berhasil</h4>
                <p class="text-sm text-slate-500 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="flex-shrink-0 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="absolute bottom-0 left-0 h-1 bg-green-500" style="animation: progress 5s linear forwards;"></div>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.duration.500ms x-cloak class="fixed top-20 right-6 z-[9999] flex items-center gap-4 bg-white border border-slate-100 p-4 rounded-2xl shadow-2xl shadow-slate-200/50 max-w-md w-full fade-in overflow-hidden">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-slate-900">Oops, Terjadi Kesalahan</h4>
                <p class="text-sm text-slate-500 mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="flex-shrink-0 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="absolute bottom-0 left-0 h-1 bg-red-500" style="animation: progress 5s linear forwards;"></div>
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-6">
            @hasSection('page_title')
            <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">@yield('page_title')</h1>
                    @hasSection('page_description')
                    <p class="text-sm text-slate-500 mt-1">@yield('page_description')</p>
                    @endif
                </div>
                @yield('page_actions')
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
