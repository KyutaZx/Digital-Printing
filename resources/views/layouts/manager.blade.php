<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manager Panel') — Jaya Mandiri</title>
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
    <style>body { font-family: 'Inter', sans-serif; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50">

<div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-slate-900 flex flex-col transition-all duration-300 shrink-0">
        {{-- Logo --}}
        <div class="h-16 flex items-center px-4 border-b border-slate-800">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center text-white font-black text-base shrink-0">J</div>
                <div x-show="sidebarOpen">
                    <p class="font-black text-white text-sm whitespace-nowrap">Jaya Mandiri</p>
                    <p class="text-xs text-secondary-400 font-semibold">Manager Panel</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
            <p x-show="sidebarOpen" class="text-xs font-bold text-slate-500 uppercase tracking-widest px-2 mb-2">Menu Utama</p>

            <a href="/manager/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/dashboard*') || request()->is('manager') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>

            <a href="/manager/verifikasi" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/verifikasi*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Verifikasi Pembayaran</span>
            </a>

            <a href="/manager/produk" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/produk*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Produk</span>
            </a>

            <a href="/manager/material" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/material*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Material Bahan</span>
            </a>

            <a href="/manager/monitoring" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/monitoring*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Monitoring Transaksi</span>
            </a>

            <a href="/manager/laporan" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/laporan*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Laporan & Audit Logs</span>
            </a>

            <a href="/manager/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/pesanan*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Semua Pesanan</span>
            </a>

            <a href="/manager/users" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/users*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Pengguna</span>
            </a>
        </nav>

        {{-- Footer --}}
        <div class="p-3 border-t border-slate-800">
            <div x-show="sidebarOpen" class="flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-800 mb-2">
                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ strtoupper(substr(session('user.name', 'M'), 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-white truncate">{{ session('user.name', 'Manager') }}</p>
                    <p class="text-xs text-slate-400 capitalize">{{ session('user.role', 'owner') }}</p>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-red-400 hover:bg-slate-800 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
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
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
