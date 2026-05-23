@php
    $activeCount = $activeCount ?? 0;
    $historyHint = $historyHint ?? false;
@endphp
<div class="rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900 text-white p-6 sm:p-8 mb-6 shadow-lg shadow-slate-900/10">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-primary-300 mb-2">Akun Saya</p>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Pesanan Saya</h1>
            <p class="text-sm text-slate-300 mt-2 max-w-md">{{ $subtitle ?? 'Lacak progres cetak dan kelola pembayaran dengan mudah' }}</p>
        </div>
        @if(isset($activeCount) && request()->routeIs('orders.index'))
        <div class="flex gap-3 shrink-0">
            <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-3 border border-white/10 min-w-[100px]">
                <p class="text-[10px] font-bold text-slate-300 uppercase">Aktif</p>
                <p class="text-2xl font-black mt-0.5">{{ $activeCount }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="flex flex-wrap gap-2 mt-6 pt-6 border-t border-white/10">
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('orders.index') ? 'bg-white text-primary-700 shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Sedang Berjalan
        </a>
        <a href="{{ route('orders.history') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('orders.history') ? 'bg-white text-primary-700 shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Riwayat
        </a>
        <a href="/katalog" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-emerald-500 hover:bg-emerald-400 text-white ml-auto transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Pesan Baru
        </a>
    </div>
</div>
