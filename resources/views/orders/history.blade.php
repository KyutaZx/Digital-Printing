@extends('layouts.app')

@section('title', 'Riwayat Pesanan — Jaya Mandiri')

@section('content')
@php
    $ordersList = is_array($orders) ? $orders : [];
    $orderCount = count($ordersList);
    $searchIndex = collect($ordersList)->map(fn ($o) => [
        'code' => $o['order_code'] ?? '',
        'product' => $o['items'][0]['product_name'] ?? '',
    ])->values();
@endphp
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-10 pb-20 fade-in"
     x-data="{
        search: '',
        index: @js($searchIndex),
        matches(code, product) {
            if (!this.search.trim()) return true;
            const q = this.search.toLowerCase();
            return String(code).toLowerCase().includes(q) || String(product).toLowerCase().includes(q);
        },
        get hasResults() {
            if (!this.search.trim()) return true;
            const q = this.search.toLowerCase();
            return this.index.some(i => i.code.toLowerCase().includes(q) || i.product.toLowerCase().includes(q));
        }
     }">

    @include('customer.partials.order-page-header', [
        'subtitle' => 'Arsip pesanan selesai dan dibatalkan',
    ])

    @if($orderCount > 0)
    <div class="mb-5 relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="search" x-model="search" placeholder="Cari riwayat pesanan..."
               class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 shadow-sm">
    </div>
    @endif

    <div class="space-y-4" x-ref="items">
        @forelse($ordersList as $order)
            @php $first = !empty($order['items']) ? ($order['items'][0]['product_name'] ?? '') : ''; @endphp
            <div data-order x-show="matches(@js($order['order_code'] ?? ''), @js($first))" x-transition.opacity.duration.150ms>
                @include('customer.partials.order-list-item', ['order' => $order, 'showActions' => false])
            </div>
        @empty
            @include('customer.partials.orders-empty', [
                'title' => 'Riwayat masih kosong',
                'message' => 'Pesanan yang sudah selesai atau dibatalkan akan tersimpan di sini.',
            ])
        @endforelse
    </div>

    @if($orderCount > 0)
    <p x-show="search.trim() && !hasResults" x-cloak class="text-center text-sm text-slate-500 py-10 bg-white rounded-2xl border border-slate-100">
        Tidak ada riwayat yang cocok dengan pencarian Anda.
    </p>
    @endif
</div>
@endsection
