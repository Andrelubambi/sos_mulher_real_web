@props(['chatsRecentes', 'usuariosNaoDoutores'])
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'CHAT | SOS-MULHER' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    
    {{-- Metas Essenciais para Laravel/Echo/Chat --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Vite Assets --}}
    @vite(['resources/css/modern-chat.css', 'resources/js/app.js'])

    {{ $styles ?? '' }} {{-- Slot para estilos adicionais --}}
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <div class="connection-status" id="connectionStatus">
        <div class="connection-dot" id="connectionDot"></div>
        <span id="connectionText">Conectando...</span>
    </div>

    <header class="app-header">
        <div class="header-left">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ url()->previous() }}" class="back-link" title="Voltar" style="margin-right: 8px; display:none;" id="headerBackLink">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="app-title">SOS-MULHER</h1>
        </div>
        <div class="header-right">
            <div class="header-actions">
                <div class="status-indicator online"></div>
            </div>
        </div>
    </header>

    <div class="chat-container">
        
        {{-- CHAMADA DO COMPONENTE SIDEBAR --}}
        <x-chat-sidebar 
            :chats-recentes="$chatsRecentes" 
            :usuarios-nao-doutores="$usuariosNaoDoutores" 
        />

        {{-- SLOT PRINCIPAL: A ÁREA DE MENSAGENS E COMPOSER --}}
        {{ $slot }}

    </div>
    
    {{-- Script para o botão Voltar (mantido no layout, pois é de controle global) --}}
    <script>
        (function(){
            const backBtn = document.getElementById('backBtn');
            const headerBackLink = document.getElementById('headerBackLink');
            function goBack(){
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = '{{ route('index') }}';  
                }
            }
            if (backBtn) backBtn.addEventListener('click', goBack);
            if (headerBackLink) {
                headerBackLink.style.display = 'inline-flex';
                headerBackLink.addEventListener('click', function(e){ e.preventDefault(); goBack(); });
            }
        })();
    </script>
    {{ $scripts ?? '' }} {{-- Slot para scripts adicionais --}}
</body>
</html>