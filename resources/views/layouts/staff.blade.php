<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Panel') — Jaya Mandiri</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-j.svg') }}">
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
<body class="bg-[#F8F9FA]">

<div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

    {{-- SIDEBAR (sama dengan admin) --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-slate-200 flex flex-col transition-all duration-300 shrink-0 z-20">
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

        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
            <div x-show="sidebarOpen" class="pb-1">
                <p class="text-[11px] font-semibold text-slate-400 px-3">Menu Utama</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-100"></div>

            @php
                $navItem = fn($active) => $active
                    ? 'bg-blue-50/80 text-blue-600 font-semibold'
                    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900';
                $navIcon = fn($active) => $active ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-500';
            @endphp

            <a href="/staff/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ $navItem(request()->is('staff/dashboard*') || request()->is('staff')) }}">
                <div class="{{ $navIcon(request()->is('staff/dashboard*') || request()->is('staff')) }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>

            <div x-show="sidebarOpen" class="pt-5 pb-1">
                <p class="text-[11px] font-semibold text-slate-400 px-3">Operasional</p>
            </div>
            <div x-show="!sidebarOpen" class="my-2 border-t border-slate-100"></div>

            <a href="/staff/desain" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ $navItem(request()->is('staff/desain*')) }}">
                <div class="{{ $navIcon(request()->is('staff/desain*')) }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Review Desain</span>
            </a>

            <a href="/staff/produksi" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ $navItem(request()->is('staff/produksi*')) }}">
                <div class="{{ $navIcon(request()->is('staff/produksi*')) }} flex items-center justify-center shrink-0 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </div>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Antrean Produksi</span>
            </a>
        </nav>

        <div class="p-3 border-t border-slate-100">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 text-sm font-medium transition-all duration-150">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
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
                        {{ strtoupper(substr(session('user.name', 'S'), 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-slate-800 leading-tight">{{ session('user.name', 'Staff') }}</p>
                        <p class="text-[10px] text-slate-500 capitalize leading-tight">Staf Toko</p>
                    </div>
                </div>
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
