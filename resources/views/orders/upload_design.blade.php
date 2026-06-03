@extends('layouts.app')

@section('title', 'Upload Desain — ' . ($order['order_code'] ?? 'Pesanan'))

@section('content')
<div class="relative min-h-screen w-full">
    {{-- Animated White and Dark Blue Background --}}
    <style>
        @keyframes blob-bounce-1 {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }
        @keyframes blob-bounce-2 {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-40px, 40px) scale(0.95); }
            66% { transform: translate(30px, -30px) scale(1.05); }
            100% { transform: translate(0, 0) scale(1); }
        }
        @keyframes blob-bounce-3 {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, 30px) scale(1.05); }
            66% { transform: translate(-30px, -40px) scale(0.95); }
            100% { transform: translate(0, 0) scale(1); }
        }
    </style>
    <div class="fixed inset-0 -z-10 bg-slate-50 overflow-hidden">
        <div class="absolute top-0 left-0 w-[800px] h-[800px] rounded-full opacity-40 blur-[120px]" 
             style="background: radial-gradient(circle, rgba(30,58,138,0.7), transparent 60%); animation: blob-bounce-1 15s infinite alternate ease-in-out;"></div>
        
        <div class="absolute top-[20%] right-[-10%] w-[700px] h-[700px] rounded-full opacity-30 blur-[100px]" 
             style="background: radial-gradient(circle, rgba(29,78,216,0.6), transparent 60%); animation: blob-bounce-2 18s infinite alternate ease-in-out;"></div>
        
        <div class="absolute bottom-[-10%] left-[10%] w-[900px] h-[900px] rounded-full opacity-35 blur-[130px]" 
             style="background: radial-gradient(circle, rgba(37,99,235,0.5), transparent 60%); animation: blob-bounce-3 20s infinite alternate ease-in-out;"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 pb-16 fade-in relative z-10">

        <div>
            <a href="/pesanan/{{ $order['id'] }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white/90 backdrop-blur-md border border-slate-200 shadow-sm hover:bg-white hover:text-primary-600 px-3 py-1.5 rounded-lg mb-4 transition-colors w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke detail pesanan
            </a>
            <h1 class="text-3xl sm:text-[40px] font-black text-slate-900 leading-[1.1] tracking-[-1.5px] sm:tracking-[-2.4px]">Upload desain cetak</h1>
            <p class="text-sm text-slate-500 mt-2">
                Pesanan <span class="font-mono font-bold text-primary-600">{{ $order['order_code'] ?? '-' }}</span>
                — pastikan gambar tidak pecah/blur
            </p>
        </div>

    @php
        $allUploadedOrApproved = true;
        $hasRevision = false;
        foreach ($order['items'] as $item) {
            $designs = $item['designs'] ?? [];
            if (empty($designs)) {
                $allUploadedOrApproved = false;
            } else {
                $latest = $designs[count($designs) - 1];
                $latestStatus = $latest['status'] ?? '';
                if ($latestStatus === 'revision_requested') {
                    $hasRevision = true;
                    $allUploadedOrApproved = false;
                }
            }
        }
        $orderStatus = $order['status'] ?? '';
        $alreadyPaid = !in_array($orderStatus, ['waiting_payment']);
    @endphp

    @if($hasRevision)
    <div class="mt-6 flex items-start gap-3 bg-amber-50 border border-amber-300 rounded-[14px] px-4 py-4">
        <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <div>
            <p class="font-bold text-amber-800 text-sm">Desain perlu direvisi</p>
            <p class="text-xs text-amber-700 mt-0.5">Staff kami meminta revisi untuk beberapa item. Silakan periksa catatan di bawah dan upload ulang desain Anda.</p>
        </div>
    </div>
    @endif

    {{-- Alur Pesanan Horizontal --}}
    <div class="mt-6 bg-white rounded-[14px] border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 sm:gap-4 relative">
            <div class="hidden sm:block absolute top-4 left-[10%] right-[10%] h-[2px] bg-slate-100 -z-0"></div>
            
            {{-- Step 1 --}}
            <div class="flex flex-col items-center relative z-10 bg-white px-2 sm:px-4 w-full sm:w-1/4">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white shrink-0 mb-2 sm:mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="font-bold text-sm text-slate-900 text-center">Pilih produk</p>
                <p class="text-xs text-slate-500 text-center mt-0.5">Selesai</p>
            </div>
            
            {{-- Step 2 --}}
            <div class="flex flex-col items-center relative z-10 bg-white px-2 sm:px-4 w-full sm:w-1/4">
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white shrink-0 mb-2 sm:mb-3">
                    <span class="text-sm font-bold">2</span>
                </div>
                <p class="font-bold text-sm text-slate-900 text-center">Upload desain</p>
                <p class="text-xs text-slate-500 text-center mt-0.5">Sedang berlangsung</p>
            </div>
            
            {{-- Step 3 --}}
            <div class="flex flex-col items-center relative z-10 bg-white px-2 sm:px-4 w-full sm:w-1/4 opacity-50">
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 shrink-0 mb-2 sm:mb-3">
                    <span class="text-sm font-bold">3</span>
                </div>
                <p class="font-bold text-sm text-slate-900 text-center">Pembayaran</p>
                <p class="text-xs text-slate-500 text-center mt-0.5">Menunggu</p>
            </div>
            
            {{-- Step 4 --}}
            <div class="flex flex-col items-center relative z-10 bg-white px-2 sm:px-4 w-full sm:w-1/4 opacity-50">
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 shrink-0 mb-2 sm:mb-3">
                    <span class="text-sm font-bold">4</span>
                </div>
                <p class="font-bold text-sm text-slate-900 text-center">Produksi</p>
                <p class="text-xs text-slate-500 text-center mt-0.5">Menunggu</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 mt-8 items-start">
        {{-- Main Upload Panel (Left) --}}
        <div class="w-full lg:flex-1 space-y-6">
            @foreach($order['items'] as $item)
            @php
                $itemDesigns = $item['designs'] ?? [];
                $totalDesigns = count($itemDesigns);
                $hasDesign   = $totalDesigns > 0;
                $latestDesign = $hasDesign ? $itemDesigns[$totalDesigns - 1] : null;
                $latestStatus = $latestDesign['status'] ?? '';
                $latestNotes  = $latestDesign['notes'] ?? '';
                $latestVersion = $latestDesign['version'] ?? 0;
                $canReupload  = !$hasDesign || $latestStatus === 'revision_requested';
                $isApproved   = $latestStatus === 'approved';
            @endphp

            <div class="bg-white rounded-[14px] border border-slate-200 overflow-hidden relative">
                {{-- Thin 4-step progress bar --}}
                <div class="absolute top-0 left-0 w-full h-1 flex">
                    @if($isApproved)
                        <div class="flex-1 bg-green-500"></div><div class="flex-1 bg-green-500"></div><div class="flex-1 bg-green-500"></div><div class="flex-1 bg-slate-200"></div>
                    @elseif($hasDesign && !$canReupload)
                        <div class="flex-1 bg-green-500"></div><div class="flex-1 bg-primary-600"></div><div class="flex-1 bg-slate-200"></div><div class="flex-1 bg-slate-200"></div>
                    @else
                        <div class="flex-1 bg-green-500"></div><div class="flex-1 bg-primary-600"></div><div class="flex-1 bg-slate-200"></div><div class="flex-1 bg-slate-200"></div>
                    @endif
                </div>

                <div class="p-6 pt-7 lg:p-8">
                    {{-- Product Row --}}
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-white rounded-xl border border-slate-200 overflow-hidden shrink-0">
                            @if(!empty($item['product_image']))
                                <img src="{{ config('app.golang_api_url') }}{{ $item['product_image'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col items-start gap-1">
                            <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $item['product_name'] ?? '-' }}</h3>
                            <div>
                                @if($isApproved)
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded bg-green-100 text-green-700">Disetujui</span>
                                @elseif($latestStatus === 'revision_requested')
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700">Perlu revisi</span>
                                @elseif($hasDesign)
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700">Menunggu review</span>
                                @else
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700">Belum upload</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item['variant_name'] ?? '' }} &bull; Qty: {{ $item['quantity'] ?? 1 }}</p>
                        </div>
                    </div>

                    <hr class="my-5 border-slate-100">

                    {{-- Upload Area / Status --}}
                    @if($hasDesign && !$canReupload)
                        <div class="flex items-start gap-4">
                            @php $ext = strtolower(pathinfo($latestDesign['file_path'] ?? '', PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg','jpeg','png']))
                                <a href="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" target="_blank"
                                   class="shrink-0 block w-20 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 hover:opacity-90 transition-opacity">
                                    <img src="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" alt="Desain" class="w-full h-full object-contain p-1">
                                </a>
                            @else
                                <a href="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" target="_blank"
                                   class="shrink-0 flex flex-col items-center justify-center w-20 h-20 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                                    <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $ext }}</span>
                                </a>
                            @endif
                            <div class="flex-1 min-w-0">
                                @if($isApproved)
                                    <p class="text-sm font-bold text-green-700 mb-1">Desain disetujui</p>
                                    <p class="text-xs text-slate-500">{{ $latestNotes ?: 'Menunggu proses produksi.' }}</p>
                                @else
                                    <p class="text-sm font-bold text-primary-700 mb-1">Menunggu review staff</p>
                                    <p class="text-xs text-slate-500">File desain sudah terupload. Biasanya selesai dalam 1x24 jam.</p>
                                @endif
                            </div>
                        </div>
                    @elseif($canReupload)
                        @if($latestStatus === 'revision_requested')
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4">
                                <p class="text-xs font-semibold text-amber-700 mb-0.5">Catatan revisi:</p>
                                <p class="text-xs text-amber-800 italic">{{ $latestNotes }}</p>
                            </div>
                        @endif

                        <form action="/desain/{{ $item['id'] }}/upload" method="POST" enctype="multipart/form-data" x-data="{ loading: false, fileName: '' }" @submit="loading = true">
                            @csrf
                            {{-- Drop Zone --}}
                            <div class="border border-dashed border-primary-400 bg-primary-50/20 rounded-xl p-10 lg:p-12 text-center hover:bg-primary-50/50 transition-colors relative"
                                 :class="fileName ? 'border-primary-500 bg-primary-50/60' : ''">
                                <input type="file" name="file" id="file_{{ $item['id'] }}" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr"
                                       @change="fileName = $event.target.files[0]?.name ?? ''">
                                
                                <svg class="w-12 h-12 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-base font-bold text-slate-700" x-text="fileName ? fileName : 'Seret file ke sini atau klik untuk pilih'"></p>
                                <p class="text-sm text-slate-500 mt-2">Maks. 10MB</p>
                                
                                <div class="flex justify-center gap-2 mt-5">
                                    @foreach(['JPG', 'PNG', 'PDF', 'AI', 'PSD', 'CDR'] as $fmt)
                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $fmt }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" :disabled="!fileName || loading"
                                class="w-full mt-6 py-4 rounded-[12px] text-base font-bold bg-primary-600 hover:bg-primary-700 text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">{{ $hasDesign ? 'Upload ulang desain' : 'Upload & cek AI' }}</span>
                                <span x-show="loading" style="display:none">Mengunggah...</span>
                            </button>

                            <div class="mt-5 p-4 bg-purple-50/80 rounded-xl flex items-start gap-4 border border-purple-100">
                                <svg class="w-6 h-6 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                <p class="text-sm text-purple-800 leading-relaxed">Gambar akan dicek secara otomatis oleh AI untuk memastikan kualitas resolusi agar hasil cetak maksimal.</p>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Sidebar Cards (Right) --}}
        <div class="w-full lg:w-[380px] xl:w-[420px] shrink-0 flex flex-col gap-6">

            {{-- Tips Desain --}}
            <div class="bg-white/90 backdrop-blur-md rounded-[14px] border border-white/20 p-6 shadow-xl shadow-blue-900/10">
                <h3 class="font-bold text-slate-900 mb-4">Tips desain</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-xs text-slate-600 leading-relaxed">Gunakan format CMYK untuk warna cetak yang akurat</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-xs text-slate-600 leading-relaxed">Pastikan resolusi minimal 300 DPI</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        <span class="text-xs text-slate-600 leading-relaxed">Hindari teks terlalu kecil di bawah 6pt</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        <span class="text-xs text-slate-600 leading-relaxed">Jangan simpan logo dalam format JPG beresolusi rendah</span>
                    </li>
                </ul>
            </div>

            {{-- Action Container --}}
            <div class="flex flex-col gap-4">
                {{-- Status Card --}}
                <div class="bg-white/90 backdrop-blur-md p-5 rounded-[14px] border border-white/20 shadow-xl shadow-blue-900/10">
                    @if(!$alreadyPaid)
                        @if($allUploadedOrApproved)
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-sm text-slate-700 font-medium leading-snug">Semua desain siap, lanjut ke pembayaran</p>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-slate-500 leading-snug">Upload semua desain untuk melanjutkan ke pembayaran</p>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-slate-700 font-medium leading-snug">Pesanan sudah dibayar</p>
                        </div>
                    @endif
                </div>

                {{-- Action Button --}}
                @if(!$alreadyPaid)
                    @if($allUploadedOrApproved)
                        <a href="/pesanan/{{ $order['id'] }}/pembayaran" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm rounded-[10px] flex items-center justify-center gap-2 transition-colors shadow-lg shadow-primary-600/20">
                            Lanjut ke pembayaran &rarr;
                        </a>
                    @else
                        <button disabled class="w-full py-3 bg-white/50 backdrop-blur-sm border border-slate-200/50 text-slate-400 font-bold text-sm rounded-[10px] flex items-center justify-center gap-2 cursor-not-allowed">
                            Lanjut ke pembayaran &rarr;
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
