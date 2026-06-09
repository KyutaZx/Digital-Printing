@extends('layouts.app')
@section('title', 'Detail Pesanan ' . ($order['order_code'] ?? '') . ' — Jaya Mandiri')
@section('content')
<style>
    /* Custom CSS variables based on user spec */
    :root {
        --color-primary: #185FA5;
        --color-success-text: #3B6D11;
        --color-success-bg: #EAF3DE;
        --color-warning-text: #854F0B;
        --color-warning-bg: #FAEEDA;
        --color-danger-text: #A32D2D;
        --color-danger-border: #E24B4A;
    }
    
    /* Tailwind arbitrary values mapping */
    .text-primary-custom { color: var(--color-primary); }
    .bg-primary-custom { background-color: var(--color-primary); }
    .border-primary-custom { border-color: var(--color-primary); }
    
    .text-success-custom { color: var(--color-success-text); }
    .bg-success-custom { background-color: var(--color-success-bg); }
    
    .text-warning-custom { color: var(--color-warning-text); }
    .bg-warning-custom { background-color: var(--color-warning-bg); }
    
    .text-danger-custom { color: var(--color-danger-text); }
    .border-danger-custom { border-color: var(--color-danger-border); }
    
    .flat-card {
        background: #ffffff;
        border: 0.5px solid #e5e5e5;
        border-radius: 12px;
        box-shadow: none !important;
    }
</style>

@php
    $s = $order['status'] ?? '';
    // Steps mapping
    $steps = [
        ['label'=>'Memesan', 'key' => 'waiting_payment'],
        ['label'=>'Verifikasi Pembayaran', 'key' => 'payment_verification'],
        ['label'=>'Verifikasi Desain', 'key' => 'design_review'],
        ['label'=>'Produksi', 'key' => 'printing'],
        ['label'=>'Selesai', 'key' => 'completed'],
    ];
    $stepIndexMap = [
        'waiting_payment' => 0,
        'payment_verification' => 1,
        'paid' => 2,
        'design_review' => 2,
        'printing' => 3,
        'ready' => 4,
        'completed' => 4,
        'cancelled' => -1,
    ];
    $currentIdx = $stepIndexMap[$s] ?? 0;
    
    $paymentRejected = (bool)($order['payment_rejected'] ?? false);
    $paymentApproved = in_array($order['payment']['payment_status'] ?? '', ['approved', 'success']);
    
    $allApproved = true; $hasRevision = false; $hasPending = false; $hasNoDesign = false;
    foreach($order['items'] ?? [] as $item) {
        $designs = $item['designs'] ?? [];
        $latest  = !empty($designs) ? $designs[count($designs)-1] : null;
        $ls      = $latest['status'] ?? '';
        if(empty($designs))                                          { $hasNoDesign = true; $allApproved = false; }
        elseif($ls === 'revision_requested' || $ls === 'rejected')   { $hasRevision = true; $allApproved = false; }
        elseif($ls !== 'approved')                                   { $hasPending  = true; $allApproved = false; }
    }
    $allDesignsUploaded = !$hasNoDesign;
@endphp

