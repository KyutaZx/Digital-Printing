@extends('layouts.app')

@section('title', ($product['name'] ?? 'Detail Produk') . ' — Jaya Mandiri')

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="/katalog" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary-600 font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Katalog
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            {{-- Image Section --}}
            <div class="space-y-4">
                <div class="card aspect-square bg-white flex items-center justify-center overflow-hidden">
                    @if(!empty($product['image']))
                        <img src="{{ $apiUrl . $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-contain p-8">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-200 gap-4">
                            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="font-bold uppercase tracking-widest text-sm">Foto Produk Belum Tersedia</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detail Section --}}
            <div x-data="{ 
                quantity: 1, 
                variantId: '', 
                basePrice: {{ $product['base_price'] ?? 0 }},
                variants: {{ json_encode($product['variants'] ?? []) }},
                get totalPrice() {
                    let v = this.variants.find(v => v.id == this.variantId);
                    let price = v ? v.price : this.basePrice;
                    return price * this.quantity;
                }
            }">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                    <div class="mb-6">
                        <span class="badge badge-blue mb-3">{{ $product['category_name'] ?? 'Digital Printing' }}</span>
                        <h1 class="text-4xl font-black text-slate-900 leading-tight mb-2">{{ $product['name'] }}</h1>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $product['description'] ?? 'Tidak ada deskripsi untuk produk ini.' }}</p>
                    </div>

                    <div class="mb-8">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Harga Satuan</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-primary-600">
                                Rp <span x-text="variantId ? variants.find(v => v.id == variantId).price.toLocaleString('id-ID') : basePrice.toLocaleString('id-ID')"></span>
                            </span>
                            <span class="text-sm text-slate-400" x-show="variantId">/ <span x-text="variants.find(v => v.id == variantId).name"></span></span>
                        </div>
                    </div>

                    <form action="/cart/add" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        
                        {{-- Variants --}}
                        @if(!empty($product['variants']))
                        <div>
                            <label class="form-label">Pilih Ukuran / Varian</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($product['variants'] as $variant)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="variant_id" value="{{ $variant['id'] }}" 
                                           x-model="variantId" required
                                           class="peer sr-only">
                                    <div class="px-4 py-3 border-2 border-slate-100 rounded-xl text-center transition-all peer-checked:border-primary-600 peer-checked:bg-primary-50 group-hover:border-slate-200">
                                        <p class="text-xs font-bold text-slate-700 peer-checked:text-primary-700">{{ $variant['name'] }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Rp {{ number_format($variant['price'], 0, ',', '.') }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="variant_id" value="0">
                        @endif

                        {{-- Quantity & Notes --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Jumlah</label>
                                <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden w-fit bg-slate-50">
                                    <button type="button" @click="if(quantity > 1) quantity--" class="px-5 py-3 text-slate-600 hover:bg-slate-100 font-black transition-colors">-</button>
                                    <input type="number" name="quantity" x-model="quantity" readonly class="w-16 text-center bg-transparent font-bold text-slate-900 border-none focus:ring-0">
                                    <button type="button" @click="quantity++" class="px-5 py-3 text-slate-600 hover:bg-slate-100 font-black transition-colors">+</button>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Catatan Tambahan (Opsional)</label>
                                <input type="text" name="notes" placeholder="Misal: Finishing mata ayam" class="form-input">
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="pt-6 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Total Bayar</p>
                                <p class="text-3xl font-black text-slate-900">Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></p>
                            </div>
                            
                            @if(session('user'))
                                <button type="submit" class="w-full btn-primary py-4 text-base shadow-xl shadow-primary-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    Tambahkan ke Keranjang
                                </button>
                            @else
                                <a href="/login" class="w-full btn-primary py-4 text-base shadow-xl shadow-primary-200">
                                    Masuk untuk Memesan
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Shipping Info --}}
                <div class="mt-6 flex gap-4">
                    <div class="card flex-1 p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-700 leading-tight">Proses Cepat<br><span class="text-slate-400 font-medium">1-2 hari kerja</span></p>
                    </div>
                    <div class="card flex-1 p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-700 leading-tight">Kualitas Terjamin<br><span class="text-slate-400 font-medium">Garansi cetak ulang</span></p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
