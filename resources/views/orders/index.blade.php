@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya — Jaya Mandiri')

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-black text-slate-900 mb-8">Pesanan Saya</h1>

        <div class="space-y-6">
            @forelse($orders as $order)
            <div class="card p-6 fade-in hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Kode Pesanan</p>
                        <h3 class="text-lg font-black text-primary-600 font-mono">{{ $order['order_code'] ?? '-' }}</h3>
                    </div>
                    <div class="flex items-center gap-3">
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
                                'waiting_payment' => 'Menunggu Pembayaran',
                                'payment_verification' => 'Menunggu Verifikasi',
                                'paid' => 'Lunas',
                                'printing' => 'Sedang Dicetak',
                                'ready' => 'Siap Diambil',
                                'completed' => 'Selesai',
                                default => ucfirst($s)
                            };
                        @endphp
                        <span class="{{ $badge }} !px-4 !py-1.5 !text-xs font-bold">{{ $label }}</span>
                        <p class="text-xs text-slate-400">{{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y') : '-' }}</p>
                    </div>
                </div>

                <div class="flex gap-4 items-start mb-6">
                    <div class="flex-1 space-y-3">
                        @if(!empty($order['items']))
                            @foreach(array_slice($order['items'], 0, 1) as $item)
                            <div class="flex gap-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-lg shrink-0 overflow-hidden">
                                    @if(!empty($item['product_image']))
                                        <img src="{{ $item['product_image'] }}" alt="product" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 leading-tight">{{ $item['product_name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $item['quantity'] }} pcs • {{ $item['variant_name'] }}</p>
                                </div>
                            </div>
                            @endforeach
                            @if(count($order['items']) > 1)
                                <p class="text-xs text-slate-400 font-medium">+ {{ count($order['items']) - 1 }} item lainnya</p>
                            @endif
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 font-medium mb-1">Total Belanja</p>
                        <p class="font-black text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="/pesanan/{{ $order['id'] }}" class="btn-secondary text-xs !px-6 py-2.5">
                        Detail Pesanan
                    </a>
                    @if($s === 'waiting_payment')
                        <a href="/pesanan/{{ $order['id'] }}" class="btn-primary text-xs !px-6 py-2.5">
                            Bayar Sekarang
                        </a>
                    @elseif($s === 'ready')
                        <form action="/pesanan/{{ $order['id'] }}/selesai" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary text-xs !px-6 py-2.5 !bg-green-600 hover:!bg-green-700">
                                Selesaikan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="card py-24 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Belum Ada Pesanan</h2>
                <p class="text-slate-500 mb-8">Riwayat pesanan Anda akan muncul di sini.</p>
                <a href="/katalog" class="btn-primary px-8 py-3">Pesan Sekarang</a>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
