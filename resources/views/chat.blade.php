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
    <link rel="stylesheet" type="text/css" href="vendors/styles/chat.css" />

<!-- Laravel Echo -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js" integrity="sha384-my6JkS7z1+r4r8eYg2l8v9d1a+C5U+G7wF7G7r2zFz5uG8d1b+F6oGz7C2o+B5G" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.min.js"></script>

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

    // Verificar se Echo está funcionando
    console.log('Verificando Echo...');
    setTimeout(() => {
        if (typeof Echo !== 'undefined') {
            console.log('Echo carregado com sucesso');
            // Testar conexão
            try {
                Echo.channel('test-channel');
                console.log('Echo conectado');
            } catch (error) {
                console.error('Erro na conexão Echo:', error);
            }
        } else {
            console.error('Echo não foi carregado');
        }
    }, 1000);

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
        
        videoCallBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
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
            videoCallBtn.innerHTML = '<i class="fa fa-video-camera"></i>';
            updateVideoCallButton();
        });
    });

    function startVideoCall(roomUrl, roomId) {
        videoCallTitle.textContent = `Videochamada - ${chatHeader.textContent}`;
        jitsiFrame.src = roomUrl;
        videoModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        callIndicator.style.display = 'block';
        isCallActive = true;
        videoCallBtn.innerHTML = '<i class="fa fa-phone"></i>';
        videoCallBtn.title = 'Chamada ativa';
        videoCallBtn.classList.add('active');
    }

    function endVideoCall() {
        videoModal.classList.remove('show');
        document.body.style.overflow = 'auto';
        jitsiFrame.src = 'about:blank';
        callIndicator.style.display = 'none';
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

    // Interface controls
    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
    });

    closeSidebar.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });

    backButton.addEventListener('click', () => {
        document.querySelector('.chat-area').style.display = 'none';
        document.querySelector('.sidebar').style.display = 'flex';
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

    // Função para adicionar mensagem ao chat
    function appendMessage(message, sentByMe = false) {
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
            console.log('Echo não disponível, usando polling');
            return;
        }

        try {
            const minId = Math.min(usuarioLogadoId, usuarioId);
            const maxId = Math.max(usuarioLogadoId, usuarioId);
            const canal = `chat.${minId}-${maxId}`;

            if (currentChannel) {
                Echo.leave(currentChannel);
            }
            
            currentChannel = canal;
            
            console.log('Conectando ao canal:', canal);

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
            
            chatHeader.textContent = userName;
            currentUserAvatar.textContent = userName.charAt(0);
            userStatus.textContent = 'Online';
            updateVideoCallButton();
            
            if (window.innerWidth < 992) {
                sidebar.classList.remove('active');
                document.querySelector('.sidebar').style.display = 'none';
                document.querySelector('.chat-area').style.display = 'flex';
            }
            
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

    conteudoInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Notificações SOS simplificadas
    let mensagensPendentes = [];
    
    function carregarMensagensSOS() {
        fetch('/mensagens_nao_lidas')
            .then(res => res.json())
            .then(dados => {
                if (dados && dados.length > 0) {
                    mensagensPendentes = dados;
                    atualizarAlerta();
                }
            })
            .catch(error => {
                console.error('Erro ao carregar mensagens SOS:', error);
            });
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

    // Eventos para notificações
    document.getElementById('fecharNotificacao')?.addEventListener('click', () => {
        document.getElementById('mensagemAlerta').classList.remove('show');
    });

    document.getElementById('enviarResposta')?.addEventListener('click', () => {
        const mensagemAtual = mensagensPendentes[0];
        if (mensagemAtual && mensagemAtual.id) {
            window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
        } else {
            alert('Mensagem inválida para responder.');
        }
    });

    document.getElementById('fecharModal')?.addEventListener('click', () => {
        document.getElementById('mensagemModal').classList.remove('show');
    });

    // Configurar notificações SOS com Echo
    if (typeof Echo !== 'undefined') {
        setTimeout(() => {
            try {
                const userIdLogado = document.querySelector('meta[name="user-id"]').getAttribute('content');
                
                Echo.channel('mensagem_sos')
                    .listen('.NovaMensagemSosEvent', (e) => {
                        console.log('Nova mensagem SOS:', e);
                        if (String(e.user_id) === userIdLogado) {
                            const mensagem = {
                                id: e.id,
                                conteudo: e.conteudo,
                                data: e.data
                            };
                            mensagensPendentes.unshift(mensagem);
                            atualizarAlerta();
                        }
                    })
                    .error((error) => {
                        console.error('Erro no canal SOS:', error);
                    });
                    
                console.log('Canal SOS configurado');
            } catch (error) {
                console.error('Erro ao configurar canal SOS:', error);
            }
        }, 2000);
    }

    // Carregar mensagens SOS iniciais
    carregarMensagensSOS();
    
    // Polling de backup para SOS a cada 30 segundos
    setInterval(carregarMensagensSOS, 30000);

    // Initialize
    updateVideoCallButton();

    // Cleanup
    window.addEventListener('beforeunload', function() {
        if (isCallActive) {
            endVideoCall();
        }
        if (currentChannel && typeof Echo !== 'undefined') {
            Echo.leave(currentChannel);
        }
    });
});
    </script>
</body>

</html>