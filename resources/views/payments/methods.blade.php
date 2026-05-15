@extends('layouts.app')

@section('title', 'Metode Pembayaran — Jaya Mandiri')
@section('meta_description', 'Pilihan metode pembayaran untuk pemesanan digital printing di Jaya Mandiri.')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12 fade-in">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Metode Pembayaran</h1>
            <p class="text-slate-500">Silakan lakukan pembayaran melalui salah satu metode di bawah ini agar pesanan Anda dapat segera kami proses.</p>
        </div>

        @if(empty($methods))
            <div class="card p-12 flex flex-col items-center justify-center text-center fade-in">
                <svg class="w-20 h-20 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Metode Pembayaran Kosong</h3>
                <p class="text-slate-500 max-w-md mx-auto">Saat ini belum ada metode pembayaran yang tersedia. Silakan hubungi admin kami.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in">
                @foreach($methods as $method)
                <div class="card p-6 flex flex-col items-center text-center transition-all hover:shadow-md">
                    <div class="w-16 h-16 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mb-4">
                        @if(strtolower($method['type'] ?? '') == 'bank')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <span class="badge badge-blue mb-3 uppercase tracking-wider">{{ $method['type'] ?? 'Transfer' }}</span>
                    <h3 class="text-xl font-black text-slate-900 mb-1">{{ $method['name'] ?? 'Nama Bank' }}</h3>
                    <p class="text-2xl font-bold text-primary-600 mb-1 font-mono tracking-wide cursor-pointer hover:text-primary-700 transition-colors" x-data @click="navigator.clipboard.writeText('{{ $method['account_number'] ?? '' }}'); alert('Nomor disalin!')" title="Klik untuk menyalin">
                        {{ $method['account_number'] ?? '-' }}
                    </p>
                    <p class="text-sm font-semibold text-slate-500 uppercase">A.N. {{ $method['account_name'] ?? '-' }}</p>
                </div>
                @endforeach
            </div>
        @endif

        <div class="mt-12 card p-8 bg-white fade-in">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Instruksi Pembayaran
            </h3>
            <ol class="list-decimal list-inside space-y-3 text-slate-600 text-sm">
                <li>Lakukan transfer sesuai dengan <strong>Total Pembayaran</strong> hingga 3 digit terakhir untuk memudahkan verifikasi.</li>
                <li>Simpan bukti transfer / struk pembayaran Anda.</li>
                <li>Buka halaman <a href="/pesanan" class="text-primary-600 font-bold hover:underline">Pesanan Saya</a>, pilih pesanan yang baru saja Anda buat.</li>
                <li>Unggah bukti pembayaran pada kolom yang disediakan.</li>
                <li>Tim kami akan memverifikasi pembayaran Anda dalam waktu maksimal 1x24 jam kerja.</li>
            </ol>
        </div>

    </div>
</div>
@endsection
