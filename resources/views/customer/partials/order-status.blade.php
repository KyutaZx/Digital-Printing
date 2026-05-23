@php
    $statusMap = [
        'waiting_payment' => ['label' => 'Perlu Pembayaran', 'dot' => 'bg-slate-400', 'bg' => 'bg-slate-50 text-slate-700 border-slate-200'],
        'payment_verification' => ['label' => 'Sedang Diverifikasi', 'dot' => 'bg-amber-500', 'bg' => 'bg-amber-50 text-amber-800 border-amber-200'],
        'paid' => ['label' => 'Verifikasi Desain', 'dot' => 'bg-blue-500', 'bg' => 'bg-blue-50 text-blue-800 border-blue-200'],
        'design_review' => ['label' => 'Verifikasi Desain', 'dot' => 'bg-blue-500', 'bg' => 'bg-blue-50 text-blue-800 border-blue-200'],
        'printing' => ['label' => 'Sedang Dicetak', 'dot' => 'bg-purple-500', 'bg' => 'bg-purple-50 text-purple-800 border-purple-200'],
        'ready' => ['label' => 'Siap Diambil', 'dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
        'completed' => ['label' => 'Selesai', 'dot' => 'bg-green-500', 'bg' => 'bg-green-50 text-green-800 border-green-200'],
        'cancelled' => ['label' => 'Dibatalkan', 'dot' => 'bg-red-400', 'bg' => 'bg-red-50 text-red-700 border-red-200'],
    ];
    $st = $status ?? '';
    $info = $statusMap[$st] ?? ['label' => ucfirst($st), 'dot' => 'bg-slate-400', 'bg' => 'bg-slate-50 text-slate-600 border-slate-200'];
@endphp
<span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-bold border {{ $info['bg'] }}">
    <span class="w-2 h-2 rounded-full {{ $info['dot'] }}"></span>
    {{ $info['label'] }}
</span>
