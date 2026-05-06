@extends('layouts.app')

@section('title', 'Detail Pesanan ' . ($order['order_code'] ?? '') . ' — Jaya Mandiri')

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <a href="/pesanan" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary-600 font-medium text-sm mb-4 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Riwayat Pesanan
                </a>
                <div class="flex items-center gap-4">
                    <h1 class="text-3xl font-black text-slate-900">{{ $order['order_code'] ?? '-' }}</h1>
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
                    <span class="{{ $badge }} font-bold">{{ $label }}</span>
                </div>
            </div>
            
            <div class="flex gap-3">
                <a href="/pesanan/{{ $order['id'] }}/invoice/pdf" class="btn-secondary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Invoice (PDF)
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Info Pesanan --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Item List --}}
                <div class="card overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="font-bold text-slate-900 text-sm uppercase tracking-widest">Daftar Produk</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($order['items'] as $item)
                        <div class="p-6 flex flex-col md:flex-row gap-6">
                            <div class="w-20 h-20 bg-slate-50 rounded-xl overflow-hidden shrink-0 border border-slate-100">
                                @if(!empty($item['product_image']))
                                    <img src="{{ $item['product_image'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row justify-between md:items-start gap-2">
                                    <div>
                                        <h3 class="font-bold text-slate-900">{{ $item['product_name'] }}</h3>
                                        <p class="text-xs text-slate-500 mt-1">{{ $item['variant_name'] }} • Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                    <p class="font-black text-slate-900">Rp {{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                                </div>
                                
                                {{-- Design Upload Area --}}
                                @if($s !== 'waiting_payment')
                                <div class="mt-4 pt-4 border-t border-slate-50">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">File Desain Cetak</p>
                                    
                                    @if(!empty($item['designs']))
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach($item['designs'] as $design)
                                            <a href="{{ $design['file_path'] }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">
                                                <svg class="w-3 h-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                <span class="text-[10px] font-bold text-slate-600">VERSI {{ $design['version'] }}</span>
                                            </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($s === 'paid' || $s === 'payment_verification')
                                    <form action="/desain/{{ $item['id'] }}/upload" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <input type="file" name="file" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                        <button type="submit" class="text-xs font-bold text-primary-600 hover:underline">Upload Baru</button>
                                    </form>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Payment Info --}}
                <div class="card p-6">
                    <h2 class="font-bold text-slate-900 mb-6">Informasi Pembayaran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Subtotal</span>
                                    <span class="font-semibold text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Biaya Layanan</span>
                                    <span class="font-semibold text-green-600">Gratis</span>
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex justify-between">
                                    <span class="font-bold text-slate-900">Total Akhir</span>
                                    <span class="font-black text-primary-600 text-lg">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            @if(!empty($order['payment']))
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Pembayaran</p>
                                    <span class="badge {{ $order['payment']['payment_status'] === 'success' ? 'badge-green' : 'badge-yellow' }} !text-[10px]">
                                        {{ strtoupper($order['payment']['payment_status']) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 mb-1">Metode: <span class="font-bold text-slate-900">{{ $order['payment']['method_name'] ?? '-' }}</span></p>
                                <p class="text-xs text-slate-600">Kode: <span class="font-mono font-bold text-slate-900">{{ $order['payment']['transaction_code'] ?? '-' }}</span></p>
                            @else
                                <div class="text-center py-2">
                                    <p class="text-xs text-slate-500 italic">Belum ada rincian pembayaran</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar Status --}}
            <div class="space-y-6">
                
                {{-- Payment Upload --}}
                @if($s === 'waiting_payment')
                <div class="card p-6 border-2 border-primary-100 shadow-lg shadow-primary-50">
                    <h3 class="font-bold text-slate-900 mb-4">Upload Bukti Bayar</h3>
                    <form action="/pembayaran/{{ $order['id'] }}/upload" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label !text-xs">Metode Pembayaran</label>
                            <select name="method_id" class="form-input !text-xs" required>
                                <option value="1">BCA - 123456789 (A/N Jaya Mandiri)</option>
                                <option value="2">Mandiri - 987654321 (A/N Jaya Mandiri)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label !text-xs">Nominal Transfer</label>
                            <input type="number" name="amount" value="{{ $order['total_price'] }}" class="form-input !text-xs" required>
                        </div>
                        <div>
                            <label class="form-label !text-xs">Kode Transaksi / Referensi</label>
                            <input type="text" name="transaction_code" placeholder="Misal: TRF123456" class="form-input !text-xs" required>
                        </div>
                        <div>
                            <label class="form-label !text-xs">Bukti Transfer (Image/PDF)</label>
                            <input type="file" name="proof" class="text-[10px] w-full" required>
                        </div>
                        <button type="submit" class="w-full btn-primary py-3 text-sm">
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
                @endif

                {{-- Status Timeline --}}
                <div class="card p-6">
                    <h3 class="font-bold text-slate-900 mb-6">Status Pesanan</h3>
                    <div class="space-y-8 relative">
                        <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                        
                        @php
                            $steps = [
                                ['id' => 'waiting_payment', 'label' => 'Pesanan Dibuat', 'desc' => 'Menunggu pembayaran dari Anda'],
                                ['id' => 'payment_verification', 'label' => 'Verifikasi', 'desc' => 'Staf kami sedang memeriksa pembayaran'],
                                ['id' => 'paid', 'label' => 'Lunas & Review', 'desc' => 'Desain sedang diperiksa staf'],
                                ['id' => 'printing', 'label' => 'Produksi', 'desc' => 'Pesanan sedang dalam proses cetak'],
                                ['id' => 'ready', 'label' => 'Siap Ambil', 'desc' => 'Pesanan Anda sudah selesai dicetak'],
                                ['id' => 'completed', 'label' => 'Selesai', 'desc' => 'Pesanan telah diterima'],
                            ];
                            
                            $currentIdx = collect($steps)->search(fn($step) => $step['id'] === $s);
                        @endphp

                        @foreach($steps as $idx => $step)
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300
                                {{ $idx <= $currentIdx ? 'bg-primary-600 border-primary-600 text-white' : 'bg-white border-slate-200 text-slate-300' }}">
                                @if($idx < $currentIdx)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <span class="text-[10px] font-black">{{ $idx + 1 }}</span>
                                @endif
                            </div>
                            <h4 class="font-bold text-sm {{ $idx <= $currentIdx ? 'text-slate-900' : 'text-slate-400' }}">{{ $step['label'] }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $step['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
