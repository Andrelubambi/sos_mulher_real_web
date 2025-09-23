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
    <script type="module" src="{{ asset('js/chat/index.js') }}"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/chat.css') }}" />
</head>

<body>
    <!-- Connection Status -->
    <div class="connection-status" id="connectionStatus">
        <div class="connection-dot" id="connectionDot"></div>
        <span id="connectionText">Conectando...</span>
    </div>

    <!-- Header -->
    <header class="app-header">
        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
        <h1>SOS-MULHER • CHAT</h1>
        <div></div>
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
                            <i class="fas fa-comments"></i>
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
                            <i class="fas fa-users"></i>
                            <p>Nenhum usuário encontrado</p>
                        </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Área de Chat -->
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

                <!-- Botão de videochamada -->
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

            <form id="sendMessageForm" class="chat-input" style="display: none;">
                @csrf
                <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." rows="1"></textarea>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>

        <!-- Video Modal -->
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

    <!-- Scripts -->
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script src="https://unpkg.com/laravel-echo@1.15.3/dist/echo.iife.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos da interface
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    const backButton = document.getElementById('backButton');
    const chatArea = document.getElementById('chatArea');
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
    
    // Video call elements
    const videoCallBtn = document.getElementById('videoCallBtn');
    const videoModal = document.getElementById('videoModal');
    const closeVideoBtn = document.getElementById('closeVideoBtn');
    const jitsiFrame = document.getElementById('jitsiFrame');
    const videoCallTitle = document.getElementById('videoCallTitle');
    
    // Connection status elements
    const connectionStatus = document.getElementById('connectionStatus');
    const connectionDot = document.getElementById('connectionDot');
    const connectionText = document.getElementById('connectionText');

    let isCallActive = false;
    let currentRoomUrl = null;
    
    // IDs de usuário
    const usuarioLogadoId = {{ auth()->id() }};
    let usuarioAtualId = null;
    let currentChannel = null;

    // Configurar Laravel Echo
    let echoConnected = false;
   // Configurar Laravel Echo - conexão direta ao Echo Server
