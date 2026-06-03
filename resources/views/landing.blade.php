@extends('layouts.app')

@section('title', 'Jaya Mandiri Digital Printing — Solusi Cetak Profesional')
@section('meta_description', 'Jaya Mandiri — percetakan digital terpercaya. Banner, spanduk, sticker, kartu nama, dan pesanan cetak online dengan kualitas premium.')

@push('head')
<style>
    .font-display { font-family: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, sans-serif; }
    @keyframes float-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .animate-float { animation: float-slow 5s ease-in-out infinite; }
    @keyframes fade-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-up { animation: fade-up 0.7s ease-out forwards; }
    @keyframes gradient-x {
        0%, 100% {
            background-size: 200% 200%;
            background-position: left center;
        }
        50% {
            background-size: 200% 200%;
            background-position: right center;
        }
    }
    .animate-gradient-x {
        animation: gradient-x 3s ease infinite;
    }
</style>
@endpush

@section('content')
@php
    $apiUrl = $apiUrl ?? config('app.golang_api_url', 'http://localhost:8080');
    $productList = isset($products) && count($products) > 0 ? array_slice($products, 0, 3) : [
        ['id' => 0, 'name' => 'Brosur A4 Premium', 'category_name' => 'Promosi', 'base_price' => 50000, 'image' => null, 'placeholder' => 'from-violet-500 to-purple-700'],
        ['id' => 0, 'name' => 'Poster', 'category_name' => 'Indoor', 'base_price' => 5000, 'image' => null, 'placeholder' => 'from-rose-500 to-orange-600'],
        ['id' => 0, 'name' => 'Banner', 'category_name' => 'Outdoor', 'base_price' => 10000, 'image' => null, 'placeholder' => 'from-primary-600 to-blue-800'],
    ];
    $services = [
        ['label' => 'Sticker Custom', 'desc' => 'Vinyl waterproof untuk branding produk & kendaraan.', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['label' => 'Banner Outdoor', 'desc' => 'Flexi premium tahan cuaca untuk promosi luar ruang.', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'Spanduk', 'desc' => 'Cetak spanduk event & toko dengan finishing rapi.', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['label' => 'Kartu Nama', 'desc' => 'Berbagai finishing: matte, glossy, dan spot UV.', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ['label' => 'Kalender', 'desc' => 'Kalender meja & dinding custom untuk corporate gift.', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'Kaos Printing', 'desc' => 'DTF & sublimasi dengan warna tajam dan awet.', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
        ['label' => 'Kemasan', 'desc' => 'Box & packaging custom untuk produk UMKM.', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['label' => 'Kanvas Print', 'desc' => 'Cetak foto & artwork premium di media kanvas.', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z'],
    ];
    $steps = [
        ['num' => '01', 'title' => 'Pilih Produk', 'desc' => 'Jelajahi katalog kami dan pilih produk sesuai kebutuhan bisnis Anda.'],
        ['num' => '02', 'title' => 'Upload Desain', 'desc' => 'Upload file desain (JPG, PNG, PDF, AI, CDR) langsung dari akun Anda.'],
        ['num' => '03', 'title' => 'Pembayaran', 'desc' => 'Bayar via transfer bank dan unggah bukti pembayaran dengan aman.'],
        ['num' => '04', 'title' => 'Terima Pesanan', 'desc' => 'Pesanan dicetak berkualitas dan siap diambil atau dikirim.'],
    ];

    $carouselFallbacks = [
        ['name' => 'Banner Outdoor', 'category_name' => 'Outdoor', 'id' => 0],
        ['name' => 'Sticker Custom', 'category_name' => 'Promosi', 'id' => 0],
        ['name' => 'Kartu Nama Premium', 'category_name' => 'Corporate', 'id' => 0],
        ['name' => 'Spanduk Event', 'category_name' => 'Event', 'id' => 0],
        ['name' => 'Poster A3', 'category_name' => 'Indoor', 'id' => 0],
    ];
    $carouselImages = [
        'https://images.unsplash.com/photo-1562577309-2592ab84b1bc?w=400&h=500&fit=crop',
        'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=400&h=500&fit=crop',
        'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=400&h=500&fit=crop',
        'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=500&fit=crop',
        'https://images.unsplash.com/photo-1542744173-8e7e16109a0e?w=400&h=500&fit=crop',
    ];
    $carouselSource = (isset($products) && count($products) > 0)
        ? array_slice($products, 0, 5)
        : $carouselFallbacks;
    $heroPrograms = [];
    foreach ($carouselSource as $i => $p) {
        $img = !empty($p['image'])
            ? $apiUrl . $p['image']
            : $carouselImages[$i % count($carouselImages)];
        $heroPrograms[] = [
            'image' => $img,
            'category' => strtoupper($p['category_name'] ?? 'PRINTING'),
            'title' => $p['name'] ?? 'Produk Cetak',
            'href' => !empty($p['id']) ? '/produk/' . $p['id'] : '/katalog',
        ];
    }

    $heroProps = [
        'title' => 'Cetak Impian Anda',
        'titleHighlight' => 'Bersama Kami',
        'subtitle' => 'Dari banner raksasa hingga kartu nama elegan — kami hadir dengan teknologi printing terkini, harga terjangkau, dan pengiriman cepat.',
        'primaryAction' => ['label' => 'Lihat Katalog', 'href' => '/katalog'],
        'secondaryAction' => ['label' => 'Cara Order', 'href' => '#cara-order'],
        'disclaimer' => '*Gratis konsultasi desain untuk pelanggan baru',
        'socialProof' => [
            'avatars' => [
                'https://i.pravatar.cc/150?img=12',
                'https://i.pravatar.cc/150?img=32',
                'https://i.pravatar.cc/150?img=45',
                'https://i.pravatar.cc/150?img=68',
            ],
            'text' => 'Dipercaya 500+ pelanggan di kota Anda',
        ],
        'programs' => $heroPrograms,
    ];
@endphp

@push('scripts')
    @vite(['resources/js/landing-hero.jsx', 'resources/js/landing-services.jsx'])
@endpush

{{-- ═══════════════════════════════════════
     HERO — PulseFit-style (React)
     ═══════════════════════════════════════ --}}
<script type="application/json" id="landing-hero-props">@json($heroProps)</script>
<div id="landing-hero-root" class="w-full"></div>

<div class="relative overflow-hidden">
    <!-- Global Infinite Grid Backdrop -->
    <div id="landing-global-bg-root" class="absolute inset-0 z-0 pointer-events-none"></div>
    
    <div class="relative z-10">
        {{-- ═══════════════════════════════════════
             LAYANAN — Expanding Cards (React)
             ═══════════════════════════════════════ --}}
        <section class="py-10 lg:py-12 bg-transparent overflow-hidden relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14 lg:mb-16">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3">Layanan Kami</p>
                    <div id="landing-services-title-root"></div>
                    <p class="mt-4 text-slate-600 leading-relaxed">Kami melayani berbagai kebutuhan printing dengan kualitas terbaik dan harga kompetitif.</p>
                </div>

                <div id="landing-services-root" class="w-full flex justify-center relative z-10"></div>
            </div>
        </section>

{{-- ═══════════════════════════════════════
     PRODUK PILIHAN — 3-column grid
     ═══════════════════════════════════════ --}}
<section class="py-10 lg:py-12 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3">Katalog</p>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 via-emerald-500 to-blue-600 animate-gradient-x tracking-tight">Produk Pilihan</h2>
            </div>
            <a href="/katalog"
               class="inline-flex items-center gap-2 text-sm font-bold text-primary-600 hover:text-primary-700 group shrink-0">
                Lihat Semua
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            @foreach($productList as $product)
            @php
                $href = !empty($product['id']) ? '/produk/' . $product['id'] : '/katalog';
                $gradient = $product['placeholder'] ?? 'from-slate-400 to-slate-600';
            @endphp
            <a href="{{ $href }}" class="group block bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[4/3] overflow-hidden relative">
                    @if(!empty($product['image']))
                        <img src="{{ $apiUrl . $product['image'] }}" alt="{{ $product['name'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-lg bg-white/90 backdrop-blur text-[10px] font-bold text-slate-700 uppercase tracking-wide">{{ $product['category_name'] ?? 'Printing' }}</span>
                    </div>
                </div>
                <div class="p-5 lg:p-6">
                    <h3 class="font-display font-bold text-slate-900 text-lg group-hover:text-primary-600 transition-colors">{{ $product['name'] }}</h3>
                    <p class="mt-3 font-display text-2xl font-extrabold text-slate-900">
                        Rp {{ number_format($product['base_price'] ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Harga mulai · belum termasuk custom</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     CARA ORDER — Timeline
     ═══════════════════════════════════════ --}}
<div id="cara-order-root"></div>
@push('scripts')
    @vite('resources/js/landing-cara-order.jsx')
@endpush

{{-- ═══════════════════════════════════════
     KONTAK
     ═══════════════════════════════════════ --}}
@php
    $waDisplay = '0812-3456-7890';
    $waLink    = '6281234567890';
    $email     = 'halo@jayamandiri.com';
@endphp
<section id="kontak" class="py-10 lg:py-12 scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center">
        <div id="landing-contact-root" class="w-full max-w-4xl"></div>
        <script type="application/json" id="landing-contact-props">
            {
                "imageUrl": "https://cdn-icons-png.freepik.com/512/5968/5968534.png",
                "title": "Hubungi Kami",
                "description": "Punya pertanyaan atau ingin konsultasi desain? Tim kami siap membantu Anda.",
                "primaryHref": "https://wa.me/{{ $waLink }}?text={{ urlencode('Halo Jaya Mandiri, saya ingin bertanya tentang layanan cetak.') }}",
                "secondaryHref": "mailto:{{ $email }}"
            }
        </script>
    </div>
</section>

    </div>
</div>
@push('scripts')
    @vite('resources/js/landing-contact.jsx')
@endpush

@endsection
