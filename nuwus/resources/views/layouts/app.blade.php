<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('nuwus.company.name'))</title>
    <meta name="description" content="@yield('description', config('nuwus.company.name').' — studio pengembang perangkat lunak di Kota Malang. Portofolio produk website & aplikasi.')">
    <script>document.documentElement.classList.add('js')</script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nuwus.css') }}?v=6">
    @stack('head')
</head>
<body class="@yield('body_class')">
    @yield('body')
    <script src="{{ asset('js/nuwus.js') }}?v=4" defer></script>
    @stack('scripts')
</body>
</html>
