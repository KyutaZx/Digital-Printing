@extends('layouts.manager')

@section('title', 'Manager Dashboard')
@section('page_title', 'Business Overview')

@section('content')
<div class="space-y-6 fade-in pb-8">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Ringkasan</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau performa bisnis Anda secara real-time</p>
        </div>
        <div class="hidden md:flex bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100 items-center gap-2">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-xs font-bold text-slate-600">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    {{-- Top Stats Highlight --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Omzet (Hero Card) --}}
        <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 shadow-xl shadow-slate-900/20 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white opacity-5 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
            <div class="flex items-center justify-between relative z-10 h-full">
                <div class="flex flex-col justify-between h-full">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pendapatan</p>
                    <div>
                        <h3 class="text-3xl font-black text-white mt-2">Rp {{ number_format($stats['total_omzet'] ?? 0, 0, ',', '.') }}</h3>
                        <div class="mt-3 flex items-center gap-2">
                            @php $trend = $stats['omzet_trend'] ?? 0; @endphp
                            <span class="px-2 py-1 rounded-lg text-[10px] font-black {{ $trend >= 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $trend >= 0 ? 'â–²' : 'â–¼' }} {{ abs(round($trend)) }}%
                            </span>
                            <span class="text-[10px] font-medium text-slate-500">vs bulan lalu</span>
                        </div>
                    </div>
                </div>
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white backdrop-blur-sm border border-white/10 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Perlu Verifikasi (Urgent Action) --}}
        <a href="/manager/verifikasi" class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-3xl p-6 shadow-xl shadow-amber-500/20 text-white relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex flex-col justify-between h-full relative z-10">
                <div class="flex items-start justify-between">
                    <p class="text-[10px] font-bold text-amber-100 uppercase tracking-widest">Perlu Verifikasi</p>
                    <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-black">{{ $stats['perlu_verifikasi'] ?? 0 }}</h3>
                    <p class="text-[10px] text-amber-100 mt-1 font-medium flex items-center gap-1">
                        Tinjau sekarang <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </p>
                </div>
            </div>
        </a>

        {{-- Pesanan Aktif --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="flex flex-col justify-between h-full">
                <div class="flex items-start justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Aktif</p>
                    <div class="w-8 h-8 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-black text-slate-900">{{ $stats['pesanan_aktif'] ?? 0 }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Dalam proses produksi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Chart & Table --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Sales Chart --}}
            <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-black text-slate-900 tracking-tight text-lg">Grafik Pendapatan</h2>
                    <span class="text-[10px] font-bold bg-slate-50 text-slate-500 px-3 py-1 rounded-full uppercase tracking-widest">7 Hari Terakhir</span>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white border border-slate-100 shadow-sm rounded-3xl overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
                    <h2 class="font-black text-slate-900 tracking-tight text-lg">Pesanan Terbaru</h2>
                    <a href="/manager/pesanan" class="text-[10px] font-bold text-primary-600 hover:text-primary-700 hover:underline uppercase tracking-widest">Semua Pesanan</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                                <th class="px-6 py-4 text-left">Pelanggan</th>
                                <th class="px-6 py-4 text-left">Produk</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50 transition-colors cursor-pointer group" onclick="window.location='/manager/pesanan?q={{ $order['order_code'] }}'">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $order['customer_name'] ?? '-' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-mono text-slate-400 bg-white border border-slate-100 px-1.5 py-0.5 rounded">{{ $order['customer_formatted_id'] ?? '-' }}</span>
                                        <span class="text-[9px] font-mono text-primary-600 font-bold uppercase tracking-tighter">{{ $order['order_code'] ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($order['items']))
                                        <p class="text-xs text-slate-700 font-medium truncate max-w-[150px]">{{ $order['items'][0]['product_name'] }}</p>
                                        @if(count($order['items']) > 1)
                                            <p class="text-[9px] font-bold text-slate-400 mt-0.5">+{{ count($order['items']) - 1 }} lainnya</p>
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
        </div>

        {{-- Right Column: Analytics & Alerts --}}
        <div class="space-y-6">
            
            {{-- Mini Stats Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Omzet Bln Ini</p>
                    <p class="text-lg font-black text-slate-900 truncate" title="Rp {{ number_format($stats['omzet_bulan_ini'] ?? 0, 0, ',', '.') }}">
                        Rp {{ number_format(floor(($stats['omzet_bulan_ini'] ?? 0)/1000), 0, ',', '.') }}<span class="text-xs text-slate-400">k</span>
                    </p>
                </div>
                <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Order Bln Ini</p>
                    <p class="text-xl font-black text-slate-900">{{ $stats['pesanan_bulan_ini'] ?? 0 }}</p>
                </div>
            </div>

            {{-- Material Alert --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-black text-slate-900 tracking-tight text-lg">Stok Material</h2>
                    <span class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </span>
                </div>
                
                <div class="space-y-4">
                    @php
                        $lowStock = array_filter($materials ?? [], fn($m) => ($m['stock'] ?? 0) < 10);
                    @endphp
                    @forelse(array_slice($lowStock, 0, 4) as $m)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $m['name'] }}</p>
                            <p class="text-[10px] text-red-500 font-bold mt-0.5">Sisa {{ $m['stock'] }} {{ $m['unit'] }}</p>
                        </div>
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                    </div>
                    @empty
                    <div class="text-center py-6 bg-slate-50 rounded-2xl">
                        <svg class="w-10 h-10 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Semua Stok Aman</p>
                    </div>
                    @endforelse
                </div>
                <a href="/manager/material" class="block w-full text-center mt-4 py-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl text-[10px] font-bold text-slate-600 transition-colors uppercase tracking-widest">Kelola Inventori</a>
            </div>

            {{-- CTA --}}
            <div class="bg-gradient-to-br from-primary-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl shadow-primary-900/20 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
                <h3 class="font-black text-xl mb-2 relative z-10">Katalog Produk</h3>
                <p class="text-primary-100 text-xs mb-6 leading-relaxed relative z-10">Pastikan layanan cetak Anda selalu up-to-date untuk pelanggan.</p>
                <a href="/manager/produk" class="relative z-10 inline-flex items-center justify-center w-full gap-2 bg-white text-primary-700 px-4 py-3 rounded-xl text-xs font-black shadow-lg hover:scale-[1.02] transition-transform uppercase tracking-widest">
                    Kelola Produk
                </a>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(14, 165, 233, 0.5)'); // primary-500
        gradient.addColorStop(1, 'rgba(14, 165, 233, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['chart_data']['labels'] ?? []) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($stats['chart_data']['revenue'] ?? []) !!},
                    borderColor: '#0ea5e9', // primary-500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0ea5e9',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: 'Inter', size: 10 } }
                    },
                    y: {
                        border: { display: false },
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { 
                            font: { family: 'Inter', size: 10 },
                            callback: function(value, index, values) {
                                if (value >= 1000000) return (value / 1000000) + 'M';
                                if (value >= 1000) return (value / 1000) + 'k';
                                return value;
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
