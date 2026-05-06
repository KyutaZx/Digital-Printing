@extends('layouts.app')
@section('title', 'Kontak Kami — Jaya Mandiri')
@section('content')
<div class="pt-24 min-h-screen bg-slate-50 flex items-center justify-center">
    <div class="card p-10 max-w-2xl w-full text-center fade-in">
        <h1 class="text-3xl font-black text-slate-900 mb-4">Hubungi Kami</h1>
        <p class="text-slate-500 mb-8">Punya pertanyaan atau ingin konsultasi desain? Tim kami siap membantu Anda.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-primary-50 rounded-2xl border border-primary-100">
                <p class="font-bold text-primary-900 mb-1">WhatsApp</p>
                <p class="text-sm text-primary-700">0812-3456-7890</p>
            </div>
            <div class="p-6 bg-secondary-50 rounded-2xl border border-secondary-100">
                <p class="font-bold text-secondary-900 mb-1">Email</p>
                <p class="text-sm text-secondary-700">halo@jayamandiri.com</p>
            </div>
        </div>
    </div>
</div>
@endsection
