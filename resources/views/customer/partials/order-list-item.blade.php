@php
    $s = $order['status'] ?? '';
    $firstItem = !empty($order['items']) ? $order['items'][0] : null;
    $itemCount = count($order['items'] ?? []);
    $created = isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at']) : null;
    $needsAction = in_array($s, ['waiting_payment', 'ready']);
@endphp
<article class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:shadow-md hover:border-primary-200/50 transition-all group">
    {{-- Header bar --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5 bg-slate-50/80 border-b border-slate-100">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
            <span class="font-mono font-bold text-slate-800">{{ $order['order_code'] ?? '-' }}</span>
            @if($created)
                <span class="text-slate-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $created->translatedFormat('d M Y, H:i') }}
                </span>
            @endif
        </div>
        @include('customer.partials.order-status', ['status' => $s])
    </div>

    {{-- Product row --}}
    <a href="/pesanan/{{ $order['id'] }}" class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50/50 transition-colors">
        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-slate-100 border border-slate-100 overflow-hidden shrink-0">
            @if(!empty($firstItem['product_image']))
                <img src="{{ url('/api-proxy/' . ltrim($firstItem['product_image'] ?? '', '/')) }}" alt="" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            @if($firstItem)
                <p class="font-bold text-slate-900 text-sm sm:text-base truncate group-hover:text-primary-700 transition-colors">{{ $firstItem['product_name'] }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $firstItem['quantity'] ?? 0 }} pcs
                    @if(!empty($firstItem['variant_name'])) · {{ $firstItem['variant_name'] }} @endif
                    @if($itemCount > 1)
                        <span class="text-primary-600 font-semibold"> · +{{ $itemCount - 1 }} item</span>
                    @endif
                </p>
            @else
                <p class="text-sm text-slate-500">Lihat detail pesanan</p>
            @endif
        </div>
        <svg class="w-5 h-5 text-slate-300 shrink-0 group-hover:text-primary-500 transition-colors hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>

    @if($s === 'waiting_payment')
    <div class="mx-5 mb-0 px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-100 flex items-start gap-2">
        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs font-medium text-amber-800">Upload desain dan selesaikan pembayaran agar pesanan segera diproses.</p>
    </div>
    @elseif($s === 'ready')
    <div class="mx-5 mb-0 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-100 flex items-start gap-2">
        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-xs font-medium text-emerald-800">Pesanan siap diambil. Konfirmasi jika sudah diterima.</p>
    </div>
    @endif

    {{-- Footer actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-5 py-4 border-t border-slate-100 {{ $needsAction ? 'bg-slate-50/30' : '' }}">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total pembayaran</p>
            <p class="text-lg sm:text-xl font-black text-slate-900">Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="flex flex-wrap gap-2 sm:justify-end">
            <a href="/pesanan/{{ $order['id'] }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all min-w-[88px]">
                Detail
            </a>
            @if($showActions ?? true)
                @if($s === 'waiting_payment')
                    <a href="/pesanan/{{ $order['id'] }}/upload-desain"
                       class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 shadow-sm shadow-primary-600/20 transition-all min-w-[120px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Bayar Sekarang
                    </a>
                @elseif($s === 'ready')
                    <form action="/pesanan/{{ $order['id'] }}/selesai" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/20 transition-all min-w-[120px]">
                            Konfirmasi Selesai
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</article>
