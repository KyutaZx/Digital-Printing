@extends('layouts.staff')
@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')

@section('content')
<div class="card">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-bold text-slate-900">Daftar Pembayaran Menunggu Verifikasi</h2>
        <p class="text-sm text-slate-500 mt-0.5">{{ count($orders) }} pesanan menunggu persetujuan Anda</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                <th class="text-left px-6 py-3 font-semibold">Kode Pesanan</th>
                <th class="text-left px-6 py-3 font-semibold">Customer</th>
                <th class="text-left px-6 py-3 font-semibold">Total Bayar</th>
                <th class="text-left px-6 py-3 font-semibold">Status</th>
                <th class="text-left px-6 py-3 font-semibold">Tanggal</th>
                <th class="text-left px-6 py-3 font-semibold">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($orders as $order)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] ?? '-' }}</td>
                <td class="px-6 py-4 text-slate-700">{{ $order['customer_name'] ?? '-' }}</td>
                <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
                <td class="px-6 py-4"><span class="badge badge-yellow">Menunggu Verifikasi</span></td>
                <td class="px-6 py-4 text-slate-500 text-xs">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}</td>
                <td class="px-6 py-4">
                    <a href="/staff/verifikasi/{{ $order['id'] }}"
                       class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-colors">
                        Periksa
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-medium">Tidak ada pembayaran yang perlu diverifikasi</p>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
