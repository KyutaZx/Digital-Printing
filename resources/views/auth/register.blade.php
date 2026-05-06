@extends('layouts.app')

@section('title', 'Daftar — Jaya Mandiri')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden fade-in">

            <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 px-8 pt-10 pb-8 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h1 class="text-2xl font-black text-white">Buat Akun Baru</h1>
                <p class="text-secondary-100 text-sm mt-1">Bergabung dengan Jaya Mandiri</p>
            </div>

            <div class="px-8 py-8">
                @if($errors->any())
                <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                </div>
                @endif

                <form method="POST" action="/register" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="form-input" placeholder="Ahmad Setiawan">
                    </div>
                    <div>
                        <label class="form-label">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               class="form-input" placeholder="08123456789">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="form-input" placeholder="email@contoh.com">
                    </div>
                    <div x-data="{ show: false }">
                        <label class="form-label">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required
                                   class="form-input pr-12" placeholder="Min. 8 karakter">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="w-full btn-primary py-3 text-base !bg-secondary-500 hover:!bg-secondary-600">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="text-center text-sm text-slate-500 mt-6">
                    Sudah punya akun?
                    <a href="/login" class="text-primary-600 hover:text-primary-700 font-semibold">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
