<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jaya Mandiri Digital Printing')</title>
    <meta name="description" content="@yield('meta_description', 'Solusi digital printing berkualitas tinggi dengan harga terjangkau.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    @stack('head')
    @vite(['resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; } [x-cloak] { display: none !important; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

{{-- NAVBAR --}}
<div class="fixed top-4 left-0 right-0 z-50 px-4 sm:px-6 lg:px-8 flex justify-center pointer-events-none">
    <header class="w-full max-w-5xl rounded-full pointer-events-auto transition-all duration-300 relative"
            style="isolation: isolate;"
            x-data="{ open: false }">
        <!-- Liquid Glass Background & Distortion Layer -->
        <div class="absolute inset-0 -z-20 bg-white/40 rounded-full overflow-hidden"
             style="backdrop-filter: url('#navbar-liquid-glass') blur(12px); -webkit-backdrop-filter: url('#navbar-liquid-glass') blur(12px);">
        </div>
        
        <!-- Liquid Glass 3D Inset Shadows & Outer Shadow (Light theme optimized) -->
        <div class="absolute inset-0 -z-10 rounded-full overflow-hidden pointer-events-none
            shadow-[0_4px_12px_rgba(0,0,0,0.02),0_8px_24px_rgba(0,0,0,0.03),inset_3px_3px_1px_-2.5px_rgba(255,255,255,0.95),inset_-3px_-3px_1px_-2.5px_rgba(0,0,0,0.04),inset_1px_1px_1px_-0.5px_rgba(255,255,255,0.95),inset_-1px_-1px_1px_-0.5px_rgba(0,0,0,0.04),inset_0_0_8px_4px_rgba(255,255,255,0.6),inset_0_0_2px_2px_rgba(255,255,255,0.4)]
            border border-white/40">
        </div>
        <div class="flex items-center justify-between h-16 px-4 lg:px-6">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 font-black text-xl">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/logo-j.svg') }}" alt="Logo" class="w-full h-full object-contain drop-shadow-sm">
                </div>
                <span class="text-slate-900 tracking-tight text-lg">Jaya Mandiri</span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-2">
                <a href="/" class="px-4 py-2 rounded-full font-semibold transition-colors text-sm {{ request()->is('/') ? 'bg-slate-100 text-slate-900' : 'text-slate-900 hover:bg-slate-100' }}">Beranda</a>
                <a href="/katalog" class="px-4 py-2 rounded-full font-semibold transition-colors text-sm {{ request()->is('katalog') ? 'bg-slate-100 text-slate-900' : 'text-slate-900 hover:bg-slate-100' }}">Katalog</a>
                <a href="/tentang" class="px-4 py-2 rounded-full font-semibold transition-colors text-sm {{ request()->is('tentang') ? 'bg-slate-100 text-slate-900' : 'text-slate-900 hover:bg-slate-100' }}">Tentang</a>
                @if(session('user'))
                    <a href="/pesanan" class="px-4 py-2 rounded-full font-semibold transition-colors text-sm {{ request()->is('pesanan*') ? 'bg-slate-100 text-slate-900' : 'text-slate-900 hover:bg-slate-100' }}">
                        Pesanan
                    </a>
                @endif
            </nav>

            {{-- Desktop Actions --}}
            <div class="hidden md:flex items-center gap-3">
                @if(session('user'))
                    <div x-data="{ count: 0 }" x-init="fetch('{{ config('app.golang_api_url', 'http://localhost:8080') }}/api/cart', {headers: {'Authorization': 'Bearer {{ session('token') }}'}}).then(r => r.json()).then(d => { if(d.items) count = d.items.reduce((acc, item) => acc + item.quantity, 0) }).catch(() => {})">
                        <a href="/cart" title="Keranjang" class="relative p-2.5 rounded-full text-slate-900 hover:bg-slate-100 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span x-show="count > 0" x-text="count" x-cloak class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white"></span>
                        </a>
                    </div>
                    <div x-data="{ userOpen: false }" class="relative pl-3 border-l border-slate-200">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 group outline-none">
                            <div class="text-right hidden lg:block">
                                <p class="text-[13px] font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">{{ session('user.name', 'Akun') }}</p>
                            </div>
                            <div class="w-9 h-9 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center text-slate-700 text-xs font-bold shrink-0">
                                {{ strtoupper(substr(session('user.name', 'U'), 0, 1)) }}
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="userOpen" x-cloak @click.away="userOpen = false"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 fade-in">
                            <div class="px-4 py-2 border-b border-slate-100 mb-1">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ session('user.name', 'Akun') }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ session('user.email', '') }}</p>
                            </div>
                            <a href="/profil" class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <div class="border-t border-slate-100 mt-1 pt-1">
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors text-left">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login" class="px-4 py-2 font-semibold text-sm text-slate-900 hover:opacity-85 transition-colors">Masuk</a>
                    <a href="/register" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-full text-sm transition-all shadow-sm">Daftar</a>
                @endif
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="open = !open" class="md:hidden p-2 rounded-full text-slate-700 hover:bg-slate-100 transition-colors">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-cloak class="md:hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 fade-in">
            <a href="/" class="block px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Beranda</a>
            <a href="/katalog" class="block px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Katalog</a>
            <a href="/tentang" class="block px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Tentang</a>
            <div class="h-px bg-slate-100 my-1 mx-4"></div>
            @if(session('user'))
                <a href="/cart" class="block px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Keranjang</a>
                <a href="/pesanan" class="block px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Pesanan Saya</a>
                <a href="/profil" class="block px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Profil Saya</a>
                <form method="POST" action="/logout">@csrf<button type="submit" class="w-full text-left px-6 py-3 text-red-600 font-medium text-sm hover:bg-red-50">Keluar</button></form>
            @else
                <a href="/login" class="block px-6 py-3 text-slate-900 hover:bg-slate-50 font-semibold text-sm">Masuk</a>
                <a href="/register" class="block px-6 py-3 text-slate-900 hover:bg-slate-50 font-semibold text-sm">Daftar</a>
            @endif
        </div>
    </header>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.duration.500ms x-cloak class="fixed top-24 right-6 z-[9999] flex items-center gap-4 bg-white border border-slate-100 p-4 rounded-2xl shadow-2xl shadow-slate-200/50 max-w-md w-full fade-in overflow-hidden">
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
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.duration.500ms x-cloak class="fixed top-24 right-6 z-[9999] flex items-center gap-4 bg-white border border-slate-100 p-4 rounded-2xl shadow-2xl shadow-slate-200/50 max-w-md w-full fade-in overflow-hidden">
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

{{-- PAGE CONTENT --}}
<main class="{{ request()->is('/') ? '' : 'pt-16 lg:pt-20' }}">
    @yield('content')
</main>

{{-- FOOTER --}}
<div id="app-footer-root" class="bg-slate-950 {{ request()->is('/') ? '' : 'mt-20' }}"></div>

@vite(['resources/js/app-footer.jsx'])

@stack('scripts')

{{-- Liquid Glass SVG Filter --}}
<svg class="hidden">
  <defs>
    <filter id="navbar-liquid-glass" x="-20%" y="-20%" width="140%" height="140%" color-interpolation-filters="sRGB">
      <feTurbulence type="fractalNoise" baseFrequency="0.015 0.015" numOctaves="1" seed="3" result="noise" />
      <feGaussianBlur in="noise" stdDeviation="2.5" result="blurredNoise" />
      <feDisplacementMap in="SourceGraphic" in2="blurredNoise" scale="30" xChannelSelector="R" yChannelSelector="B" result="displaced" />
      <feGaussianBlur in="displaced" stdDeviation="4" result="finalBlur" />
      <feComposite in="finalBlur" in2="SourceGraphic" operator="over" />
    </filter>
  </defs>
</svg>

</body>
</html>
