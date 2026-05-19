@extends('layouts.staff')
@section('title', 'Dashboard Staff')
@section('page_title', 'Dashboard Staff')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    @php
    $cards = [
        ['label' => 'Review Desain', 'value' => $stats['design_review'], 'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Sedang Dicetak',      'value' => $stats['printing'],        'color' => 'blue',   'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z'],
        ['label' => 'Siap Diambil',        'value' => $stats['ready'],          'color' => 'green',  'icon' => 'M5 13l4 4L19 7'],
        ['label' => 'Selesai',             'value' => $stats['completed'],    'color' => 'purple', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
    ];
    $colorMap = ['yellow' => 'bg-yellow-50 text-yellow-600 border-yellow-100', 'blue' => 'bg-blue-50 text-blue-600 border-blue-100', 'green' => 'bg-green-50 text-green-600 border-green-100', 'purple' => 'bg-purple-50 text-purple-600 border-purple-100'];
    @endphp
    @foreach($cards as $card)
    <div class="card p-5 fade-in">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ $card['label'] }}</span>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center border {{ $colorMap[$card['color']] }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
        </div>
        <div class="text-3xl font-black text-slate-900">{{ $card['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <a href="/staff/desain" class="card p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        </div>
        <div>
            <p class="font-bold text-slate-900 text-sm group-hover:text-primary-600 transition-colors">Review Desain</p>
            <p class="text-xs text-slate-500">Approve atau tolak desain</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
    <a href="/staff/produksi" class="card p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        </div>
        <div>
            <p class="font-bold text-slate-900 text-sm group-hover:text-primary-600 transition-colors">Antrean Produksi</p>
            <p class="text-xs text-slate-500">{{ $stats['printing'] }} sedang dicetak</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
</div>

{{-- Recent Orders Table --}}
<div class="card">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-bold text-slate-900">Pesanan Terbaru</h2>

    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                <th class="text-left px-6 py-3 font-semibold">Kode Pesanan</th>
                <th class="text-left px-6 py-3 font-semibold">Customer</th>
                <th class="text-left px-6 py-3 font-semibold">Total</th>
                <th class="text-left px-6 py-3 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 font-mono font-bold text-primary-600 text-xs">{{ $order['order_code'] ?? '-' }}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-bold text-slate-900">{{ $order['customer_name'] ?? '-' }}</div>
                    <div class="text-[10px] text-slate-500 font-mono">{{ $order['customer_formatted_id'] ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</td>
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
                    $label = match($s) {
                        'waiting_payment' => 'Menunggu Bayar',
                        'payment_verification' => 'Verifikasi',
                        'paid' => 'Lunas',
                        'printing' => 'Sedang Cetak',
                        'ready' => 'Siap Ambil',
                        'completed' => 'Selesai',
                        default => ucfirst($s)
                    };
                    @endphp
                    <span class="{{ $badge }}">{{ $label }}</span>
                </td>

            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm">Belum ada pesanan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
