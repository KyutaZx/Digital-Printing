@extends('layouts.app')

@section('title', 'Keranjang Belanja — Jaya Mandiri')

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3 mb-8">
            <h1 class="text-3xl font-black text-slate-900">Keranjang Belanja</h1>
            <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-bold">{{ count($items) }} Item</span>
        </div>

        @if(count($items) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- List Item --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                <div class="card p-4 flex gap-4 items-center fade-in">
                    <div class="w-24 h-24 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                        @if(!empty($item['product_image']))
                            <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-slate-900 leading-tight">{{ $item['product_name'] }}</h3>
                                <p class="text-xs text-slate-500 mt-1">{{ $item['variant_name'] }}</p>
                                @if(!empty($item['notes']))
                                    <p class="text-xs text-slate-400 italic mt-1">Note: {{ $item['notes'] }}</p>
                                @endif
                            </div>
                            <form action="/cart/remove/{{ $item['cart_item_id'] }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex justify-between items-center mt-4">
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden">
                                <form action="/cart/update" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                    <button type="submit" class="px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                </form>
                                <span class="px-4 py-1 text-sm font-bold text-slate-800">{{ $item['quantity'] }}</span>
                                <form action="/cart/update" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                    <button type="submit" class="px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold">+</button>
                                </form>
                            </div>
                            <p class="font-black text-primary-600">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <a href="/katalog" class="inline-flex items-center gap-2 text-primary-600 font-bold text-sm hover:underline mt-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali Belanja
                </a>
            </div>

            {{-- Ringkasan --}}
            <div>
                <div class="card p-6 sticky top-24">
                    <h2 class="font-bold text-slate-900 mb-4">Ringkasan Pesanan</h2>
                    
                    @php
                        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
                    @endphp
                    
                    <div class="space-y-3 pb-4 border-b border-slate-100 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Biaya Layanan</span>
                            <span class="font-semibold text-slate-900 text-green-600">Gratis</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-slate-900">Total Harga</span>
                        <span class="text-xl font-black text-primary-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <form action="/checkout" method="POST">
                        @csrf
                        <button type="submit" class="w-full btn-primary py-4 text-base shadow-lg shadow-primary-200">
                            Lanjut ke Checkout
                        </button>
                    </form>
                    
                    <p class="text-[10px] text-slate-400 text-center mt-4 uppercase tracking-widest font-bold">
                        Aman & Terpercaya • Jaya Mandiri
                    </p>
                </div>
            </div>

        </div>
        @else
        <div class="card py-20 text-center">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Keranjang Kosong</h2>
            <p class="text-slate-500 mb-8">Sepertinya Anda belum memilih produk apa pun.</p>
            <a href="/katalog" class="btn-primary px-8 py-3">Mulai Belanja</a>
        </div>
        @endif

    </div>
</div>
@endsection
