@extends('layouts.app')

@section('title', 'Keranjang Belanja — Jaya Mandiri')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 pb-24 fade-in font-sans">
    
    @if(count($items) > 0)
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left Column: Cart Items -->
        <div class="w-full lg:w-[65%] xl:w-[70%]">
            
            <div class="bg-white rounded-3xl border border-slate-200 p-6 lg:p-8 shadow-sm mb-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-baseline gap-2">
                        <h1 class="text-3xl font-bold text-slate-900">Cart</h1>
                        <span class="text-slate-400 font-medium text-sm">({{ count($items) }} products)</span>
                    </div>
                    <button type="button" class="text-red-500 font-semibold text-sm flex items-center gap-1 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear cart
                    </button>
                </div>

                <!-- Table Headers -->
                <div class="hidden md:grid grid-cols-12 gap-4 text-slate-900 font-semibold text-sm mb-4 px-2">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-3 text-center">Count</div>
                    <div class="col-span-3 text-right">Price</div>
                </div>

                <!-- Items List -->
                <div class="space-y-4">
                    @foreach($items as $item)
                    <div class="flex flex-col md:grid md:grid-cols-12 gap-4 items-center bg-slate-50/50 border border-slate-100 rounded-3xl p-4 transition-all hover:bg-slate-50">
                        
                        <!-- Product Info (Image + Name) -->
                        <div class="col-span-6 w-full flex items-center gap-4">
                            <div class="w-20 h-20 bg-white rounded-2xl overflow-hidden shrink-0 border border-slate-100">
                                @if(!empty($item['product_image']))
                                    <img src="{{ url('/api-proxy/' . ltrim($item['product_image'], '/')) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $item['product_name'] }}</h3>
                                <p class="text-sm text-slate-400 mt-1">{{ $item['variant_name'] ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Count / Quantity Control -->
                        <div class="col-span-3 w-full flex justify-center items-center">
                            <div class="flex items-center gap-4">
                                <form action="/cart/update" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors shadow-sm" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                </form>
                                
                                <span class="font-bold text-slate-900 w-4 text-center">{{ $item['quantity'] }}</span>
                                
                                <form action="/cart/update" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Price & Remove -->
                        <div class="col-span-3 w-full flex justify-between md:justify-end items-center gap-6">
                            <div class="font-bold text-slate-900 text-lg">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </div>
                            
                            <form action="/cart/remove/{{ $item['cart_item_id'] }}" method="POST" class="m-0" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Remove item">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Banner placeholder like in reference -->
            <div class="rounded-3xl overflow-hidden shadow-sm relative h-48 bg-slate-900">
                <img src="https://images.unsplash.com/photo-1543128639-4cb7e6eeef1b?q=80&w=1200&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50" alt="Banner">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent flex items-center p-8">
                    <div class="text-white max-w-sm">
                        <h4 class="text-2xl font-bold mb-2">Check the newest<br>Printing products</h4>
                        <p class="text-slate-300 text-sm mb-4">Official Jaya Mandiri retailer</p>
                        <a href="/katalog" class="inline-block px-6 py-2 border border-white/50 rounded-full text-sm font-medium hover:bg-white hover:text-slate-900 transition-colors">
                            Shop now
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary -->
        <div class="w-full lg:w-[35%] xl:w-[30%]">
            @php 
                $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']); 
            @endphp
            <div class="bg-white rounded-3xl p-6 lg:p-8 sticky top-6 border border-slate-200 shadow-sm">
                <!-- Totals -->
                <div class="space-y-4 text-sm mb-6 border-b border-slate-200 pb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="text-slate-900 font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Discount</span>
                        <span class="text-slate-900 font-bold">-Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-8">
                    <span class="text-blue-900 font-black text-lg">Total</span>
                    <span class="text-blue-900 font-black text-2xl">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <!-- Checkout -->
                <form action="/checkout" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-medium rounded-2xl py-4 transition-colors">
                        Continue to checkout
                    </button>
                </form>

            </div>
        </div>

    </div>
    @else
    <div class="bg-white rounded-3xl py-24 text-center border border-slate-100 shadow-sm max-w-2xl mx-auto">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h2 class="text-2xl font-black text-slate-900 mb-3">Cart Empty</h2>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">There are no products in your cart yet. Browse our catalog to find what you need.</p>
        <a href="/katalog" class="inline-block bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-full px-8 py-3 transition-colors">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
