@extends('layouts.manager')

@section('title', 'Monitoring & Laporan')
@section('page_title', 'Business Intelligence & Monitoring')

@section('content')
<div class="space-y-8">
    
    {{-- Status Distribution Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
            $statuses = [
                'waiting_payment' => ['label' => 'Belum Bayar', 'color' => 'bg-gray-100 text-gray-600'],
                'payment_verification' => ['label' => 'Verifikasi', 'color' => 'bg-yellow-100 text-yellow-600'],
                'paid' => ['label' => 'Lunas', 'color' => 'bg-blue-100 text-blue-600'],
                'printing' => ['label' => 'Diproses', 'color' => 'bg-purple-100 text-purple-600'],
                'ready' => ['label' => 'Siap Ambil', 'color' => 'bg-green-100 text-green-600'],
                'completed' => ['label' => 'Selesai', 'color' => 'bg-emerald-600 text-white'],
            ];
        @endphp

        @foreach($statuses as $key => $meta)
        <div class="card p-4 text-center border-none shadow-sm {{ $key === 'completed' ? 'bg-emerald-600' : 'bg-white' }}">
            <p class="text-[9px] font-black uppercase tracking-widest {{ $key === 'completed' ? 'text-emerald-100' : 'text-slate-400' }} mb-1">{{ $meta['label'] }}</p>
            <h4 class="text-xl font-black {{ $key === 'completed' ? 'text-white' : 'text-slate-900' }}">{{ $statusCount[$key] ?? 0 }}</h4>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Live Monitoring --}}
        <div class="lg:col-span-2 card border-none shadow-md overflow-hidden bg-white">
            <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
                <h2 class="font-black text-slate-900 tracking-tight">Monitoring Transaksi</h2>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Real-time Data</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-6 py-4 text-left">Transaksi</th>
                            <th class="px-6 py-4 text-left">Waktu</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach(array_slice($orders, 0, 15) as $order)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $order['order_code'] }}</p>
                                <p class="text-[9px] text-slate-400">{{ $order['customer_name'] ?? 'Guest' }}</p>
                            </td>
                            <td class="px-6 py-4 text-[10px] text-slate-500 font-medium">
                                {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->diffForHumans() : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php $s = $order['status'] ?? ''; @endphp
                                <span class="text-[9px] font-black uppercase {{ $s === 'completed' ? 'text-green-600' : 'text-slate-400' }}">{{ $s }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-900 text-xs">
                                Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sales Summary --}}
        <div class="space-y-6">
            <div class="card p-6 border-none shadow-md bg-white">
                <h2 class="font-black text-slate-900 tracking-tight mb-6">Ringkasan Laporan</h2>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase mb-2">
                            <span>Target Bulanan</span>
                            <span>75%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-600 w-3/4 rounded-full"></div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rata-rata Proses</p>
                                <p class="text-sm font-black text-slate-900">1.4 Hari / Order</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tingkat Retensi</p>
                                <p class="text-sm font-black text-slate-900">82% Pelanggan Puas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-8 btn-primary !py-3 !text-[10px] uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Laporan Tahunan
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
