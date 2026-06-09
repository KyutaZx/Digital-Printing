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
    
    $steps = [
        ['num' => '01', 'title' => 'Pilih Produk', 'desc' => 'Jelajahi katalog kami dan pilih produk sesuai kebutuhan bisnis Anda.'],
        ['num' => '02', 'title' => 'Upload Desain', 'desc' => 'Upload file desain (JPG, PNG, PDF, AI, CDR) langsung dari akun Anda.'],
        ['num' => '03', 'title' => 'Pembayaran', 'desc' => 'Bayar via transfer bank dan unggah bukti pembayaran dengan aman.'],
        ['num' => '04', 'title' => 'Terima Pesanan', 'desc' => 'Pesanan dicetak berkualitas dan siap diambil atau dikirim.'],
    ];

    $carouselSource = isset($products) ? array_slice($products, 0, 5) : [];
    $heroPrograms = [];
    foreach ($carouselSource as $i => $p) {
        $img = !empty($p['image'])
            ? url('/api-proxy/' . ltrim($p['image'], '/'))
            : null;
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
        @if(isset($categories) && count($categories) > 0)
        <section id="landing-services-section" class="py-10 lg:py-12 bg-transparent overflow-hidden relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14 lg:mb-16">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3">Layanan Kami</p>
                    <div id="landing-services-title-root"></div>
                    <p class="mt-4 text-slate-600 leading-relaxed">Kami melayani berbagai kebutuhan printing dengan kualitas terbaik dan harga kompetitif.</p>
                </div>

                <script type="application/json" id="landing-services-data">@json($categories)</script>
                <div id="landing-services-root" class="w-full flex justify-center relative z-10"></div>
            </div>
        </section>
        @endif


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

        @if(isset($products) && count($products) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                @foreach($products as $product)
                @php
                    $href = !empty($product['id']) ? '/produk/' . $product['id'] : '/katalog';
                @endphp
                <a href="{{ $href }}" class="group block bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="aspect-[4/3] overflow-hidden relative">
                        @if(!empty($product['image']))
                            <img src="{{ url('/api-proxy/' . ltrim($product['image'] ?? '', '/')) }}" alt="{{ $product['name'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-[#1A56E8] flex items-center justify-center">
                                <span class="text-6xl font-bold text-white opacity-90">{{ strtoupper(substr($product['name'], 0, 1)) }}</span>
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
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center col-span-1 md:col-span-3">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Produk belum tersedia.</h3>
                <p class="text-slate-500">Silakan kunjungi kembali nanti.</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     CARA ORDER — Timeline
     ═══════════════════════════════════════ --}}
<div id="cara-order-root"></div>
@push('scripts')
    @vite('resources/js/landing-cara-order.jsx')
@endpush

    </div>
</div>

@endsection
