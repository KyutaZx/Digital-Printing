@extends('layouts.manager')

@section('page_title', 'Laporan Pesanan')

@section('content')
<div class="fade-in">
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card p-6 bg-gradient-to-br from-blue-500 to-primary-700 text-white shadow-blue-200">
            <h4 class="text-blue-100 font-semibold mb-1">Total Pesanan</h4>
            <div class="text-3xl font-black">{{ $stats['total_orders'] ?? 0 }}</div>
            <p class="text-xs text-blue-200 mt-2">Dalam periode ini</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-emerald-500 to-secondary-700 text-white shadow-emerald-200">
            <h4 class="text-emerald-100 font-semibold mb-1">Total Pendapatan</h4>
            <div class="text-3xl font-black">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-emerald-200 mt-2">Gross revenue pesanan lunas</p>
        </div>
        <div class="card p-6 border-slate-200">
            <h4 class="text-slate-500 font-semibold mb-1">Rata-rata Order</h4>
            <div class="text-3xl font-black text-slate-800">Rp {{ number_format($stats['avg_order'] ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-slate-400 mt-2">Per transaksi</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-5 mb-6">
        <form method="GET" action="" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input">
            </div>
            <div class="flex-1 w-full">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input">
            </div>
            <div class="flex-1 w-full">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                </select>
            </div>
            <button type="submit" class="btn-primary w-full md:w-auto h-[46px]">Terapkan Filter</button>
            @if(request()->has('start_date'))
                <a href="?" class="btn-secondary h-[46px]">Reset</a>
            @endif
        </form>
    </div>

    {{-- Data Table --}}
    <div class="card overflow-x-auto">
        @if(empty($orders) || count($orders) == 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Data Kosong</h3>
                <p class="text-slate-500 text-sm">Tidak ada data laporan pesanan pada periode ini.</p>
            </div>
        @else
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Kode Pesanan</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $row)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 whitespace-nowrap">{{ date('d/m/Y H:i', strtotime($row['created_at'] ?? 'now')) }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $row['order_code'] ?? '-' }}</td>
                        <td class="p-4">{{ $row['customer_name'] ?? 'Guest' }}</td>
                        <td class="p-4">
                            @if(($row['status'] ?? '') == 'selesai')
                                <span class="badge badge-green">Selesai</span>
                            @elseif(($row['status'] ?? '') == 'batal')
                                <span class="badge badge-red">Dibatalkan</span>
                            @else
                                <span class="badge badge-blue capitalize">{{ str_replace('_', ' ', $row['status'] ?? '') }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-right font-bold text-primary-600">Rp {{ number_format($row['total_amount'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
