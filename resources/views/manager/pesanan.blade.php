@extends('layouts.manager')

@section('title', 'Semua Pesanan')
@section('page_title', 'Kelola & Monitoring Pesanan')

@section('content')
@php
    $orderList = is_array($orders) ? $orders : [];
    $pageCount = count($orderList);
    $pageRevenue = collect($orderList)->sum(fn ($o) => (float) ($o['total_price'] ?? 0));

    $statusLabels = [
        'waiting_payment' => 'Belum Bayar',
        'payment_verification' => 'Verifikasi',
        'paid' => 'Verifikasi Desain',
        'design_review' => 'Verifikasi Desain',
        'printing' => 'Diproses',
        'ready' => 'Siap Ambil',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $statusStyles = [
        'waiting_payment' => 'bg-slate-100 text-slate-600 ring-slate-200',
        'payment_verification' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'paid' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'design_review' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'printing' => 'bg-purple-50 text-purple-700 ring-purple-100',
        'ready' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'completed' => 'bg-green-50 text-green-700 ring-green-100',
        'cancelled' => 'bg-red-50 text-red-700 ring-red-100',
    ];

    $statusFilters = [
        '' => ['label' => 'Semua', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
        'waiting_payment' => ['label' => 'Belum Bayar', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'payment_verification' => ['label' => 'Verifikasi', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
        'paid' => ['label' => 'Lunas', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'printing' => ['label' => 'Diproses', 'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z'],
        'ready' => ['label' => 'Siap Ambil', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
        'completed' => ['label' => 'Selesai', 'icon' => 'M5 13l4 4L19 7'],
        'cancelled' => ['label' => 'Dibatalkan', 'icon' => 'M6 18L18 6M6 6l12 12'],
    ];

    $activeFilterLabel = $statusLabels[$status] ?? 'Semua Status';
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

    @include('manager.partials.flash')

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Semua Pesanan</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau status transaksi pelanggan di seluruh sistem</p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100 shrink-0">
            <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
            <span class="text-xs font-bold text-slate-600">Filter: <span class="text-primary-600">{{ $activeFilterLabel }}</span></span>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pesanan (Halaman Ini)</p>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $pageCount }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Halaman {{ $page }}</p>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-emerald-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Omzet Halaman</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-2">Rp {{ number_format($pageRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="lg:col-span-1 bg-gradient-to-br from-primary-600 to-primary-500 rounded-3xl p-5 shadow-lg shadow-primary-500/20 text-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <p class="text-[10px] font-bold text-primary-100 uppercase tracking-widest relative z-10">Status Aktif</p>
            <h3 class="text-lg font-black mt-2 relative z-10">{{ $activeFilterLabel }}</h3>
            <a href="/manager/verifikasi" class="inline-flex items-center gap-1 mt-2 text-[10px] font-bold text-primary-100 hover:text-white relative z-10">
                Ke Verifikasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Toolbar: Search + Filters --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="relative flex-1 max-w-md">
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
        <div class="px-5 pb-5">
            <div class="bg-slate-50 p-1.5 rounded-2xl flex gap-1 overflow-x-auto scrollbar-hide">
                @foreach($statusFilters as $key => $filter)
                <a href="?status={{ $key }}"
                   class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ ($status === $key) ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white' }}">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $filter['icon'] }}"/></svg>
                    {{ $filter['label'] }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-black text-slate-900 text-sm">Daftar Transaksi</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Klik detail untuk melihat item & ubah status</p>
            </div>
            <span class="shrink-0 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-[10px] font-black uppercase">{{ $pageCount }} pesanan</span>
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
                        $s = $order['status'] ?? '';
                        $sLabel = $statusLabels[$s] ?? ucfirst(str_replace('_', ' ', $s));
                        $sStyle = $statusStyles[$s] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
                        $searchCode = strtolower($order['order_code'] ?? '');
                        $searchName = strtolower($order['customer_name'] ?? '');
                        $initial = strtoupper(substr($order['customer_name'] ?? 'G', 0, 1));
                    @endphp
                    <tr data-order-row
                        class="hover:bg-slate-50/80 transition-colors"
                        x-show='searchQuery === "" || @js($searchCode).includes(searchQuery.toLowerCase()) || @js($searchName).includes(searchQuery.toLowerCase())'>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <span class="font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white text-[10px] font-black shrink-0">
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
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight ring-1 {{ $sStyle }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                                {{ $sLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
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

        {{-- No search results --}}
        <div x-show="noSearchResults" x-cloak class="px-6 py-16 flex flex-col items-center justify-center text-center border-t border-slate-50">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Pesanan Tidak Ditemukan</h4>
            <p class="text-xs text-slate-400 mt-1">Tidak ada hasil untuk "<span x-text="searchQuery" class="font-bold text-slate-600"></span>"</p>
            <button @click="searchQuery = ''; checkVisible()" class="mt-4 text-xs font-bold text-primary-600 hover:underline">Hapus pencarian</button>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500">Halaman {{ $page }}</span>
                <span class="text-[10px] text-slate-400">· {{ $pageCount }} pesanan ditampilkan</span>
            </div>
            <div class="flex gap-2">
                @if($page > 1)
                <a href="?status={{ $status }}&page={{ $page - 1 }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </a>
                @endif
                @if($pageCount >= 20)
                <a href="?status={{ $status }}&page={{ $page + 1 }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-600 text-white text-xs font-bold hover:bg-primary-700 shadow-sm transition-colors">
                    Selanjutnya
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>
        </div>

        @else
        <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Tidak Ada Pesanan</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-xs">Belum ada pesanan dengan status <strong>{{ $activeFilterLabel }}</strong>.</p>
            @if($status)
            <a href="?status=" class="mt-4 text-xs font-bold text-primary-600 hover:underline">Lihat semua pesanan</a>
            @endif
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

            {{-- Drawer Header --}}
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rincian Transaksi</p>
                        <h3 class="font-black text-lg font-mono mt-0.5" x-text="selectedOrder ? selectedOrder.order_code : ''"></h3>
                    </div>
                    <button @click="selectedOrder = null" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <template x-if="loading">
                    <div class="flex flex-col items-center justify-center h-48 space-y-3">
                        <svg class="animate-spin h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Memuat rincian...</p>
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
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                                <p class="text-sm font-black text-slate-900 mt-1 capitalize" x-text="(selectedOrder.status || '').replace(/_/g, ' ')"></p>
                                <p class="text-[10px] text-slate-400 mt-0.5" x-show="selectedOrder.estimated_finish_date">
                                    Est: <span x-text="new Date(selectedOrder.estimated_finish_date).toLocaleDateString('id-ID')"></span>
                                </p>
                            </div>
                        </div>

                        <div x-show="selectedOrder.notes" class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Catatan Pelanggan</p>
                            <p class="text-xs text-amber-800" x-text="selectedOrder.notes"></p>
                        </div>

                        <div>
                            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider mb-3">Item Pesanan</h4>
                            <div class="space-y-2 rounded-2xl border border-slate-100 overflow-hidden">
                                <template x-for="item in selectedOrder.items" :key="item.id">
                                    <div class="flex justify-between items-center px-4 py-3 bg-white hover:bg-slate-50 border-b border-slate-50 last:border-0">
                                        <div>
                                            <p class="font-bold text-xs text-slate-800" x-text="item.product_name"></p>
                                            <p class="text-[10px] text-slate-400"><span x-text="item.variant_name"></span> × <span x-text="item.quantity"></span></p>
                                        </div>
                                        <p class="text-xs font-black text-slate-900">Rp <span x-text="(item.unit_price * item.quantity).toLocaleString('id-ID')"></span></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col gap-3">
                            <template x-if="selectedOrder.payment_proof">
                                <a :href="'/api-proxy/' + selectedOrder.payment_proof.replace(/^\/+/, '')" target="_blank"
                                   class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-xl hover:bg-slate-100 border border-slate-100 transition-colors">
                                    <span class="text-xs font-bold text-slate-600">Lihat Bukti Pembayaran</span>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </template>

                            <template x-if="['design_review', 'paid', 'printing', 'ready'].includes(selectedOrder.status)">
                                <form method="POST" :action="'/manager/pesanan/' + selectedOrder.id + '/status'" class="flex gap-2">
                                    @csrf
                                    <select name="status" class="form-input !text-xs !py-2.5 flex-1" required>
                                        <option value="" disabled selected>Ubah status...</option>
                                        <template x-if="['paid', 'design_review'].includes(selectedOrder.status)"><option value="printing">Proses Cetak</option></template>
                                        <template x-if="selectedOrder.status === 'printing'"><option value="ready">Selesai Cetak (Siap Ambil)</option></template>
                                        <template x-if="selectedOrder.status === 'ready'"><option value="completed">Pesanan Selesai</option></template>
                                    </select>
                                    <button type="submit" class="btn-primary !text-xs !py-2.5 !px-4 shrink-0">Update</button>
                                </form>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Drawer Footer --}}
            <div class="p-6 border-t border-slate-100 bg-slate-50 shrink-0">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-bold text-slate-500">Total Tagihan</span>
                    <span class="text-2xl font-black text-primary-600" x-text="selectedOrder ? 'Rp ' + Number(selectedOrder.total_price).toLocaleString('id-ID') : 'Rp 0'"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="window.open('{{ url('/api-proxy/orders') }}/' + selectedOrder.id + '/invoice/pdf', '_blank')"
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
