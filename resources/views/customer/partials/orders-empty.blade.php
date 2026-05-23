<div class="bg-white rounded-2xl border border-dashed border-slate-200 py-20 px-6 text-center">
    <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-gradient-to-br from-primary-50 to-slate-50 flex items-center justify-center">
        <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
    <h2 class="text-xl font-black text-slate-900 mb-2">{{ $title ?? 'Belum ada pesanan' }}</h2>
    <p class="text-sm text-slate-500 max-w-sm mx-auto mb-8">{{ $message ?? 'Pilih produk di katalog lalu checkout untuk memulai pesanan cetak.' }}</p>
    <a href="/katalog" class="btn-primary px-8 py-3">Jelajahi Katalog</a>
</div>
