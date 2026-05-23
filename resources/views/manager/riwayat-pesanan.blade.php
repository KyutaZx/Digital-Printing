@extends('layouts.manager')

@section('title', 'Riwayat Pesanan')
@section('page_title', 'Riwayat Pesanan')

@section('content')
@php
    $orderList = is_array($orders) ? $orders : [];
    $pageCount = count($orderList);
    $pageRevenue = collect($orderList)->sum(fn ($o) => (float) ($o['total_price'] ?? 0));
@endphp

<div class="space-y-6 fade-in pb-8"
     x-data="{
        selectedOrder: null,
        loading: false,
        searchQuery: '',
        noSearchResults: false,
        openDetail(orderId) {
            this.loading = true;
            this.selectedOrder = null;
            fetch('/manager/pesanan/' + orderId)
                .then(res => res.json())
                .then(data => { this.selectedOrder = data; this.loading = false; })
                .catch(() => { alert('Gagal mengambil rincian pesanan'); this.loading = false; });
        },
        checkVisible() {
            this.$nextTick(() => {
                const rows = this.$refs.tableBody?.querySelectorAll('[data-order-row]') ?? [];
                this.noSearchResults = this.searchQuery.trim() !== '' &&
                    [...rows].every(r => r.offsetParent === null);
            });
        }
     }"
     x-init="checkVisible()">

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Pesanan Selesai</h1>
            <p class="text-xs text-slate-500 mt-1">Arsip transaksi digital printing yang telah diselesaikan</p>
        </div>
        <a href="/manager/pesanan" class="inline-flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100 text-xs font-bold text-slate-600 hover:text-primary-600 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Semua Pesanan
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Selesai</p>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $pageCount }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Halaman {{ $page }}</p>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-emerald-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Omzet</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-2">Rp {{ number_format($pageRevenue, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Pada halaman ini</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl p-5 shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest relative z-10">Status</p>
            <h3 class="text-lg font-black mt-2 relative z-10">Semua Selesai</h3>
            <p class="text-[10px] text-emerald-100 mt-1 relative z-10">Arsip transaksi completed</p>
        </div>
    </div>

    {{-- Search Toolbar --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5">
        <div class="relative max-w-md">
            <input type="text"
                   x-model="searchQuery"
                   @input.debounce.150ms="checkVisible()"
                   placeholder="Cari kode pesanan atau nama pelanggan..."
                   class="form-input !text-sm !py-2.5 !pl-10 w-full bg-slate-50 border-slate-100 focus:bg-white">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <button x-show="searchQuery" @click="searchQuery = ''; checkVisible()"
                    class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-black text-slate-900 text-sm">Arsip Transaksi</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Pesanan dengan status selesai / completed</p>
            </div>
            <span class="shrink-0 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase">{{ $pageCount }} arsip</span>
        </div>

        @if($pageCount > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 text-left">Pesanan</th>
                        <th class="px-6 py-4 text-left">Pelanggan</th>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" x-ref="tableBody">
                    @foreach($orderList as $order)
                    @php
                        $searchCode = strtolower($order['order_code'] ?? '');
                        $searchName = strtolower($order['customer_name'] ?? '');
                        $initial = strtoupper(substr($order['customer_name'] ?? 'G', 0, 1));
                    @endphp
                    <tr data-order-row
                        class="hover:bg-slate-50/80 transition-colors"
                        x-show='searchQuery === "" || @js($searchCode).includes(searchQuery.toLowerCase()) || @js($searchName).includes(searchQuery.toLowerCase())'>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white text-[10px] font-black shrink-0">
                                    {{ $initial }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-xs truncate">{{ $order['customer_name'] ?? 'Guest' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $order['customer_formatted_id'] ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                            {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y') : '-' }}
                            <span class="block text-[10px] text-slate-400">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('H:i') : '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase ring-1 ring-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900">
                            Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="openDetail({{ $order['id'] }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 text-[10px] font-black uppercase transition-colors ring-1 ring-primary-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div x-show="noSearchResults" x-cloak class="px-6 py-16 flex flex-col items-center justify-center text-center border-t border-slate-50">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Pesanan Tidak Ditemukan</h4>
            <p class="text-xs text-slate-400 mt-1">Tidak ada hasil untuk "<span x-text="searchQuery" class="font-bold text-slate-600"></span>"</p>
            <button @click="searchQuery = ''; checkVisible()" class="mt-4 text-xs font-bold text-primary-600 hover:underline">Hapus pencarian</button>
        </div>

        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs font-bold text-slate-500">Halaman {{ $page }} · {{ $pageCount }} pesanan</span>
            <div class="flex gap-2">
                @if($page > 1)
                <a href="?page={{ $page - 1 }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </a>
                @endif
                @if($pageCount >= 20)
                <a href="?page={{ $page + 1 }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-600 text-white text-xs font-bold hover:bg-primary-700 shadow-sm transition-colors">
                    Selanjutnya
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>
        </div>

        @else
        <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Belum Ada Riwayat</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-xs">Pesanan selesai akan tercatat di sini setelah status completed.</p>
        </div>
        @endif
    </div>

    {{-- Detail Drawer --}}
    <div x-show="selectedOrder" x-cloak class="fixed inset-0 z-[100] flex justify-end">
        <div x-show="selectedOrder" x-transition.opacity @click="selectedOrder = null"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="selectedOrder"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="relative w-full max-w-lg bg-white h-full shadow-2xl flex flex-col z-10">

            <div class="px-6 py-5 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest">Arsip Transaksi</p>
                        <h3 class="font-black text-lg font-mono mt-0.5" x-text="selectedOrder ? selectedOrder.order_code : ''"></h3>
                    </div>
                    <button @click="selectedOrder = null" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <template x-if="loading">
                    <div class="flex flex-col items-center justify-center h-48 space-y-3">
                        <svg class="animate-spin h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-xs text-slate-400 font-bold uppercase">Memuat rincian...</p>
                    </div>
                </template>
                <template x-if="!loading && selectedOrder">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan</p>
                                <p class="text-sm font-black text-slate-900 mt-1" x-text="selectedOrder.customer_name || 'Guest'"></p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="selectedOrder.customer_formatted_id || '-'"></p>
                            </div>
                            <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Status</p>
                                <p class="text-sm font-black text-emerald-700 mt-1">Selesai</p>
                            </div>
                        </div>
                        <div x-show="selectedOrder.notes" class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Catatan</p>
                            <p class="text-xs text-amber-800" x-text="selectedOrder.notes"></p>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider mb-3">Item Pesanan</h4>
                            <div class="rounded-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                                <template x-for="item in selectedOrder.items" :key="item.id">
                                    <div class="flex justify-between items-center px-4 py-3 bg-white">
                                        <div>
                                            <p class="font-bold text-xs text-slate-800" x-text="item.product_name"></p>
                                            <p class="text-[10px] text-slate-400"><span x-text="item.variant_name"></span> × <span x-text="item.quantity"></span></p>
                                        </div>
                                        <p class="text-xs font-black">Rp <span x-text="(item.unit_price * item.quantity).toLocaleString('id-ID')"></span></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 shrink-0">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-bold text-slate-500">Total Tagihan</span>
                    <span class="text-2xl font-black text-emerald-600" x-text="selectedOrder ? 'Rp ' + Number(selectedOrder.total_price).toLocaleString('id-ID') : 'Rp 0'"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="window.open('{{ config('app.golang_api_url', 'http://localhost:8080') }}/api/orders/' + selectedOrder.id + '/invoice/pdf', '_blank')"
                            class="flex-1 btn-secondary !py-2.5 !text-xs font-bold flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Invoice PDF
                    </button>
                    <button @click="selectedOrder = null" class="flex-1 btn-secondary !py-2.5 !text-xs font-bold">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
