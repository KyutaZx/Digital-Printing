@extends('layouts.manager')

@section('title', 'Katalog Produk')
@section('page_title', 'Katalog Produk')
@section('page_description', 'Kelola produk, varian, dan harga jual toko')

@section('page_actions')
<button @click="openModal()" class="btn-primary !text-sm !py-2.5 !px-5 shrink-0">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Produk Baru
</button>
@endsection

@section('content')
<div x-data="{ 
    modalOpen: false, 
    editMode: false, 
    currentProduct: { name: '', description: '', base_price: '', category_id: '', estimated_days: 1, variants: [] },
    variants: [{ id: 0, name: '', price: '', stock: '', material_id: '', material_usage: '' }],
    addVariant() { this.variants.push({ id: 0, name: '', price: '', stock: '', material_id: '', material_usage: '' }); },
    removeVariant(i) { if (this.variants.length > 1) this.variants.splice(i, 1); },
    openModal(product = null) {
        if(product) {
            this.editMode = true;
            this.currentProduct = JSON.parse(JSON.stringify(product));
            if (product.variants && product.variants.length > 0) {
                this.variants = product.variants.map(v => ({ 
                    id: v.id, 
                    name: v.variant_name, 
                    price: v.price, 
                    stock: v.stock ?? 999,
                    material_id: v.material_id ?? '',
                    material_usage: v.material_usage ?? ''
                }));
            } else {
                this.variants = [{ id: 0, name: '', price: '', stock: '', material_id: '', material_usage: '' }];
            }
        } else {
            this.editMode = false;
            this.currentProduct = { name: '', description: '', base_price: '', category_id: '', estimated_days: 1, variants: [] };
            this.variants = [{ id: 0, name: '', price: '', stock: '', material_id: '', material_usage: '' }];
        }
        this.modalOpen = true;
    }
}" class="space-y-6 fade-in pb-8">

    @include('manager.partials.flash')


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Produk</p>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ count($products) }}</h3>
        </div>
        <div class="lg:col-span-2 bg-gradient-to-br from-primary-600 to-primary-500 rounded-3xl p-5 shadow-lg shadow-primary-500/20 text-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <p class="text-[10px] font-bold text-primary-100 uppercase tracking-widest relative z-10">Manajemen Katalog</p>
            <p class="text-xs text-primary-100 mt-1 relative z-10">Klik kartu produk untuk edit atau hapus</p>
        </div>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group flex flex-col h-full hover:shadow-md transition-shadow">
            <div class="aspect-video bg-slate-100 relative overflow-hidden shrink-0">
                @if(!empty($product['image']))
                    <img src="{{ url('/api-proxy/' . ltrim($product['image'] ?? '', '/')) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 px-4">
                    <button @click="openModal({{ json_encode($product) }})" class="p-2 bg-white rounded-xl text-primary-600 hover:bg-primary-50 transition-colors shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <form action="/manager/produk/{{ $product['id'] }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 bg-white rounded-xl text-red-600 hover:bg-red-50 transition-colors shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-primary-600 mb-1 block">{{ $product['category_name'] ?? 'Category' }}</span>
                    <h3 class="font-bold text-slate-900 text-sm mb-1 leading-tight">{{ $product['name'] }}</h3>
                    <p class="text-xs text-slate-400 line-clamp-2">{{ $product['description'] }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                    <div>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Mulai Dari</p>
                        <p class="font-black text-slate-900">Rp {{ number_format($product['base_price'], 0, ',', '.') }}</p>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 italic">{{ count($product['variants'] ?? []) }} Varian</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Modal Form (Tambah/Edit) --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl relative z-10 overflow-hidden fade-in">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-900 tracking-tight" x-text="editMode ? 'Edit Produk' : 'Tambah Produk Baru'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="editMode ? '/manager/produk/' + currentProduct.id : '/manager/produk'" method="POST" enctype="multipart/form-data" class="px-8 py-8 space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" x-model="currentProduct.name" class="form-input text-sm" required placeholder="Contoh: Banner Flexi 280gr">
                    </div>
                    <div>
                        <label class="form-label">Kategori</label>
                        <select name="category_id" x-model="currentProduct.category_id" class="form-input text-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
 
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" x-model="currentProduct.description" class="form-input text-sm h-24" required placeholder="Jelaskan detail produk..."></textarea>
                </div>
 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Harga Dasar (Rp)</label>
                        <input type="number" name="base_price" x-model="currentProduct.base_price" class="form-input text-sm font-black text-primary-600" required placeholder="0">
                    </div>
                    <div>
                        <label class="form-label">Estimasi Pengerjaan (Hari)</label>
                        <input type="number" name="estimated_days" x-model="currentProduct.estimated_days" class="form-input text-sm" min="1" placeholder="1">
                    </div>
                </div>
 
                {{-- VARIAN PRODUK --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">Varian Produk</label>
                        <button type="button" @click="addVariant()" class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Varian
                        </button>
                    </div>
                    <div class="space-y-2">
                        <div class="hidden md:flex gap-2 items-center text-[10px] font-bold text-slate-400 uppercase tracking-wider px-1">
                            <span class="flex-[3]">Nama Varian</span>
                            <span class="flex-[2]">Harga (Rp)</span>
                            <span class="flex-1">Stok</span>
                            <span class="flex-[2.5]">Bahan Baku Gudang</span>
                            <span class="flex-[1.5]">Konsumsi</span>
                            <span class="w-8 shrink-0"></span>
                        </div>
                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="flex gap-2 items-center flex-wrap md:flex-nowrap border-b border-slate-100 pb-3 md:pb-0 md:border-none">
                                <input type="hidden" :name="'variant_id[' + index + ']'" x-model="variant.id">
                                
                                <input type="text" :name="'variant_name[' + index + ']'" x-model="variant.name"
                                       class="form-input text-xs flex-[3] w-full" placeholder="Varian (e.g. A3 Glossy)" required>
                                       
                                <input type="number" :name="'variant_price[' + index + ']'" x-model="variant.price"
                                       class="form-input text-xs flex-[2] w-full" placeholder="Harga" min="0">
                                       
                                <input type="number" :name="'variant_stock[' + index + ']'" x-model="variant.stock"
                                       class="form-input text-xs flex-1 w-full" placeholder="Stok" min="0">

                                <select :name="'variant_material_id[' + index + ']'" x-model="variant.material_id"
                                        class="form-input text-xs flex-[2.5] w-full">
                                    <option value="">-- Tanpa Bahan --</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material['id'] }}">{{ $material['name'] }} ({{ $material['unit'] }})</option>
                                    @endforeach
                                </select>

                                <input type="number" step="0.01" :name="'variant_material_usage[' + index + ']'" x-model="variant.material_usage"
                                       class="form-input text-xs flex-[1.5] w-full" placeholder="Jumlah" min="0">
                                       
                                <button type="button" @click="removeVariant(index)" x-show="variants.length > 1"
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Kosongkan kolom harga untuk menggunakan harga dasar.</p>
                </div>

                <div>
                    <label class="form-label">Foto Produk (JPG/PNG)</label>
                    <input type="file" name="image" class="form-input text-xs" accept="image/*">
                    <p class="text-[10px] text-slate-400 mt-1">* Kosongkan jika tidak ingin mengubah foto</p>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="modalOpen = false" class="btn-secondary !text-xs">Batal</button>
                    <button type="submit" class="btn-primary !text-xs !px-8" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Produk'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
