@extends('layouts.manager')

@section('page_title', 'Laporan Penjualan Produk')

@section('content')
<div class="fade-in">

    <div class="card p-5 mb-6 flex flex-wrap gap-4 items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Breakdown Kinerja Produk</h3>
            <p class="text-xs text-slate-500">Omzet dan kuantitas produk yang terjual.</p>
        </div>
        <button class="btn-outline border-slate-200 text-slate-700 hover:bg-slate-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download CSV
        </button>
    </div>

    <div class="card overflow-x-auto">
        @if(empty($salesData) || count($salesData) == 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Data Kosong</h3>
                <p class="text-slate-500 text-sm">Tidak ada riwayat penjualan.</p>
            </div>
        @else
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Produk</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4 text-center">Total Terjual (Qty)</th>
                        <th class="p-4 text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($salesData as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 font-bold text-slate-900">{{ $item['product_name'] ?? '-' }}</td>
                        <td class="p-4"><span class="badge badge-gray">{{ $item['category_name'] ?? '-' }}</span></td>
                        <td class="p-4 text-center font-bold">{{ $item['total_qty'] ?? 0 }}</td>
                        <td class="p-4 text-right font-black text-primary-600">Rp {{ number_format($item['revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
