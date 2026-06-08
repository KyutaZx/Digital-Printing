@extends('layouts.manager')

@section('title', 'Laporan & Audit Logs')
@section('page_title', 'Laporan & Audit Logs')
@section('page_description', 'Monitoring performa toko digital printing secara real-time')

@php
    $revenueList = is_array($revenue) ? $revenue : [];
    $topProductsList = is_array($topProducts) ? $topProducts : [];
    $productionList = is_array($productionLogs) ? $productionLogs : [];
    $auditList = is_array($auditLogs) ? $auditLogs : [];
    $paymentList = is_array($loginLogs) ? $loginLogs : [];

    $totalRevenue = collect($revenueList)->sum(fn ($r) => (float) ($r['total_revenue'] ?? 0));
    $totalOrders = collect($revenueList)->sum(fn ($r) => (int) ($r['total_orders'] ?? 0));
    $activeDays = count($revenueList);
    $avgDaily = $activeDays > 0 ? $totalRevenue / $activeDays : 0;

    $topProductSold = collect($topProductsList)->sum(fn ($p) => (int) ($p['total_sold'] ?? 0));
    $topProductRevenue = collect($topProductsList)->sum(fn ($p) => (float) ($p['total_revenue'] ?? 0));

    $periodLabel = \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') . ' — ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y');
@endphp

@section('page_actions')
<div class="flex flex-wrap items-center gap-2 shrink-0">
    <div class="hidden md:flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100">
        <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span class="text-xs font-bold text-slate-600">{{ $periodLabel }}</span>
    </div>
    <a href="/manager/laporan/export?start_date={{ $startDate }}&end_date={{ $endDate }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors"
       title="Unduh laporan keuangan (3 sheet Excel)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export Laporan Keuangan
    </a>
</div>
@endsection

@section('content')

