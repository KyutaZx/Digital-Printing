@extends('layouts.manager')

@section('title', 'Laporan & Logs Sistem')
@section('page_title', 'Business Intelligence & System Logs')

@section('content')
<div class="space-y-8 fade-in" x-data="{ tab: 'revenue' }">

    {{-- Filter Rentang Tanggal (Hanya tampil di tab revenue) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Analisis Laporan & Riwayat</h2>
            <p class="text-xs text-slate-400 mt-0.5">Monitoring performa toko digital printing secara real-time</p>
        </div>
        <div x-show="tab === 'revenue'" class="flex items-center gap-2">
            <form method="GET" action="/manager/laporan" class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-input !text-xs !py-1.5 !px-3 border-slate-200 rounded-xl">
                <span class="text-slate-400 text-xs font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-input !text-xs !py-1.5 !px-3 border-slate-200 rounded-xl">
                <button type="submit" class="btn-primary !text-xs !py-1.5 !px-4 !bg-primary-600 hover:!bg-primary-700 shadow-sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
            </form>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide border-b border-slate-100">
        <button @click="tab = 'revenue'" :class="tab === 'revenue' ? 'border-primary-600 text-primary-600 font-black' : 'border-transparent text-slate-400 hover:text-slate-600 font-medium'" class="px-4 py-2 border-b-2 text-xs uppercase tracking-widest transition-all">
            💰 Pendapatan
        </button>
        <button @click="tab = 'products'" :class="tab === 'products' ? 'border-primary-600 text-primary-600 font-black' : 'border-transparent text-slate-400 hover:text-slate-600 font-medium'" class="px-4 py-2 border-b-2 text-xs uppercase tracking-widest transition-all">
            📦 Produk Terlaris
        </button>
        <button @click="tab = 'production'" :class="tab === 'production' ? 'border-primary-600 text-primary-600 font-black' : 'border-transparent text-slate-400 hover:text-slate-600 font-medium'" class="px-4 py-2 border-b-2 text-xs uppercase tracking-widest transition-all">
            ⚙️ Produksi Staf
        </button>
        <button @click="tab = 'audit'" :class="tab === 'audit' ? 'border-primary-600 text-primary-600 font-black' : 'border-transparent text-slate-400 hover:text-slate-600 font-medium'" class="px-4 py-2 border-b-2 text-xs uppercase tracking-widest transition-all">
            🛡️ Verifikasi Desain
        </button>
        <button @click="tab = 'login'" :class="tab === 'login' ? 'border-primary-600 text-primary-600 font-black' : 'border-transparent text-slate-400 hover:text-slate-600 font-medium'" class="px-4 py-2 border-b-2 text-xs uppercase tracking-widest transition-all">
            🔑 Verifikasi Pembayaran
        </button>
    </div>

    {{-- TAB CONTENT: REVENUE --}}
    <div x-show="tab === 'revenue'" class="space-y-6">
        <div class="card border-none shadow-md overflow-hidden bg-white">
            <div class="px-6 py-5 border-b border-slate-50">
                <h3 class="font-black text-slate-900 tracking-tight text-sm uppercase">Laporan Pendapatan Toko</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Tanggal</th>
                            <th class="px-6 py-4 text-center">Jumlah Pesanan</th>
                            <th class="px-6 py-4 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($revenue as $rev)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $rev['date'] }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-600">{{ $rev['total_orders'] }}</td>
                            <td class="px-6 py-4 text-right font-black text-green-600">
                                Rp {{ number_format($rev['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data pendapatan untuk periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB CONTENT: PRODUCTS --}}
    <div x-show="tab === 'products'" class="space-y-6" x-cloak>
        <div class="card border-none shadow-md overflow-hidden bg-white">
            <div class="px-6 py-5 border-b border-slate-50">
                <h3 class="font-black text-slate-900 tracking-tight text-sm uppercase">10 Produk Terlaris</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Nama Produk</th>
                            <th class="px-6 py-4 text-center">Terjual (Pcs)</th>
                            <th class="px-6 py-4 text-right">Total Akumulasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($topProducts as $prod)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $prod['product_name'] }}</td>
                            <td class="px-6 py-4 text-center font-bold text-slate-600">{{ $prod['total_sold'] }}</td>
                            <td class="px-6 py-4 text-right font-black text-primary-600">
                                Rp {{ number_format($prod['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">Belum ada transaksi produk terlaris.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB CONTENT: PRODUCTION LOGS --}}
    <div x-show="tab === 'production'" class="space-y-6" x-cloak>
        <div class="card border-none shadow-md overflow-hidden bg-white">
            <div class="px-6 py-5 border-b border-slate-50">
                <h3 class="font-black text-slate-900 tracking-tight text-sm uppercase">Riwayat Produksi Cetak</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Kode Pesanan</th>
                            <th class="px-6 py-4 text-left">Staf</th>
                            <th class="px-6 py-4 text-left">Mulai Cetak</th>
                            <th class="px-6 py-4 text-left">Selesai Cetak</th>
                            <th class="px-6 py-4 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($productionLogs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-primary-600">{{ $log['order_code'] }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $log['staff_name'] }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ isset($log['start_time']) ? \Carbon\Carbon::parse($log['start_time'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ isset($log['end_time']) ? \Carbon\Carbon::parse($log['end_time'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 italic">{{ $log['notes'] ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada logs aktivitas produksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB CONTENT: DESIGN VERIFICATIONS --}}
    <div x-show="tab === 'audit'" class="space-y-6" x-cloak>
        <div class="card border-none shadow-md overflow-hidden bg-white">
            <div class="px-6 py-5 border-b border-slate-50">
                <h3 class="font-black text-slate-900 tracking-tight text-sm uppercase">Riwayat Verifikasi Desain</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Waktu</th>
                            <th class="px-6 py-4 text-left">Verifikator (Staf)</th>
                            <th class="px-6 py-4 text-left">Kode Pesanan</th>
                            <th class="px-6 py-4 text-center">Versi Desain</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-left">Catatan / Revisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($auditLogs as $audit)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ isset($audit['created_at']) ? \Carbon\Carbon::parse($audit['created_at'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $audit['user_name'] ?? 'System' }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-600">{{ $audit['action'] }}</td>
                            <td class="px-6 py-4 text-center text-xs font-bold text-slate-500">Versi {{ $audit['entity_id'] }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($audit['role'] === 'approved')
                                    <span class="badge badge-green !text-[9px] font-black uppercase">Disetujui</span>
                                @else
                                    <span class="badge badge-red !text-[9px] font-black uppercase">Minta Revisi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium italic">{{ $audit['entity_type'] ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat verifikasi desain.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB CONTENT: PAYMENT VERIFICATIONS --}}
    <div x-show="tab === 'login'" class="space-y-6" x-cloak>
        <div class="card border-none shadow-md overflow-hidden bg-white">
            <div class="px-6 py-5 border-b border-slate-50">
                <h3 class="font-black text-slate-900 tracking-tight text-sm uppercase">Riwayat Verifikasi Pembayaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Waktu</th>
                            <th class="px-6 py-4 text-left">Verifikator (Staf)</th>
                            <th class="px-6 py-4 text-left">Kode Pesanan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Nominal Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($loginLogs as $login)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ isset($login['created_at']) ? \Carbon\Carbon::parse($login['created_at'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $login['user_name'] }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-600">{{ $login['ip_address'] }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($login['activity_type'] === 'approve_payment')
                                    <span class="badge badge-green !text-[9px] font-black uppercase">Diterima</span>
                                @else
                                    <span class="badge badge-red !text-[9px] font-black uppercase">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-black text-primary-600">
                                Rp {{ number_format(floatval($login['user_agent']), 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat verifikasi pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
