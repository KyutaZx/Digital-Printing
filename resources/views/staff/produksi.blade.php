@extends('layouts.staff')
@section('title', 'Antrean Produksi')
@section('page_title', 'Antrean Produksi')

@section('content')
<div class="space-y-5">

    {{-- Filter Status --}}
    <div class="flex gap-2 flex-wrap">
        @foreach(['Semua' => '', 'Lunas' => 'paid', 'Sedang Cetak' => 'printing', 'Siap Ambil' => 'ready'] as $label => $val)
        <span class="badge {{ request('status', '') === $val ? 'bg-primary-600 text-white' : 'badge-gray' }} cursor-pointer px-3 py-1.5 text-xs">{{ $label }}</span>
        @endforeach
    </div>

    <div class="card">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Antrian</h2>
            <span class="badge badge-blue">{{ count($antrian) }} pesanan</span>
        </div>
        <div class="divide-y divide-slate-100">
        @forelse($antrian as $order)
        <div class="px-6 py-5 flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-mono font-bold text-primary-600 text-sm">{{ $order['order_code'] ?? '-' }}</span>
                    @php
                    $s = $order['status'] ?? '';
                    $badge = match($s) { 'paid' => 'badge-blue', 'printing' => 'badge-purple', 'ready' => 'badge-green', default => 'badge-gray' };
                    $label = match($s) { 'paid' => 'Siap Cetak', 'printing' => 'Sedang Cetak', 'ready' => 'Siap Ambil', default => $s };
                    @endphp
                    <span class="{{ $badge }}">{{ $label }}</span>
                </div>
                <p class="text-sm text-slate-600 font-bold">{{ $order['customer_name'] ?? 'Customer' }} <span class="text-xs font-mono text-slate-400 font-normal">({{ $order['customer_formatted_id'] ?? '-' }})</span></p>
                <p class="text-xs text-slate-500 mt-0.5">Total: Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</p>
                @if(($order['estimated_finish_date'] ?? null))
                <p class="text-xs text-slate-400 mt-1">Est. selesai: {{ \Carbon\Carbon::parse($order['estimated_finish_date'])->format('d M Y') }}</p>
                @endif
            </div>

            {{-- Aksi berdasarkan status --}}
            <div class="flex gap-2 shrink-0">
                @if($s === 'paid')
                <form method="POST" action="/staff/produksi/{{ $order['id'] }}/mulai" onsubmit="return confirm('Mulai proses cetak untuk pesanan ini?')">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mulai Cetak
                    </button>
                </form>
                @elseif($s === 'printing')
                <form method="POST" action="/staff/produksi/{{ $order['id'] }}/selesai" onsubmit="return confirm('Tandai pesanan ini sebagai selesai cetak?')">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Selesai Cetak
                    </button>
                </form>
                @else
                <span class="px-4 py-2 bg-green-50 text-green-700 text-xs font-bold rounded-xl border border-green-200">✓ Siap Ambil</span>
                @endif
            </div>
        </div>
        @empty
        <div class="py-16 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <p>Belum ada antrian produksi</p>
        </div>
        @endforelse
        </div>
    </div>
</div>
@endsection
