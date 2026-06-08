@extends('layouts.staff')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_description', 'Pantau antrean desain dan produksi cetak hari ini')

@section('page_actions')
<div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100 shrink-0">
    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
    <span class="text-xs font-bold text-slate-600">{{ now()->translatedFormat('l, d F Y') }}</span>
</div>
@endsection

@section('content')
<div class="space-y-6 fade-in pb-8">


    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $cards = [
            ['label' => 'Review Desain', 'value' => $stats['design_review'] ?? 0, 'style' => 'border-l-amber-400', 'text' => 'text-amber-600', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Sedang Dicetak', 'value' => $stats['printing'] ?? 0, 'style' => 'border-l-purple-400', 'text' => 'text-purple-600', 'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z'],
            ['label' => 'Siap Diambil', 'value' => $stats['ready'] ?? 0, 'style' => 'border-l-emerald-400', 'text' => 'text-emerald-600', 'icon' => 'M5 13l4 4L19 7'],
            ['label' => 'Selesai', 'value' => $stats['completed'] ?? 0, 'style' => 'border-l-primary-400', 'text' => 'text-primary-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 {{ $card['style'] }} border-l-4">
            <div class="flex items-start justify-between">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center {{ $card['text'] }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $card['value'] }}</h3>
        </div>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="/staff/desain" class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-purple-100 transition-all group">
            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-purple-100 transition-colors">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-black text-slate-900 text-sm group-hover:text-primary-600 transition-colors">Review Desain</p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $stats['design_review'] ?? 0 }} antrian menunggu</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="/staff/produksi" class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-primary-100 transition-all group">
            <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-primary-100 transition-colors">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-black text-slate-900 text-sm group-hover:text-primary-600 transition-colors">Antrean Produksi</p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $stats['printing'] ?? 0 }} sedang dicetak</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Recent Orders --}}
    @php
        $recentList = is_array($recentOrders) ? $recentOrders : [];
        $statusLabels = [
            'waiting_payment' => ['Belum Bayar', 'bg-slate-100 text-slate-600 ring-slate-200'],
            'payment_verification' => ['Verifikasi', 'bg-amber-50 text-amber-700 ring-amber-100'],
            'paid' => ['Lunas', 'bg-blue-50 text-blue-700 ring-blue-100'],
            'printing' => ['Diproses', 'bg-purple-50 text-purple-700 ring-purple-100'],
            'ready' => ['Siap Ambil', 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
            'completed' => ['Selesai', 'bg-green-50 text-green-700 ring-green-100'],
        ];
    @endphp
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-900 text-sm">Pesanan Terbaru</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Aktivitas pesanan terakhir di sistem</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-[10px] font-black uppercase">{{ count($recentList) }} pesanan</span>
        </div>
        @if(count($recentList) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 text-left">Pesanan</th>
                        <th class="px-6 py-4 text-left">Pelanggan</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($recentList as $order)
                    @php
                        $s = $order['status'] ?? '';
                        [$sLabel, $sStyle] = $statusLabels[$s] ?? [ucfirst($s), 'bg-slate-100 text-slate-600 ring-slate-200'];
                        $initial = strtoupper(substr($order['customer_name'] ?? 'G', 0, 1));
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-slate-700 text-white text-[10px] font-black flex items-center justify-center">{{ $initial }}</div>
                                <div>
                                    <p class="font-bold text-slate-900 text-xs">{{ $order['customer_name'] ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $order['customer_formatted_id'] ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase ring-1 {{ $sStyle }}">{{ $sLabel }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <p class="text-sm font-bold text-slate-600">Belum ada pesanan</p>
            <p class="text-xs text-slate-400 mt-1">Data akan muncul setelah ada transaksi baru.</p>
        </div>
        @endif
    </div>

</div>
@endsection
