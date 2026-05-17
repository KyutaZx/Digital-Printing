@extends('layouts.manager')

@section('page_title', 'Riwayat Login')

@section('content')
<div class="fade-in">
    <div class="card overflow-x-auto">
        @if(empty($attempts) || count($attempts) == 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Riwayat Kosong</h3>
                <p class="text-slate-500 text-sm">Belum ada percobaan login yang dicatat.</p>
            </div>
        @else
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">IP Address</th>
                        <th class="p-4">User Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($attempts as $attempt)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 whitespace-nowrap">{{ date('d/m/Y H:i:s', strtotime($attempt['created_at'] ?? 'now')) }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $attempt['email'] ?? '-' }}</td>
                        <td class="p-4">
                            @if(($attempt['status'] ?? '') == 'success')
                                <span class="badge badge-green">Sukses</span>
                            @else
                                <span class="badge badge-red">Gagal</span>
                            @endif
                        </td>
                        <td class="p-4 text-xs font-mono text-slate-400">{{ $attempt['ip_address'] ?? '-' }}</td>
                        <td class="p-4 text-xs text-slate-400 max-w-[200px] truncate" title="{{ $attempt['user_agent'] ?? '' }}">
                            {{ $attempt['user_agent'] ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
