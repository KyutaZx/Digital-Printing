@extends('layouts.app')

@section('title', 'Keranjang Belanja — Jaya Mandiri')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 pb-16 fade-in">

    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Keranjang</h1>
            <p class="text-sm text-slate-500 mt-1">{{ count($items) }} item</p>
        </div>
        <a href="/katalog" class="text-xs font-bold text-primary-600 hover:text-primary-700 shrink-0">+ Tambah</a>
    </div>

    @if(count($items) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            @foreach($items as $item)
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex gap-4 items-center">
                <div class="w-20 h-20 bg-slate-100 rounded-2xl overflow-hidden shrink-0">
                    @if(!empty($item['product_image']))
                        <img src="{{ $apiUrl . $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">{{ $item['product_name'] }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item['variant_name'] ?? '-' }}</p>
                        </div>
                        <form action="/cart/remove/{{ $item['cart_item_id'] }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden">
                            <form action="/cart/update" method="POST">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                <button type="submit" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-sm" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>−</button>
                            </form>
                            <span class="px-4 py-1.5 text-sm font-bold text-slate-800">{{ $item['quantity'] }}</span>
                            <form action="/cart/update" method="POST">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                <button type="submit" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-sm">+</button>
                            </form>
                        </div>
                        <p class="font-black text-primary-600 text-sm">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div>
            @php $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']); @endphp
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 sticky top-6">
                <h2 class="font-black text-slate-900 text-sm mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-3 pb-4 border-b border-slate-100 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Biaya Layanan</span>
                        <span class="font-semibold text-emerald-600">Gratis</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-slate-900 text-sm">Total</span>
                    <span class="text-xl font-black text-primary-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <form action="/checkout" method="POST">
                    @csrf
                    <button type="submit" class="w-full btn-primary py-3.5">Lanjut Checkout</button>
                </form>
                <p class="text-[10px] text-slate-400 text-center mt-4 font-bold uppercase tracking-widest">Aman & Terpercaya</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-3xl py-20 text-center border border-slate-100 shadow-sm">
        <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h2 class="text-xl font-black text-slate-900 mb-2">Keranjang Kosong</h2>
        <p class="text-sm text-slate-500 mb-6">Belum ada produk di keranjang Anda.</p>
        <a href="/katalog" class="btn-primary px-8">Mulai Belanja</a>
    </div>
    @endif
</div>
@endsection
