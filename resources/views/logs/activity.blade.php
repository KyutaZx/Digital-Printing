@extends('layouts.manager')

@section('page_title', 'Aktivitas Pengguna')

@section('content')
<div class="fade-in">
    <div class="card overflow-x-auto">
        @if(empty($logs) || count($logs) == 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Log Kosong</h3>
                <p class="text-slate-500 text-sm">Belum ada aktivitas yang terekam.</p>
            </div>
        @else
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Aktivitas</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 whitespace-nowrap">{{ date('d/m/Y H:i:s', strtotime($log['created_at'] ?? 'now')) }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $log['user_name'] ?? 'System' }}</td>
                        <td class="p-4">{{ $log['action'] ?? '-' }}</td>
                        <td class="p-4 text-xs font-mono text-slate-400">{{ $log['ip_address'] ?? '127.0.0.1' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
