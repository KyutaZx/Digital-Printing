@extends('layouts.manager')

@section('title', 'Semua Pesanan')
@section('page_title', 'Kelola & Monitoring Pesanan')

@section('content')
<div class="space-y-6 fade-in" x-data="{ 
    selectedOrder: null,
    loading: false,
    openDetail(orderId) {
        this.loading = true;
        this.selectedOrder = null;
        fetch('/manager/pesanan/' + orderId)
            .then(res => res.json())
            .then(data => {
                this.selectedOrder = data;
                this.loading = false;
            })
            .catch(err => {
                alert('Gagal mengambil rincian pesanan');
                this.loading = false;
            });
    }
}">

    {{-- Filter & Search Header --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col gap-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Daftar Semua Pesanan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pantau status transaksi pelanggan di seluruh sistem</p>
            </div>
        </div>

        {{-- Filter Status --}}
        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide border-t border-slate-50 pt-4">
            @php
                $statusFilters = [
                    '' => 'Semua',
                    'waiting_payment' => 'Belum Bayar',
                    'payment_verification' => 'Verifikasi',
                    'paid' => 'Lunas',
                    'printing' => 'Diproses',
                    'ready' => 'Siap Ambil',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan'
                ];
            @endphp
            @foreach($statusFilters as $key => $label)
            <a href="?status={{ $key }}" 
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 {{ ($status === $key) ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Table List --}}
    <div class="card border-none shadow-md overflow-hidden bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                        <th class="px-6 py-4 text-left">Kode Pesanan</th>
                        <th class="px-6 py-4 text-left">Pelanggan</th>
                        <th class="px-6 py-4 text-left">Tanggal Transaksi</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Total Tagihan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $order['customer_name'] ?? 'Guest' }}</p>
                            <p class="text-[9px] text-slate-400 font-mono">{{ $order['customer_formatted_id'] ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $s = $order['status'] ?? '';
                                $badge = match($s) {
                                    'waiting_payment' => 'badge-gray',
                                    'payment_verification' => 'badge-yellow',
                                    'paid' => 'badge-blue',
                                    'printing' => 'badge-purple',
                                    'ready' => 'badge-green',
                                    'completed' => 'badge-green',
                                    'cancelled' => 'badge-red',
                                    default => 'badge-gray'
                                };
                            @endphp
                            <span class="{{ $badge }} !text-[9px] font-black uppercase tracking-tighter">{{ $s }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900">
                            Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="openDetail({{ $order['id'] }})" 
                                    class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 hover:underline">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-16 text-center text-slate-400 italic">Tidak ada pesanan ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Simple Pagination --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-400 font-semibold">Halaman {{ $page }}</span>
            <div class="flex gap-2">
                @if($page > 1)
                <a href="?status={{ $status }}&page={{ $page - 1 }}" 
                   class="btn-secondary !py-1.5 !px-3 !text-xs">Sebelumnya</a>
                @endif
                @if(count($orders) >= 20)
                <a href="?status={{ $status }}&page={{ $page + 1 }}" 
                   class="btn-primary !py-1.5 !px-3 !text-xs !bg-primary-600 hover:!bg-primary-700">Selanjutnya</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Detail Modal Drawer --}}
    <div x-show="selectedOrder" x-cloak class="fixed inset-0 z-[100] flex justify-end">
        {{-- Backdrop --}}
        <div x-show="selectedOrder" x-transition.opacity @click="selectedOrder = null" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Panel Drawer --}}
        <div x-show="selectedOrder" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="relative w-full max-w-lg bg-white h-full shadow-2xl flex flex-col justify-between z-10">
            
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-900">Rincian Transaksi</h3>
                    <p class="text-xs text-slate-400 font-mono mt-0.5" x-text="selectedOrder ? selectedOrder.order_code : ''"></p>
                </div>
                <button @click="selectedOrder = null" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body Scrollable --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <template x-if="loading">
                    <div class="flex flex-col items-center justify-center h-48 space-y-3">
                        <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Memuat Rincian...</p>
                    </div>
                </template>
                <template x-if="!loading && selectedOrder">
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Pelanggan</p>
                            <p class="text-sm font-black text-slate-900 mt-0.5" x-text="selectedOrder.customer_name || 'Guest'"></p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Pesanan</p>
                            <p class="text-sm font-black text-slate-900 mt-0.5 uppercase" x-text="selectedOrder.status"></p>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Item Pesanan</h4>
                            <div class="space-y-2">
                                <template x-for="item in selectedOrder.items" :key="item.id">
                                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                        <div>
                                            <p class="font-bold text-xs text-slate-800" x-text="item.product_name"></p>
                                            <p class="text-[10px] text-slate-400"><span x-text="item.variant_name"></span> x <span x-text="item.quantity"></span></p>
                                        </div>
                                        <p class="text-xs font-black text-slate-900">Rp <span x-text="(item.unit_price * item.quantity).toLocaleString('id-ID')"></span></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer Summary --}}
            <div class="p-6 border-t border-slate-100 bg-slate-50">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-bold text-slate-500">Total Tagihan</span>
                    <span class="text-xl font-black text-primary-600" x-text="selectedOrder ? 'Rp ' + selectedOrder.total_price.toLocaleString('id-ID') : 'Rp 0'"></span>
                </div>
                <button @click="selectedOrder = null" class="w-full btn-secondary !py-3 !text-xs font-bold uppercase tracking-widest">
                    Tutup Rincian
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
