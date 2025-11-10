@extends('layouts.app')

@section('title', 'Chat | SOS-MULHER')

@push('styles')
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #8e44ad;
        --primary-dark: #7d3c98;
        --secondary-color: #f39c12;
        --light-bg: #f8f9fa;
        --dark-bg: #343a40;
        --text-color: #333;
        --text-light: #6c757d;
        --border-color: #dee2e6;
        --success-color: #28a745;
        --online-status: #28a745;
        --offline-status: #dc3545;
        --chat-left-bg: #e9ecef;
        --chat-right-bg: #d1ecf1;
        --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    .main-container {
        padding: 0 !important;
        height: calc(100vh - 60px);
        overflow: hidden;
    }

    /* Layout principal */
    .chat-container {
        display: flex;
        height: 100%;
        overflow: hidden;
    }

    /* Sidebar */
    .chat-sidebar {
        width: 320px;
        background: white;
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        transition: var(--transition);
        box-shadow: var(--shadow);
        z-index: 90;
    }

    .chat-sidebar-header {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-sidebar-header h2 {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .close-sidebar {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-light);
    }

    .tab-buttons {
        display: flex;
        border-bottom: 1px solid var(--border-color);
    }

    .tab-buttons button {
        flex: 1;
        padding: 12px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
    }

    .tab-buttons button.active {
        background-color: var(--primary-color);
        color: white;
    }

    .tab-content {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .user-list, .recent-chats {
        list-style: none;
    }

    .user-item, .chat-item {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .user-item:hover, .chat-item:hover {
        background-color: var(--light-bg);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-weight: 600;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .last-message {
        font-size: 0.85rem;
        color: var(--text-light);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .timestamp {
        font-size: 0.75rem;
        color: var(--text-light);
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-left: 8px;
    }

    .online {
        background-color: var(--online-status);
    }

    .offline {
        background-color: var(--offline-status);
    }

    /* Área de chat */
    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: white;
    }

    .chat-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        background-color: white;
        box-shadow: var(--shadow);
        z-index: 10;
    }

    .back-button {
        display: none;
        background: none;
        border: none;
        font-size: 1.2rem;
        margin-right: 12px;
        cursor: pointer;
    }

    .current-chat-user {
        display: flex;
        align-items: center;
    }

    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #f0f2f5;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
    }

    .message.received {
        justify-content: flex-start;
    }

    .message.sent {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 18px;
        position: relative;
    }

    .received .message-content {
        background-color: var(--chat-left-bg);
        border-top-left-radius: 4px;
    }

    .sent .message-content {
        background-color: var(--chat-right-bg);
        border-top-right-radius: 4px;
    }

    .message-time {
        font-size: 0.7rem;
        color: var(--text-light);
        margin-top: 5px;
        text-align: right;
    }

    .chat-input {
        padding: 15px;
        border-top: 1px solid var(--border-color);
        display: flex;
        background-color: white;
    }

    .chat-input textarea {
        flex: 1;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        resize: none;
        height: 45px;
        font-family: inherit;
        margin-right: 10px;
    }

    .chat-input button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        cursor: pointer;
        transition: var(--transition);
    }

    .chat-input button:hover {
        background-color: var(--primary-dark);
    }

    /* Notificações */
    .notification-alert {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: white;
        border-left: 4px solid var(--secondary-color);
        padding: 15px;
        border-radius: 8px;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        z-index: 1000;
        max-width: 320px;
        transform: translateX(150%);
        transition: transform 0.3s ease;
    }

    .notification-alert.show {
        transform: translateX(0);
    }

    .notification-content {
        flex: 1;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .notification-actions button {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .btn-responder {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-fechar {
        background-color: var(--light-bg);
        color: var(--text-color);
    }

    /* Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
    }

    .modal.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        padding: 20px;
        box-shadow: var(--shadow);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .modal-body {
        margin-bottom: 20px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Estados vazios */
    .empty-state {
        text-align: center;
        padding: 30px 15px;
        color: var(--text-light);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Responsividade */
    @media (max-width: 992px) {
        .chat-sidebar {
            position: fixed;
            left: -320px;
            top: 0;
            bottom: 0;
            z-index: 1000;
        }

        .chat-sidebar.active {
            left: 0;
        }

        .close-sidebar {
            display: block;
        }

        .back-button {
            display: block;
        }
    }

    @media (max-width: 576px) {
        .message-content {
            max-width: 85%;
        }

        .chat-input {
            padding: 10px;
        }

        .chat-input textarea {
            margin-right: 8px;
        }
    }
</style>
@endpush

@section('content')
<!-- Container principal do chat -->
<div class="chat-container">
    <!-- Sidebar -->
    <div class="chat-sidebar" id="chatSidebar">
        <div class="chat-sidebar-header">
            <h2>Conversas</h2>
            <button class="close-sidebar" id="closeSidebar">×</button>
        </div>

        <div class="tab-buttons">
            <button id="tabMensagens" class="active">Mensagens</button>
            <button id="tabUsuarios">Usuários</button>
        </div>

        <div class="tab-content">
            <!-- Mensagens Recentes -->
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
                        <i class="fa fa-comments"></i>
                        <p>Nenhuma conversa recente</p>
                    </div>
                    @endforelse
                </ul>
            </div>

            <!-- Lista de Usuários -->
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
                        <i class="fa fa-users"></i>
                        <p>Nenhum usuário encontrado</p>
                    </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Área de Chat -->
    <div class="chat-area">
        <div class="chat-header">
            <button class="back-button" id="backButton">←</button>
            <div class="current-chat-user">
                <div class="user-avatar" id="currentUserAvatar">U</div>
                <div>
                    <div id="chatHeader">Selecione um usuário</div>
                    <small id="userStatus" class="text-muted">Disponível</small>
                </div>
            </div>
        </div>

        <div id="messages" class="chat-messages">
            <div class="empty-state">
                <i class="fa fa-comment"></i>
                <p>Selecione uma conversa para começar</p>
            </div>
        </div>

        <form id="sendMessageForm" class="chat-input" style="display: none;">
            @csrf
            <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." rows="1"></textarea>
            <button type="submit">
                <i class="fa fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<!-- Notificação -->
<div class="notification-alert" id="notificationAlert">
    <div class="notification-content">
        <strong>Nova mensagem SOS</strong>
        <p id="notificationText">Você tem novas mensagens não lidas</p>
        <div class="notification-actions">
            <button class="btn-responder" id="btnResponder">Responder</button>
            <button class="btn-fechar" id="btnFecharNotificacao">Fechar</button>
        </div>
    </div>
</div>
 
@endsection

@push('scripts')
{{-- Garanta que o app.js (que importa chat.js, echo.js e mensagens.js) seja carregado --}}
<script src="{{ asset('js/app.js') }}" defer></script> 

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Lógica para carregar o chat da vítima imediatamente (se veio de um SOS)
        const initialUserIdMeta = document.querySelector('meta[name="initial-chat-user-id"]');
        
        if (initialUserIdMeta) {
            const initialUserId = initialUserIdMeta.getAttribute('content');
            
            // Um pequeno atraso é necessário para garantir que o DOM (user-item) e o JS (setupChat) estejam prontos.
            setTimeout(() => {
                const targetItem = document.querySelector(`.user-item[data-user-id="${initialUserId}"], .chat-item[data-user-id="${initialUserId}"]`);
                
                if (targetItem) {
                    console.log(`[Chat SOS] Selecionando usuário ID: ${initialUserId}`);
                    // Simula o clique no item de chat para carregar a conversa
                    targetItem.click(); 
                    
                    // Se estiver em mobile, garanta que a sidebar seja fechada
                    const chatSidebar = document.getElementById('chatSidebar');
                    if (window.innerWidth < 992) {
                        chatSidebar.classList.remove('active');
                    }
                } else {
                    console.warn(`[Chat SOS] Usuário ID ${initialUserId} não encontrado na lista de chats.`);
                }
            }, 500); 
        }

        // 2. Seus listeners de UI móvel (devem permanecer aqui se não estiverem no setupUI/setupChat)
        // Isso é o mínimo para garantir que a interface funcione no mobile.
        const closeSidebar = document.getElementById('closeSidebar');
        const chatSidebar = document.getElementById('chatSidebar');
        const backButton = document.getElementById('backButton');

        closeSidebar?.addEventListener('click', () => {
            chatSidebar.classList.remove('active');
        });

        backButton?.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                // Você pode precisar de uma classe para esconder/mostrar a área de chat/sidebar no CSS/JS.
                // Usando o seu estilo atual (menos recomendado, mas funciona):
                document.querySelector('.chat-area').style.display = 'flex'; // Mostra a área de chat
                document.querySelector('.chat-sidebar').style.display = 'none'; // Esconde a sidebar
            }
        });
        
        // Tab switching logic (se não estiver no setupUI)
        document.getElementById('tabMensagens')?.addEventListener('click', () => {
             document.getElementById('mensagensRecentes').style.display = 'block';
             document.getElementById('listaUsuarios').style.display = 'none';
             document.getElementById('tabMensagens').classList.add('active');
             document.getElementById('tabUsuarios').classList.remove('active');
        });

        document.getElementById('tabUsuarios')?.addEventListener('click', () => {
             document.getElementById('mensagensRecentes').style.display = 'none';
             document.getElementById('listaUsuarios').style.display = 'block';
             document.getElementById('tabMensagens').classList.remove('active');
             document.getElementById('tabUsuarios').classList.add('active');
        });
        
    });
</script>
@endpush