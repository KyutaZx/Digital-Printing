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
@endphp

{{-- ═══════════════════════════════════════
     HERO — Split layout, above the fold
     ═══════════════════════════════════════ --}}
<section class="relative bg-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-primary-50/40 pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-[55%] h-full bg-gradient-to-l from-primary-50/60 to-transparent pointer-events-none hidden lg:block"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 lg:pt-28 pb-16 lg:pb-24">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Copy --}}
            <div class="animate-fade-up max-w-xl">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary-50 border border-primary-100 text-primary-700 text-xs font-bold mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Percetakan Digital Terpercaya #1 di Kota Anda
                </span>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.25rem] font-extrabold text-slate-900 leading-[1.1] tracking-tight">
                    Cetak Impian Anda
                    <span class="block text-primary-600 mt-1">Bersama Kami</span>
                </h1>
                <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                    Dari banner raksasa hingga kartu nama elegan — kami hadir dengan teknologi printing terkini, harga terjangkau, dan pengiriman cepat.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    <a href="/katalog"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-emerald-600/25 transition-all hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Lihat Katalog
                    </a>
                    <a href="#cara-order"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-800 font-bold rounded-xl text-sm border-2 border-slate-200 hover:border-primary-300 transition-all">
                        Cara Order
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                </div>
                <div class="mt-12 flex flex-wrap gap-8 pt-8 border-t border-slate-100">
                    @foreach([['500+', 'Pelanggan'], ['10K+', 'Pesanan'], ['2 Jam', 'Estimasi Cetak']] as $stat)
                    <div>
                        <p class="font-display text-2xl font-extrabold text-slate-900">{{ $stat[0] }}</p>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ $stat[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Visual mockup --}}
            <div class="relative lg:h-[520px] animate-fade-up" style="animation-delay: 0.15s">
                <div class="absolute -inset-4 bg-gradient-to-br from-primary-100/50 to-emerald-100/30 rounded-[2rem] blur-2xl"></div>
                <div class="relative h-full min-h-[360px] lg:min-h-0 rounded-3xl overflow-hidden border border-slate-200/80 shadow-2xl shadow-slate-900/10">
                    <img src="https://images.unsplash.com/photo-1562577309-2592ab84b1bc?w=900&q=80&auto=format&fit=crop"
                         alt="Produk cetak premium Jaya Mandiri"
                         class="absolute inset-0 w-full h-full object-cover"
                         loading="eager">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>
                    {{-- Floating cards --}}
                    <div class="absolute bottom-6 left-6 right-6 flex gap-3 animate-float">
                        <div class="flex-1 bg-white/95 backdrop-blur rounded-2xl p-4 shadow-lg border border-white/50">
                            <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">Kualitas</p>
                            <p class="text-sm font-bold text-slate-900">Print HD Premium</p>
                        </div>
                        <div class="flex-1 bg-white/95 backdrop-blur rounded-2xl p-4 shadow-lg border border-white/50" style="animation-delay: 0.5s">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">Cepat</p>
                            <p class="text-sm font-bold text-slate-900">Proses Kilat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     LAYANAN — 4×2 grid
     ═══════════════════════════════════════ --}}
<section class="py-20 lg:py-28 bg-slate-50/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 lg:mb-16">
            <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3">Layanan Kami</p>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Semua Kebutuhan Cetak Anda</h2>
            <p class="mt-4 text-slate-600 leading-relaxed">Kami melayani berbagai kebutuhan printing dengan kualitas terbaik dan harga kompetitif.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            @foreach($services as $service)
            <a href="/katalog"
               class="group bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 hover:border-primary-200/60 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mb-5 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $service['icon'] }}"/></svg>
                </div>
                <h3 class="font-display font-bold text-slate-900 text-base mb-2">{{ $service['label'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $service['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     PRODUK PILIHAN — 3-column grid
     ═══════════════════════════════════════ --}}
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3">Katalog</p>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Produk Pilihan</h2>
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
<section id="cara-order" class="py-20 lg:py-28 bg-slate-50/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 lg:mb-20">
            <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3">Prosedur</p>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Cara Order Mudah</h2>
        </div>

        <div class="relative">
            <div class="hidden lg:block absolute top-14 left-[12%] right-[12%] h-px bg-gradient-to-r from-transparent via-primary-300 to-transparent"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6">
                @foreach($steps as $step)
                <div class="relative text-center lg:text-left bg-white lg:bg-transparent rounded-2xl lg:rounded-none p-6 lg:p-0 border border-slate-100 lg:border-0 shadow-sm lg:shadow-none">
                    <div class="inline-flex lg:flex w-14 h-14 rounded-2xl bg-primary-600 text-white font-display font-extrabold text-lg items-center justify-center shadow-lg shadow-primary-600/30 mb-5">
                        {{ $step['num'] }}
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-14">
            <a href="/katalog"
               class="inline-flex items-center gap-2 px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-primary-600/25 transition-all hover:-translate-y-0.5">
                Mulai Order Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     KONTAK
     ═══════════════════════════════════════ --}}
@php
    $waDisplay = '0812-3456-7890';
    $waLink    = '6281234567890';
    $email     = 'halo@jayamandiri.com';
@endphp
<section id="kontak" class="py-20 lg:py-24 scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900 px-8 py-14 sm:px-14 sm:py-20 text-center">
            <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative max-w-3xl mx-auto">
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Hubungi Kami</h2>
                <p class="mt-4 text-slate-300 text-lg leading-relaxed">
                    Punya pertanyaan atau ingin konsultasi desain? Tim kami siap membantu Anda.
                </p>
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto text-left">
                    <a href="https://wa.me/{{ $waLink }}?text={{ urlencode('Halo Jaya Mandiri, saya ingin bertanya tentang layanan cetak.') }}"
                       target="_blank" rel="noopener noreferrer"
                       class="group flex items-start gap-4 p-5 rounded-2xl bg-white/10 hover:bg-emerald-500/20 border border-white/15 hover:border-emerald-400/40 backdrop-blur transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-900/30 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-300">WhatsApp</p>
                            <p class="mt-1 text-lg font-bold text-white">{{ $waDisplay }}</p>
                            <p class="mt-1 text-xs text-slate-400 group-hover:text-emerald-200 transition-colors">Klik untuk chat langsung →</p>
                        </div>
                    </a>
                    <a href="mailto:{{ $email }}"
                       class="group flex items-start gap-4 p-5 rounded-2xl bg-white/10 hover:bg-primary-500/20 border border-white/15 hover:border-primary-400/40 backdrop-blur transition-all">
                        <div class="w-12 h-12 rounded-xl bg-primary-500 flex items-center justify-center shrink-0 shadow-lg shadow-primary-900/30 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary-300">Email</p>
                            <p class="mt-1 text-lg font-bold text-white break-all">{{ $email }}</p>
                            <p class="mt-1 text-xs text-slate-400 group-hover:text-primary-200 transition-colors">Klik untuk kirim email →</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
