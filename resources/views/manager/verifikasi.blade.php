@extends('layouts.manager')
@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')

@section('content')
<div class="card">
    <div x-data="{ tab: 'pending' }">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-4">
            <button @click="tab = 'pending'" 
                    class="font-bold text-sm px-4 py-2 rounded-xl transition-all"
                    :class="tab === 'pending' ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50'">
                Menunggu Verifikasi 
                <span class="ml-1 text-xs px-2 py-0.5 rounded-full" :class="tab === 'pending' ? 'bg-primary-100' : 'bg-slate-100'">{{ count($pending) }}</span>
            </button>
            <button @click="tab = 'history'" 
                    class="font-bold text-sm px-4 py-2 rounded-xl transition-all"
                    :class="tab === 'history' ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50'">
                Riwayat Verifikasi
            </button>
        </div>
        
        <!-- Tab: Pending -->
        <div x-show="tab === 'pending'" class="overflow-x-auto">
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
                @forelse($pending as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $order['customer_name'] ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4"><span class="badge badge-yellow">Menunggu Verifikasi</span></td>
                    <td class="px-6 py-4 text-slate-500 text-xs">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}</td>
                    <td class="px-6 py-4">
                        <a href="/manager/verifikasi/{{ $order['id'] }}"
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

        <!-- Tab: History -->
        <div x-show="tab === 'history'" x-cloak class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <th class="text-left px-6 py-3 font-semibold">Kode Pesanan</th>
                    <th class="text-left px-6 py-3 font-semibold">Customer</th>
                    <th class="text-left px-6 py-3 font-semibold">Total Bayar</th>
                    <th class="text-left px-6 py-3 font-semibold">Status Saat Ini</th>
                    <th class="text-left px-6 py-3 font-semibold">Tanggal</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($history as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-slate-600 text-xs">{{ $order['order_code'] ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $order['customer_name'] ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $s = $order['status'] ?? '';
                            $badge = match($s) {
                                'paid' => 'bg-blue-100 text-blue-700',
                                'design_review' => 'bg-blue-100 text-blue-700',
                                'printing' => 'bg-purple-100 text-purple-700',
                                'ready' => 'bg-green-100 text-green-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                            $label = match($s) {
                                'paid' => 'Lunas (Menunggu Desain)',
                                'design_review' => 'Lunas (Review Desain)',
                                'printing' => 'Sedang Diproses',
                                'ready' => 'Siap Diambil',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => ucfirst($s),
                            };
                        @endphp
                        <span class="text-xs font-bold px-2 py-1 rounded-full {{ $badge }}">{{ $label }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3 text-slate-400">
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="font-medium">Belum ada riwayat verifikasi</p>
                    </div>
                </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