function initializeEcho() {
    try {
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: `${window.location.hostname}:6001`,
            transports: ['websocket'], // Sem polling fallback
            autoConnect: true,
            auth: {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }
        });

        window.Echo.connector.socket.on('connect', () => {
            console.log('Laravel Echo Server conectado!');
            echoConnected = true;
            updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.log('Laravel Echo Server desconectado:', reason);
            echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('Erro na conexão com Laravel Echo Server:', error);
            echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log('Laravel Echo Server reconectado após', attemptNumber, 'tentativas');
        });

        console.log('Laravel Echo inicializado');
    } catch (error) {
        console.error('Erro ao inicializar Laravel Echo:', error);
        updateConnectionStatus(false);
    }
}
    function updateConnectionStatus(connected) {
        if (connected) {
            connectionDot.classList.remove('disconnected');
            connectionText.textContent = 'Conectado';
        } else {
            connectionDot.classList.add('disconnected');
            connectionText.textContent = 'Desconectado';
        }
    }

    // Inicializar Echo
    initializeEcho();

    // Interface controls
    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
    });

    closeSidebar.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });

    backButton.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            chatArea.classList.remove('active');
            sidebar.style.display = 'flex';
        }
    });

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

    // Enable/disable video call button
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

    // Video call functionality
    videoCallBtn.addEventListener('click', function() {
        if (!usuarioAtualId || usuarioAtualId == usuarioLogadoId) {
            alert('Selecione um usuário para iniciar a videochamada');
            return;
        }
        
        videoCallBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        videoCallBtn.disabled = true;
        
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
            videoCallBtn.innerHTML = '<i class="fas fa-video"></i>';
            updateVideoCallButton();
        });
    });

    function startVideoCall(roomUrl, roomId) {
        videoCallTitle.textContent = `Videochamada - ${chatHeader.textContent}`;
        jitsiFrame.src = roomUrl;
        videoModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        isCallActive = true;
        videoCallBtn.innerHTML = '<i class="fas fa-phone"></i>';
        videoCallBtn.title = 'Chamada ativa';
        videoCallBtn.classList.add('active');
    }

    function endVideoCall() {
        videoModal.classList.remove('show');
        document.body.style.overflow = 'auto';
        jitsiFrame.src = 'about:blank';
        isCallActive = false;
        currentRoomUrl = null;
        videoCallBtn.classList.remove('active');
        updateVideoCallButton();
    }

    closeVideoBtn.addEventListener('click', endVideoCall);

    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            endVideoCall();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal.classList.contains('show')) {
            endVideoCall();
        }
    });

    // Função para adicionar mensagem ao chat
    function appendMessage(message, sentByMe = false) {
        const emptyState = messagesDiv.querySelector('.empty-state');
        if (emptyState) emptyState.remove();
        
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sentByMe ? 'sent' : 'received');
        messageDiv.innerHTML = `
            <div class="message-content">
                <strong>${sentByMe ? 'Você' : (message.remetente ? message.remetente.name : 'Usuário')}:</strong><br>
                ${message.conteudo.replace(/\n/g, '<br>')}<br>
                <div class="message-time">${new Date(message.created_at).toLocaleString()}</div>
            </div>
        `;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    // Escutar mensagens em tempo real com Echo
    function escutarMensagens(usuarioId) {
        if (!window.Echo) {
            console.log('Echo não disponível');
            return;
        }

        try {
            // Sair do canal anterior se existir
            if (currentChannel) {
                window.Echo.leave(currentChannel);
                console.log('Saiu do canal anterior:', currentChannel);
            }
            
            const minId = Math.min(usuarioLogadoId, usuarioId);
            const maxId = Math.max(usuarioLogadoId, usuarioId);
            const canal = `chat.${minId}-${maxId}`;
            
            currentChannel = canal;
            
            console.log('Conectando ao canal:', canal);

            window.Echo.private(canal)
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
            
            chatHeader.textContent = userName;
            currentUserAvatar.textContent = userName.charAt(0);
            userStatus.textContent = 'Online';
            updateVideoCallButton();
            
            // Mobile: mostrar chat area
            if (window.innerWidth < 992) {
                sidebar.classList.remove('active');
                chatArea.classList.add('active');
            }
            
            messagesDiv.innerHTML = '';
            sendMessageForm.style.display = 'flex';

            // Carregar mensagens
            fetch(`/chat/messages/${usuarioAtualId}`)
                .then(res => res.json())
                .then(messages => {
                    if (messages.length === 0) {
                        messagesDiv.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-comment"></i>
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

            // Escutar mensagens em tempo real
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
                conteudoInput.style.height = '45px';
            })
            .catch(err => {
                console.error('Erro ao enviar mensagem:', err);
                alert('Erro ao enviar mensagem.');
            });
    });

    // Auto-resize textarea
    conteudoInput.addEventListener('input', function() {
        this.style.height = '45px';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Enter para enviar (Shift+Enter para nova linha)
    conteudoInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessageForm.dispatchEvent(new Event('submit'));
        }
    });

    // Initialize
    updateVideoCallButton();
    updateConnectionStatus(false);

    // Cleanup
    window.addEventListener('beforeunload', function() {
        if (isCallActive) {
            endVideoCall();
        }
        if (currentChannel && window.Echo) {
            window.Echo.leave(currentChannel);
        }
    });

    // Retry connection every 10 seconds if disconnected
    setInterval(() => {
        if (!echoConnected && window.Echo) {
            console.log('Tentando reconectar...');
            try {
                window.Echo.connector.socket.connect();
            } catch (error) {
                console.error('Erro ao tentar reconectar:', error);
            }
        }
    }, 10000);
});
    </script>
</body>

</html>