@extends('layouts.app')

@section('title', 'Katalog Produk — Jaya Mandiri')
@section('meta_description', 'Temukan berbagai produk digital printing berkualitas tinggi di Jaya Mandiri.')

@section('content')
<div class="min-h-screen bg-slate-50">

    {{-- Page Header --}}
    <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/80 text-[10px] font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-widest">Katalog</span>
            <h1 class="text-3xl md:text-4xl font-black text-white mb-2">Katalog Produk</h1>
            <p class="text-slate-400 text-sm mb-6 max-w-2xl">
                Temukan produk cetak yang Anda butuhkan. Mulai dari perlengkapan bisnis hingga cetakan kustom.
            </p>

            {{-- Search & Categories --}}
            <div class="flex flex-col gap-5">
                {{-- Search Bar --}}
                <form method="GET" action="/katalog" class="relative w-full max-w-2xl">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ $search ?? '' }}"
                           placeholder="Cari produk (banner, sticker, kaos...)"
                           class="w-full pl-12 pr-4 py-3 rounded-full bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white/20 transition-all shadow-inner">
                </form>

                {{-- Categories --}}
                <div class="flex flex-nowrap md:flex-wrap overflow-x-auto gap-3 hide-scrollbar pb-2 md:pb-0 w-full">
                    @php
                        $currentCategory = request('category', '');
                    @endphp

                    {{-- 'All' category button --}}
                    <a href="/katalog" 
                       class="whitespace-nowrap px-5 py-2 rounded-full text-sm font-medium transition-colors {{ $currentCategory == '' ? 'bg-primary-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Semua
                    </a>

                    @php
                        $presetCategories = ['Banner Outdoor', 'Spanduk', 'Kartu Nama', 'Kalender', 'Kaos Printing', 'Kemasan', 'Kanvas Print'];
                        
                        // Merge preset with dynamic ones (if any) and remove duplicates, case-insensitive
                        $allCats = collect($presetCategories);
                        if(isset($categories) && is_iterable($categories)) {
                            foreach($categories as $c) {
                                if(!$allCats->contains(function ($val) use ($c) {
                                    return strtolower($val) === strtolower($c);
                                })) {
                                    $allCats->push($c);
                                }
                            }
                        }
                    @endphp

                    {{-- Categories List --}}
                    @foreach($allCats as $cat)
                        <a href="/katalog?category={{ urlencode($cat) }}" 
                           class="whitespace-nowrap px-5 py-2 rounded-full text-sm font-medium transition-colors {{ strcasecmp($currentCategory, $cat) === 0 ? 'bg-primary-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($search)
        <p class="text-slate-600 mb-6 text-sm">
            Hasil pencarian untuk "<span class="font-bold text-primary-600">{{ $search }}</span>" — {{ count($products) }} produk ditemukan
        </p>
        @endif

        {{-- React Entry Point --}}
        <script type="application/json" id="catalog-data">
            {!! json_encode($products) !!}
        </script>
        <script type="text/plain" id="api-url">
            {{ $apiUrl ?? '' }}
        </script>
        
        <div id="catalog-grid-root" class="w-full"></div>

    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/catalog-grid.jsx'])
@endpush
