@extends('layouts.manager')

@section('title', 'Kategori Layanan')
@section('page_title', 'Kategori Layanan')
@section('page_description', 'Kelola kategori yang akan tampil di halaman depan "Layanan Kami"')

@section('page_actions')
<button @click="$dispatch('open-modal')" class="btn-primary !text-sm !py-2.5 !px-5 shrink-0">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Kategori Baru
</button>
@endsection

@section('content')
<div x-data="{ 
    modalOpen: false, 
    editMode: false, 
    currentCategory: { id: '', name: '', description: '', image: '' },
    openModal: function(category) {
        if(category) {
            this.editMode = true;
            this.currentCategory = JSON.parse(JSON.stringify(category));
        } else {
            this.editMode = false;
            this.currentCategory = { id: '', name: '', description: '', image: '' };
        }
        this.modalOpen = true;
    }
}" @open-modal.window="openModal()" class="space-y-6 fade-in pb-8">

    @include('manager.partials.flash')


    {{-- Grid Kategori --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($categories as $cat)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group flex flex-col h-full hover:shadow-md transition-shadow">
            <div class="aspect-video bg-slate-100 relative overflow-hidden shrink-0">
                @if(!empty($cat['image']))
                    <img src="{{ url('/api-proxy/' . ltrim($cat['image'] ?? '', '/')) }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-primary-50">
                        <span class="text-3xl font-black text-primary-200 uppercase">{{ substr($cat['name'], 0, 1) }}</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 px-4">
                    <button @click="openModal({{ json_encode($cat) }})" class="p-2 bg-white rounded-xl text-primary-600 hover:bg-primary-50 transition-colors shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <form action="/manager/kategori/{{ $cat['id'] }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Jika ada produk di kategori ini, mungkin akan error.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 bg-white rounded-xl text-red-600 hover:bg-red-50 transition-colors shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm mb-1 leading-tight">{{ $cat['name'] }}</h3>
                    <p class="text-xs text-slate-400 line-clamp-2">{{ $cat['description'] ?: 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Modal Form (Tambah/Edit) --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl relative z-10 overflow-hidden fade-in">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-900 tracking-tight" x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="editMode ? '/manager/kategori/' + currentCategory.id : '/manager/kategori'" method="POST" enctype="multipart/form-data" class="px-8 py-8 space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
 
                <div>
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" x-model="currentCategory.name" class="form-input text-sm" required placeholder="Contoh: Banner & Spanduk">
                </div>
 
                <div>
                    <label class="form-label">Deskripsi Layanan</label>
                    <textarea name="description" x-model="currentCategory.description" class="form-input text-sm h-24" placeholder="Muncul di halaman depan..."></textarea>
                </div>

                <div>
                    <label class="form-label">Foto Kategori (Muncul di Layanan Kami)</label>
                    <input type="file" name="image" class="form-input text-xs" accept="image/*">
                    <p class="text-[10px] text-slate-400 mt-1">* Kosongkan jika tidak ingin mengubah foto</p>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="modalOpen = false" class="btn-secondary !text-xs">Batal</button>
                    <button type="submit" class="btn-primary !text-xs !px-8" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Kategori'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
