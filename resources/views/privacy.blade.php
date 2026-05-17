@extends('layouts.app')

@section('title', 'Kebijakan Privasi — Jaya Mandiri')
@section('meta_description', 'Kebijakan Privasi Jaya Mandiri Digital Printing.')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-8 md:p-12 fade-in">
            <h1 class="text-3xl font-black text-slate-900 mb-8 pb-4 border-b border-slate-100">Kebijakan Privasi</h1>
            
            <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-600 space-y-6">
                <p>Jaya Mandiri Digital Printing menghargai privasi Anda. Halaman ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi dan file desain Anda.</p>
                
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Pengumpulan Data</h3>
                    <p>Kami mengumpulkan informasi dari Anda saat Anda mendaftar di situs kami, melakukan pemesanan, atau mengisi form kontak. Informasi tersebut meliputi nama, alamat email, nomor telepon, dan alamat (jika diperlukan untuk pengiriman).</p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Penggunaan File Desain</h3>
                    <p>File desain yang Anda unggah murni digunakan HANYA untuk keperluan pencetakan pesanan Anda. Kami menjamin:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Tidak mempublikasikan desain Anda tanpa izin tertulis.</li>
                        <li>Tidak memperjualbelikan file desain Anda ke pihak ketiga.</li>
                        <li>Kami berhak menghapus file desain dari server kami secara berkala setelah pesanan selesai untuk menghemat penyimpanan.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Keamanan Data</h3>
                    <p>Kami menerapkan berbagai langkah keamanan untuk menjaga keamanan informasi pribadi Anda. Namun, perlu dipahami bahwa tidak ada metode transmisi data melalui internet atau penyimpanan elektronik yang 100% aman.</p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Persetujuan Anda</h3>
                    <p>Dengan menggunakan situs web kami, Anda menyetujui kebijakan privasi kami. Jika kami memutuskan untuk mengubah kebijakan privasi kami, kami akan memposting perubahan tersebut di halaman ini.</p>
                </div>

                <p class="mt-8 text-xs text-slate-400 border-t border-slate-100 pt-4">Terakhir diperbarui: {{ date('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
