@extends('layouts.app')
@section('title', 'Detail Pesanan ' . ($order['order_code'] ?? '') . ' — Jaya Mandiri')
@section('content')
<div class="pt-24 min-h-screen bg-slate-50 pb-20">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

@php
    $s = $order['status'] ?? '';
    $badge = match($s) {
        'waiting_payment'      => ['cls'=>'bg-slate-100 text-slate-600',   'label'=>'Menunggu Pembayaran'],
        'payment_verification' => ['cls'=>'bg-yellow-100 text-yellow-700', 'label'=>'Menunggu Verifikasi'],
        'paid'                 => ['cls'=>'bg-blue-100 text-blue-700',     'label'=>'Lunas'],
        'printing'             => ['cls'=>'bg-purple-100 text-purple-700', 'label'=>'Sedang Dicetak'],
        'ready'                => ['cls'=>'bg-green-100 text-green-700',   'label'=>'Siap Diambil'],
        'completed'            => ['cls'=>'bg-green-100 text-green-700',   'label'=>'Selesai'],
        'cancelled'            => ['cls'=>'bg-red-100 text-red-600',       'label'=>'Dibatalkan'],
        default                => ['cls'=>'bg-slate-100 text-slate-500',   'label'=>ucfirst($s)],
    };
    $steps = [
        ['id'=>'waiting_payment',      'label'=>'Dibuat'],
        ['id'=>'payment_verification', 'label'=>'Verifikasi'],
        ['id'=>'paid',                 'label'=>'Lunas'],
        ['id'=>'printing',             'label'=>'Produksi'],
        ['id'=>'ready',                'label'=>'Siap Ambil'],
        ['id'=>'completed',            'label'=>'Selesai'],
    ];
    $currentIdx = collect($steps)->search(fn($step) => $step['id'] === $s) ?? 0;

    // Design status summary
    $allApproved = true; $hasRevision = false; $hasPending = false; $hasNoDesign = false;
    foreach($order['items'] ?? [] as $item) {
        $designs = $item['designs'] ?? [];
        $latest  = !empty($designs) ? $designs[count($designs)-1] : null;
        $ls      = $latest['status'] ?? '';
        if(empty($designs))                                          { $hasNoDesign = true; $allApproved = false; }
        elseif($ls === 'revision_requested' || $ls === 'rejected')   { $hasRevision = true; $allApproved = false; }
        elseif($ls !== 'approved')                                   { $hasPending  = true; $allApproved = false; }
    }
@endphp

{{-- Flash --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm font-medium">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
     class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm font-medium">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- Top Bar --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <a href="/pesanan" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-primary-600 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Pesanan
        </a>
        <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-2xl font-black text-slate-900 font-mono">{{ $order['order_code'] ?? '-' }}</h1>
            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $badge['cls'] }}">{{ $badge['label'] }}</span>
        </div>
        <p class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d M Y, H:i') }} WIB</p>
    </div>
    <div class="flex gap-2 flex-wrap">
        @if(in_array($s, ['waiting_payment','payment_verification']))
        <form action="/pesanan/{{ $order['id'] }}/batal" method="POST"
              onsubmit="return confirm('Batalkan pesanan ini?')">
            @csrf
            <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold border border-red-200 text-red-500 hover:bg-red-50 transition-colors bg-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Batalkan
            </button>
        </form>
        @endif
        <a href="/pesanan/{{ $order['id'] }}/invoice/view"
           class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors bg-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Invoice
        </a>
        @if($s === 'ready')
        <form action="/pesanan/{{ $order['id'] }}/selesai" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-green-600 text-white hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Konfirmasi Selesai
            </button>
        </form>
        @endif
    </div>
</div>

