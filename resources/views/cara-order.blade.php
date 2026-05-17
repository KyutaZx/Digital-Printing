@extends('layouts.app')

@section('title', 'Cara Order — Jaya Mandiri')
@section('meta_description', 'Panduan lengkap cara memesan produk digital printing di Jaya Mandiri.')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16 fade-in">
            <span class="text-primary-600 font-bold uppercase tracking-widest text-sm mb-2 block">Panduan</span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">Cara Memesan</h1>
            <p class="text-slate-500 text-lg">Ikuti langkah-langkah mudah berikut untuk mencetak kebutuhan Anda bersama Jaya Mandiri.</p>
        </div>

        <div class="space-y-8 fade-in relative before:absolute before:inset-0 before:ml-5 md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-primary-200 before:to-primary-100">
            
            {{-- Step 1 --}}
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-bold text-lg shadow-lg shrink-0 z-10 md:absolute md:left-1/2 md:-translate-x-1/2 group-hover:scale-110 transition-transform">1</div>
                <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] card p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pilih Produk & Spesifikasi</h3>
                    <p class="text-sm text-slate-600">Jelajahi <a href="/katalog" class="text-primary-600 font-bold hover:underline">katalog kami</a>. Pilih produk yang Anda inginkan, tentukan ukuran, bahan, dan jumlah pesanan.</p>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-bold text-lg shadow-lg shrink-0 z-10 md:absolute md:left-1/2 md:-translate-x-1/2 group-hover:scale-110 transition-transform">2</div>
                <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] card p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Checkout & Login</h3>
                    <p class="text-sm text-slate-600">Masukkan produk ke keranjang belanja dan lanjutkan ke proses checkout. Anda diwajibkan login/daftar untuk memproses pesanan.</p>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-bold text-lg shadow-lg shrink-0 z-10 md:absolute md:left-1/2 md:-translate-x-1/2 group-hover:scale-110 transition-transform">3</div>
                <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] card p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Unggah Desain</h3>
                    <p class="text-sm text-slate-600">Setelah pesanan dibuat, buka halaman detail pesanan dan unggah file desain Anda (format PDF, JPG, PNG resolusi tinggi).</p>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-bold text-lg shadow-lg shrink-0 z-10 md:absolute md:left-1/2 md:-translate-x-1/2 group-hover:scale-110 transition-transform">4</div>
                <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] card p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pembayaran</h3>
                    <p class="text-sm text-slate-600">Transfer sesuai total tagihan ke rekening kami dan unggah bukti pembayarannya. Tim kami akan memverifikasi pesanan Anda.</p>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-secondary-500 text-white font-bold text-lg shadow-lg shrink-0 z-10 md:absolute md:left-1/2 md:-translate-x-1/2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] card p-6 border-secondary-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Produksi & Pengambilan</h3>
                    <p class="text-sm text-slate-600">Pantau status pesanan Anda. Jika status sudah <span class="badge badge-green">Selesai</span>, Anda bisa mengambilnya di toko kami atau menunggu pengiriman.</p>
                </div>
            </div>
            
        </div>

        {{-- FAQ --}}
        <div class="mt-20 fade-in">
            <h2 class="text-2xl font-black text-slate-900 mb-6 text-center">Pertanyaan yang Sering Diajukan</h2>
            <div class="space-y-4" x-data="{ active: null }">
                
                <div class="card overflow-hidden">
                    <button @click="active = active === 1 ? null : 1" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                        Berapa lama proses pengerjaan?
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 1" x-collapse style="display: none;" class="px-6 pb-4 text-sm text-slate-600">
                        Waktu standar produksi kami adalah 1-2 hari kerja setelah desain disetujui dan pembayaran dikonfirmasi. Untuk pesanan dalam jumlah besar, waktu estimasi akan dinformasikan lebih lanjut.
                    </div>
                </div>

                <div class="card overflow-hidden">
                    <button @click="active = active === 2 ? null : 2" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-800 hover:bg-slate-50 transition-colors">
                        Format desain apa yang diterima?
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 2" x-collapse style="display: none;" class="px-6 pb-4 text-sm text-slate-600">
                        Kami sangat menyarankan file berformat PDF atau CorelDraw (.cdr). Kami juga menerima format gambar (JPG/PNG) asalkan beresolusi tinggi (minimal 300dpi) agar hasil cetak tidak pecah.
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
