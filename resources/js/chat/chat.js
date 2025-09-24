// Configuração do Laravel Echo para se conectar ao seu servidor Socket.IO
// IMPORTANTE: Unificado com a lógica de chat para garantir a ordem de execução
import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    // Use HTTPS através do Nginx, com path padrão do Socket.IO
    client: io,
    transports: ['websocket', 'polling'],
    host: `https://${window.location.hostname}`,
    // Garantir que o caminho é /socket.io atrás do Nginx
    path: '/socket.io',
});

window.Echo.connector.socket.on('connect', () => {
    console.log('✅ CONECTADO ao WebSocket!');
});

window.Echo.connector.socket.on('disconnect', () => {
    console.log('❌ DESCONECTADO do WebSocket!');
});


export function setupChat() {
    console.log('[setupChat] Iniciado');
    const messagesList = document.getElementById('messagesList');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    const participantName = document.getElementById('participantName');
    const currentAvatar = document.getElementById('currentAvatar');
    const participantStatus = document.getElementById('participantStatus');
    const videoCallBtn = document.getElementById('videoCallBtn');
    const sendBtn = sendMessageForm?.querySelector('.send-btn');
    const usuarioLogadoId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
    const typingIndicator = document.getElementById('typingIndicator');

    console.log('[setupChat] Elementos capturados:', {
        messagesList: !!messagesList,
        sendMessageForm: !!sendMessageForm,
        messageInput: !!messageInput,
        participantName: !!participantName,
        currentAvatar: !!currentAvatar,
        participantStatus: !!participantStatus,
        videoCallBtn: !!videoCallBtn,
        sendBtn: !!sendBtn,
        usuarioLogadoId
    });

    if (!sendMessageForm || !messageInput || !sendBtn || !usuarioLogadoId) {
        console.error('[setupChat] Elementos essenciais não encontrados!');
        return;
    }

    window.usuarioAtualId = null;
    window.currentChannel = null;

    function appendMessage(message, sentByMe = false) {
        const welcomeMessage = messagesList.querySelector('.welcome-message');
        if (welcomeMessage) welcomeMessage.remove();

        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sentByMe ? 'sent' : 'received');
        messageDiv.innerHTML = `
            <div class="message-content">
                <strong>${sentByMe ? 'Você' : (message.remetente ? message.remetente.name : 'Usuário')}:</strong><br>
                ${message.conteudo.replace(/\n/g, '<br>')}<br>
                <div class="message-time">${new Date(message.created_at).toLocaleString()}</div>
            </div>
        `;
        messagesList.appendChild(messageDiv);
        messagesList.scrollTop = messagesList.scrollHeight;

        if (!sentByMe && message.de !== parseInt(usuarioLogadoId)) {
            updateUnreadCount(message.de);
        }
    }

    function updateUnreadCount(senderId) {
        fetch(`/chat/messages/${senderId}`)
            .then(res => res.json())
            .then(messages => {
                const unreadCount = messages.filter(msg => msg.para == usuarioLogadoId && !msg.read_at).length;
                const conversationItem = document.querySelector(`.chat-item[data-user-id="${senderId}"]`);
                if (conversationItem) {
                    const badge = conversationItem.querySelector('.unread-badge');
                    if (unreadCount > 0) {
                        if (!badge) {
                            const metaDiv = conversationItem.querySelector('.conversation-meta');
                            const newBadge = document.createElement('div');
                            newBadge.className = 'unread-badge';
                            newBadge.textContent = unreadCount;
                            metaDiv.appendChild(newBadge);
                        } else {
                            badge.textContent = unreadCount;
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                }
            })
            .catch(error => console.error('[Unread] Erro ao atualizar unread_count:', error));
    }

    function escutarMensagens(usuarioId) {
        console.log('[Echo] 🎧 Escutando mensagens para usuário:', usuarioId);
        
        if (!window.Echo) {
            console.error('[Echo] ❌ Echo não disponível!');
            return;
        }

        try {
            // Sair do canal anterior
            if (window.currentChannel) {
                window.Echo.leave(window.currentChannel);
                console.log('[Echo] 👋 Saiu do canal anterior:', window.currentChannel);
            }

            // Calcular canal corretamente
            const minId = Math.min(parseInt(usuarioLogadoId), parseInt(usuarioId));
            const maxId = Math.max(parseInt(usuarioLogadoId), parseInt(usuarioId));
            const canal = `chat.${minId}-${maxId}`;
            window.currentChannel = canal;

            console.log('[Echo] 🔐 Tentando conectar ao canal privado:', canal);
            console.log('[Echo] 📤 ENVIANDO mensagem para canal:', canal);

            // CONECTAR AO CANAL PRIVADO COM DEBUG COMPLETO
            window.Echo.private(canal)
                .subscribed(() => {
                    console.log('[Echo] ✅ CANAL AUTENTICADO com sucesso!', canal);
                })
                .error((error) => {
                    console.error('[Echo] ❌ ERRO na autenticação do canal:', canal, error);
                })
                .listen('.MessageSent', (e) => {
                    console.log('[Echo] 📨 MENSAGEM RECEBIDA:', e);
                    console.log('[Echo] 📨 Detalhes da mensagem:', {
                        de: e.de,
                        para: e.para,
                        conteudo: e.conteudo,
                        usuarioLogado: parseInt(usuarioLogadoId)
                    });
                    
                    // Só adicionar se não foi enviada pelo usuário logado
                    if (e.de !== parseInt(usuarioLogadoId)) {
                        appendMessage(e, false);
                    }
                })
                .listenForWhisper('typing', (e) => {
                    console.log('[Echo] ⌨️ Usuário digitando:', e);
                    if (e.userId !== parseInt(usuarioLogadoId)) {
                        typingIndicator.style.display = 'flex';
                        setTimeout(() => {
                            typingIndicator.style.display = 'none';
                        }, 3000);
                    }
                });

        } catch (error) {
            console.error('[Echo] 💥 Erro ao escutar mensagens:', error);
        }
    }

    // Event listeners para seleção de usuários
    document.querySelectorAll('.user-item, .chat-item').forEach(item => {
        item.addEventListener('click', () => {
            console.log('[User Click] 👤 Usuário selecionado:', item.dataset.userName, 'ID:', item.dataset.userId);
            window.usuarioAtualId = item.dataset.userId;
            const userName = item.dataset.userName;

            participantName.textContent = userName;
            currentAvatar.textContent = userName.charAt(0);
            participantStatus.textContent = 'Online';
            updateVideoCallButton();

            // Mobile responsive
            if (window.innerWidth < 768) {
                document.getElementById('sidebar')?.classList.remove('active');
                document.getElementById('overlay')?.classList.remove('active');
                document.getElementById('chatMain')?.classList.add('active');
            }

            // Limpar e ativar interface
            messagesList.innerHTML = '';
            messageInput.disabled = false;
            sendBtn.disabled = false;
            
            requestAnimationFrame(() => {
                messageInput.focus();
                console.log('[User Click] ✅ Formulário ativado');
            });

            // Carregar mensagens
            fetch(`/chat/messages/${window.usuarioAtualId}`)
                .then(res => {
                    console.log('[Fetch] 📥 Resposta recebida:', res.status);
                    return res.json();
                })
                .then(messages => {
                    console.log('[Fetch] 📨 Mensagens carregadas:', messages.length, 'mensagens');
                    
                    if (messages.length === 0) {
                        messagesList.innerHTML = `
                            <div class="welcome-message">
                                <i class="fas fa-comment-dots"></i>
                                <p>Nenhuma mensagem ainda</p>
                                <small>Envie uma mensagem para iniciar a conversa</small>
                            </div>
                        `;
                    } else {
                        messages.forEach(msg => {
                            appendMessage(msg, msg.de == usuarioLogadoId);
                        });
                    }
                    
                    messagesList.scrollTop = messagesList.scrollHeight;
                    updateUnreadCount(window.usuarioAtualId);
                })
                .catch(error => {
                    console.error('[Fetch] ❌ Erro ao carregar mensagens:', error);
                });

            // INICIAR ESCUTA DO CANAL
            escutarMensagens(window.usuarioAtualId);
        });
    });

    // Event listener para envio de mensagens
    sendMessageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const conteudo = messageInput.value.trim();
        
        console.log('[Submit] 📝 Conteúdo digitado:', conteudo);
        console.log('[Submit] 👤 Usuário destinatário:', window.usuarioAtualId);
        
        if (!conteudo || !window.usuarioAtualId) {
            console.log('[Submit] ⚠️ Conteúdo ou usuário inválido');
            return;
        }

        fetch(`/chat/send/${window.usuarioAtualId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ conteudo }),
        })
        .then(res => {
            console.log('[Submit] 📡 Status da resposta:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('[Submit] ✅ Mensagem enviada:', data);
            appendMessage(data, true);
            messageInput.value = '';
            messageInput.style.height = '40px';
        })
        .catch(err => {
            console.error('[Submit] ❌ Erro ao enviar mensagem:', err);
            alert('Erro ao enviar mensagem.');
        });
    });

    // Event listeners para typing e enter
    messageInput.addEventListener('input', function() {
        this.style.height = '40px';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        
        if (window.currentChannel && this.value.trim() && window.Echo) {
            try {
                window.Echo.private(window.currentChannel).whisper('typing', {
                    userId: parseInt(usuarioLogadoId)
                });
            } catch (error) {
                console.error('[Typing] Erro ao enviar whisper:', error);
            }
        }
    });

    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessageForm.dispatchEvent(new Event('submit'));
        }
    });

    function updateVideoCallButton() {
        if (window.usuarioAtualId && window.usuarioAtualId != usuarioLogadoId) {
            videoCallBtn.disabled = false;
            videoCallBtn.style.opacity = '1';
            videoCallBtn.title = 'Iniciar videochamada';
        } else {
            videoCallBtn.disabled = true;
            videoCallBtn.style.opacity = '0.5';
            videoCallBtn.title = 'Selecione um usuário para iniciar videochamada';
        }
    }
}
