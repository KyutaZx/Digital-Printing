@extends('layouts.manager')

@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')
@section('page_description', 'Periksa bukti transfer dan setujui atau tolak pembayaran pelanggan')

@php
    $pendingList = is_array($pending) ? $pending : [];
    $historyList = is_array($history) ? $history : [];
    $pendingTotal = collect($pendingList)->sum(fn ($o) => (float) ($o['total_price'] ?? 0));
@endphp

@section('page_actions')
@if(count($pendingList) > 0)
<div class="flex items-center gap-2 bg-amber-50 px-4 py-2.5 rounded-2xl border border-amber-100 shrink-0">
    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
    <span class="text-xs font-bold text-amber-700">{{ count($pendingList) }} menunggu verifikasi</span>
</div>
@endif
@endsection

@section('content')

<div class="space-y-6 fade-in pb-8" x-data="{ tab: 'pending' }">

    @include('manager.partials.flash')


    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-amber-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menunggu</p>
            <h3 class="text-2xl font-black text-amber-600 mt-2">{{ count($pendingList) }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nilai Pending</p>
            <h3 class="text-2xl font-black text-slate-900 mt-2">Rp {{ number_format($pendingTotal, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-primary-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Riwayat</p>
            <h3 class="text-2xl font-black text-primary-600 mt-2">{{ count($historyList) }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Sudah diverifikasi</p>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Tabs --}}
        <div class="p-2 m-4 mb-0 bg-slate-50 rounded-2xl flex gap-1">
            <button @click="tab = 'pending'"
                    :class="tab === 'pending' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Menunggu Verifikasi
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                      :class="tab === 'pending' ? 'bg-white/20' : 'bg-amber-100 text-amber-700'">{{ count($pendingList) }}</span>
            </button>
            <button @click="tab = 'history'"
                    :class="tab === 'history' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Verifikasi
            </button>
        </div>

        {{-- Tab: Pending --}}
        <div x-show="tab === 'pending'" class="mt-4">
            @if(count($pendingList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left">Pesanan</th>
                            <th class="px-6 py-4 text-left">Pelanggan</th>
                            <th class="px-6 py-4 text-right">Total Bayar</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-left">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($pendingList as $order)
                        @php $initial = strtoupper(substr($order['customer_name'] ?? 'G', 0, 1)); @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-[10px] font-black">{{ $initial }}</div>
                                    <span class="font-bold text-slate-900 text-xs">{{ $order['customer_name'] ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-black uppercase ring-1 ring-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Verifikasi
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="/manager/verifikasi/{{ $order['id'] }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-600 text-white hover:bg-primary-700 text-[10px] font-black uppercase transition-colors shadow-sm">
                                    Periksa
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="font-black text-slate-800 text-sm">Semua Terverifikasi</h4>
                <p class="text-xs text-slate-400 mt-1">Tidak ada pembayaran yang perlu diperiksa saat ini.</p>
            </div>
            @endif
        </div>

        {{-- Tab: History --}}
        <div x-show="tab === 'history'" x-cloak class="mt-4">
            @if(count($historyList) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 text-left">Pesanan</th>
                            <th class="px-6 py-4 text-left">Pelanggan</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($historyList as $order)
                        @php
                            $s = $order['status'] ?? '';
                            $sLabel = match($s) {
                                'paid' => 'Lunas',
                                'design_review' => 'Review Desain',
                                'printing' => 'Diproses',
                                'ready' => 'Siap Ambil',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => ucfirst(str_replace('_', ' ', $s)),
                            };
                            $sStyle = match($s) {
                                'paid', 'design_review' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                'printing' => 'bg-purple-50 text-purple-700 ring-purple-100',
                                'ready', 'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                                'cancelled' => 'bg-red-50 text-red-700 ring-red-100',
                                default => 'bg-slate-100 text-slate-600 ring-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-600 text-xs">{{ $order['order_code'] ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-xs">{{ $order['customer_name'] ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-black text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase ring-1 {{ $sStyle }}">{{ $sLabel }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-bold text-slate-600">Belum ada riwayat verifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Riwayat akan muncul setelah pembayaran diverifikasi.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
