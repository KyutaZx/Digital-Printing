<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi — Jaya Mandiri')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-j.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/auth-page.jsx'])
    @stack('head')
</head>
<body class="bg-white text-slate-900 antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
