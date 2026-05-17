@extends('layouts.manager')

@section('page_title', 'Audit Trail (Data Changes)')

@section('content')
<div class="fade-in">
    <div class="card overflow-x-auto">
        @if(empty($audits) || count($audits) == 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Audit Trail Kosong</h3>
                <p class="text-slate-500 text-sm">Belum ada perubahan data kritikal yang tercatat.</p>
            </div>
        @else
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Entitas</th>
                        <th class="p-4">Aksi</th>
                        <th class="p-4">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($audits as $audit)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 whitespace-nowrap">{{ date('d/m/Y H:i:s', strtotime($audit['created_at'] ?? 'now')) }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $audit['user_name'] ?? 'System' }}</td>
                        <td class="p-4"><span class="badge badge-gray">{{ $audit['entity'] ?? '-' }}</span></td>
                        <td class="p-4">
                            @if(strtolower($audit['action'] ?? '') == 'create')
                                <span class="badge badge-green">Create</span>
                            @elseif(strtolower($audit['action'] ?? '') == 'update')
                                <span class="badge badge-yellow">Update</span>
                            @elseif(strtolower($audit['action'] ?? '') == 'delete')
                                <span class="badge badge-red">Delete</span>
                            @else
                                <span class="badge badge-blue">{{ $audit['action'] ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-xs font-mono text-slate-500 bg-slate-50 rounded p-2 m-2 inline-block max-w-xs truncate" title="{{ $audit['changes'] ?? '' }}">
                            {{ Str::limit($audit['changes'] ?? '{}', 50) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
