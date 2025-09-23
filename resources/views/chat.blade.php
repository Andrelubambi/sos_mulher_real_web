<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>CHAT | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/chat.css') }}" />
    @vite('resources/js/chat/index.js')
</head>
<body>
    <div class="connection-status" id="connectionStatus">
        <div class="connection-dot" id="connectionDot"></div>
        <span id="connectionText">Conectando...</span>
    </div>
    <header class="app-header">
        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
        <h1>SOS-MULHER • CHAT</h1>
        <div></div>
    </header>
<div class="chat-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Conversas</h2>
                <button class="close-sidebar" id="closeSidebar">×</button>
            </div>
            <div class="tab-buttons">
                <button id="tabMensagens" class="active">Mensagens</button>
                <button id="tabUsuarios">Usuários</button>
            </div>
            <div class="tab-content">
                <div id="mensagensRecentes">
                    <ul class="recent-chats">
                        @forelse ($chatsRecentes as $chat)
                        <li class="chat-item" data-user-id="{{ $chat['user']->id }}" data-user-name="{{ $chat['user']->name }}">
                            <div class="user-avatar">{{ substr($chat['user']->name, 0, 1) }}</div>
                            <div class="user-info">
                                <div class="user-name">{{ $chat['user']->name }}</div>
                                <div class="last-message">{{ \Illuminate\Support\Str::limit($chat['mensagem']->conteudo, 40) }}</div>
                            </div>
                            <div class="timestamp">{{ \Carbon\Carbon::parse($chat['mensagem']->created_at)->format('H:i') }}</div>
                        </li>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-comments"></i>
                            <p>Nenhuma conversa recente</p>
                        </div>
                        @endforelse
                    </ul>
                </div>
                <div id="listaUsuarios" style="display: none;">
                    <ul class="user-list">
                        @forelse ($usuariosNaoDoutores as $user)
                        <li class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="last-message">Clique para iniciar conversa</div>
                            </div>
                            <div class="status-indicator offline"></div>
                        </li>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Nenhum usuário encontrado</p>
                        </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="chat-area" id="chatArea">
            <div class="chat-header">
                <button class="back-button" id="backButton">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="current-chat-user">
                    <div class="user-avatar" id="currentUserAvatar">U</div>
                    <div>
                        <div id="chatHeader">Selecione um usuário</div>
                        <small id="userStatus" class="text-muted">Disponível</small>
                    </div>
                </div>
                <button class="video-call-btn" id="videoCallBtn" title="Iniciar videochamada" disabled>
                    <i class="fas fa-video"></i>
                </button>
            </div>
            <div id="messages" class="chat-messages">
                <div class="empty-state">
                    <i class="fas fa-comment"></i>
                    <p>Selecione uma conversa para começar</p>
                </div>
            </div>
            <form id="sendMessageForm" class="chat-input hidden">
                @csrf
                <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." rows="1"></textarea>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
        <div class="video-modal" id="videoModal">
            <div class="video-modal-content">
                <div class="video-modal-header">
                    <h3 id="videoCallTitle">Videochamada - Carregando...</h3>
                    <button class="close-video-btn" id="closeVideoBtn" title="Fechar videochamada">
                        ×
                    </button>
                </div>
                <div class="video-frame-container">
                    <iframe id="jitsiFrame" class="video-frame" allow="camera; microphone; display-capture">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</body>
</html>