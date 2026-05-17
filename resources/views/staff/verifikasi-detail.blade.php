@extends('layouts.staff')
@section('title', 'Detail Verifikasi #' . ($order['order_code'] ?? ''))
@section('page_title', 'Detail Verifikasi Pembayaran')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kiri: Detail Pesanan --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Header Pesanan --}}
        <div class="card p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-black text-slate-900">{{ $order['order_code'] ?? '-' }}</h2>
                    <p class="text-sm text-slate-500">Customer: <span class="font-semibold text-slate-700">{{ $order['customer_name'] ?? '-' }}</span></p>
                </div>
                <span class="badge badge-yellow">{{ $order['status'] ?? '' }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-slate-400 text-xs font-semibold uppercase mb-1">Total Harga</p>
                    <p class="font-black text-xl text-primary-600">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</p></div>
                <div><p class="text-slate-400 text-xs font-semibold uppercase mb-1">Tanggal Order</p>
                    <p class="font-semibold text-slate-800">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}</p></div>
            </div>
        </div>

        {{-- Item Pesanan --}}
        @if(!empty($order['items']))
        <div class="card">
            <div class="px-6 py-4 border-b border-slate-100"><h3 class="font-bold text-slate-900">Item Pesanan</h3></div>
            <div class="divide-y divide-slate-100">
            @foreach($order['items'] as $item)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">{{ $item['product_name'] ?? '-' }}</p>
                    <p class="text-xs text-slate-500">{{ $item['variant_name'] ?? '' }} • Qty: {{ $item['quantity'] }}</p>
                    @if($item['notes'] ?? '') <p class="text-xs text-slate-400 italic mt-1">"{{ $item['notes'] }}"</p>@endif
                </div>
                <p class="font-bold text-slate-900 text-sm">Rp {{ number_format($item['subtotal'] ?? ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
            </div>
            @endforeach
            </div>
        </div>
        @endif

        {{-- Bukti Pembayaran --}}
        @if(!empty($order['payment']) && !empty($order['payment']['payment_proof']))
        <div class="card p-6">
            <h3 class="font-bold text-slate-900 mb-4">Bukti Pembayaran</h3>
            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                <div><p class="text-xs text-slate-400 font-semibold uppercase mb-1">Kode Transaksi</p>
                    <p class="font-mono font-bold text-slate-900">{{ $order['payment']['transaction_code'] ?? '-' }}</p></div>
                <div><p class="text-xs text-slate-400 font-semibold uppercase mb-1">Jumlah Transfer</p>
                    <p class="font-black text-green-600">Rp {{ number_format($order['payment']['amount'] ?? 0, 0, ',', '.') }}</p></div>
            </div>
            <div class="rounded-xl overflow-hidden border border-slate-200">
                <img src="{{ config('app.golang_api_url') }}{{ $order['payment']['payment_proof'] }}" alt="Bukti Bayar"
                     class="w-full max-h-80 object-contain bg-slate-50">
            </div>
        </div>
        @endif
    </div>

    {{-- Kanan: Aksi --}}
    <div class="space-y-4">
        <div class="card p-6">
            <h3 class="font-bold text-slate-900 mb-4">Tindakan Verifikasi</h3>

            @if(($order['payment']['payment_status'] ?? '') === 'pending')

            {{-- Approve --}}
            <form method="POST" action="/staff/pembayaran/{{ $order['payment']['id'] ?? 0 }}/setujui"
                  onsubmit="return confirm('Setujui pembayaran ini? Pesanan akan otomatis diproses.')"
                  class="mb-3">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Setujui Pembayaran
                </button>
            </form>

            {{-- Reject --}}
            <form method="POST" action="/staff/pembayaran/{{ $order['payment']['id'] ?? 0 }}/tolak"
                  onsubmit="return confirm('Tolak pembayaran ini? Customer dapat upload ulang bukti.')">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition-all text-sm border border-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Pembayaran
                </button>
            </form>

            @else
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                <p class="text-sm font-semibold text-slate-600">Pembayaran sudah diproses</p>
                <span class="badge badge-green mt-2">{{ $order['payment']['payment_status'] ?? '' }}</span>
            </div>
            @endif
        </div>

        <a href="/staff/verifikasi" class="btn-secondary w-full justify-center text-sm">
            ← Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