<div class="space-y-6 fade-in pb-8" x-data="{ tab: 'revenue' }">

    @include('manager.partials.flash')


    {{-- Date Filter (revenue tab) --}}
    <div x-show="tab === 'revenue'" x-cloak class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100">
        <form method="GET" action="/manager/laporan" class="flex flex-col sm:flex-row sm:items-end gap-3">
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-input !text-sm !py-2.5">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-input !text-sm !py-2.5">
                </div>
            </div>
            <button type="submit" class="btn-primary !text-sm !py-2.5 !px-5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Terapkan Filter
            </button>
        </form>
    </div>

    {{-- Tab Navigation --}}
    <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 flex gap-1 overflow-x-auto scrollbar-hide">
        <button @click="tab = 'revenue'"
                :class="tab === 'revenue' ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pendapatan
        </button>
        <button @click="tab = 'products'"
                :class="tab === 'products' ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Produk Terlaris
        </button>
        <button @click="tab = 'production'"
                :class="tab === 'production' ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Produksi Staf
        </button>
        <button @click="tab = 'audit'"
                :class="tab === 'audit' ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Verifikasi Desain
        </button>
        <button @click="tab = 'login'"
                :class="tab === 'login' ? 'bg-primary-600 text-white shadow-sm shadow-primary-100' : 'text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            Verifikasi Pembayaran
        </button>
    </div>

    {{-- TAB: REVENUE --}}
    <div x-show="tab === 'revenue'" x-cloak class="space-y-4">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 shadow-xl shadow-slate-900/20 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-5 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pendapatan</p>
                    <h3 class="text-3xl font-black text-white mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">{{ $periodLabel }}</p>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pesanan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-2">{{ number_format($totalOrders) }}</h3>
                <p class="text-[10px] text-slate-400 mt-1">Dalam periode filter</p>
            </div>
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rata-rata / Hari</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-2">Rp {{ number_format($avgDaily, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-slate-400 mt-1">{{ $activeDays }} hari aktif</p>
            </div>
        </div>

        {{-- Revenue Table --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-black text-slate-900 text-sm">Laporan Pendapatan Harian</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Rincian omzet per tanggal transaksi</p>
                </div>
                <span class="shrink-0 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-[10px] font-black uppercase tracking-wider">{{ $activeDays }} entri</span>
            </div>

            @if(count($revenueList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left">Tanggal</th>
                            <th class="px-6 py-4 text-center">Jumlah Pesanan</th>
                            <th class="px-6 py-4 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($revenueList as $rev)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($rev['date'])->translatedFormat('d M Y') }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $rev['date'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-black">{{ $rev['total_orders'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-emerald-600">
                                Rp {{ number_format($rev['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td class="px-6 py-4 font-black text-xs uppercase tracking-wider">Total Keseluruhan</td>
                            <td class="px-6 py-4 text-center font-black">{{ number_format($totalOrders) }}</td>
                            <td class="px-6 py-4 text-right font-black text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h4 class="font-black text-slate-800 text-sm">Belum Ada Data Pendapatan</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-xs">Tidak ada transaksi pada periode {{ $periodLabel }}. Coba ubah rentang tanggal filter.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TAB: PRODUCTS --}}
    <div x-show="tab === 'products'" x-cloak class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Terjual</p>
                <h3 class="text-2xl font-black text-slate-900 mt-2">{{ number_format($topProductSold) }} <span class="text-sm font-bold text-slate-400">pcs</span></h3>
            </div>
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akumulasi Omzet Produk</p>
                <h3 class="text-2xl font-black text-primary-600 mt-2">Rp {{ number_format($topProductRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-900 text-sm">10 Produk Terlaris</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Berdasarkan volume penjualan & pendapatan</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-black uppercase">Top {{ count($topProductsList) }}</span>
            </div>

            @if(count($topProductsList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left w-12">#</th>
                            <th class="px-6 py-4 text-left">Nama Produk</th>
                            <th class="px-6 py-4 text-center">Terjual</th>
                            <th class="px-6 py-4 text-right">Total Akumulasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($topProductsList as $i => $prod)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                @if($i < 3)
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black {{ $i === 0 ? 'bg-amber-100 text-amber-700' : ($i === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-700') }}">{{ $i + 1 }}</span>
                                @else
                                <span class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-400">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $prod['product_name'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-primary-50 text-primary-700 text-xs font-black">{{ $prod['total_sold'] }} pcs</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-primary-600">
                                Rp {{ number_format($prod['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h4 class="font-black text-slate-800 text-sm">Belum Ada Produk Terlaris</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-xs">Data akan muncul setelah ada transaksi produk.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TAB: PRODUCTION --}}
    <div x-show="tab === 'production'" x-cloak class="space-y-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Riwayat Produksi</p>
                <h3 class="text-xl font-black text-slate-900 mt-1">{{ count($productionList) }} <span class="text-sm font-bold text-slate-400">log aktivitas</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-black text-slate-900 text-sm">Riwayat Produksi Cetak</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Aktivitas staf dari mulai cetak hingga selesai</p>
            </div>

            @if(count($productionList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left">Kode Pesanan</th>
                            <th class="px-6 py-4 text-left">Staf</th>
                            <th class="px-6 py-4 text-left">Mulai Cetak</th>
                            <th class="px-6 py-4 text-left">Selesai Cetak</th>
                            <th class="px-6 py-4 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($productionList as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-primary-600 text-xs">{{ $log['order_code'] }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[10px] font-black shrink-0">
                                        {{ strtoupper(substr($log['staff_name'] ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-800 text-xs">{{ $log['staff_name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ isset($log['start_time']) ? \Carbon\Carbon::parse($log['start_time'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ isset($log['end_time']) ? \Carbon\Carbon::parse($log['end_time'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 max-w-[200px] truncate" title="{{ $log['notes'] ?? '' }}">{{ $log['notes'] ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-bold text-slate-600">Belum ada log produksi</p>
                <p class="text-xs text-slate-400 mt-1">Aktivitas cetak staf akan tercatat di sini.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TAB: DESIGN VERIFICATION --}}
    <div x-show="tab === 'audit'" x-cloak class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @php
                $approvedDesign = collect($auditList)->where('role', 'approved')->count();
                $revisionDesign = count($auditList) - $approvedDesign;
            @endphp
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-emerald-500">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Disetujui</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-2">{{ $approvedDesign }}</h3>
            </div>
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-red-400">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Minta Revisi</p>
                <h3 class="text-2xl font-black text-red-600 mt-2">{{ $revisionDesign }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-black text-slate-900 text-sm">Riwayat Verifikasi Desain</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ count($auditList) }} catatan verifikasi terakhir</p>
            </div>

            @if(count($auditList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left">Waktu</th>
                            <th class="px-6 py-4 text-left">Verifikator</th>
                            <th class="px-6 py-4 text-left">Kode Pesanan</th>
                            <th class="px-6 py-4 text-center">Versi</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($auditList as $audit)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ isset($audit['created_at']) ? \Carbon\Carbon::parse($audit['created_at'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-xs">{{ $audit['user_name'] ?? 'System' }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-primary-600">{{ $audit['action'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-black">v{{ $audit['entity_id'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($audit['role'] ?? '') === 'approved')
                                    <span class="badge badge-green !text-[9px] font-black uppercase">Disetujui</span>
                                @else
                                    <span class="badge badge-red !text-[9px] font-black uppercase">Revisi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-[180px] truncate" title="{{ $audit['entity_type'] ?? '' }}">{{ $audit['entity_type'] ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-bold text-slate-600">Belum ada verifikasi desain</p>
                <p class="text-xs text-slate-400 mt-1">Log akan muncul setelah staf memverifikasi desain pelanggan.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TAB: PAYMENT VERIFICATION --}}
    <div x-show="tab === 'login'" x-cloak class="space-y-4">
        @php
            $approvedPay = collect($paymentList)->where('activity_type', 'approve_payment')->count();
            $rejectedPay = count($paymentList) - $approvedPay;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-emerald-500">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pembayaran Diterima</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-2">{{ $approvedPay }}</h3>
            </div>
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-red-400">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pembayaran Ditolak</p>
                <h3 class="text-2xl font-black text-red-600 mt-2">{{ $rejectedPay }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-black text-slate-900 text-sm">Riwayat Verifikasi Pembayaran</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ count($paymentList) }} transaksi diverifikasi staf</p>
            </div>

            @if(count($paymentList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left">Waktu</th>
                            <th class="px-6 py-4 text-left">Verifikator</th>
                            <th class="px-6 py-4 text-left">Kode Pesanan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($paymentList as $login)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ isset($login['created_at']) ? \Carbon\Carbon::parse($login['created_at'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-xs">{{ $login['user_name'] }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-primary-600">{{ $login['ip_address'] }}</td>
                            <td class="px-6 py-4 text-center">
                                @if(($login['activity_type'] ?? '') === 'approve_payment')
                                    <span class="badge badge-green !text-[9px] font-black uppercase">Diterima</span>
                                @else
                                    <span class="badge badge-red !text-[9px] font-black uppercase">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-black text-primary-600">
                                Rp {{ number_format(floatval($login['user_agent'] ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-bold text-slate-600">Belum ada verifikasi pembayaran</p>
                <p class="text-xs text-slate-400 mt-1">Riwayat akan tercatat setelah staf memverifikasi bukti transfer.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
