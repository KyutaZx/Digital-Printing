@extends('layouts.app')

@section('title', 'Tentang Kami — Jaya Mandiri')

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="card p-8 md:p-12 fade-in">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest mb-4 block">Siapa Kami</span>
            <h1 class="text-4xl font-black text-slate-900 mb-6 leading-tight">Membangun Kepercayaan Melalui Kualitas Cetak Terbaik</h1>
            
            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-6">
                <p>
                    <span class="font-bold text-slate-900">Jaya Mandiri Digital Printing</span> adalah solusi terpercaya untuk segala kebutuhan percetakan Anda. Berdiri dengan visi untuk memberikan kemudahan bagi masyarakat dan pelaku bisnis dalam mendapatkan layanan cetak berkualitas tinggi dengan proses yang transparan dan cepat.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-10">
                    <div class="bg-primary-50 p-6 rounded-2xl border border-primary-100">
                        <h3 class="font-bold text-primary-900 mb-2">Visi Kami</h3>
                        <p class="text-sm text-primary-800/80">Menjadi percetakan digital nomor satu yang mengintegrasikan teknologi terkini untuk kepuasan pelanggan maksimal.</p>
                    </div>
                    <div class="bg-secondary-50 p-6 rounded-2xl border border-secondary-100">
                        <h3 class="font-bold text-secondary-900 mb-2">Misi Kami</h3>
                        <p class="text-sm text-secondary-800/80">Memberikan layanan cetak yang akurat, cepat, dan terjangkau dengan dukungan tim profesional dan mesin teknologi terbaru.</p>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">Mengapa Memilih Kami?</h3>
                <ul class="space-y-3 list-none p-0">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span><span class="font-bold text-slate-800">Teknologi Modern:</span> Kami menggunakan mesin cetak generasi terbaru untuk hasil warna yang tajam dan tahan lama.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span><span class="font-bold text-slate-800">Proses Online:</span> Pesan, upload desain, dan bayar dari mana saja tanpa harus datang ke toko.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span><span class="font-bold text-slate-800">Tim Ahli:</span> Desain Anda akan diperiksa oleh staf ahli kami untuk memastikan hasil cetak sempurna.</span>
                    </li>
                </ul>

                <p class="pt-8 border-t border-slate-100 mt-10">
                    Kami percaya bahwa setiap cetakan memiliki cerita dan nilai tersendiri. Biarkan kami membantu Anda mewujudkan ide-ide brilian Anda menjadi kenyataan yang nyata.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
