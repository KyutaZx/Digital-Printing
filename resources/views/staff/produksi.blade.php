@extends('layouts.staff')

@section('title', 'Antrean Produksi')
@section('page_title', 'Antrean Produksi')

@section('content')
@php
    $antrianList = is_array($antrian) ? $antrian : [];
    $currentStatus = request('status', '');
    $statusFilter = [
        '' => 'Semua',
        'paid' => 'Siap Cetak',
        'design_review' => 'Review Desain',
        'printing' => 'Sedang Cetak',
        'ready' => 'Siap Ambil',
    ];
    $statusLabels = [
        'paid' => ['Siap Cetak', 'bg-blue-50 text-blue-700 ring-blue-100'],
        'design_review' => ['Review Desain', 'bg-amber-50 text-amber-800 ring-amber-100'],
        'printing' => ['Sedang Cetak', 'bg-purple-50 text-purple-700 ring-purple-100'],
        'ready' => ['Siap Ambil', 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
    ];
    $countPaid = collect($antrianList)->where('status', 'paid')->count();
    $countPrinting = collect($antrianList)->where('status', 'printing')->count();
    $countReady = collect($antrianList)->where('status', 'ready')->count();
@endphp

<div class="space-y-6 fade-in pb-8">

    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Antrean Produksi Cetak</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola proses cetak dari siap cetak hingga siap diambil</p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100 shrink-0">
            <span class="text-xs font-bold text-slate-600">Filter: <span class="text-primary-600">{{ $statusFilter[$currentStatus] ?? 'Semua' }}</span></span>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siap Cetak</p>
            <h3 class="text-2xl font-black text-blue-600 mt-2">{{ $countPaid }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-purple-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sedang Cetak</p>
            <h3 class="text-2xl font-black text-purple-600 mt-2">{{ $countPrinting }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 border-l-4 border-l-emerald-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siap Ambil</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-2">{{ $countReady }}</h3>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5">
        <div class="bg-slate-50 p-1.5 rounded-2xl flex gap-1 overflow-x-auto scrollbar-hide">
            @foreach($statusFilter as $val => $label)
            <a href="?status={{ $val }}"
               class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentStatus === $val ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:bg-white' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Queue List --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-900 text-sm">Daftar Antrian</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Mulai cetak atau tandai selesai per pesanan</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-[10px] font-black uppercase">{{ count($antrianList) }} pesanan</span>
        </div>

        @if(count($antrianList) > 0)
        <div class="divide-y divide-slate-50">
            @foreach($antrianList as $order)
            @php
                $s = $order['status'] ?? '';
                [$sLabel, $sStyle] = $statusLabels[$s] ?? [ucfirst($s), 'bg-slate-100 text-slate-600 ring-slate-200'];
                $initial = strtoupper(substr($order['customer_name'] ?? 'G', 0, 1));
            @endphp
            <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-slate-50/80 transition-colors">
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-2xl bg-primary-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="font-mono font-bold text-primary-600 text-sm">{{ $order['order_code'] ?? '-' }}</span>
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase ring-1 {{ $sStyle }}">{{ $sLabel }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-slate-700 text-white text-[10px] font-black flex items-center justify-center shrink-0">{{ $initial }}</div>
                            <p class="font-bold text-slate-900 text-sm truncate">{{ $order['customer_name'] ?? 'Customer' }}</p>
                            <span class="text-[10px] text-slate-400 font-mono hidden sm:inline">{{ $order['customer_formatted_id'] ?? '' }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Total: <span class="font-black text-slate-800">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span></p>
                        @if($order['estimated_finish_date'] ?? null)
                        <p class="text-[10px] text-slate-400 mt-0.5">Est. selesai: {{ \Carbon\Carbon::parse($order['estimated_finish_date'])->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2 shrink-0 sm:ml-auto">
                    @if($s === 'paid')
                    <form method="POST" action="/staff/produksi/{{ $order['id'] }}/mulai" onsubmit="return confirm('Mulai proses cetak untuk pesanan ini?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-[10px] font-black uppercase rounded-xl transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mulai Cetak
                        </button>
                    </form>
                    @elseif($s === 'printing')
                    <form method="POST" action="/staff/produksi/{{ $order['id'] }}/selesai" onsubmit="return confirm('Tandai pesanan ini sebagai selesai cetak?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase rounded-xl transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesai Cetak
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase rounded-xl ring-1 ring-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Siap Ambil
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            </div>
            <h4 class="font-black text-slate-800 text-sm">Belum Ada Antrian</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-xs">Tidak ada pesanan pada filter <strong>{{ $statusFilter[$currentStatus] ?? 'Semua' }}</strong>.</p>
            @if($currentStatus)
            <a href="?status=" class="mt-4 text-xs font-bold text-primary-600 hover:underline">Lihat semua antrian</a>
            @endif
        </div>
        @endif
    </div>

</div>
@endsection
