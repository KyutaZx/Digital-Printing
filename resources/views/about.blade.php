@extends('layouts.app')

@section('title', 'Tentang Kami — Jaya Mandiri')

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            
            <!-- Left Side: Text Content -->
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm flex flex-col justify-center">
                <span class="text-blue-800 font-bold text-sm mb-4 block tracking-wide">Awal Mula Kami</span>
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-8 leading-tight tracking-tight">
                    Mimpi Kami adalah<br>Transformasi<br>Cetak Digital
                </h1>
                
                <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                    Jaya Mandiri didirikan dari sebuah mimpi besar, didorong oleh semangat untuk mempermudah akses layanan percetakan bagi semua kalangan. Visi bersama kami adalah menciptakan ekosistem digital yang efisien dan berkualitas tinggi. Dipersatukan oleh keyakinan akan kekuatan teknologi, kami memulai perjalanan membangun platform ini. Dengan dedikasi tanpa henti, kami mengumpulkan tim profesional dan menghadirkan inovasi, menciptakan komunitas pelanggan setia yang selalu menginginkan hasil cetak terbaik.
                </p>
            </div>

            <!-- Right Side: Image and Stats -->
            <div class="flex flex-col gap-6">
                
                <!-- Image Section -->
                <div class="relative bg-blue-200 rounded-3xl overflow-hidden h-64 md:h-80 flex items-end justify-center w-full shadow-sm">
                    <!-- Abstract Background Pattern (Simulated with CSS) -->
                    <div class="absolute inset-0 opacity-20">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="polygons" width="100" height="100" patternUnits="userSpaceOnUse">
                                    <polygon points="0,100 50,0 100,100" fill="#ffffff" opacity="0.3"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#polygons)" />
                        </svg>
                    </div>
                    
                    <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?q=80&w=1200&auto=format&fit=crop" 
                         alt="Tim Jaya Mandiri" 
                         class="absolute inset-0 w-full h-full object-cover" />
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-4 md:gap-6 bg-white p-6 rounded-3xl shadow-sm">
                    
                    <div class="bg-slate-50 rounded-2xl p-6 flex flex-col justify-center">
                        <h3 class="text-3xl font-bold text-slate-900 mb-1">3.5</h3>
                        <p class="text-xs text-slate-500 font-medium">Tahun Pengalaman</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-6 flex flex-col justify-center">
                        <h3 class="text-3xl font-bold text-slate-900 mb-1">23</h3>
                        <p class="text-xs text-slate-500 font-medium">Mesin Cetak</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-6 flex flex-col justify-center">
                        <h3 class="text-3xl font-bold text-slate-900 mb-1">830+</h3>
                        <p class="text-xs text-slate-500 font-medium">Ulasan Positif</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-6 flex flex-col justify-center">
                        <h3 class="text-3xl font-bold text-slate-900 mb-1">100K</h3>
                        <p class="text-xs text-slate-500 font-medium">Pelanggan Setia</p>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- FAQ React Mount Point -->
    <div id="about-faq-root"></div>
</div>

@push('scripts')
    @vite('resources/js/about-faq.jsx')
@endpush
@endsection