<div class="bg-slate-50 min-h-screen py-8 pt-20">
    <div class="max-w-[860px] mx-auto px-6">
        
        {{-- Topbar --}}
        <div class="flex justify-between items-center mb-6">
            <a href="/pesanan" class="text-[13px] font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2">
                &larr; Kembali ke daftar pesanan
            </a>
            <div class="flex gap-2">
                @if(in_array($s, ['waiting_payment','payment_verification']))
                <form action="/pesanan/{{ $order['id'] }}/batal" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded text-[13px] font-semibold text-danger-custom border border-danger-custom bg-white hover:bg-red-50">
                        Batalkan
                    </button>
                </form>
                @endif
                <a href="/pesanan/{{ $order['id'] }}/invoice/view" class="px-3 py-1.5 rounded text-[13px] font-semibold text-slate-700 border border-slate-300 bg-white hover:bg-slate-50">
                    Invoice
                </a>
            </div>
        </div>

        {{-- Header Order Info --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $order['order_code'] ?? '-' }}</h1>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide bg-blue-100 text-primary-custom">{{ str_replace('_', ' ', $s) }}</span>
                <span class="text-[13px] text-slate-500">{{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>

        {{-- 1. Status Stepper (Full Width Card) --}}
        @if($s !== 'cancelled')
        <div class="flat-card p-6 mb-6 overflow-hidden hidden sm:block">
            <div class="relative flex items-start justify-between px-2">
                <!-- Lines -->
                <div class="absolute top-3 left-[10%] right-[10%] h-[2px] bg-slate-200 z-0">
                    <div class="h-full bg-primary-custom transition-all duration-700"
                         style="width:{{ $currentIdx > 0 ? ($currentIdx / (count($steps)-1)) * 100 : 0 }}%"></div>
                </div>
                <!-- Steps -->
                @foreach($steps as $idx => $step)
                <div class="relative z-10 flex flex-col items-center w-1/5">
                    @if($idx < $currentIdx || ($idx == 4 && $s == 'completed'))
                        <div class="w-6 h-6 rounded-full bg-primary-custom flex items-center justify-center text-white ring-4 ring-white">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @elseif($idx == $currentIdx)
                        <div class="w-6 h-6 rounded-full bg-white border-[2px] border-primary-custom flex items-center justify-center text-primary-custom ring-4 ring-white">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary-custom"></div>
                        </div>
                    @else
                        <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-[11px] font-bold ring-4 ring-white">
                            {{ $idx + 1 }}
                        </div>
                    @endif
                    <p class="text-[11px] font-semibold text-center mt-3 px-2 {{ $idx <= $currentIdx ? 'text-slate-800' : 'text-slate-400' }} leading-tight">
                        {{ $step['label'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 2. Main Content (Full Width) --}}
        <div class="flex flex-col gap-6">
            
            {{-- Kolom Utama --}}
            <div class="space-y-6 w-full">
                
                {{-- Card: Detail Pesanan --}}
                <div class="flat-card">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Detail Pesanan</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach($order['items'] ?? [] as $item)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                                @if(!empty($item['product_image']))
                                    <img src="{{ url('/api-proxy/' . ltrim($item['product_image'] ?? '', '/')) }}" class="w-full h-full object-cover rounded">
                                @else
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-[13px] text-slate-900 leading-tight">{{ $item['product_name'] ?? '-' }}</p>
                                        <p class="text-[12px] text-slate-500 mt-0.5">{{ $item['variant_name'] ?? '' }} &times; {{ $item['quantity'] ?? 1 }}</p>
                                        <div class="mt-1.5">
                                            @php
                                                $ls = !empty($item['designs']) ? $item['designs'][count($item['designs'])-1]['status'] ?? '' : '';
                                            @endphp
                                            @if($ls === 'approved')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-success-custom text-success-custom">Disetujui</span>
                                            @elseif($ls === 'revision_requested' || $ls === 'rejected')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-warning-custom text-warning-custom">Perlu Revisi</span>
                                            @elseif(!empty($item['designs']))
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-primary-custom">Menunggu Review</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-warning-custom text-warning-custom">Belum upload desain</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="font-bold text-[13px] text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-5 pb-5">
                        <div class="h-[1px] bg-slate-100 w-full mb-4"></div>
                        <div class="flex justify-between items-center text-[13px] mb-2">
                            <span class="text-slate-500">Subtotal Produk</span>
                            <span class="font-semibold text-slate-800">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[13px]">
                            <span class="text-slate-500">Biaya Layanan</span>
                            <span class="font-semibold text-success-custom">Gratis</span>
                        </div>
                    </div>
                    <div class="bg-slate-100 p-5 rounded-b-[12px] border-t border-slate-200 flex justify-between items-center">
                        <span class="font-bold text-[13px] text-slate-700">Total Bayar</span>
                        <span class="font-black text-[20px] text-primary-custom">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Card: Upload Desain --}}
                <div class="flat-card">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Upload Desain</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach($order['items'] ?? [] as $item)
                        @php
                            $designs   = $item['designs'] ?? [];
                            $lastIdx   = count($designs) - 1;
                            $latest    = $lastIdx >= 0 ? $designs[$lastIdx] : null;
                            $ls        = $latest['status'] ?? '';
                            $hasDesign = !empty($designs);
                        @endphp
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <p class="font-bold text-[13px] text-slate-900">{{ $item['product_name'] ?? '-' }}</p>
                                @if($hasDesign)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-success-custom text-success-custom">Terupload</span>
                                @endif
                            </div>
                            
                            @if($hasDesign)
                                <div class="bg-slate-50 rounded-lg p-3 border border-slate-200 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded bg-white border border-slate-200 flex items-center justify-center shrink-0 text-success-custom">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            @php $fileName = basename($latest['file_path']); @endphp
                                            <p class="text-[12px] font-semibold text-slate-700 truncate" title="{{ $fileName }}">{{ $fileName }}</p>
                                            <div class="mt-1 flex items-center gap-2">
                                                <span class="text-[11px] text-slate-500">Versi {{ $latest['version'] ?? 1 }}</span>
                                                <span class="text-slate-300">&bull;</span>
                                                @if($ls === 'approved')
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-success-custom/10 text-success-custom text-[10px] font-bold uppercase tracking-wider">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Accepted
                                                    </span>
                                                @else
                                                    <span class="text-[11px] text-slate-500">
                                                        {{ $ls === 'revision_requested' ? 'Perlu revisi' : 'Menunggu review' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="{{ url('/api-proxy/' . ltrim($latest['file_path'] ?? '', '/')) }}" target="_blank" class="px-2.5 py-1.5 rounded bg-white border border-slate-200 text-[11px] font-bold text-slate-600 hover:bg-slate-50">
                                            Lihat
                                        </a>
                                        @if(in_array($s, ['waiting_payment', 'payment_verification', 'paid', 'design_review']) && $ls !== 'approved')
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="px-2.5 py-1.5 rounded bg-white border border-slate-200 text-[11px] font-bold text-primary-custom hover:bg-slate-50">
                                                Revisi
                                            </button>
                                            <div x-show="open" x-cloak class="absolute top-full mt-2 right-0 bg-white border border-slate-200 shadow-xl rounded-lg p-3 z-20 min-w-[280px]">
                                                <form action="/desain/{{ $item['id'] }}/upload" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3" x-data="{ fileName: '' }">
                                                    @csrf
                                                    <div class="relative border border-dashed border-blue-300 bg-blue-50/50 rounded-lg p-4 text-center hover:bg-blue-50 transition-colors">
                                                        <input type="file" name="file" required accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0]?.name ?? ''">
                                                        <svg class="w-6 h-6 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                                        <p class="text-[11px] font-semibold text-slate-700 truncate px-2" x-text="fileName ? fileName : 'Pilih file revisi'"></p>
                                                    </div>
                                                    <button type="submit" class="relative z-20 w-full px-3 py-2 rounded-lg bg-blue-600 text-white text-[11px] font-bold hover:bg-blue-700 transition-colors disabled:opacity-50" :disabled="!fileName">Upload Revisi</button>
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if($ls === 'revision_requested' && !empty($latest['notes']))
                                    <p class="text-[11px] text-warning-custom mt-2 bg-warning-custom/50 px-2 py-1.5 rounded border border-warning-custom/20">Catatan Revisi: {{ $latest['notes'] }}</p>
                                @endif
                            @else
                                <form action="/desain/{{ $item['id'] }}/upload" method="POST" enctype="multipart/form-data" 
                                      x-data="{ fileName: '' }"
                                      class="border border-dashed border-blue-300 bg-blue-50/30 rounded-xl p-6 text-center hover:bg-blue-50/50 transition-colors relative"
                                      :class="fileName ? 'border-blue-500 bg-blue-50/80' : ''">
                                    @csrf
                                    <input type="file" name="file" required accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="fileName = $event.target.files[0]?.name ?? ''">
                                           
                                    <svg class="w-8 h-8 text-blue-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm font-bold text-slate-700 mb-1 px-4 truncate" x-text="fileName ? fileName : 'Upload desain untuk item ini'"></p>
                                    <p class="text-[11px] text-slate-500 mb-4" x-show="!fileName">Format: JPG, PNG, PDF. Maks 10MB.</p>
                                    
                                    <button type="submit" class="relative z-20 mt-2 w-full py-2.5 rounded-lg bg-blue-600 text-white text-[13px] font-bold hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!fileName">
                                        Upload Sekarang
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Card: Upload Pembayaran --}}
                <div class="flat-card" x-data="{ showForm: false }">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Upload Pembayaran</h2>
                    </div>
                    <div class="p-5">
                        @if(!empty($order['payment']))
                            @php 
                                $ps = strtolower($order['payment']['payment_status'] ?? '');
                                $rawProofUrl = $order['payment']['payment_proof'] ?? $order['payment_proof'] ?? '';
                                $filename = basename($rawProofUrl) ?: 'bukti_transfer.jpg';
                                $proofUrl = str_starts_with($rawProofUrl, 'http') ? $rawProofUrl : url('/api-proxy/' . ltrim($rawProofUrl, '/'));
                            @endphp
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-success-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-bold text-slate-700 truncate">{{ $filename }}</p>
                                        <div class="mt-1">
                                            @if($ps === 'approved')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-success-custom/10 text-success-custom text-[10px] font-bold uppercase tracking-wider">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Accepted
                                                </span>
                                            @else
                                                <p class="text-[11px] text-slate-500">Menunggu verifikasi admin</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    @if(!empty($rawProofUrl))
                                        <a href="{{ $proofUrl }}" target="_blank" class="px-4 py-1.5 bg-white border border-slate-300 text-slate-600 font-bold text-[12px] rounded-lg hover:bg-slate-50 transition-colors text-center">Lihat</a>
                                    @endif
                                    @if($ps !== 'approved' && !$paymentRejected)
                                        <button @click="showForm = !showForm" class="px-4 py-1.5 bg-white border border-slate-300 text-primary-custom font-bold text-[12px] rounded-lg hover:bg-blue-50 transition-colors text-center">Revisi</button>
                                    @endif
                                </div>
                            </div>
                            @if($paymentRejected)
                                <div class="mt-4 p-3 rounded-lg bg-red-50 border border-danger-custom text-danger-custom text-[12px]">
                                    <b class="block mb-1">Bukti Ditolak:</b> {{ $order['payment_reject_notes'] ?? 'Silakan upload ulang.' }}
                                    <button @click="showForm = !showForm" class="block mt-2 font-bold underline">Upload Ulang Bukti</button>
                                </div>
                            @endif
                        @else
                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-8 h-8 rounded bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-800 leading-tight">Belum ada bukti pembayaran</p>
                                    <p class="text-[12px] text-slate-500 mt-0.5">Silakan upload bukti transfer agar pesanan diproses.</p>
                                </div>
                            </div>
                            
                            @if($allDesignsUploaded)
                                <button x-show="!showForm" @click="showForm = true" class="w-full py-2.5 bg-primary-custom text-white font-bold text-[13px] rounded-lg hover:opacity-90 transition-opacity">
                                    Upload bukti pembayaran
                                </button>
                            @else
                                <button disabled class="w-full py-2.5 bg-slate-100 text-slate-400 font-bold text-[13px] rounded-lg cursor-not-allowed border border-slate-200">
                                    Upload Semua Desain Dulu
                                </button>
                            @endif
                        @endif

                        {{-- Inline Payment Form --}}
                        <div x-show="showForm" x-cloak class="mt-4 pt-4 border-t border-slate-100">
                            <form action="/pembayaran/{{ $order['id'] }}/upload" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ bank: '1' }">
                                @csrf
                                
                                {{-- Metode Pembayaran: Bank Selection --}}
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-700 mb-2">Pilih Bank Tujuan</label>
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="method_id" value="1" x-model="bank" class="peer sr-only">
                                            <div class="p-3 border border-slate-200 rounded-lg text-center peer-checked:border-primary-custom peer-checked:bg-blue-50 transition-colors">
                                                <p class="font-bold text-[13px] text-slate-900">Bank BCA</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="method_id" value="2" x-model="bank" class="peer sr-only">
                                            <div class="p-3 border border-slate-200 rounded-lg text-center peer-checked:border-primary-custom peer-checked:bg-blue-50 transition-colors">
                                                <p class="font-bold text-[13px] text-slate-900">Bank Mandiri</p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    {{-- Account Details Card --}}
                                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-2">
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Transfer Ke Rekening</p>
                                        <div x-show="bank === '1'">
                                            <div class="flex justify-between items-center mt-2">
                                                <div>
                                                    <p class="text-2xl font-black text-slate-900 tracking-wider font-mono">1234 567 890</p>
                                                    <p class="text-[12px] text-slate-500 font-semibold mt-1">a.n. Jaya Mandiri Printing</p>
                                                </div>
                                                <div class="font-black text-primary-custom italic text-xl">BCA</div>
                                            </div>
                                        </div>
                                        <div x-show="bank === '2'" x-cloak>
                                            <div class="flex justify-between items-center mt-2">
                                                <div>
                                                    <p class="text-2xl font-black text-slate-900 tracking-wider font-mono">098 7654 321</p>
                                                    <p class="text-[12px] text-slate-500 font-semibold mt-1">a.n. Jaya Mandiri Printing</p>
                                                </div>
                                                <div class="font-black text-blue-700 italic text-xl">Mandiri</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Nominal Transfer & Hidden Code --}}
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nominal Transfer</p>
                                        <input type="hidden" name="amount" value="{{ $order['total_price'] }}">
                                        <input type="hidden" name="transaction_code" value="AUTO-{{ $order['order_code'] }}-{{ time() }}">
                                        <p class="text-xl font-black text-primary-custom">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- Bukti Transfer --}}
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-700 mb-2">Upload Bukti Transfer</label>
                                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 hover:border-primary-custom transition-colors cursor-pointer group">
                                        <input type="file" name="proof" required accept="image/*,application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('file-name').textContent = this.files[0].name; document.getElementById('file-icon').classList.add('text-primary-custom');">
                                        <svg id="file-icon" class="w-8 h-8 text-slate-400 mx-auto mb-2 group-hover:text-primary-custom transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <p id="file-name" class="font-bold text-[13px] text-slate-700 mb-1 group-hover:text-primary-custom">Klik untuk memilih file</p>
                                        <p class="text-[11px] text-slate-500">Mendukung JPG, PNG, atau PDF</p>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 pt-2">
                                    <button type="submit" class="flex-1 py-3 bg-primary-custom text-white font-bold text-[13px] rounded-lg hover:opacity-90 shadow-lg shadow-primary-custom/20">Kirim Bukti Pembayaran</button>
                                    <button type="button" @click="showForm = false" class="px-4 py-3 bg-white border border-slate-300 text-slate-600 font-bold text-[13px] rounded-lg hover:bg-slate-50">Batal</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>



            </div>

            {{-- Kolom Bawah (Info & Bantuan) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                
                {{-- Card: Info Pesanan --}}
                <div class="flat-card h-full">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Info Pesanan</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500 mb-0.5">Kode pesanan</p>
                                <p class="font-bold text-[13px] text-slate-900">{{ $order['order_code'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="h-[1px] bg-slate-100 w-full"></div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500 mb-0.5">Pengambilan</p>
                                <p class="font-bold text-[13px] text-slate-900">Di Toko (Pick-up)</p>
                            </div>
                        </div>
                        <div class="h-[1px] bg-slate-100 w-full"></div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500 mb-0.5">Estimasi selesai</p>
                                <p class="font-bold text-[13px] text-slate-900">1 - 2 Hari Kerja</p>
                            </div>
                        </div>
                        <div class="h-[1px] bg-slate-100 w-full"></div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500 mb-0.5">Tanggal pesan</p>
                                <p class="font-bold text-[13px] text-slate-900">{{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

    </div>
</div>
@endsection
