```html
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Estilos Modernos -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/modern-chat.css') }}" />
    <!-- Scripts -->
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script src="https://unpkg.com/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    @vite('resources/js/chat/index.js')
</head>
<body>
    <!-- Overlay para mobile -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Status de Conexão -->
    <div class="connection-status" id="connectionStatus">
        <div class="connection-dot" id="connectionDot"></div>
        <span id="connectionText">Conectando...</span>
    </div>

    <!-- Header Moderno -->
    <header class="app-header">
        <div class="header-left">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="app-title">SOS-MULHER</h1>
        </div>
        <div class="header-right">
            <div class="header-actions">
                <button class="search-btn" id="searchBtn" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>
                <div class="status-indicator online"></div>
            </div>
        </div>
    </header>

    <!-- Container Principal -->
    <div class="chat-container">
        <!-- Sidebar Moderna -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">
                    <h2>Mensagens</h2>
                    <button class="new-chat-btn" id="newChatBtn" title="Nova conversa">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="sidebar-actions">
                    <button class="tab-btn active" id="tabMensagens">Recentes</button>
                    <button class="tab-btn" id="tabUsuarios">Usuários</button>
                </div>
            </div>
            
            <div class="tab-content">
                <!-- Conversas Recentes -->
                <div id="mensagensRecentes" class="tab-panel active">
                    <div class="conversations-list">
                        @forelse ($chatsRecentes as $chat)
                        <div class="conversation-item chat-item" data-user-id="{{ $chat['user']->id }}" data-user-name="{{ $chat['user']->name }}">
                            <div class="avatar-container">
                                <div class="avatar">{{ substr($chat['user']->name, 0, 1) }}</div>
                                <div class="online-status online"></div>
                            </div>
                            <div class="conversation-info">
                                <div class="user-name">{{ $chat['user']->name }}</div>
                                <div class="preview-message">{{ \Illuminate\Support\Str::limit($chat['mensagem']->conteudo, 50) }}</div>
                            </div>
                            <div class="conversation-meta">
                                <span class="timestamp">{{ \Carbon\Carbon::parse($chat['mensagem']->created_at)->format('H:i') }}</span>
                                @if($chat['unread_count'] > 0)
                                <div class="unread-badge">{{ $chat['unread_count'] }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-comments"></i>
                            <p>Nenhuma conversa recente</p>
                            <small>Inicie uma nova conversa</small>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Lista de Usuários -->
                <div id="listaUsuarios" class="tab-panel">
                    <div class="users-list">
                        @forelse ($usuariosNaoDoutores as $user)
                        <div class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="avatar-container">
                                <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                                <div class="online-status offline"></div>
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-status">Clique para iniciar conversa</div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Nenhum usuário encontrado</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </aside>

        <!-- Área de Chat Moderna -->
        <main class="chat-main" id="chatMain">
            <div class="chat-header">
                <div class="chat-header-left">
                    <button class="back-btn" id="backBtn">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="chat-participant">
                        <div class="avatar-container">
                            <div class="avatar" id="currentAvatar">U</div>
                            <div class="online-status online"></div>
                        </div>
                        <div class="participant-info">
                            <div class="participant-name" id="participantName">Selecione um usuário</div>
                            <div class="participant-status" id="participantStatus">Online</div>
                        </div>
                    </div>
                </div>
                <div class="chat-header-right">
                    <button class="video-call-btn" id="videoCallBtn" disabled>
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="more-options" title="Mais opções">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer">
                <div class="messages-list" id="messagesList">
                    <div class="welcome-message">
                        <i class="fas fa-comment-dots"></i>
                        <p>Selecione uma conversa para começar a bater papo</p>
                    </div>
                </div>
                
                <!-- Indicador de digitação -->
                <div class="typing-indicator" id="typingIndicator" style="display: none;">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <span class="typing-text">Digitando...</span>
                </div>
            </div>

            <!-- Formulário de Mensagem Moderna -->
            <div class="message-composer" id="messageComposer">
                <form id="sendMessageForm">
                    @csrf
                    <div class="composer-input-container">
                        <div class="composer-input-wrapper">
                            <textarea 
                                name="conteudo" 
                                id="messageInput" 
                                placeholder="Digite sua mensagem..." 
                                rows="1"
                                disabled
                            ></textarea>
                            <div class="composer-actions">
                                <button type="button" class="emoji-btn" title="Emojis">
                                    <i class="fas fa-smile"></i>
                                </button>
                                <button type="button" class="attach-btn" title="Anexar arquivo">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="send-btn" disabled>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Modal de Videochamada Moderna -->
        <div class="video-modal" id="videoModal">
            <div class="video-modal-overlay" onclick="endVideoCall()"></div>
            <div class="video-modal-content">
                <div class="video-header">
                    <h3 id="videoCallTitle">Videochamada</h3>
                    <button class="close-video-btn" id="closeVideoBtn" title="Fechar videochamada">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="video-frame-container">
                    <iframe id="jitsiFrame" class="video-frame" allow="camera; microphone; display-capture"></iframe>
                </div>
                <div class="video-controls">
                    <button class="video-control-btn" id="toggleCameraBtn" title="Ligar/Desligar câmera">
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="video-control-btn" id="toggleMicBtn" title="Ligar/Desligar microfone">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button class="video-control-btn" id="endCallBtn" title="Encerrar chamada">
                        <i class="fas fa-phone-slash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```