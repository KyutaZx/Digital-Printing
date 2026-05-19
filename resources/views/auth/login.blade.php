@extends('layouts.app')

@section('title', 'Masuk — Jaya Mandiri')
@section('meta_description', 'Login ke akun Digital Printing Jaya Mandiri Anda.')

@section('content')
<div class="min-h-screen bg-slate-50 flex">
    
    {{-- Left Side: Image/Branding (Hidden on mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden">
        {{-- Background Image --}}
        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?q=80&w=2071&auto=format&fit=crop" 
             alt="Digital Printing" 
             class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
             
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-primary-900/60 to-transparent"></div>
        
        {{-- Content --}}
        <div class="relative z-10 w-full p-12 flex flex-col justify-between h-full">
            <div>
                <a href="/" class="flex items-center gap-3 w-fit group">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="text-2xl font-black text-white tracking-tight">Jaya<span class="text-primary-400">Mandiri</span></span>
                </a>
            </div>
            
            <div class="mb-10 fade-in">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">Solusi Cetak <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-300">Digital Profesional</span></h2>
                <p class="text-lg text-slate-300 max-w-md leading-relaxed">Kelola pesanan cetak Anda, pantau status produksi, dan kembangkan bisnis Anda bersama layanan printing terbaik kami.</p>
                

            </div>
        </div>
    </div>
    
    {{-- Right Side: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden">
        {{-- Decorative blobs for right side --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-primary-100 opacity-50 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-secondary-100 opacity-50 blur-3xl"></div>
        
        <div class="w-full max-w-md relative z-10 fade-in">
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center justify-center gap-3 mb-10">
                <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span class="text-3xl font-black text-slate-900 tracking-tight">Jaya<span class="text-primary-600">Mandiri</span></span>
            </div>

            <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 sm:p-10 shadow-2xl shadow-slate-200/50 border border-white">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang</h1>
                    <p class="text-slate-500 font-medium">Silakan masuk ke akun Anda</p>
                </div>

                @if($errors->any())
                <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-100 text-red-600 px-4 py-3.5 rounded-2xl text-sm shadow-sm">
                    <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div class="font-medium">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                <form method="POST" action="/login" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="form-input pl-11 bg-slate-50 border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500" placeholder="admin@jayamandiri.com">
                        </div>
                    </div>
                    
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="form-label mb-0">Password</label>
                            <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">Lupa password?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                   class="form-input pl-11 pr-12 bg-slate-50 border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500" placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-600 bg-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" type="checkbox" class="w-4 h-4 text-primary-600 bg-slate-100 border-slate-300 rounded focus:ring-primary-500 focus:ring-2">
                        <label for="remember" class="ml-2 text-sm font-medium text-slate-600">Ingat saya</label>
                    </div>

                    <button type="submit"
                            class="w-full btn-primary py-3.5 text-base shadow-lg shadow-primary-500/30">
                        Masuk Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Belum punya akun?
                        <a href="/register" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">Daftar sekarang</a>
                    </p>
                </div>
            </div>
            
            {{-- Footer Text --}}
            <div class="mt-8 text-center lg:text-left flex items-center justify-between">
                <p class="text-xs text-slate-400 font-medium">&copy; {{ date('Y') }} Jaya Mandiri Digital Printing.</p>
                <div class="flex gap-3">
                    <a href="#" class="text-xs text-slate-400 hover:text-primary-600 transition-colors">Privasi</a>
                    <a href="#" class="text-xs text-slate-400 hover:text-primary-600 transition-colors">Syarat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
