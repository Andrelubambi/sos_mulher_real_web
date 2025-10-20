<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>SOS-MULHER</title>

    <link rel="apple-touch-icon" href="{{ asset('vendors/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('vendors/images/favicon-32x32.png') }}">
    <link rel="stylesheet" href="{{ asset('vendors/styles/core.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/styles/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-loading />

    <x-header />
    <x-sidebar :grupos="$grupos" />

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        @yield('content')
    </div>

    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
