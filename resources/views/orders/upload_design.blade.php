@extends('layouts.app')

@section('title', 'Upload Desain — ' . ($order['order_code'] ?? 'Pesanan'))

@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm font-medium">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm font-medium">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Header --}}
        <div class="mb-8">
            <a href="/pesanan/{{ $order['id'] }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-primary-600 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Detail Pesanan
            </a>
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 mb-1">Upload Desain Cetak</h1>
                    <p class="text-slate-500 text-sm">
                        Pesanan <span class="font-mono font-bold text-primary-600">{{ $order['order_code'] ?? '-' }}</span>
                        &bull; Pastikan gambar tidak pecah/blur sebelum diunggah.
                    </p>
                </div>
            </div>
        </div>

        @php
            // Designs dari API diurutkan version ASC → index terakhir = versi terbaru
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

        {{-- Info Banner: Revision needed --}}
        @if($hasRevision)
        <div class="mb-6 flex items-start gap-3 bg-amber-50 border border-amber-300 rounded-2xl px-4 py-4">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div>
                <p class="font-bold text-amber-800 text-sm">Desain perlu direvisi</p>
                <p class="text-xs text-amber-700 mt-0.5">Staff kami meminta revisi untuk beberapa item. Silakan periksa catatan di bawah dan upload ulang desain Anda.</p>
            </div>
        </div>
        @endif

        <div class="space-y-5">
            @foreach($order['items'] as $item)
            @php
                $itemDesigns = $item['designs'] ?? [];
                $totalDesigns = count($itemDesigns);
                $hasDesign   = $totalDesigns > 0;
                // Designs sorted ASC → last = latest version
                $latestDesign = $hasDesign ? $itemDesigns[$totalDesigns - 1] : null;
                $latestStatus = $latestDesign['status'] ?? '';
                $latestNotes  = $latestDesign['notes'] ?? '';
                $latestVersion = $latestDesign['version'] ?? 0;
                $canReupload  = !$hasDesign || $latestStatus === 'revision_requested';
                $isApproved   = $latestStatus === 'approved';
            @endphp

            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden
                {{ $isApproved ? 'border-green-200' : ($latestStatus === 'revision_requested' ? 'border-amber-300' : 'border-slate-200') }}">

                {{-- Item Header --}}
                <div class="px-6 py-4 border-b
                    {{ $isApproved ? 'border-green-100 bg-green-50/40' : ($latestStatus === 'revision_requested' ? 'border-amber-100 bg-amber-50/30' : 'border-slate-100 bg-slate-50/50') }}
                    flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl border border-slate-200 overflow-hidden shrink-0">
                        @if(!empty($item['product_image']))
                            <img src="{{ config('app.golang_api_url') }}{{ $item['product_image'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-slate-900 text-sm">{{ $item['product_name'] ?? '-' }}</h3>
                            @if($isApproved)
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full bg-green-100 text-green-700">✓ Disetujui Staff</span>
                            @elseif($latestStatus === 'revision_requested')
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700">↩ Perlu Revisi</span>
                            @elseif($hasDesign)
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700">⏳ Menunggu Review</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500">Belum Upload</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['variant_name'] ?? '' }} &bull; Qty: {{ $item['quantity'] ?? 1 }}</p>
                    </div>
                </div>

                <div class="px-6 py-5">

                    {{-- Show current uploaded design --}}
                    @if($hasDesign)
                        <div class="flex items-start gap-4 mb-5">
                            {{-- Thumbnail of latest design --}}
                            @php $ext = strtolower(pathinfo($latestDesign['file_path'] ?? '', PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg','jpeg','png']))
                                <a href="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" target="_blank"
                                   class="shrink-0 block w-24 h-24 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 hover:opacity-90 transition-opacity">
                                    <img src="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}"
                                         alt="Desain v{{ $latestVersion }}" class="w-full h-full object-contain p-1">
                                </a>
                            @else
                                <a href="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" target="_blank"
                                   class="shrink-0 flex flex-col items-center justify-center w-24 h-24 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $ext }}</span>
                                </a>
                            @endif

                            <div class="flex-1 min-w-0">
                                @if($isApproved)
                                    <p class="text-sm font-bold text-green-700 mb-1">Desain Versi {{ $latestVersion }} — Disetujui!</p>
                                    <p class="text-xs text-green-600">{{ $latestNotes ?: 'Desain sudah oke dan siap dicetak.' }}</p>
                                    <p class="text-xs text-slate-400 mt-2">✅ Tidak perlu upload ulang. Menunggu proses produksi.</p>
                                @elseif($latestStatus === 'revision_requested')
                                    <p class="text-sm font-bold text-amber-700 mb-1">Desain Versi {{ $latestVersion }} — Revisi Diperlukan</p>
                                    @if($latestNotes)
                                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mt-1">
                                            <p class="text-xs font-semibold text-amber-700 mb-0.5">Catatan dari Staff:</p>
                                            <p class="text-xs text-amber-800 italic">{{ $latestNotes }}</p>
                                        </div>
                                    @endif
                                    <p class="text-xs text-slate-500 mt-2">Upload desain baru di bawah ini (versi ke-{{ $latestVersion + 1 }}).</p>
                                @else
                                    <p class="text-sm font-bold text-blue-700 mb-1">Desain Versi {{ $latestVersion }} — Menunggu Review</p>
                                    <p class="text-xs text-slate-500">File desain sudah terupload dan sedang ditinjau oleh staff kami. Biasanya selesai dalam 1x24 jam.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Upload Form (only if can re-upload) --}}
                    @if($canReupload)
                        <form action="/desain/{{ $item['id'] }}/upload" method="POST" enctype="multipart/form-data"
                              x-data="{ loading: false, fileName: '' }" @submit="loading = true">
                            @csrf
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-5 text-center hover:border-primary-400 transition-colors"
                                 :class="fileName ? 'border-primary-400 bg-primary-50/30' : ''">
                                <input type="file" name="file" id="file_{{ $item['id'] }}" required
                                       class="absolute opacity-0 w-0 h-0"
                                       accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr"
                                       @change="fileName = $event.target.files[0]?.name ?? ''">
                                <label for="file_{{ $item['id'] }}" class="cursor-pointer block">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400" :class="fileName ? 'text-primary-500' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-slate-700" x-text="fileName ? fileName : 'Klik untuk pilih file'"></p>
                                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, PDF, AI, PSD, CDR &bull; Maks. 10MB</p>
                                </label>
                            </div>
                            <div class="mt-3 flex items-center gap-2 flex-wrap">
                                <button type="submit" :disabled="!fileName || loading"
                                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-primary-600 hover:bg-primary-700 text-white transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                    <span x-show="!loading" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        {{ $hasDesign ? 'Upload Ulang Desain' : 'Upload & Cek AI' }}
                                    </span>
                                    <span x-show="loading" class="flex items-center gap-2" style="display:none">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Mengecek... Mohon tunggu
                                    </span>
                                </button>
                                @if($hasDesign)
                                    <a href="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" target="_blank"
                                       class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat File Sebelumnya
                                    </a>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mt-2">⚡ Gambar akan dicek secara otomatis oleh AI untuk memastikan kualitas resolusi.</p>
                        </form>
                    @elseif($hasDesign && !$isApproved)
                        <p class="text-xs text-slate-400 italic mt-1">Desain sedang dalam proses review. Anda tidak perlu melakukan apa-apa.</p>
                    @endif

                </div>
            </div>
            @endforeach

            {{-- Action Footer --}}
            <div class="mt-6 pt-6 border-t border-slate-200 flex items-center justify-between gap-4 flex-wrap">
                <p class="text-sm text-slate-500">
                    @if($allUploadedOrApproved)
                        ✅ Semua desain sudah diupload
                    @elseif($hasRevision)
                        ⚠️ Ada desain yang perlu direvisi sebelum lanjut
                    @else
                        ⏳ Upload semua desain untuk melanjutkan ke pembayaran
                    @endif
                </p>
                @if(!$alreadyPaid)
                    @if($allUploadedOrApproved)
                        <a href="/pesanan/{{ $order['id'] }}/pembayaran"
                           class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold bg-primary-600 hover:bg-primary-700 text-white transition-colors shadow-lg shadow-primary-500/20">
                            Lanjut ke Pembayaran
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <button disabled class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold bg-slate-200 text-slate-400 cursor-not-allowed">
                            Upload Semua Desain Dulu
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    @endif
                @else
                    <a href="/pesanan/{{ $order['id'] }}"
                       class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold bg-slate-700 hover:bg-slate-800 text-white transition-colors">
                        Lihat Detail Pesanan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
