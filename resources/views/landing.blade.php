@extends('layouts.app')

@section('title', 'Jaya Mandiri Digital Printing — Solusi Cetak Berkualitas')
@section('meta_description', 'Jaya Mandiri menyediakan layanan digital printing berkualitas tinggi: banner, spanduk, sticker, kartu nama, dan lainnya.')

@section('content')

{{-- ============================
     HERO SECTION
     ============================ --}}
<section class="relative min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex items-center overflow-hidden">
    {{-- Decorative orbs --}}
    <div class="absolute top-20 right-10 w-80 h-80 bg-primary-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 text-center">
        <div class="fade-in">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/80 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 bg-secondary-400 rounded-full animate-pulse"></span>
                Percetakan Digital Terpercaya #1 di Kota Anda
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6">
                Cetak Impian Anda<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-400">Bersama Kami</span>
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                Dari banner raksasa hingga kartu nama elegan — kami hadir dengan teknologi printing terkini, harga terjangkau, dan pengiriman cepat.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/katalog" class="btn-primary text-base px-8 py-4 shadow-xl shadow-primary-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                    Lihat Katalog
                </a>
                <a href="#cara-order" class="btn-outline text-base px-8 py-4">
                    Cara Order
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="mt-20 grid grid-cols-3 gap-8 max-w-2xl mx-auto">
            @foreach([['500+', 'Pelanggan Puas'], ['10K+', 'Pesanan Selesai'], ['2 Jam', 'Estimasi Cetak']] as $stat)
            <div class="text-center">
                <div class="text-3xl font-black text-white mb-1">{{ $stat[0] }}</div>
                <div class="text-sm text-slate-400">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/40 text-xs animate-bounce">
        <span>Scroll</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

{{-- ============================
     LAYANAN UNGGULAN
     ============================ --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Layanan Kami</span>
            <h2 class="text-4xl font-black text-slate-900 mt-2">Semua Kebutuhan Cetak Anda</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Kami melayani berbagai kebutuhan printing dengan kualitas terbaik dan harga kompetitif.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach([
                ['🏷️', 'Sticker Custom', 'Sticker vinyl waterproof untuk keperluan branding'],
                ['🎪', 'Banner Outdoor', 'Cetak banner besar dengan bahan flexi premium'],
                ['📋', 'Spanduk', 'Spanduk promosi untuk segala event'],
                ['💌', 'Kartu Nama', 'Kartu nama profesional berbagai finishing'],
                ['📅', 'Kalender', 'Kalender meja dan dinding custom'],
                ['👕', 'Kaos Printing', 'Sablon kaos dengan teknik DTF & Sublimasi'],
                ['📦', 'Kemasan', 'Box packaging custom untuk produk Anda'],
                ['🖼️', 'Kanvas Print', 'Cetak foto di atas kanvas berkualitas tinggi'],
            ] as $service)
            <div class="group card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer text-center">
                <div class="text-4xl mb-3">{{ $service[0] }}</div>
                <h3 class="font-bold text-slate-900 text-sm mb-1">{{ $service[1] }}</h3>
                <p class="text-xs text-slate-500 leading-relaxed">{{ $service[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================
     PRODUK TERBARU
     ============================ --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Produk</span>
                <h2 class="text-3xl font-black text-slate-900 mt-1">Produk Pilihan</h2>
            </div>
            <a href="/katalog" class="btn-secondary text-sm hidden md:flex">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if(isset($products) && count($products) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <a href="/produk/{{ $product['id'] }}" class="group card hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="aspect-square bg-slate-100 overflow-hidden">
                    @if(!empty($product['image']))
                        <img src="{{ $apiUrl . $product['image'] }}" alt="{{ $product['name'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-slate-900 text-sm mb-1 line-clamp-2">{{ $product['name'] }}</h3>
                    <p class="text-xs text-slate-500 mb-2">{{ $product['category_name'] ?? 'Printing' }}</p>
                    <p class="text-primary-600 font-black text-base">
                        Rp {{ number_format($product['base_price'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p>Produk belum tersedia</p>
        </div>
        @endif
    </div>
</section>

{{-- ============================
     CARA ORDER
     ============================ --}}
<section id="cara-order" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Prosedur</span>
            <h2 class="text-4xl font-black text-slate-900 mt-2">Cara Order Mudah</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            {{-- Line --}}
            <div class="hidden md:block absolute top-10 left-1/8 right-1/8 h-0.5 bg-gradient-to-r from-primary-200 via-primary-400 to-primary-200 z-0"></div>

            @foreach([
                ['1', 'Pilih Produk', 'Jelajahi katalog kami dan pilih produk yang Anda butuhkan.', '🛍️'],
                ['2', 'Upload Desain', 'Upload file desain Anda (JPG/PNG/PDF/AI/CDR) dengan mudah.', '🎨'],
                ['3', 'Pembayaran', 'Bayar melalui transfer bank dan upload bukti pembayaran.', '💳'],
                ['4', 'Terima Pesanan', 'Pesanan Anda dicetak & siap diambil atau dikirim!', '🚚'],
            ] as $step)
            <div class="relative z-10 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-primary-200 mb-5">
                    {{ $step[3] }}
                </div>
                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-black mb-4 -mt-2">
                    {{ $step[0] }}
                </div>
                <h3 class="font-black text-slate-900 text-lg mb-2">{{ $step[1] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="/katalog" class="btn-primary text-base px-8 py-4">
                Mulai Order Sekarang →
            </a>
        </div>
    </div>
</section>

{{-- ============================
     TESTIMONI
     ============================ --}}
<section class="py-24 bg-gradient-to-br from-primary-600 to-primary-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-primary-200 font-bold text-sm uppercase tracking-widest">Ulasan</span>
            <h2 class="text-4xl font-black text-white mt-2">Apa Kata Pelanggan Kami?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['Budi Santoso', 'Pengusaha', 'Kualitas banner luar biasa! Warna tajam, bahan tebal. Pasti order lagi!', 5],
                ['Siti Rahayu', 'Event Organizer', 'Proses ordernya gampang banget, desainnya bisa langsung di-review. Mantap!', 5],
                ['Ahmad Fauzi', 'UMKM Owner', 'Harga bersaing dan hasilnya memuaskan. Recommended banget buat semua pelaku bisnis.', 5],
            ] as $review)
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6">
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < $review[3]; $i++)
                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-white/90 text-sm leading-relaxed mb-4 italic">"{{ $review[2] }}"</p>
                <div>
                    <p class="font-bold text-white text-sm">{{ $review[0] }}</p>
                    <p class="text-white/60 text-xs">{{ $review[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================
     CTA SECTION
     ============================ --}}
<section class="py-24 bg-white">
    <div class="max-w-3xl mx-auto text-center px-4">
        <h2 class="text-4xl font-black text-slate-900 mb-4">Siap Mulai Mencetak?</h2>
        <p class="text-slate-500 mb-8">Daftar sekarang dan nikmati kemudahan memesan cetak secara online kapan saja dan di mana saja.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" class="btn-primary text-base px-8 py-4">Daftar Gratis →</a>
            <a href="/katalog" class="btn-secondary text-base px-8 py-4">Lihat Produk</a>
        </div>
    </div>
</section>

@endsection
