<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <title>CHAT | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">

    <!-- Ícones -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Add this CSS to your existing styles in chat.blade.php */

/* Enhanced video call button states */
.video-call-btn {
    background-color: var(--success-color);
    color: white;
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    cursor: pointer;
    transition: var(--transition);
    margin-left: 15px;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.video-call-btn:hover:not(:disabled) {
    background-color: #218838;
    transform: scale(1.05);
}

.video-call-btn:disabled {
    background-color: var(--text-light);
    cursor: not-allowed;
    transform: none;
    opacity: 0.5;
}

.video-call-btn.calling {
    background-color: #fd7e14;
    animation: pulse 1.5s infinite;
}

.video-call-btn.active {
    background-color: #dc3545;
}

/* Loading spinner for video button */
.video-call-btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Enhanced video modal */
.video-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3000;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
    backdrop-filter: blur(5px);
}

.video-modal.show {
    opacity: 1;
    visibility: visible;
}

.video-modal-content {
    width: 95%;
    height: 90%;
    max-width: 1400px;
    max-height: 900px;
    background-color: #1a1a1a;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    0% {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.video-modal-header {
    background-color: var(--primary-color);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.video-modal-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.video-modal-header::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
}

.close-video-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.close-video-btn:hover {
    background-color: rgba(220, 53, 69, 0.8);
    border-color: #dc3545;
    transform: rotate(90deg);
}

.video-frame-container {
    width: 100%;
    height: calc(100% - 70px);
    background-color: #000;
    position: relative;
    overflow: hidden;
}

.video-frame {
    width: 100%;
    height: 100%;
    border: none;
    background-color: #000;
}

/* Enhanced call indicator */
.call-indicator {
    position: fixed;
    top: 80px;
    right: 20px;
    background: linear-gradient(135deg, var(--success-color), #20c997);
    color: white;
    padding: 12px 18px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4);
    z-index: 1001;
    display: none;
    animation: slideInFromRight 0.3s ease-out, pulse 2s infinite;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

@keyframes slideInFromRight {
    0% {
        transform: translateX(100%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}

.call-indicator i {
    margin-right: 8px;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.5; }
}

/* Connection status indicator */
.connection-status {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 8px;
}

.connection-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--success-color);
    animation: connectionPulse 2s infinite;
}

@keyframes connectionPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Video call controls overlay */
.video-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 15px;
    z-index: 20;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.video-modal-content:hover .video-controls {
    opacity: 1;
}

.video-control-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-control-btn.mute {
    background-color: rgba(108, 117, 125, 0.8);
}

.video-control-btn.camera {
    background-color: rgba(23, 162, 184, 0.8);
}

.video-control-btn.end-call {
    background-color: rgba(220, 53, 69, 0.8);
}

.video-control-btn:hover {
    transform: scale(1.1);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .video-modal-content {
        width: 100%;
        height: 100%;
        border-radius: 0;
    }
    
    .video-call-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
        margin-left: 10px;
    }
    
    .call-indicator {
        top: 70px;
        right: 10px;
        padding: 10px 14px;
        font-size: 0.8rem;
    }
    
    .video-controls {
        bottom: 10px;
        gap: 10px;
    }
    
    .video-control-btn {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
}

/* Error states */
.video-error {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    background: rgba(220, 53, 69, 0.9);
    padding: 20px;
    border-radius: 8px;
    z-index: 100;
}

/* Loading state */
.video-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    z-index: 100;
}

