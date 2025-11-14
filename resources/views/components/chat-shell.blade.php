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
    
    {{-- Linkagens de Ícones e Fontes... --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Vite Assets --}}
    @vite(['resources/css/modern-chat.css', 'resources/js/app.js'])

    {{ $styles ?? '' }}
</head>
<body class="full-screen-chat-layout"> 
    
  <div class="overlay" id="overlay"></div>
    
    {{-- STATUS DE CONEXÃO: Necessário para a luz de status --}}
    <div class="connection-status" id="connectionStatus" style="display:none;"> 
        <div class="connection-dot" id="connectionDot"></div>
        <span id="connectionText">Conectando...</span>
    </div>

   <header class="app-header">
        <div class="header-left">
            {{-- MANTIDO: Botão Hambúrguer, mas será visível apenas no mobile via CSS --}}
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            {{-- REMOVIDO: <a href="..." id="headerBackLink"> --}}
            <h1 class="app-title">SOS-MULHER</h1>
        </div>
        <div class="header-right">
            <div class="header-actions">
                {{-- LUZ DE CONEXÃO ÚNICA --}}
                <div class="connection-dot" id="headerConnectionDot"></div> 
            </div>
        </div>
    </header>

    
    @if (isset($initialChatUserId))
<meta name="initial-chat-user-id" content="{{ $initialChatUserId }}">
@endif
    <div class="chat-container">
        
        {{-- CHAMADA DO COMPONENTE SIDEBAR --}}
        <x-chat-sidebar 
            :chats-recentes="$chatsRecentes" 
            :usuarios-nao-doutores="$usuariosNaoDoutores" 
        />

        {{-- SLOT PRINCIPAL: A ÁREA DE MENSAGENS E COMPOSER (x-chat-main-area) --}}
        {{ $slot }}

    </div>
    
    {{-- Script simplificado para remover a dependência do headerBackLink --}}
    <script>
        (function(){ 
            const backBtn = document.getElementById('backBtn'); 
            
            function goBack(){
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = '{{ route('index') }}';  
                }
            }
            if (backBtn) backBtn.addEventListener('click', goBack);
             
        })();
    </script>
    {{ $scripts ?? '' }}  
</body>
</html>  