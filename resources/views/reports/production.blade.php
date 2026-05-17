@extends('layouts.manager')

@section('page_title', 'Laporan Produksi')

@section('content')
<div class="fade-in">
    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="card p-6 border-l-4 border-l-emerald-500 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-slate-500 font-semibold text-sm">Item Selesai Cetak</h4>
                <div class="text-2xl font-black text-slate-900">{{ $stats['items_completed'] ?? 0 }} <span class="text-sm font-normal text-slate-400">item</span></div>
            </div>
        </div>
        <div class="card p-6 border-l-4 border-l-blue-500 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-slate-500 font-semibold text-sm">Rata-rata Waktu Produksi</h4>
                <div class="text-2xl font-black text-slate-900">{{ $stats['avg_production_time'] ?? '0' }} <span class="text-sm font-normal text-slate-400">jam</span></div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-x-auto">
        @if(empty($logs) || count($logs) == 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Data</h3>
                <p class="text-slate-500 text-sm">Tidak ada log produksi untuk saat ini.</p>
            </div>
        @else
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Kode Pesanan</th>
                        <th class="p-4">Produk</th>
                        <th class="p-4">Staff Produksi</th>
                        <th class="p-4">Waktu Mulai</th>
                        <th class="p-4">Waktu Selesai</th>
                        <th class="p-4 text-right">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-900">{{ $log['order_code'] ?? '-' }}</td>
                        <td class="p-4">{{ $log['product_name'] ?? '-' }}</td>
                        <td class="p-4">{{ $log['staff_name'] ?? '-' }}</td>
                        <td class="p-4">{{ date('d/m/Y H:i', strtotime($log['started_at'] ?? 'now')) }}</td>
                        <td class="p-4">{{ !empty($log['finished_at']) ? date('d/m/Y H:i', strtotime($log['finished_at'])) : '-' }}</td>
                        <td class="p-4 text-right font-medium">
                            @if(!empty($log['duration_minutes']))
                                {{ floor($log['duration_minutes'] / 60) }}j {{ $log['duration_minutes'] % 60 }}m
                            @else
                                <span class="badge badge-yellow">Proses</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
