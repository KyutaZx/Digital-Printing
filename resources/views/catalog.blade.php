@extends('layouts.app')

@section('title', 'Katalog Produk — Jaya Mandiri')
@section('meta_description', 'Temukan berbagai produk digital printing berkualitas tinggi di Jaya Mandiri.')

@section('content')
<div class="pt-20 min-h-screen bg-slate-50">

    {{-- Page Header --}}
    <div class="bg-gradient-to-br from-slate-900 to-blue-950 py-16 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl font-black text-white mb-3">Katalog Produk</h1>
            <p class="text-slate-400 mb-8">Temukan produk cetak yang Anda butuhkan</p>

            {{-- Search --}}
            <form method="GET" action="/katalog" class="max-w-lg mx-auto">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ $search ?? '' }}"
                           placeholder="Cari produk (banner, sticker, kaos...)"
                           class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white/20 transition-all">
                    @if($search)
                    <a href="/katalog" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        @if($search)
        <p class="text-slate-600 mb-6 text-sm">
            Hasil pencarian untuk "<span class="font-bold text-primary-600">{{ $search }}</span>" — {{ count($products) }} produk ditemukan
        </p>
        @endif

        @if(count($products) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($products as $product)
            <a href="/produk/{{ $product['id'] }}" class="group card hover:shadow-xl hover:-translate-y-1 transition-all duration-200">
                {{-- Product Image --}}
                <div class="aspect-square bg-slate-100 overflow-hidden">
                    @if(!empty($product['image_url']))
                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-2">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs">Foto belum ada</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <span class="badge badge-blue text-xs mb-2">{{ $product['category_name'] ?? 'Printing' }}</span>
                    <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 leading-snug group-hover:text-primary-600 transition-colors">
                        {{ $product['name'] }}
                    </h3>
                    <p class="text-xs text-slate-400 line-clamp-2 mb-3">{{ $product['description'] ?? '' }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400">Mulai dari</span>
                            <p class="text-primary-600 font-black text-base">
                                Rp {{ number_format($product['base_price'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-primary-50 group-hover:bg-primary-600 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-primary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @else
        <div class="text-center py-24">
            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-slate-500 mb-6">Coba kata kunci yang berbeda atau lihat semua produk kami.</p>
            <a href="/katalog" class="btn-primary">Lihat Semua Produk</a>
        </div>
        @endif

    </div>
</div>
@endsection
