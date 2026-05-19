@extends('layouts.manager')

@section('title', 'Manager Dashboard')
@section('page_title', 'Business Overview')

@section('content')
<div class="space-y-8 fade-in">
    
    {{-- Top Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                ['label' => 'Total Omzet', 'value' => 'Rp ' . number_format($stats['total_omzet'] ?? 0, 0, ',', '.'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-green-500'],
                ['label' => 'Perlu Verifikasi', 'value' => $stats['perlu_verifikasi'] ?? 0, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-yellow-500', 'link' => '/manager/verifikasi'],
                ['label' => 'Stok Menipis', 'value' => $stats['material_rendah'] ?? 0, 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'bg-amber-500'],
                ['label' => 'Pesanan Selesai', 'value' => $stats['pesanan_selesai'] ?? 0, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-indigo-500'],
            ];
        @endphp

        @foreach($cards as $card)
        <a href="{{ $card['link'] ?? '#' }}" class="card p-6 border-none shadow-md relative overflow-hidden group block hover:shadow-lg transition-shadow">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 {{ $card['color'] }} opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $card['color'] }} bg-opacity-10 rounded-2xl flex items-center justify-center text-{{ str_replace('bg-', '', $card['color']) }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $card['value'] }}</h3>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Recent Orders --}}
        <div class="lg:col-span-2 card border-none shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between bg-white">
                <h2 class="font-black text-slate-900 tracking-tight">Pesanan Terbaru</h2>
                <a href="/manager/pesanan" class="text-xs font-bold text-primary-600 hover:underline uppercase tracking-widest">Semua Pesanan</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Pelanggan</th>
                            <th class="px-6 py-4 text-left">Produk</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location='/manager/pesanan?q={{ $order['order_code'] }}'">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $order['customer_name'] ?? '-' }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[9px] font-mono text-slate-400 bg-slate-50 px-1 rounded">{{ $order['customer_formatted_id'] ?? '-' }}</span>
                                    <span class="text-[9px] font-mono text-primary-600 font-bold uppercase tracking-tighter">{{ $order['order_code'] ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(!empty($order['items']))
                                    <p class="text-xs text-slate-600 truncate max-w-[150px]">{{ $order['items'][0]['product_name'] }}</p>
                                    @if(count($order['items']) > 1)
                                        <p class="text-[10px] text-slate-400">+{{ count($order['items']) - 1 }} lainnya</p>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $s = $order['status'] ?? '';
                                    $badge = match($s) {
                                        'waiting_payment' => 'badge-gray',
                                        'payment_verification' => 'badge-yellow',
                                        'paid' => 'badge-blue',
                                        'printing' => 'badge-purple',
                                        'ready' => 'badge-green',
                                        'completed' => 'badge-green',
                                        default => 'badge-gray'
                                    };
                                @endphp
                                <span class="{{ $badge }} !text-[9px] font-black uppercase tracking-tighter">{{ $s }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-900">
                                Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Belum ada pesanan masuk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Analytics / Action --}}
        <div class="space-y-6">
            
            {{-- Material Alert --}}
            <div class="card p-6 border-none shadow-md bg-white">
                <h2 class="font-black text-slate-900 tracking-tight mb-4">Material Bahan</h2>
                <div class="space-y-4">
                    @php
                        // Mock or fetch actual low stock materials
                        $lowStock = array_filter($materials ?? [], fn($m) => ($m['stock'] ?? 0) < 10);
                    @endphp
                    @forelse(array_slice($lowStock, 0, 5) as $m)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $m['name'] }}</p>
                            <p class="text-[10px] text-slate-400">Tersisa {{ $m['stock'] }} {{ $m['unit'] }}</p>
                        </div>
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <svg class="w-10 h-10 text-slate-100 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Stok Aman Terkendali</p>
                    </div>
                    @endforelse
                </div>
                <a href="/manager/material" class="block w-full text-center mt-6 py-2 border border-slate-100 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-colors uppercase tracking-widest">Kelola Stok</a>
            </div>

            {{-- CTA --}}
            <div class="bg-gradient-to-br from-primary-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl shadow-primary-100">
                <h3 class="font-black text-xl mb-2">Ingin menambah produk baru?</h3>
                <p class="text-primary-100 text-xs mb-6 leading-relaxed">Kelola katalog produk Anda agar pelanggan memiliki lebih banyak pilihan cetak.</p>
                <a href="/manager/produk" class="inline-flex items-center gap-2 bg-white text-primary-700 px-4 py-2 rounded-xl text-xs font-black shadow-lg hover:scale-105 transition-transform uppercase tracking-widest">
                    Ke Katalog Produk
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
