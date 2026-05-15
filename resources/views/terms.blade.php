@extends('layouts.app')

@section('title', 'Syarat & Ketentuan — Jaya Mandiri')
@section('meta_description', 'Syarat dan ketentuan layanan Jaya Mandiri Digital Printing.')

@section('content')
<div class="pt-24 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-8 md:p-12 fade-in">
            <h1 class="text-3xl font-black text-slate-900 mb-8 pb-4 border-b border-slate-100">Syarat & Ketentuan Layanan</h1>
            
            <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-600 space-y-6">
                <p>Selamat datang di Jaya Mandiri Digital Printing. Dengan menggunakan layanan kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di bawah ini.</p>
                
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">1. Pemesanan & Desain</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Semua file desain yang diunggah harus sesuai dengan spesifikasi ukuran dan siap cetak (ready to print).</li>
                        <li>Kami tidak bertanggung jawab atas hasil cetak yang kurang maksimal (pecah/blur) akibat resolusi file desain yang rendah.</li>
                        <li>Perbedaan warna antara monitor dan hasil cetak (sekitar 10-15%) adalah hal wajar karena perbedaan profil warna (RGB ke CMYK).</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">2. Pembayaran</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Pesanan hanya akan diproses setelah pembayaran lunas dikonfirmasi oleh tim kami.</li>
                        <li>Bukti transfer palsu akan kami proses ke pihak berwajib.</li>
                        <li>Pesanan yang telah dibayar tidak dapat dibatalkan secara sepihak oleh pelanggan kecuali terdapat kesalahan dari pihak kami.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">3. Waktu Pengerjaan & Pengambilan</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Waktu pengerjaan standar dihitung sejak desain dinyatakan ACC (layak cetak) dan pembayaran dikonfirmasi.</li>
                        <li>Keterlambatan penyelesaian pesanan karena force majeure (bencana alam, pemadaman listrik, kerusakan mesin) tidak dapat dituntut ganti rugi.</li>
                        <li>Barang pesanan harap diambil maksimal 30 hari setelah dinyatakan selesai. Lebih dari itu, kerusakan atau kehilangan bukan tanggung jawab kami.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">4. Garansi & Komplain</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Komplain diterima maksimal 1x24 jam setelah pesanan diterima oleh pelanggan.</li>
                        <li>Garansi cetak ulang berlaku hanya apabila terdapat cacat produksi murni dari kesalahan mesin/operator kami (misal: tulisan terpotong, warna belang yang parah).</li>
                    </ul>
                </div>

                <p class="mt-8 text-xs text-slate-400 border-t border-slate-100 pt-4">Terakhir diperbarui: {{ date('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
