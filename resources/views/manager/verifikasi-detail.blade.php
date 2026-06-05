@extends('layouts.manager')

@section('title', 'Detail Verifikasi #' . ($order['order_code'] ?? ''))
@section('page_title', 'Detail Verifikasi Pembayaran')

@section('content')
<div class="space-y-6 fade-in pb-8">

    @include('manager.partials.flash')

    <div class="flex items-center gap-3">
        <a href="/manager/verifikasi" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-primary-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kiri: Detail --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Pesanan</p>
                        <h2 class="text-2xl font-black text-slate-900 font-mono mt-1">{{ $order['order_code'] ?? '-' }}</h2>
                        <p class="text-sm text-slate-500 mt-2">Pelanggan: <span class="font-bold text-slate-800">{{ $order['customer_name'] ?? '-' }}</span></p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 text-[10px] font-black uppercase ring-1 ring-amber-100 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ $order['status'] ?? 'Pending' }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Harga</p>
                        <p class="font-black text-2xl text-primary-600 mt-1">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Order</p>
                        <p class="font-bold text-slate-900 mt-1">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            @if(!empty($order['items']))
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-sm">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($order['items'] as $item)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50">
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $item['product_name'] ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $item['variant_name'] ?? '' }} · Qty {{ $item['quantity'] }}</p>
                            @if($item['notes'] ?? '') <p class="text-[10px] text-slate-400 italic mt-1">"{{ $item['notes'] }}"</p>@endif
                        </div>
                        <p class="font-black text-slate-900 text-sm">Rp {{ number_format($item['subtotal'] ?? ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @php $proof = $order['payment']['payment_proof'] ?? $order['payment_proof'] ?? ''; @endphp
            @if(!empty($order['payment']) && !empty($proof))
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-black text-slate-900 text-sm mb-4">Bukti Pembayaran</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Transaksi</p>
                        <p class="font-mono font-bold text-slate-900 mt-1">{{ $order['payment']['transaction_code'] ?? '-' }}</p>
                    </div>
                    <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Jumlah Transfer</p>
                        <p class="font-black text-emerald-700 mt-1">Rp {{ number_format($order['payment']['amount'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="rounded-2xl">
                    <img src="/api-proxy/{{ ltrim($proof, '/') }}" alt="Bukti Bayar"
                         class="w-full rounded-xl shadow-sm border border-slate-100 cursor-pointer hover:opacity-90 transition-opacity"
                         onclick="window.open(this.src, '_blank')">
                </div>
            </div>
            @endif
        </div>

        {{-- Kanan: Aksi --}}
        <div class="space-y-4">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-6">
                <h3 class="font-black text-slate-900 text-sm mb-4">Tindakan Verifikasi</h3>

                @if(($order['payment']['payment_status'] ?? '') === 'pending')
                <form method="POST" action="/manager/pembayaran/{{ $order['payment']['id'] ?? 0 }}/setujui"
                      onsubmit="return confirm('Setujui pembayaran ini? Pesanan akan otomatis diproses.')"
                      class="mb-3">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui Pembayaran
                    </button>
                </form>
                <form method="POST" action="/manager/pembayaran/{{ $order['payment']['id'] ?? 0 }}/tolak"
                      onsubmit="return confirm('Tolak pembayaran ini? Customer dapat upload ulang bukti.')">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-2xl transition-all text-sm border border-red-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak Pembayaran
                    </button>
                </form>
                @else
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-center">
                    <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-bold text-emerald-800">Pembayaran sudah diproses</p>
                    <span class="inline-flex mt-2 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">{{ $order['payment']['payment_status'] ?? '' }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
