@extends('layouts.manager')

@section('title', 'Monitoring & Laporan')
@section('page_title', 'Monitoring Transaksi')

@section('content')
<div class="space-y-6 fade-in pb-8">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Monitoring Transaksi</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau alur pesanan dan performa bisnis secara menyeluruh</p>
        </div>
        <div class="flex items-center gap-2 bg-emerald-50 text-emerald-600 px-4 py-2 rounded-2xl border border-emerald-100">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
            <span class="text-[10px] font-bold uppercase tracking-widest">Real-time</span>
        </div>
    </div>

    {{-- Status Flow Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        @php
            $statuses = [
                'waiting_payment'      => ['label' => 'Belum Bayar',  'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'bg' => 'bg-slate-100',   'text' => 'text-slate-600',  'dot' => 'bg-slate-400'],
                'payment_verification' => ['label' => 'Verifikasi',   'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                          'bg' => 'bg-amber-100',   'text' => 'text-amber-600',  'dot' => 'bg-amber-500'],
                'paid'                 => ['label' => 'Lunas',         'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'dot' => 'bg-blue-500'],
                'printing'             => ['label' => 'Diproses',      'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z', 'bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'dot' => 'bg-purple-500'],
                'ready'                => ['label' => 'Siap Ambil',    'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',   'bg' => 'bg-teal-100',   'text' => 'text-teal-600',   'dot' => 'bg-teal-500'],
                'completed'            => ['label' => 'Selesai',       'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'bg' => 'bg-emerald-500', 'text' => 'text-white', 'dot' => 'bg-white'],
            ];
            $total = array_sum(array_map(fn($k) => $statusCount[$k] ?? 0, array_keys($statuses)));
        @endphp

        @foreach($statuses as $key => $meta)
        @php $count = $statusCount[$key] ?? 0; @endphp
        <div class="relative rounded-2xl p-4 {{ $key === 'completed' ? 'bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20' : 'bg-white border border-slate-100 shadow-sm' }} overflow-hidden group">
            <div class="absolute -right-3 -bottom-3 w-16 h-16 {{ $meta['bg'] }} opacity-30 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex flex-col gap-3 relative z-10">
                <div class="flex items-center justify-between">
                    <span class="w-2 h-2 rounded-full {{ $meta['dot'] }} {{ $key === 'payment_verification' ? 'animate-pulse' : '' }}"></span>
                    <div class="w-7 h-7 {{ $meta['bg'] }} {{ $meta['text'] }} rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/></svg>
                    </div>
                </div>
                <div>
                    <p class="text-[9px] font-bold {{ $key === 'completed' ? 'text-emerald-100' : 'text-slate-400' }} uppercase tracking-widest mb-1">{{ $meta['label'] }}</p>
                    <h4 class="text-2xl font-black {{ $key === 'completed' ? 'text-white' : 'text-slate-900' }}">{{ $count }}</h4>
                    @if($total > 0)
                    <p class="text-[9px] font-bold {{ $key === 'completed' ? 'text-emerald-200' : 'text-slate-400' }} mt-0.5">{{ round($count/$total*100) }}% dari total</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Live Monitoring Table --}}
        <div class="lg:col-span-2 bg-white border border-slate-100 shadow-sm rounded-3xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
                <h2 class="font-black text-slate-900 tracking-tight text-lg">Aliran Transaksi</h2>
                <div class="flex items-center gap-2 text-emerald-500">
                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Live</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Transaksi</th>
                            <th class="px-6 py-4 text-left">Waktu</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse(array_slice($orders, 0, 15) as $order)
                        @php
                            $s = $order['status'] ?? '';
                            $statusStyle = match($s) {
                                'waiting_payment'      => 'bg-slate-100 text-slate-600',
                                'payment_verification' => 'bg-amber-100 text-amber-700',
                                'paid'                 => 'bg-blue-100 text-blue-700',
                                'printing'             => 'bg-purple-100 text-purple-700',
                                'ready'                => 'bg-teal-100 text-teal-700',
                                'completed'            => 'bg-emerald-100 text-emerald-700',
                                'cancelled'            => 'bg-red-100 text-red-700',
                                default                => 'bg-slate-100 text-slate-500',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $order['order_code'] }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $order['customer_name'] ?? 'Guest' }}</p>
                            </td>
                            <td class="px-6 py-4 text-[10px] text-slate-500 font-medium">
                                {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->diffForHumans() : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tight {{ $statusStyle }}">{{ $s }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-900 text-xs">
                                Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Ringkasan & KPI --}}
        <div class="space-y-4">

            {{-- Completion Rate --}}
            <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-6">
                <h2 class="font-black text-slate-900 text-lg mb-5">Performa Bisnis</h2>

                <div class="space-y-5">
                    {{-- Completion Rate --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tingkat Penyelesaian</span>
                            <span class="text-xs font-black text-primary-600">{{ $realStats['completion_rate'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full transition-all duration-1000" style="width: {{ $realStats['completion_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    {{-- Cancellation Rate --}}
                    @php
                        $cancelCount = $statusCount['cancelled'] ?? 0;
                        $cancelRate = $total > 0 ? round($cancelCount / $total * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tingkat Pembatalan</span>
                            <span class="text-xs font-black text-red-500">{{ $cancelRate }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-400 to-red-300 rounded-full" style="width: {{ $cancelRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rata-rata Proses</p>
                        <p class="text-xl font-black text-slate-900 mt-0.5">{{ $realStats['avg_days'] ?? 1 }} <span class="text-sm font-semibold text-slate-400">Hari / Order</span></p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kepuasan Pelanggan</p>
                        <p class="text-xl font-black text-slate-900 mt-0.5">{{ $realStats['satisfaction_rate'] ?? 80 }}<span class="text-sm font-semibold text-slate-400">% Puas</span></p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Transaksi</p>
                        <p class="text-xl font-black text-slate-900 mt-0.5">{{ $total }} <span class="text-sm font-semibold text-slate-400">Order</span></p>
                    </div>
                </div>
            </div>

            {{-- Export Button --}}
            <a href="/manager/laporan" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-700 hover:to-indigo-700 text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-primary-900/20 transition-all hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                Lihat Laporan Lengkap
            </a>
        </div>

    </div>

</div>
@endsection
