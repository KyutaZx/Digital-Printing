<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Panel') — Jaya Mandiri</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
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
            .card { @apply bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden; }
            .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
            .badge-blue { @apply bg-blue-100 text-blue-700; }
            .badge-green { @apply bg-green-100 text-green-700; }
            .badge-yellow { @apply bg-yellow-100 text-yellow-700; }
            .badge-red { @apply bg-red-100 text-red-700; }
            .badge-gray { @apply bg-slate-100 text-slate-600; }
            .badge-purple { @apply bg-purple-100 text-purple-700; }
            .form-input { @apply w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all; }
            .form-label { @apply block text-sm font-semibold text-slate-700 mb-1.5; }
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
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        aside::-webkit-scrollbar { display: none; }
        aside { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-slate-50">

<div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR (sama dengan admin) --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-slate-900 flex flex-col transition-all duration-300 shrink-0">
        <div class="h-16 flex items-center px-4 border-b border-slate-800">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center text-white font-black text-base shrink-0">J</div>
                <div x-show="sidebarOpen">
                    <p class="font-black text-white text-sm whitespace-nowrap">Jaya Mandiri</p>
                    <p class="text-xs text-secondary-400 font-semibold">Staff Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
            <div x-show="sidebarOpen" class="pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-3">Menu Utama</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-800"></div>

            @php
                $navItem = fn($active) => $active
                    ? 'bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-900/30'
                    : 'text-slate-400 hover:bg-slate-800 hover:text-white';
                $navIcon = fn($active) => $active ? 'bg-white/20' : 'bg-slate-800 group-hover:bg-slate-700';
            @endphp

            <a href="/staff/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-150 group {{ $navItem(request()->is('staff/dashboard*') || request()->is('staff')) }}">
                <div class="{{ $navIcon(request()->is('staff/dashboard*') || request()->is('staff')) }} w-8 h-8 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>

            <div x-show="sidebarOpen" class="pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-3">Operasional</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-800"></div>

            <a href="/staff/desain" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-150 group {{ $navItem(request()->is('staff/desain*')) }}">
                <div class="{{ $navIcon(request()->is('staff/desain*')) }} w-8 h-8 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Review Desain</span>
            </a>

            <a href="/staff/produksi" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-150 group {{ $navItem(request()->is('staff/produksi*')) }}">
                <div class="{{ $navIcon(request()->is('staff/produksi*')) }} w-8 h-8 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Antrean Produksi</span>
            </a>
        </nav>

        <div class="p-3 border-t border-slate-800">
            <div x-show="sidebarOpen" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-800 mb-2">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ strtoupper(substr(session('user.name', 'S'), 0, 1)) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">{{ session('user.name', 'Staff') }}</p>
                    <p class="text-xs text-secondary-400 capitalize">Staf Toko</p>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="hidden md:flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-xs font-bold text-slate-600">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </header>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition x-cloak
             class="fixed top-20 right-6 z-[9999] flex items-center gap-4 bg-white border border-slate-100 p-4 rounded-2xl shadow-2xl max-w-md w-full fade-in overflow-hidden">
            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-slate-900">Berhasil</h4>
                <p class="text-sm text-slate-500 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            <div class="absolute bottom-0 left-0 h-1 bg-emerald-500" style="animation: progress 5s linear forwards;"></div>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition x-cloak
             class="fixed top-20 right-6 z-[9999] flex items-center gap-4 bg-white border border-slate-100 p-4 rounded-2xl shadow-2xl max-w-md w-full fade-in overflow-hidden">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-slate-900">Terjadi Kesalahan</h4>
                <p class="text-sm text-slate-500 mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
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
