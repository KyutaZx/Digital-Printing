@extends('layouts.auth')

@section('title', ($mode ?? 'login') === 'register' ? 'Daftar — Jaya Mandiri' : 'Masuk — Jaya Mandiri')

@section('content')
@php
    $authProps = [
        'initialIsSignIn' => ($mode ?? 'login') === 'login',
        'csrfToken' => csrf_token(),
        'loginUrl' => url('/login'),
        'registerUrl' => url('/register'),
        'oldValues' => old(),
        'errors' => $errors->all(),
        'successMessage' => session('success'),
    ];
@endphp

<script type="application/json" id="auth-props">@json($authProps)</script>
<div id="auth-root" class="min-h-screen w-full"></div>
@endsection