.video-loading .spinner {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header */
        .app-header {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            z-index: 100;
        }

        .app-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Layout principal */
        .chat-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 320px;
            background: white;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            box-shadow: var(--shadow);
            z-index: 90;
        }

        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
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

        /* Responsividade */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: -320px;
                top: 0;
                bottom: 0;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }

            .close-sidebar {
                display: block;
            }

            .mobile-menu-btn {
                display: block;
            }

            .back-button {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .app-header h1 {
                font-size: 1.2rem;
            }

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

        /* Botão de videochamada no header do chat */
        .video-call-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            cursor: pointer;
            transition: var(--transition);
            margin-left: 15px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-call-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .video-call-btn:disabled {
            background-color: var(--text-light);
            cursor: not-allowed;
            transform: none;
        }

        /* Modal de videochamada */
        .video-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .video-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .video-modal-content {
            width: 95%;
            height: 90%;
            max-width: 1200px;
            max-height: 800px;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
        }

        .video-modal-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .video-modal-header h3 {
            margin: 0;
            font-size: 1.1rem;
        }

        .close-video-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .close-video-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .video-frame-container {
            width: 100%;
            height: calc(100% - 70px);
        }

        .video-frame {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Indicador de chamada ativa */
        .call-indicator {
            position: fixed;
            top: 80px;
            right: 20px;
            background-color: var(--success-color);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 0.85rem;
            box-shadow: var(--shadow);
            z-index: 1001;
            display: none;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Responsividade para modal de vídeo */
        @media (max-width: 768px) {
            .video-modal-content {
                width: 100%;
                height: 100%;
                border-radius: 0;
            }
            
            .video-call-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                margin-left: 10px;
            }
        }
    </style>
    <script src="https://cdn.socket.io/4.5.0/socket.io.min.js"></script>

<!-- Laravel Echo -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

<!-- Configuração do Echo para Socket.IO -->
<script>
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001', // Usando o container laravel_echo na porta 6001
        transports: ['websocket', 'polling'],
        upgrade: true,
        rememberUpgrade: false,
    });
</script>
</head>

<body>
    <!-- Header -->
    <header class="app-header">
        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
        <h1>SOS-MULHER • CHAT</h1>
        <div></div> <!-- Espaço para balancear o flexbox -->
    </header>

    <!-- Container principal -->
    <div class="chat-container">
        <!-- Sidebar -->
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

                  <!-- Botão de videochamada -->
                    <button class="video-call-btn" id="videoCallBtn" title="Iniciar videochamada" disabled>
                        <i class="fa fa-video-camera"></i>
                    </button>
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

    <!-- Indicador de chamada ativa -->
    <div class="call-indicator" id="callIndicator">
        <i class="fa fa-video-camera"></i> Videochamada ativa
    </div>
    </div>

    <!-- Notificação -->
    <div class="notification-alert" id="mensagemAlerta">
        <div class="notification-content">
            <strong>Nova mensagem SOS</strong>
            <p id="mensagemTextoCompleto">Você tem novas mensagens não lidas</p>
            <div class="notification-actions">
                <button class="btn-responder" id="enviarResposta">Responder</button>
                <button class="btn-fechar" id="fecharNotificacao">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Mensagem -->
    <div class="modal" id="mensagemModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Mensagem SOS</h3>
                <button id="fecharModal">×</button>
            </div>
            <div class="modal-body">
                <p id="mensagemConteudo"></p>
                <small id="mensagemData"></small>
            </div>
            <div class="modal-footer">
                <button class="btn-responder">Responder</button>
                <button class="btn-fechar">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
       // Add this JavaScript code to replace your existing <script> section in chat.blade.php

