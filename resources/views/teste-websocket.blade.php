<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Broadcast Redis Socket io - Messages</title>

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.css" />

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <h1>Laravel Broadcast Redis Socket io - Messages</h1>
        <div id="notification"></div>
    </div>

    {{-- Porta definida para o Laravel Echo (opcional, pode ser usado direto no JS) --}}
    <script>
        window.laravel_echo_port = '{{ env("LARAVEL_ECHO_PORT", 6001) }}';
    </script>

    {{-- Socket.io (importado via CDN baseado no host + porta) --}}
    <script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT', 6001) }}/socket.io/socket.io.js"></script>

    {{-- laravel-echo-setup.js deve ser incluído via Vite também (veja nota abaixo) --}}
</body>
</html>
