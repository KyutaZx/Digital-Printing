@php
    $images = !empty($product['images']) ? $product['images'] : (!empty($product['image']) ? [$product['image']] : []);
    $categoryName = $product['category_name'] ?? 'Kategori';
    $isCustomSize = in_array($categoryName, ['Banner Outdoor', 'Spanduk', 'Sticker Custom']);
@endphp
@extends('layouts.app')

@section('title', ($product['name'] ?? 'Detail Produk') . ' — Jaya Mandiri')

@section('content')
<div class="min-h-screen bg-slate-50 pt-24 pb-24" x-data="{
    activeImage: 0,
    images: {{ json_encode($images) }},
    quantity: 1,
    activeTab: 'deskripsi',
    variantId: '',
    basePrice: {{ $product['base_price'] ?? 0 }},
    variants: {{ json_encode($product['variants'] ?? []) }},
    width: 1,
    height: 1,
    selectedMaterial: 'Standar',
    isCustomSize: {{ $isCustomSize ? 'true' : 'false' }},
    get area() {
        return this.width * this.height;
    },
    get calculatedPrice() {
        let price = this.basePrice;
        if (this.variantId) {
            let v = this.variants.find(v => v.id == this.variantId);
            if (v) price = v.price;
        }
        
        if (this.isCustomSize) {
            let materialMultiplier = 1;
            if (this.selectedMaterial.includes('Korea')) materialMultiplier = 1.5;
            if (this.selectedMaterial.includes('Vinyl')) materialMultiplier = 1.8;
            return price * this.area * materialMultiplier;
        }
        return price;
    },
    get totalPrice() {
        return this.calculatedPrice * this.quantity;
    },
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="/katalog" class="inline-flex items-center gap-2 text-slate-600 hover:text-primary-600 font-medium text-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <!-- Breadcrumb -->
        <div class="text-sm text-slate-500 mb-8">
            Beranda <span class="mx-2">/</span> Katalog <span class="mx-2">/</span> <span class="text-slate-900 font-medium">{{ $product['name'] ?? 'Produk' }}</span>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 mb-16">
            
<<<<<<< Updated upstream
            {{-- Image Section --}}
            <div class="space-y-4">
                <div class="card aspect-square bg-white flex items-center justify-center overflow-hidden">
                    @if(!empty($product['image_url']))
                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-contain p-8">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-200 gap-4">
                            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="font-bold uppercase tracking-widest text-sm">Foto Produk Belum Tersedia</span>
=======
            <!-- Left: Images -->
            <div class="w-full lg:w-1/2 space-y-4">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-white border border-slate-200 flex items-center justify-center p-4">
                    <template x-if="images.length > 0">
                        <img :src="'{{ $apiUrl ?? '' }}' + images[activeImage]" alt="{{ $product['name'] ?? '' }}" class="w-full h-full object-contain">
                    </template>
                    <template x-if="images.length === 0">
                        <div class="text-slate-300 flex flex-col items-center justify-center gap-4">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="font-bold uppercase tracking-widest text-sm">Belum Ada Foto</span>