document.addEventListener('DOMContentLoaded', function() {
    // Elementos da interface
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    const backButton = document.getElementById('backButton');
    const tabMensagens = document.getElementById('tabMensagens');
    const tabUsuarios = document.getElementById('tabUsuarios');
    const mensagensRecentes = document.getElementById('mensagensRecentes');
    const listaUsuarios = document.getElementById('listaUsuarios');
    const messagesDiv = document.getElementById('messages');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const conteudoInput = document.getElementById('conteudo');
    const chatHeader = document.getElementById('chatHeader');
    const currentUserAvatar = document.getElementById('currentUserAvatar');
    const userStatus = document.getElementById('userStatus');
    const notificationAlert = document.getElementById('mensagemAlerta');
    const mensagemModal = document.getElementById('mensagemModal');
    
    // Video call elements
    const videoCallBtn = document.getElementById('videoCallBtn');
    const videoModal = document.getElementById('videoModal');
    const closeVideoBtn = document.getElementById('closeVideoBtn');
    const jitsiFrame = document.getElementById('jitsiFrame');
    const videoCallTitle = document.getElementById('videoCallTitle');
    const callIndicator = document.getElementById('callIndicator');

    let isCallActive = false;
    let currentRoomUrl = null;
    
    // IDs de usuário
    const usuarioLogadoId = {{ auth()->id() }};
    let usuarioAtualId = null;
    let currentChannel = null;

    // Verificar se Echo está disponível
    if (typeof Echo === 'undefined') {
        console.error('Laravel Echo não está carregado. Verifique os scripts.');
        alert('Sistema de mensagens em tempo real não está funcionando. Por favor, recarregue a página.');
    } else {
        console.log('Laravel Echo carregado com sucesso');
    }

    // Enable/disable video call button based on selected user
    function updateVideoCallButton() {
        if (usuarioAtualId && usuarioAtualId != usuarioLogadoId) {
            videoCallBtn.disabled = false;
            videoCallBtn.style.opacity = '1';
            videoCallBtn.title = 'Iniciar videochamada';
        } else {
            videoCallBtn.disabled = true;
            videoCallBtn.style.opacity = '0.5';
            videoCallBtn.title = 'Selecione um usuário para iniciar videochamada';
        }
    }

    // Start video call
    videoCallBtn.addEventListener('click', function() {
        if (!usuarioAtualId || usuarioAtualId == usuarioLogadoId) {
            alert('Selecione um usuário para iniciar a videochamada');
            return;
        }
        
        // Show loading state
        videoCallBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        videoCallBtn.disabled = true;
        
        // Get room URL from backend
        fetch(`/video-call/room/${usuarioAtualId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.room_url) {
                currentRoomUrl = data.room_url;
                startVideoCall(data.room_url, data.room_id);
            } else {
                throw new Error(data.error || 'Erro desconhecido ao iniciar videochamada');
            }
        })
        .catch(error => {
            console.error('Erro ao iniciar videochamada:', error);
            alert('Erro ao iniciar videochamada: ' + error.message);
        })
        .finally(() => {
            // Restore button state
            videoCallBtn.innerHTML = '<i class="fa fa-video-camera"></i>';
            updateVideoCallButton();
        });
    });

    function startVideoCall(roomUrl, roomId) {
        // Update modal title
        videoCallTitle.textContent = `Videochamada - Sala: ${roomId}`;
        
        // Configure Jitsi iframe
        jitsiFrame.src = roomUrl;
        
        // Show modal
        videoModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Show call indicator
        callIndicator.style.display = 'block';
        isCallActive = true;
        
        // Update video button state
        videoCallBtn.innerHTML = '<i class="fa fa-phone"></i>';
        videoCallBtn.title = 'Chamada ativa - Clique para voltar';
        videoCallBtn.classList.add('active');
        
        // Listen for iframe events (optional)
        window.addEventListener('message', handleJitsiEvents);
    }

    function endVideoCall() {
        // Hide modal
        videoModal.classList.remove('show');
        document.body.style.overflow = 'auto';
        
        // Clear iframe
        jitsiFrame.src = 'about:blank';
        
        // Hide call indicator
        callIndicator.style.display = 'none';
        isCallActive = false;
        currentRoomUrl = null;
        
        // Reset button state
        videoCallBtn.classList.remove('active');
        updateVideoCallButton();
        
        // Remove event listener
        window.removeEventListener('message', handleJitsiEvents);
    }

    // Close video call
    closeVideoBtn.addEventListener('click', endVideoCall);

    // Handle Jitsi events (optional)
    function handleJitsiEvents(event) {
        if (event.data && typeof event.data === 'object') {
            switch (event.data.type) {
                case 'video-conference-left':
                    endVideoCall();
                    break;
                case 'video-conference-joined':
                    console.log('Usuário entrou na videochamada');
                    break;
            }
        }
    }

    // Close modal when clicking outside
    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            endVideoCall();
        }
    });

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal.classList.contains('show')) {
            endVideoCall();
        }
    });

    // Alternar sidebar no mobile
    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
    });

    closeSidebar.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });

    // Botão voltar no mobile
    backButton.addEventListener('click', () => {
        document.querySelector('.chat-area').style.display = 'none';
        document.querySelector('.sidebar').style.display = 'flex';
    });

    // Alternar entre abas
    tabMensagens.addEventListener('click', () => {
        mensagensRecentes.style.display = 'block';
        listaUsuarios.style.display = 'none';
        tabMensagens.classList.add('active');
        tabUsuarios.classList.remove('active');
    });

    tabUsuarios.addEventListener('click', () => {
        mensagensRecentes.style.display = 'none';
        listaUsuarios.style.display = 'block';
        tabMensagens.classList.remove('active');
        tabUsuarios.classList.add('active');
    });

    // Função para adicionar mensagem ao chat
    function appendMessage(message, sentByMe = false) {
        // Remover estado vazio se existir
        const emptyState = messagesDiv.querySelector('.empty-state');
        if (emptyState) emptyState.remove();
        
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sentByMe ? 'sent' : 'received');
        messageDiv.innerHTML = `<div class="message-content">
            <strong>${sentByMe ? 'Você' : message.remetente.name}:</strong><br>
            ${message.conteudo.replace(/\n/g, '<br>')}<br>
            <div class="message-time">${new Date(message.created_at).toLocaleString()}</div>
        </div>`;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    // Escutar mensagens em tempo real
    function escutarMensagens(usuarioId) {
        if (typeof Echo === 'undefined') {
            console.error('Echo não está disponível');
            return;
        }

        const minId = Math.min(usuarioLogadoId, usuarioId);
        const maxId = Math.max(usuarioLogadoId, usuarioId);
        const canal = `chat.${minId}-${maxId}`;

        try {
            // Deixar canal anterior se existir
            if (currentChannel) {
                Echo.leave(currentChannel);
            }
            
            currentChannel = canal;

            Echo.private(canal)
                .listen('MessageSent', (e) => {
                    console.log('Nova mensagem recebida:', e);
                    if (e.de !== usuarioLogadoId) {
                        appendMessage(e, false);
                    }
                })
                .error((error) => {
                    console.error('Erro no canal:', error);
                });
        } catch (error) {
            console.error('Erro ao escutar mensagens:', error);
        }
    }

    // Selecionar usuário para conversa
    document.querySelectorAll('.user-item, .chat-item').forEach(item => {
        item.addEventListener('click', () => {
            usuarioAtualId = item.dataset.userId;
            const userName = item.dataset.userName;
            
            // Atualizar header do chat
            chatHeader.textContent = userName;
            currentUserAvatar.textContent = userName.charAt(0);
            userStatus.textContent = 'Online';
            
            // Update video call button state
            updateVideoCallButton();
            
            // Esconder sidebar e mostrar área de chat no mobile
            if (window.innerWidth < 992) {
                sidebar.classList.remove('active');
                document.querySelector('.sidebar').style.display = 'none';
                document.querySelector('.chat-area').style.display = 'flex';
            }
            
            // Limpar e carregar mensagens
            messagesDiv.innerHTML = '';
            sendMessageForm.style.display = 'flex';

            fetch(`/chat/messages/${usuarioAtualId}`)
                .then(res => res.json())
                .then(messages => {
                    if (messages.length === 0) {
                        messagesDiv.innerHTML = `
                            <div class="empty-state">
                                <i class="fa fa-comment"></i>
                                <p>Nenhuma mensagem ainda</p>
                                <small>Envie uma mensagem para iniciar a conversa</small>
                            </div>
                        `;
                    } else {
                        messages.forEach(msg => {
                            appendMessage(msg, msg.de == usuarioLogadoId);
                        });
                    }
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                })
                .catch(error => {
                    console.error('Erro ao carregar mensagens:', error);
                });

            // Iniciar escuta de mensagens em tempo real
            escutarMensagens(usuarioAtualId);
        });
    });

    // Enviar mensagem
    sendMessageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const conteudo = conteudoInput.value.trim();
        if (!conteudo || !usuarioAtualId) return;

        fetch(`/chat/send/${usuarioAtualId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
                body: JSON.stringify({
                    conteudo
                }),
            })
            .then(res => res.json())
            .then(data => {
                appendMessage(data, true);
                conteudoInput.value = '';
            })
            .catch(err => {
                console.error('Erro ao enviar mensagem:', err);
                alert('Erro ao enviar mensagem.');
            });
    });

    // Ajustar altura do textarea automaticamente
    conteudoInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Notificações SOS em tempo real
    let mensagensPendentes = [];
    let carregamentoConcluido = false;

    const userIdLogado = document.querySelector('meta[name="user-id"]').getAttribute('content');
    
    fetch('/mensagens_nao_lidas')
        .then(res => res.json())
        .then(dados => {
            if (dados && dados.length > 0) {
                mensagensPendentes = dados;
                atualizarAlerta();
            }
            carregamentoConcluido = true;
        })
        .catch(error => {
            console.error('Erro ao carregar mensagens SOS:', error);
        });

    // Escutar mensagens SOS em tempo real
    if (typeof Echo !== 'undefined' && !window.echoRegistered) {
        try {
            Echo.channel('mensagem_sos')
                .listen('.NovaMensagemSosEvent', (e) => {
                    console.log('Nova mensagem SOS recebida:', e);
                    if (String(e.user_id) !== userIdLogado) {
                        return;
                    }
                    const mensagem = {
                        id: e.id,
                        conteudo: e.conteudo,
                        data: e.data
                    };
                    mensagensPendentes.unshift(mensagem);
                    atualizarAlerta();
                })
                .error((error) => {
                    console.error('Erro no canal SOS:', error);
                });
            
            window.echoRegistered = true;
        } catch (error) {
            console.error('Erro ao registrar canal SOS:', error);
        }
    }

    function atualizarAlerta() {
        const alerta = document.getElementById('mensagemAlerta');
        const texto = document.getElementById('mensagemTextoCompleto');

        if (mensagensPendentes.length > 0) { 
            alerta.classList.add('show');
            texto.textContent = `Você tem ${mensagensPendentes.length} mensagem(ns) não lida(s)`;
        } else {
            alerta.classList.remove('show');
            texto.textContent = '';
        } 
    }

    document.getElementById('fecharNotificacao').addEventListener('click', () => {
        notificationAlert.classList.remove('show');
    });

    document.getElementById('enviarResposta').addEventListener('click', () => {
        const mensagemAtual = mensagensPendentes[0];
        if (mensagemAtual && mensagemAtual.id) {
            window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
        } else {
            alert('Mensagem inválida para responder.');
        }
    });

    document.getElementById('fecharModal').addEventListener('click', () => {
        mensagemModal.classList.remove('show');
    });

    // Initialize video button state on page load
    updateVideoCallButton();

    // Handle page unload to clean up video call
    window.addEventListener('beforeunload', function() {
        if (isCallActive) {
            endVideoCall();
        }
        // Limpar canais Echo
        if (currentChannel && typeof Echo !== 'undefined') {
            Echo.leave(currentChannel);
        }
    });
});
    </script>
</body>

</html>