{{-- Progress Stepper --}}
@if($s !== 'cancelled')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 hidden md:block">
    <div class="relative flex items-start justify-between px-6">
        <div class="absolute top-5 left-[3.5rem] right-[3.5rem] h-[2px] bg-slate-100 z-0">
            <div class="h-full bg-gradient-to-r from-primary-500 to-blue-500 transition-all duration-700"
                 style="width:{{ $currentIdx > 0 ? ($currentIdx / (count($steps)-1)) * 100 : 0 }}%"></div>
        </div>
        @foreach($steps as $idx => $step)
        <div class="relative z-10 flex flex-col items-center gap-2 flex-1">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all font-bold text-sm
                {{ $idx < $currentIdx  ? 'bg-primary-600 text-white shadow-md shadow-primary-200'
                 : ($idx == $currentIdx ? 'bg-primary-600 text-white ring-4 ring-primary-100 shadow-lg'
                 : 'bg-slate-100 text-slate-400') }}">
                @if($idx < $currentIdx)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                @else
                    {{ $idx + 1 }}
                @endif
            </div>
            <p class="text-[10px] font-bold text-center leading-tight
               {{ $idx == $currentIdx ? 'text-primary-600' : ($idx < $currentIdx ? 'text-slate-700' : 'text-slate-400') }}">
                {{ $step['label'] }}
            </p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Main Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Items + Design --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Design Upload CTA (adapts to status & revision state) --}}
        @if($s === 'waiting_payment')
            @if($hasRevision)
            {{-- Has revision: show amber warning, embed form inline below --}}
            <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-amber-800">Desain Perlu Direvisi</p>
                        <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">Staff kami meminta perbaikan pada desain Anda. Silakan upload ulang file yang sudah diperbaiki pada item di bawah, kemudian lanjutkan ke pembayaran.</p>
                    </div>
                </div>
            </div>
            @elseif($hasNoDesign)
            {{-- No design at all: show blue upload CTA --}}
            <div class="bg-gradient-to-br from-primary-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-primary-500/20">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-base mb-1">Langkah Berikutnya: Upload Desain & Bayar</p>
                        <p class="text-white/80 text-xs leading-relaxed">Upload file desain cetak Anda, kemudian lakukan pembayaran. Desain dicek kualitasnya oleh AI kami secara otomatis.</p>
                    </div>
                </div>
                <a href="/pesanan/{{ $order['id'] }}/upload-desain"
                   class="mt-4 flex items-center justify-center gap-2 bg-white text-primary-700 font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-50 transition-colors w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Unggah Desain & Lanjut Bayar
                </a>
            </div>
            @else
            {{-- All uploaded pending review --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 animate-pulse shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold text-blue-800 text-sm">Desain Terupload — Menunggu Review Staff</p>
                    <p class="text-xs text-blue-600 mt-0.5">Sambil menunggu, Anda bisa langsung melanjutkan ke pembayaran.</p>
                    <a href="/pesanan/{{ $order['id'] }}/pembayaran" class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-blue-700 hover:underline">
                        Lanjut ke Pembayaran →
                    </a>
                </div>
            </div>
            @endif
        @elseif($hasRevision && in_array($s, ['paid','payment_verification']))
        <div class="bg-amber-50 border border-amber-300 rounded-2xl p-5">
            <div class="flex items-start gap-3 mb-3">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <div>
                    <p class="font-bold text-amber-800 text-sm">Desain Perlu Direvisi</p>
                    <p class="text-xs text-amber-700 mt-0.5">Staff meminta revisi. Periksa catatan pada setiap item lalu upload ulang.</p>
                </div>
            </div>
            <a href="/pesanan/{{ $order['id'] }}/upload-desain"
               class="flex items-center justify-center gap-2 bg-amber-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-amber-700 transition-colors">
                Upload Ulang Desain
            </a>
        </div>
        @endif

        {{-- Items List --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="font-bold text-slate-800 text-sm">Daftar Produk</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($order['items'] ?? [] as $item)
                @php
                    $designs   = $item['designs'] ?? [];
                    $lastIdx   = count($designs) - 1;
                    $latest    = $lastIdx >= 0 ? $designs[$lastIdx] : null;
                    $ls        = $latest['status'] ?? '';
                    $ln        = $latest['notes'] ?? '';
                @endphp
                <div class="px-6 py-5">
                    <div class="flex gap-4">
                        {{-- Product thumb --}}
                        <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden shrink-0 border border-slate-200">
                            @if(!empty($item['product_image']))
                                <img src="{{ config('app.golang_api_url') }}{{ $item['product_image'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">{{ $item['product_name'] ?? '-' }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $item['variant_name'] ?? '' }} &bull; Qty: {{ $item['quantity'] ?? 1 }}</p>
                                </div>
                                <p class="font-black text-slate-900 text-sm shrink-0">
                                    Rp {{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Design status badge per item --}}
                            @if(!empty($designs))
                            <div class="mt-3 space-y-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($ls === 'approved')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700">✓ Desain Disetujui</span>
                                    @elseif($ls === 'revision_requested' || $ls === 'rejected')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">↩ Perlu Revisi</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">⏳ Menunggu Review</span>
                                    @endif
                                    <a href="{{ config('app.golang_api_url') }}{{ $latest['file_path'] }}" target="_blank"
                                       class="text-xs text-primary-600 hover:underline flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Lihat v{{ $latest['version'] ?? 1 }}
                                    </a>
                                </div>

                                {{-- Staff notes --}}
                                @if($ln && ($ls === 'revision_requested' || $ls === 'rejected'))
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                                    <p class="text-xs font-semibold text-amber-700 mb-0.5">Catatan Staff:</p>
                                    <p class="text-xs text-amber-800 italic">"{{ $ln }}"</p>
                                </div>
                                @endif

                                {{-- Inline upload form for revision (for all active states) --}}
                                @if(($ls === 'revision_requested' || $ls === 'rejected') && in_array($s, ['waiting_payment', 'payment_verification', 'paid', 'design_review']))
                                <form action="/desain/{{ $item['id'] }}/upload" method="POST" enctype="multipart/form-data"
                                      x-data="{ loading: false, fileName: '' }" @submit="loading = true"
                                      class="mt-3">
                                    @csrf
                                    <div class="border-2 border-dashed border-amber-300 rounded-xl p-5 text-center bg-amber-50/10 hover:bg-amber-50/20 transition-all cursor-pointer relative"
                                         :class="fileName ? 'border-primary-400 bg-primary-50/10' : ''">
                                        <input type="file" name="file" id="file_{{ $item['id'] }}" required
                                               class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10"
                                               accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr"
                                               @change="fileName = $event.target.files[0]?.name ?? ''">
                                        <div class="relative z-0">
                                            <svg class="w-8 h-8 mx-auto mb-2 text-amber-500" :class="fileName ? 'text-primary-500 animate-bounce' : ''"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="text-xs font-semibold text-slate-700" x-text="fileName ? fileName : 'Pilih file revisi baru untuk diunggah'"></p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Format: JPG, PNG, PDF, AI, PSD, CDR &bull; Maks. 10MB</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center gap-2">
                                        <button type="submit" :disabled="!fileName || loading"
                                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white transition-colors disabled:opacity-40 shrink-0 whitespace-nowrap">
                                            <span x-show="!loading" class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                Upload Revisi (v{{ ($latest['version'] ?? 0) + 1 }})
                                            </span>
                                            <span x-show="loading" class="flex items-center gap-1" style="display:none">
                                                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                Mengecek AI...
                                            </span>
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">⚡ File akan otomatis diverifikasi resolusinya oleh sistem AI.</p>
                                </form>
                                @endif
                            </div>
                            @else
                            <div class="mt-3">
                                <span class="text-xs text-slate-400 italic">Belum ada desain terupload</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Order Notes --}}
        @if(!empty($order['notes']))
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Pesanan</p>
            <p class="text-sm text-slate-600">{{ $order['notes'] }}</p>
        </div>
        @endif

    </div>

    {{-- Right Sidebar --}}
    <div class="space-y-5 lg:sticky lg:top-24 lg:self-start">

        {{-- Design Review Status --}}
        @if(in_array($s, ['paid','payment_verification','printing','ready']))
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-slate-800 text-sm mb-4">Status Desain</h3>
            @if($allApproved)
                <div class="flex items-start gap-3 p-3 bg-green-50 border border-green-200 rounded-xl">
                    <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-green-800">Semua Disetujui ✅</p>
                        <p class="text-xs text-green-600 mt-0.5">Desain memenuhi standar cetak.</p>
                    </div>
                </div>
            @elseif($hasRevision)
                <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-amber-800">Perlu Revisi</p>
                        <p class="text-xs text-amber-600 mt-0.5">Ada desain yang ditolak staff.</p>
                    </div>
                </div>
            @else
                <div class="flex items-start gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-800">Sedang Direview</p>
                        <p class="text-xs text-blue-600 mt-0.5">Tim kami sedang memeriksa desain.</p>
                    </div>
                </div>
            @endif
        </div>
        @endif

        {{-- Payment Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-slate-800 text-sm mb-4">Ringkasan Pembayaran</h3>
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Biaya Layanan</span>
                    <span class="font-semibold text-green-600">Gratis</span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                    <span class="font-bold text-slate-900">Total</span>
                    <span class="font-black text-primary-700 text-lg">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            @if(!empty($order['payment']))
            <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-slate-500 font-semibold">Status Bayar</p>
                    @php $ps = strtolower($order['payment']['payment_status'] ?? ''); @endphp
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                        {{ $ps === 'success' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ strtoupper($ps === 'success' ? 'Terverifikasi' : $ps) }}
                    </span>
                </div>
                <p class="text-xs text-slate-600">Metode: <span class="font-semibold text-slate-800">{{ $order['payment']['method_name'] ?? '-' }}</span></p>
                @if(!empty($order['payment']['transaction_code']))
                <p class="text-xs text-slate-600">Kode: <span class="font-mono font-semibold text-slate-800">{{ $order['payment']['transaction_code'] }}</span></p>
                @endif
            </div>
            @else
            <div class="mt-4 p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center">
                <p class="text-xs text-slate-400 italic">Belum ada pembayaran</p>
            </div>
            @endif

            {{-- Proceed to pay CTA --}}
            @if($s === 'waiting_payment')
            <a href="/pesanan/{{ $order['id'] }}/pembayaran"
               class="mt-4 flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm px-5 py-3 rounded-xl transition-colors w-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Konfirmasi Pembayaran
            </a>
            @endif
        </div>

    </div>
</div>

</div>
</div>
@endsection
