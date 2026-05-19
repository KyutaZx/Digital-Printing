@extends('layouts.app')

@section('title', 'Pembayaran — ' . ($order['order_code'] ?? 'Pesanan'))

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="/pesanan/{{ $order['id'] }}/upload-desain" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary-600 font-medium text-sm mb-4 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                <h1 class="text-3xl font-black text-slate-900 mb-2">Upload Bukti Pembayaran</h1>
                <p class="text-slate-500">Selesaikan pembayaran untuk memproses pesanan Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="card p-6 bg-primary-600 text-white rounded-2xl shadow-lg shadow-primary-200">
                <p class="text-primary-100 font-bold text-sm uppercase tracking-widest mb-1">Total Pembayaran</p>
                <h2 class="text-3xl font-black mb-4">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</h2>
                <div class="pt-4 border-t border-primary-500 flex justify-between items-center text-sm">
                    <span class="text-primary-100">Kode Pesanan</span>
                    <span class="font-mono font-bold">{{ $order['order_code'] ?? '-' }}</span>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-slate-900 mb-4">Informasi Rekening</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 font-bold mb-1">BCA</p>
                        <p class="font-mono font-bold text-lg text-slate-900">1234 567 890</p>
                        <p class="text-sm text-slate-600">A.N. Jaya Mandiri Printing</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 font-bold mb-1">Bank Mandiri</p>
                        <p class="font-mono font-bold text-lg text-slate-900">098 7654 321</p>
                        <p class="text-sm text-slate-600">A.N. Jaya Mandiri Printing</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-8 shadow-sm">
            <h3 class="font-bold text-xl text-slate-900 mb-6">Formulir Konfirmasi</h3>
            
            <form action="/pembayaran/{{ $order['id'] }}/upload" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="method_id" class="form-input bg-slate-50 border-slate-200 focus:bg-white" required>
                        <option value="1">Transfer BCA</option>
                        <option value="2">Transfer Mandiri</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Nominal Transfer</label>
                    <input type="number" name="amount" value="{{ $order['total_price'] }}" class="form-input bg-slate-50 border-slate-200 focus:bg-white font-bold" readonly required>
                    <p class="text-xs text-slate-500 mt-1">Nominal telah disesuaikan dengan total pesanan.</p>
                </div>
                
                <div class="hidden">
                    <label class="form-label">Kode Transaksi / Referensi</label>
                    <input type="text" name="transaction_code" value="AUTO-{{ $order['order_code'] ?? time() }}" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Bukti Transfer (Image/PDF)</label>
                    <div class="mt-2 flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-primary-300 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-sm">
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="mb-2 text-sm text-slate-600"><span class="font-bold text-primary-600">Klik untuk upload bukti</span> atau drag and drop</p>
                                <p class="text-xs text-slate-400 font-medium">PNG, JPG, atau PDF (Maks. 5MB)</p>
                            </div>
                            <input type="file" name="proof" class="hidden" required onchange="this.parentElement.querySelector('p').innerHTML = '<span class=\'font-bold text-primary-600\'>File terpilih:</span> ' + this.files[0].name" />
                        </label>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" class="w-full btn-primary py-4 text-base font-bold shadow-lg shadow-primary-200">
                        Kirim Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
