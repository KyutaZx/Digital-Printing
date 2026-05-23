@extends('layouts.app')

@section('title', 'Cara Order — Jaya Mandiri')
@section('meta_description', 'Panduan lengkap cara memesan produk digital printing di Jaya Mandiri.')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16 fade-in">
            <span class="text-primary-600 font-bold uppercase tracking-widest text-sm mb-2 block">Panduan</span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">Cara Memesan</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">Ikuti 6 langkah berikut untuk memesan cetak online di Jaya Mandiri — dari pilih produk hingga barang siap diambil.</p>
        </div>

        <div class="space-y-6 fade-in">
            @php
                $steps = [
                    ['title' => 'Pilih Produk & Checkout', 'desc' => 'Jelajahi <a href="/katalog" class="text-primary-600 font-bold hover:underline">katalog</a>, pilih ukuran/bahan/jumlah, masukkan ke keranjang, lalu checkout. Login atau daftar akun jika belum punya.'],
                    ['title' => 'Unggah Desain', 'desc' => 'Upload file desain untuk <strong>setiap item</strong> pesanan (PDF, JPG, PNG, AI, PSD, atau CDR). Langkah ini wajib diselesaikan sebelum pembayaran.'],
                    ['title' => 'Pembayaran & Verifikasi', 'desc' => 'Transfer sesuai total tagihan, lalu unggah bukti pembayaran. Tim kami memverifikasi transfer Anda (biasanya 1×24 jam kerja). Jika ditolak, Anda dapat mengunggah ulang bukti tanpa mengulang dari awal.'],
                    ['title' => 'Verifikasi Desain', 'desc' => 'Setelah pembayaran lunas, tim kami meninjau kualitas desain. Jika perlu revisi, Anda akan mendapat catatan dan dapat mengunggah ulang file yang sudah diperbaiki.'],
                    ['title' => 'Produksi', 'desc' => 'Semua desain disetujui → pesanan masuk antrian cetak. Pantau status di halaman <a href="/pesanan" class="text-primary-600 font-bold hover:underline">Pesanan Saya</a>.'],
                    ['title' => 'Siap Ambil & Selesai', 'desc' => 'Saat status <span class="font-bold text-emerald-700">Siap Diambil</span>, datang ke toko atau tunggu pengiriman (jika tersedia). Konfirmasi <span class="font-bold text-emerald-700">Selesai</span> setelah barang Anda terima.'],
                ];
            @endphp

            @foreach($steps as $i => $step)
            <div class="card p-6 flex gap-5 items-start">
                <div class="w-11 h-11 rounded-xl bg-primary-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-md shadow-primary-200">
                    {{ $i + 1 }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{!! $step['desc'] !!}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center fade-in">
            <a href="/katalog"
               class="inline-flex items-center gap-2 px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-primary-600/25 transition-all">
                Mulai Pesan Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- FAQ --}}
        <div class="mt-20 fade-in">
            <h2 class="text-2xl font-black text-slate-900 mb-6 text-center">Pertanyaan yang Sering Diajukan</h2>
            <div class="space-y-4" x-data="{ active: null }">

                <div class="card overflow-hidden">
                    <button type="button" @click="active = active === 1 ? null : 1" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                        Apakah bisa bayar sebelum upload desain?
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 1" x-collapse style="display: none;" class="px-6 pb-4 text-sm text-slate-600">
                        Tidak. Semua item pesanan harus memiliki file desain terlebih dahulu, baru sistem mengizinkan unggah bukti pembayaran.
                    </div>
                </div>

                <div class="card overflow-hidden">
                    <button type="button" @click="active = active === 2 ? null : 2" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                        Berapa lama proses pengerjaan?
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 2" x-collapse style="display: none;" class="px-6 pb-4 text-sm text-slate-600">
                        Estimasi 1–2 hari kerja setelah pembayaran lunas dan semua desain disetujui. Pesanan besar dapat memakan waktu lebih lama; estimasi akan diinformasikan di detail pesanan.
                    </div>
                </div>

                <div class="card overflow-hidden">
                    <button type="button" @click="active = active === 3 ? null : 3" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                        Format desain apa yang diterima?
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 3" x-collapse style="display: none;" class="px-6 pb-4 text-sm text-slate-600">
                        PDF, JPG, PNG, AI, PSD, dan CDR. Untuk file gambar, gunakan resolusi tinggi (disarankan 300 dpi) agar hasil cetak tajam.
                    </div>
                </div>

            </div>
        </div>

        <p class="mt-10 text-center text-sm text-slate-500">
            Butuh bantuan? <a href="/#kontak" class="text-primary-600 font-bold hover:underline">Hubungi kami via WhatsApp atau email</a>.
        </p>

    </div>
</div>
@endsection