>>>>>>> Stashed changes
                        </div>
                    </template>
                </div>
                <!-- Thumbnail Gallery -->
                <template x-if="images.length > 1">
                    <div class="flex gap-4 overflow-x-auto pb-2 hide-scrollbar">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="activeImage = index" 
                                :class="{'border-primary-600': activeImage === index, 'border-transparent': activeImage !== index}"
                                class="shrink-0 w-24 h-24 rounded-lg overflow-hidden border-2 transition-colors bg-white p-2">
                                <img :src="'{{ $apiUrl ?? '' }}' + img" alt="Thumbnail" class="w-full h-full object-contain">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Right: Info & Actions -->
            <div class="w-full lg:w-1/2 flex flex-col">
                <div class="mb-2 text-primary-600 font-medium text-sm uppercase tracking-wider">{{ $categoryName }}</div>
                <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">{{ $product['name'] ?? '' }}</h1>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center text-yellow-400">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <span class="text-slate-700 font-bold ml-1">4.8</span>
                        <span class="text-slate-500 font-normal ml-1 text-sm">(120 ulasan)</span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    @if(($product['stock'] ?? 0) > 10)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Tersedia</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Stok Terbatas</span>
                    @endif
                </div>

                <div class="text-4xl font-bold text-primary-600 mb-8 flex items-end gap-2">
                    <span x-text="formatPrice(calculatedPrice)"></span>
                    <template x-if="isCustomSize"><span class="text-lg font-normal text-slate-500 pb-1"> / pcs</span></template>
                </div>

                <form action="/cart/add" method="POST" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm mb-8 space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">
                    <input type="hidden" name="material" x-model="selectedMaterial">
                    <template x-if="isCustomSize">
                        <input type="hidden" name="dimensions" :value="width + 'm x ' + height + 'm'">
                    </template>
                    
                    <!-- Variants -->
                    @if(!empty($product['variants']))
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wide">Pilih Varian / Ukuran</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($product['variants'] as $variant)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="variant_id" value="{{ $variant['id'] }}" x-model="variantId" required class="peer sr-only">
                                <div class="px-4 py-3 border-2 border-slate-100 rounded-xl text-center transition-all peer-checked:border-primary-600 peer-checked:bg-primary-50 hover:border-slate-300">
                                    <p class="text-sm font-bold text-slate-700 peer-checked:text-primary-700">{{ $variant['variant_name'] }}</p>
                                    <p class="text-xs text-slate-500 mt-1">+Rp {{ number_format($variant['price'], 0, ',', '.') }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="variant_id" value="0">
                    @endif

                    <!-- Custom Dimensions -->
                    <template x-if="isCustomSize">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Pilih Material</label>
                                <select x-model="selectedMaterial" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3.5 outline-none transition-colors">
                                    <option value="Standar">Standar</option>
                                    <option value="Flexi Korea 440gr">Flexi Korea 440gr</option>
                                    <option value="Vinyl Transparan">Vinyl Transparan</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-5 rounded-xl border border-slate-200">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Lebar (Meter)</label>
                                    <input type="number" min="0.5" step="0.5" x-model.number="width" class="w-full bg-white border border-slate-200 rounded-lg p-3 text-slate-900 outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tinggi (Meter)</label>
                                    <input type="number" min="0.5" step="0.5" x-model.number="height" class="w-full bg-white border border-slate-200 rounded-lg p-3 text-slate-900 outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all">
                                </div>
                                <div class="col-span-2 text-sm text-slate-700 flex items-center gap-2 mt-2 bg-white p-3 rounded-lg border border-slate-200">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Total Luas: <span class="font-bold text-primary-600" x-text="area + ' m²'"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Quantity & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Jumlah</label>
                            <div class="flex items-center w-full">
                                <button type="button" @click="if(quantity > 1) quantity--" class="w-12 h-12 flex items-center justify-center border border-slate-200 rounded-l-xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>
                                <input type="number" name="quantity" x-model="quantity" readonly class="flex-1 h-12 border-y border-x-0 border-slate-200 text-center font-bold text-slate-900 bg-white focus:ring-0 outline-none">
                                <button type="button" @click="quantity++" class="w-12 h-12 flex items-center justify-center border border-slate-200 rounded-r-xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Catatan (Opsional)</label>
                            <input type="text" name="notes" placeholder="Misal: Finishing mata ayam" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 outline-none transition-colors h-12">
                        </div>
                    </div>

                    <!-- Total & Buttons -->
                    <div class="pt-6 border-t border-slate-100 mt-6">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-slate-500 font-bold uppercase tracking-wide">Total Bayar</span>
                            <span class="text-3xl font-black text-slate-900" x-text="formatPrice(totalPrice)"></span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            @if(session('user'))
                                <button type="submit" name="action" value="cart" class="flex-1 bg-white border-2 border-primary-600 text-primary-600 hover:bg-primary-50 font-bold rounded-xl py-4 flex items-center justify-center gap-2 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Tambah Keranjang
                                </button>
                                <button type="submit" name="action" value="buy" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl py-4 shadow-lg shadow-primary-200 flex items-center justify-center transition-all">
                                    Beli Sekarang
                                </button>
                            @else
                                <a href="/login" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl py-4 shadow-lg shadow-primary-200 flex items-center justify-center transition-all">
                                    Masuk untuk Memesan
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                
                {{-- Shipping Info --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200 flex-1 p-5 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Proses Cepat</p>
                            <p class="text-xs text-slate-500 mt-1">Estimasi 1-2 hari kerja</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 flex-1 p-5 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Kualitas Terjamin</p>
                            <p class="text-xs text-slate-500 mt-1">Garansi cetak ulang</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabs section -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mt-12">
            <div class="flex border-b border-slate-200 overflow-x-auto hide-scrollbar">
                <button @click="activeTab = 'deskripsi'" :class="{'text-primary-600 border-b-2 border-primary-600': activeTab === 'deskripsi', 'text-slate-500 hover:bg-slate-50': activeTab !== 'deskripsi'}" class="px-8 py-5 text-sm font-bold uppercase tracking-wider whitespace-nowrap transition-all flex-1 text-center">
                    Deskripsi
                </button>
                <button @click="activeTab = 'spesifikasi'" :class="{'text-primary-600 border-b-2 border-primary-600': activeTab === 'spesifikasi', 'text-slate-500 hover:bg-slate-50': activeTab !== 'spesifikasi'}" class="px-8 py-5 text-sm font-bold uppercase tracking-wider whitespace-nowrap transition-all flex-1 text-center">
                    Spesifikasi
                </button>
                <button @click="activeTab = 'ulasan'" :class="{'text-primary-600 border-b-2 border-primary-600': activeTab === 'ulasan', 'text-slate-500 hover:bg-slate-50': activeTab !== 'ulasan'}" class="px-8 py-5 text-sm font-bold uppercase tracking-wider whitespace-nowrap transition-all flex-1 text-center">
                    Ulasan
                </button>
            </div>
            
            <div class="p-8 md:p-12 min-h-[300px]">
                <div x-show="activeTab === 'deskripsi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="prose max-w-none text-slate-600 leading-relaxed text-base">
                        <p>{{ $product['description'] ?? 'Tidak ada deskripsi untuk produk ini.' }}</p>
                    </div>
                </div>
                
                <div x-show="activeTab === 'spesifikasi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12">
                        @if(!empty($product['specs']) && is_array($product['specs']))
                            @foreach($product['specs'] as $key => $value)
                            <div class="flex flex-col py-4 border-b border-slate-100">
                                <span class="text-sm font-bold text-slate-900 mb-1">{{ $key }}</span>
                                <span class="text-slate-600">{{ $value }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="col-span-2 text-center text-slate-500 py-8">Belum ada data spesifikasi.</div>
                        @endif
                    </div>
                </div>
                
                <div x-show="activeTab === 'ulasan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada ulasan</h3>
                        <p class="text-slate-500 max-w-md mx-auto">Jadilah yang pertama memberikan ulasan setelah membeli produk ini. Pendapat Anda sangat berarti bagi pelanggan lain.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
