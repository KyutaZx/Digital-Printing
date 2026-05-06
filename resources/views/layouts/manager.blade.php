<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manager Panel') — Jaya Mandiri</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <span x-show="sidebarOpen" class="whitespace-nowrap">Monitoring & Laporan</span>
            </a>

            <a href="/manager/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->is('manager/pesanan*') ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Semua Pesanan</span>
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
        <div class="mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
