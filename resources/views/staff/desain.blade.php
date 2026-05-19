@extends('layouts.staff')
@section('title', 'Review Desain')
@section('page_title', 'Review Desain Customer')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
     class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl shadow-sm text-sm font-medium">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
     class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl shadow-sm text-sm font-medium">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    {{ session('error') }}
</div>
@endif

<div x-data="{ tab: 'pending' }">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Review Desain Customer</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola antrian review dan riwayat persetujuan desain</p>
        </div>
        
        <div class="flex items-center p-1 bg-slate-100 rounded-xl">
            <button @click="tab = 'pending'" 
                    class="font-bold text-sm px-4 py-2 rounded-lg transition-all flex items-center gap-2"
                    :class="tab === 'pending' ? 'bg-white text-purple-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                Antrian Review
                <span class="text-[10px] px-2 py-0.5 rounded-full" :class="tab === 'pending' ? 'bg-purple-100' : 'bg-slate-200'">{{ count($pending) }}</span>
            </button>
            <button @click="tab = 'history'" 
                    class="font-bold text-sm px-4 py-2 rounded-lg transition-all"
                    :class="tab === 'history' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                Riwayat Review
            </button>
        </div>
    </div>

    <!-- TAB PENDING -->
    <div x-show="tab === 'pending'">
        @forelse($pending as $order)
        @php
            $hasAnyDesign = false;
            foreach ($order['items'] ?? [] as $item) {
                if (!empty($item['designs'])) { $hasAnyDesign = true; break; }
            }
        @endphp

        <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-4 overflow-hidden hover:shadow-md transition-shadow">
            {{-- Order Header (clickable) --}}
            <button @click="open = !open" class="w-full text-left px-6 py-4 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $hasAnyDesign ? 'bg-purple-100 text-purple-600' : 'bg-amber-100 text-amber-600' }}">
                    @if($hasAnyDesign)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-mono font-bold text-slate-800 text-sm">{{ $order['order_code'] ?? '-' }}</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $hasAnyDesign ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $hasAnyDesign ? '🎨 Ada Desain' : '⏳ Belum Upload' }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">{{ count($order['items'] ?? []) }} item</span>
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5 truncate">
                        <span class="font-medium text-slate-700">{{ $order['customer_name'] ?? 'Customer' }}</span>
                        &bull; Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <svg class="w-5 h-5 text-slate-400 transition-transform shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Expanded Content --}}
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="border-t border-slate-100">
                @if(!empty($order['items']))
                    @foreach($order['items'] as $item)
                    @php
                        $itemDesigns = $item['designs'] ?? [];
                        $totalDesigns = count($itemDesigns);
                        $latestDesign = $totalDesigns > 0 ? $itemDesigns[$totalDesigns - 1] : null;
                        $latestDesignId = $latestDesign['id'] ?? 0;
                        $latestStatus = $latestDesign['status'] ?? '';
                        $latestNotes  = $latestDesign['notes'] ?? '';
                    @endphp

                    <div class="p-5 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ $item['product_name'] ?? '-' }}</p>
                                <p class="text-xs text-slate-500">Qty: {{ $item['quantity'] ?? 1 }} &bull; Item #{{ $item['id'] ?? '-' }}</p>
                            </div>
                        </div>

                        @if(!empty($itemDesigns))
                            <div class="mb-4">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">File Desain Terupload ({{ $totalDesigns }} versi)</p>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($itemDesigns as $design)
                                    @php
                                        $dExt    = strtolower(pathinfo($design['file_path'] ?? '', PATHINFO_EXTENSION));
                                        $dStatus = $design['status'] ?? '';
                                    @endphp
                                    <div class="relative border rounded-xl overflow-hidden bg-white shadow-sm transition-shadow hover:shadow-md {{ $dStatus === 'approved' ? 'border-green-300 ring-1 ring-green-200' : ($dStatus === 'revision_requested' ? 'border-amber-300 ring-1 ring-amber-200' : 'border-slate-200') }}">
                                        @if($dStatus)
                                        <div class="absolute top-2 left-2 z-10">
                                            @if($dStatus === 'approved')
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-600 text-white shadow">✓ OK</span>
                                            @elseif($dStatus === 'revision_requested')
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500 text-white shadow">↩ Revisi</span>
                                            @endif
                                        </div>
                                        @endif
                                        <div class="absolute top-2 right-2 z-10">
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-black/40 text-white">v{{ $design['version'] ?? 1 }}</span>
                                        </div>
                                        @if(in_array($dExt, ['jpg','jpeg','png']))
                                            <a href="{{ config('app.golang_api_url') }}{{ $design['file_path'] }}" target="_blank">
                                                <img src="{{ config('app.golang_api_url') }}{{ $design['file_path'] }}" alt="Desain v{{ $design['version'] ?? 1 }}" class="w-44 h-44 object-contain bg-slate-50 p-2">
                                            </a>
                                        @else
                                            <a href="{{ config('app.golang_api_url') }}{{ $design['file_path'] }}" target="_blank" class="flex flex-col items-center justify-center w-44 h-44 bg-slate-50 hover:bg-slate-100 transition-colors p-4 text-center">
                                                <svg class="w-10 h-10 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ $dExt }}</span>
                                                <span class="text-[10px] text-primary-600 mt-1">Klik untuk buka</span>
                                            </a>
                                        @endif
                                        @if($design['notes'] ?? '')
                                            <div class="px-3 py-2 bg-slate-50 border-t border-slate-100 max-w-44">
                                                <p class="text-[10px] text-slate-500 line-clamp-2 italic">{{ $design['notes'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($latestDesignId)
                                @if($latestStatus === 'approved')
                                    <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl p-4">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-green-800 text-sm">Desain Sudah Disetujui ✅</p>
                                            @if($latestNotes)<p class="text-xs text-green-600 mt-0.5">{{ $latestNotes }}</p>@endif
                                            <p class="text-xs text-green-500 mt-1">Menunggu antrean cetak / produksi</p>
                                        </div>
                                    </div>
                                @else
                                    @if($latestStatus === 'revision_requested')
                                        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-3 mb-3">
                                            <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                            <div>
                                                <p class="text-xs font-bold text-amber-700">Revisi diminta sebelumnya.</p>
                                                @if($latestNotes)<p class="text-xs text-amber-600 mt-0.5">Catatan: "{{ $latestNotes }}"</p>@endif
                                            </div>
                                        </div>
                                    @endif
                                    <form method="POST" action="/staff/desain/{{ $latestDesignId }}/review" x-data="{ decision: '' }">
                                        @csrf
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Keputusan Review</p>
                                        <div class="grid grid-cols-2 gap-3 mb-4">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status" value="approved" x-model="decision" class="sr-only" required>
                                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all border-slate-200 hover:border-green-300" :class="decision === 'approved' ? 'border-green-500 bg-green-50' : ''">
                                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all" :class="decision === 'approved' ? 'border-green-500' : 'border-slate-300'">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 transition-all" :class="decision === 'approved' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold transition-colors" :class="decision === 'approved' ? 'text-green-700' : 'text-slate-700'">✅ Setujui</p>
                                                        <p class="text-xs text-slate-400">Desain diterima, lanjut cetak</p>
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status" value="rejected" x-model="decision" class="sr-only">
                                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all border-slate-200 hover:border-red-300" :class="decision === 'rejected' ? 'border-red-500 bg-red-50' : ''">
                                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all" :class="decision === 'rejected' ? 'border-red-500' : 'border-slate-300'">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 transition-all" :class="decision === 'rejected' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold transition-colors" :class="decision === 'rejected' ? 'text-red-700' : 'text-slate-700'">❌ Tolak</p>
                                                        <p class="text-xs text-slate-400">Minta customer upload ulang</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan untuk Customer <span class="text-red-400">*</span></label>
                                            <textarea name="notes" rows="2" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 focus:bg-white transition resize-none" :placeholder="decision === 'rejected' ? 'Jelaskan alasan penolakan.' : 'Contoh: Desain sudah oke, siap cetak!'"></textarea>
                                        </div>
                                        <button type="submit" :disabled="!decision" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-sm" :class="decision === 'rejected' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-600 hover:bg-green-700'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span x-text="decision === 'rejected' ? 'Kirim Penolakan' : (decision ? 'Setujui Desain' : 'Simpan Review')"></span>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl p-4">
                                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-sm text-amber-700 font-medium">Customer belum mengupload file desain untuk item ini.</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-slate-400">
                        <p class="text-sm">Tidak ada item dalam pesanan ini.</p>
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm py-20 text-center">
            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700 mb-1">Tidak ada antrean desain</h3>
            <p class="text-sm text-slate-400">Semua desain sudah diproses atau belum ada pesanan yang lunas.</p>
        </div>
        @endforelse
    </div>

    <!-- TAB HISTORY -->
    <div x-show="tab === 'history'" x-cloak>
        @forelse($history as $order)
        <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-4 overflow-hidden hover:shadow-md transition-shadow">
            <button @click="open = !open" class="w-full text-left px-6 py-4 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-green-100 text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-mono font-bold text-slate-800 text-sm">{{ $order['order_code'] ?? '-' }}</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                            {{ count($order['items'] ?? []) }} item
                        </span>
                        <span class="text-xs text-slate-500 font-medium">Desain Selesai</span>
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5 truncate">
                        <span class="font-medium text-slate-700">{{ $order['customer_name'] ?? 'Customer' }}</span>
                        &bull; Status Pesanan: {{ ucfirst($order['status']) }}
                    </p>
                </div>
                <svg class="w-5 h-5 text-slate-400 transition-transform shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak class="border-t border-slate-100 p-5 bg-slate-50/50">
                @if(!empty($order['items']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($order['items'] as $item)
                        @php
                            $itemDesigns = $item['designs'] ?? [];
                            $latestDesign = !empty($itemDesigns) ? end($itemDesigns) : null;
                        @endphp
                        <div class="bg-white border border-slate-200 p-4 rounded-xl flex items-start gap-3">
                            @if($latestDesign && in_array(strtolower(pathinfo($latestDesign['file_path'], PATHINFO_EXTENSION)), ['jpg','jpeg','png']))
                                <img src="{{ config('app.golang_api_url') }}{{ $latestDesign['file_path'] }}" class="w-16 h-16 object-contain bg-slate-50 rounded border">
                            @else
                                <div class="w-16 h-16 bg-slate-100 rounded border flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-slate-800 text-sm">{{ $item['product_name'] ?? '-' }}</p>
                                @if($latestDesign && ($latestDesign['status'] ?? '') === 'approved')
                                    <p class="text-xs text-green-600 font-semibold mt-1">✓ Disetujui (v{{ $latestDesign['version'] }})</p>
                                    @if($latestDesign['notes'] ?? '')
                                        <p class="text-[10px] text-slate-500 mt-0.5 italic">"{{ $latestDesign['notes'] }}"</p>
                                    @endif
                                @else
                                    <p class="text-xs text-slate-500 mt-1">Disetujui</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm py-20 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700 mb-1">Belum ada riwayat review</h3>
            <p class="text-sm text-slate-400">Desain yang telah disetujui akan muncul di sini.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
