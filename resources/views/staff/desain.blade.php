@extends('layouts.staff')
@section('title', 'Review Desain')
@section('page_title', 'Review Desain Customer')

@section('content')
<div class="card">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-bold text-slate-900">Pesanan Siap Review Desain</h2>
        <p class="text-sm text-slate-500 mt-0.5">Pesanan sudah lunas dan menunggu approval desain sebelum dicetak</p>
    </div>
    <div class="divide-y divide-slate-100">
    @forelse($orders as $order)
    <div x-data="{ open: false }" class="px-6 py-5">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-mono font-bold text-primary-600 text-sm">{{ $order['order_code'] ?? '-' }}</span>
                    <span class="badge badge-blue">Lunas — Menunggu Desain</span>
                </div>
                <p class="text-sm text-slate-600">{{ $order['customer_name'] ?? 'Customer' }}</p>
            </div>
            <button @click="open = !open"
                    class="flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Review Desain
            </button>
        </div>

        {{-- Panel review (expand) --}}
        <div x-show="open" x-cloak class="mt-4 bg-slate-50 rounded-xl p-4 space-y-4 fade-in">
            @if(!empty($order['items']))
            @foreach($order['items'] as $item)
            <div class="bg-white rounded-xl p-4 border border-slate-200">
                <p class="font-semibold text-slate-900 text-sm mb-3">{{ $item['product_name'] ?? '-' }} (Qty: {{ $item['quantity'] ?? 1 }})</p>
                <form method="POST" action="/staff/desain/{{ $item['latest_design_id'] ?? 0 }}/review" class="space-y-3">
                    @csrf
                    <div>
                        <label class="form-label">Status Review</label>
                        <select name="status" class="form-input" required>
                            <option value="">Pilih Keputusan</option>
                            <option value="approved">✅ Setujui Desain</option>
                            <option value="rejected">❌ Tolak (Minta Revisi)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Catatan untuk Customer</label>
                        <textarea name="notes" class="form-input" rows="2" placeholder="Berikan komentar atau alasan penolakan..." required></textarea>
                    </div>
                    <button type="submit" class="btn-primary text-sm">Simpan Review</button>
                </form>
            </div>
            @endforeach
            @else
            <p class="text-sm text-slate-500 text-center py-4">Tidak ada item desain untuk di-review.</p>
            @endif
        </div>
    </div>
    @empty
    <div class="py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        <p>Tidak ada desain yang perlu direview saat ini</p>
    </div>
    @endforelse
    </div>
</div>
@endsection
