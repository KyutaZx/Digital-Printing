@extends('layouts.manager')

@section('title', 'Kelola Material')
@section('page_title', 'Manajemen Stok Bahan Baku')

@section('content')
<div x-data="{ 
    restockModal: false, 
    addModal: false,
    selectedMaterial: { id: 0, name: '', stock: 0, unit: '' },
    openRestock(material) {
        this.selectedMaterial = material;
        this.restockModal = true;
    }
}" class="space-y-6">

    {{-- Top Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">Inventaris Bahan</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pantau dan update ketersediaan bahan cetak</p>
        </div>
        <button @click="addModal = true" class="btn-primary !py-2 !px-4 !text-xs !bg-slate-800 hover:!bg-slate-900 shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Material Baru
        </button>
    </div>

    {{-- Material Table --}}
    <div class="card border-none shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                        <th class="px-6 py-4 text-left">Nama Material</th>
                        <th class="px-6 py-4 text-left">Satuan</th>
                        <th class="px-6 py-4 text-left">Stok Saat Ini</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse($materials as $material)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $material['name'] }}</p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-0.5">ID: {{ $material['id'] }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $material['unit'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-black {{ ($material['stock'] ?? 0) < 10 ? 'text-red-600' : 'text-slate-900' }}">
                                {{ $material['stock'] ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if(($material['stock'] ?? 0) < 5)
                                <span class="badge badge-red !text-[9px] font-black uppercase">Kritis</span>
                            @elseif(($material['stock'] ?? 0) < 15)
                                <span class="badge badge-yellow !text-[9px] font-black uppercase">Menipis</span>
                            @else
                                <span class="badge badge-green !text-[9px] font-black uppercase">Aman</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="openRestock({{ json_encode($material) }})" class="text-xs font-black text-primary-600 hover:text-primary-700 bg-primary-50 px-4 py-2 rounded-xl transition-all">
                                Update Stok
                            </button>
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

</div>
@endsection
