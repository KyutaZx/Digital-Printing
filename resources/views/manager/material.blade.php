@extends('layouts.manager')

@section('title', 'Material Bahan')
@section('page_title', 'Material Bahan')
@section('page_description', 'Pantau dan update ketersediaan bahan cetak')

@section('page_actions')
<button @click="addModal = true" class="btn-primary !text-sm !py-2.5 !px-5 shrink-0">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Material Baru
</button>
@endsection

@section('content')
<div x-data="{ 
    restockModal: false, 
    addModal: false,
    editModal: false,
    deleteModal: false,
    selectedMaterial: { id: 0, name: '', stock: 0, unit: '' },
    openRestock: function(material) {
        this.selectedMaterial = material;
        this.restockModal = true;
    },
    openEdit: function(material) {
        this.selectedMaterial = { ...material };
        this.editModal = true;
    },
    openDelete: function(material) {
        this.selectedMaterial = material;
        this.deleteModal = true;
    }
}" class="space-y-6 fade-in pb-8">

    @php
        $materialList = is_array($materials) ? $materials : [];
        $totalMaterials = count($materialList);
        $lowStock = collect($materialList)->filter(fn ($m) => ($m['stock'] ?? 0) < 15)->count();
        $criticalStock = collect($materialList)->filter(fn ($m) => ($m['stock'] ?? 0) < 5)->count();
    @endphp

    @include('manager.partials.flash')


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Material</p>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $totalMaterials }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-amber-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Menipis</p>
            <h3 class="text-2xl font-black text-amber-600 mt-2">{{ $lowStock }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-red-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Kritis</p>
            <h3 class="text-2xl font-black text-red-600 mt-2">{{ $criticalStock }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-black text-slate-900 text-sm">Daftar Bahan Baku</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Update stok agar produksi tidak terhambat</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 text-left">Nama Material</th>
                        <th class="px-6 py-4 text-left">Satuan</th>
                        <th class="px-6 py-4 text-left">Stok Saat Ini</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($materialList as $material)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $material['name'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">ID #{{ $material['id'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $material['unit'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-black {{ ($material['stock'] ?? 0) < 10 ? 'text-red-600' : 'text-slate-900' }}">
                                {{ $material['stock'] ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if(($material['stock'] ?? 0) < 5)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-700 text-[10px] font-black uppercase ring-1 ring-red-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Kritis
                                </span>
                            @elseif(($material['stock'] ?? 0) < 15)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-black uppercase ring-1 ring-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase ring-1 ring-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openRestock({{ json_encode($material) }})" class="text-[11px] font-black text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-xl transition-all">
                                    Update Stok
                                </button>
                                <button @click="openEdit({{ json_encode($material) }})" class="text-[11px] font-black text-slate-600 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition-all flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button @click="openDelete({{ json_encode($material) }})" class="text-[11px] font-black text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-xl transition-all flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data material</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Restock Modal --}}
    <div x-show="restockModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="restockModal = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl relative z-10 overflow-hidden fade-in">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 tracking-tight">Update Stok</h3>
                <button @click="restockModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="'/manager/material/' + selectedMaterial.id + '/restock'" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Material</p>
                    <p class="font-bold text-slate-900" x-text="selectedMaterial.name"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stok Saat Ini</p>
                    <p class="text-sm font-bold text-slate-600"><span x-text="selectedMaterial.stock"></span> <span x-text="selectedMaterial.unit"></span></p>
                </div>
                <div>
                    <label class="form-label !text-xs">Jumlah Tambah Stok</label>
                    <div class="relative">
                        <input type="number" name="quantity" class="form-input !text-base font-black text-primary-600 pr-12" required placeholder="0">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400" x-text="selectedMaterial.unit"></span>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full btn-primary !py-3 !text-xs uppercase tracking-widest">Update Ketersediaan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Material Modal --}}
    <div x-show="addModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModal = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl relative z-10 overflow-hidden fade-in">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 tracking-tight">Material Bahan Baru</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="/manager/material" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="form-label !text-xs">Nama Material</label>
                    <input type="text" name="name" class="form-input text-sm" required placeholder="Contoh: Kertas Matte A4">
                </div>
                <div>
                    <label class="form-label !text-xs">Satuan</label>
                    <input type="text" name="unit" class="form-input text-sm" required placeholder="Contoh: Rim, Meter, Pcs">
                </div>
                <div>
                    <label class="form-label !text-xs">Stok Awal</label>
                    <input type="number" name="stock" class="form-input text-sm" required placeholder="0" min="0" step="any">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full btn-primary !py-3 !text-xs uppercase tracking-widest">Simpan Material</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Material Modal --}}
    <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl relative z-10 overflow-hidden fade-in">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 tracking-tight">Edit Bahan Baku</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="'/manager/material/' + selectedMaterial.id" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label !text-xs">Nama Material</label>
                    <input type="text" name="name" x-model="selectedMaterial.name" class="form-input text-sm" required placeholder="Contoh: Kertas Matte A4">
                </div>
                <div>
                    <label class="form-label !text-xs">Satuan</label>
                    <input type="text" name="unit" x-model="selectedMaterial.unit" class="form-input text-sm" required placeholder="Contoh: Rim, Meter, Pcs">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full btn-primary !py-3 !text-xs uppercase tracking-widest">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="deleteModal = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl relative z-10 overflow-hidden fade-in">
            <div class="p-6 text-center space-y-4">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto text-red-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-lg tracking-tight">Hapus Bahan Baku?</h3>
                    <p class="text-xs text-slate-500 mt-1">Anda akan menghapus bahan baku <strong class="text-slate-800" x-text="selectedMaterial.name"></strong> secara permanen.</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button @click="deleteModal = false" type="button" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 rounded-xl transition-all uppercase tracking-widest">
                        Batal
                    </button>
                    <form :action="'/manager/material/' + selectedMaterial.id" method="POST" class="w-1/2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs py-3 rounded-xl transition-all uppercase tracking-widest shadow-lg shadow-red-500/20">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
