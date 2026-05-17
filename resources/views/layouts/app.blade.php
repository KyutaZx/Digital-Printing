<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jaya Mandiri Digital Printing')</title>
    <meta name="description" content="@yield('meta_description', 'Solusi digital printing berkualitas tinggi dengan harga terjangkau.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white text-slate-800">

{{-- NAVBAR --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm border-b border-slate-100"
        x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 font-black text-xl">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center text-white text-sm">J</div>
                <span class="text-slate-900">Jaya Mandiri</span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="/" class="px-4 py-2 rounded-lg font-medium transition-colors text-sm text-slate-700 hover:text-primary-600 hover:bg-primary-50">Beranda</a>
                <a href="/katalog" class="px-4 py-2 rounded-lg font-medium transition-colors text-sm text-slate-700 hover:text-primary-600 hover:bg-primary-50">Katalog</a>
                <a href="/tentang" class="px-4 py-2 rounded-lg font-medium transition-colors text-sm text-slate-700 hover:text-primary-600 hover:bg-primary-50">Tentang</a>
            </nav>

            {{-- Desktop Actions --}}
            <div class="hidden md:flex items-center gap-3">
                @if(session('user'))
                    {{-- Cart Icon --}}
                    <div x-data="{ count: 0 }" x-init="fetch('{{ config('app.golang_api_url', 'http://localhost:8080') }}/api/cart', {headers: {'Authorization': 'Bearer {{ session('token') }}'}}).then(r => r.json()).then(d => { if(d.items) count = d.items.reduce((acc, item) => acc + item.quantity, 0) }).catch(() => {})">
                        <a href="/cart" class="relative p-2 rounded-xl transition-colors flex items-center justify-center text-slate-700 hover:bg-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span x-show="count > 0" x-text="count" x-cloak class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white"></span>
                        </a>
                    </div>
                    {{-- User Dropdown --}}
                    <div x-data="{ userOpen: false }" class="relative">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 px-3 py-2 rounded-xl font-semibold text-sm transition-colors text-slate-700 bg-slate-100 hover:bg-slate-200">
                            <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(session('user.name', 'U'), 0, 1)) }}
                            </div>
                            {{ session('user.name', 'Akun') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="userOpen" x-cloak @click.away="userOpen = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1 z-50 fade-in">
                            <a href="/pesanan" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Pesanan Saya
                            </a>
                            <a href="/profil" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <hr class="my-1 border-slate-100">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="px-4 py-2 font-semibold text-sm transition-colors" :class="scrolled ? 'text-slate-700 hover:text-primary-600' : 'text-white/80 hover:text-white'">Masuk</a>
                    <a href="/register" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm transition-all shadow-sm">Daftar</a>
                @endif
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="open = !open" class="md:hidden p-2 rounded-lg transition-colors" :class="scrolled ? 'text-slate-700' : 'text-white'">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-cloak class="md:hidden bg-white rounded-2xl shadow-xl border border-slate-100 mb-4 py-2 fade-in">
            <a href="/" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Beranda</a>
            <a href="/katalog" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Katalog</a>
            @if(session('user'))
                <a href="/cart" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Keranjang</a>
                <a href="/pesanan" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 font-medium text-sm">Pesanan Saya</a>
                <form method="POST" action="/logout"><@csrf<button type="submit" class="w-full text-left px-4 py-3 text-red-600 font-medium text-sm">Keluar</button></form>
            @else
                <a href="/login" class="block px-4 py-3 text-primary-600 hover:bg-primary-50 font-semibold text-sm">Masuk</a>
                <a href="/register" class="block px-4 py-3 text-primary-600 hover:bg-primary-50 font-semibold text-sm">Daftar</a>
            @endif
        </div>
    </div>
</header>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-cloak class="fixed top-20 right-4 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg font-medium text-sm flex items-center gap-2 fade-in">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
    <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-cloak class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg font-medium text-sm flex items-center gap-2 fade-in">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    {{ session('error') }}
    <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
</div>
@endif

{{-- PAGE CONTENT --}}
<main class="pt-16 lg:pt-20">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-slate-900 text-slate-300 pt-16 pb-8 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 font-black text-xl text-white mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center text-white text-sm">J</div>
                    Jaya Mandiri
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">Solusi digital printing berkualitas tinggi untuk kebutuhan bisnis dan personal Anda.</p>
            </div>
            <div>
                <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wide">Layanan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/katalog" class="hover:text-white transition-colors">Banner Outdoor</a></li>
                    <li><a href="/katalog" class="hover:text-white transition-colors">Spanduk</a></li>
                    <li><a href="/katalog" class="hover:text-white transition-colors">Sticker Custom</a></li>
                    <li><a href="/katalog" class="hover:text-white transition-colors">Kartu Nama</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wide">Perusahaan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/tentang" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="/cara-order" class="hover:text-white transition-colors">Cara Order</a></li>
                    <li><a href="/kontak" class="hover:text-white transition-colors">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wide">Hukum</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/syarat-ketentuan" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="/kebijakan-privasi" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500">
            © {{ date('Y') }} Jaya Mandiri Digital Printing. Hak Cipta Dilindungi.
        </div>
    </div>
</footer>

</body>
</html>
