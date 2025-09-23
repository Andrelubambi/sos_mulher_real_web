 
export function setupChat() {
    console.log('[setupChat] Iniciado');
    const messagesList = document.getElementById('messagesList');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    const participantName = document.getElementById('participantName');
    const currentAvatar = document.getElementById('currentAvatar');
    const participantStatus = document.getElementById('participantStatus');
    const videoCallBtn = document.getElementById('videoCallBtn');
    const sendBtn = sendMessageForm.querySelector('.send-btn');
    const usuarioLogadoId = document.querySelector('meta[name="user-id"]').getAttribute('content');
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

    if (!sendMessageForm || !messageInput || !sendBtn) {
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
    }

    function escutarMensagens(usuarioId) {
        console.log('[Echo] Escutando mensagens para usuário:', usuarioId);
        if (!window.Echo) {
            console.log('Echo não disponível');
            return;
        }

        try {
            if (window.currentChannel) {
                window.Echo.leave(window.currentChannel);
                console.log('Saiu do canal anterior:', window.currentChannel);
            }

            const minId = Math.min(parseInt(usuarioLogadoId), parseInt(usuarioId));
            const maxId = Math.max(parseInt(usuarioLogadoId), parseInt(usuarioId));
            const canal = `chat.${minId}-${maxId}`;
            window.currentChannel = canal;

            console.log('Conectando ao canal:', canal);

            window.Echo.private(canal)
                .listen('MessageSent', (e) => {
                    console.log('Nova mensagem recebida:', e);
                    if (e.de !== parseInt(usuarioLogadoId)) {
                        appendMessage(e, false);
                    }
                })
                .listenForWhisper('typing', (e) => {
                    if (e.userId !== parseInt(usuarioLogadoId)) {
                        typingIndicator.style.display = 'flex';
                        setTimeout(() => {
                            typingIndicator.style.display = 'none';
                        }, 3000);
                    }
                })
                .error((error) => {
                    console.error('Erro no canal:', error);
                });
        } catch (error) {
            console.error('Erro ao escutar mensagens:', error);
        }
    }

    document.querySelectorAll('.user-item, .chat-item').forEach(item => {
        item.addEventListener('click', () => {
            console.log('[User Click] Usuário selecionado:', item.dataset.userName, 'ID:', item.dataset.userId);
            window.usuarioAtualId = item.dataset.userId;
            const userName = item.dataset.userName;

            participantName.textContent = userName;
            currentAvatar.textContent = userName.charAt(0);
            participantStatus.textContent = 'Online';
            updateVideoCallButton();

            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('active');
                document.getElementById('overlay').classList.remove('active');
                document.getElementById('chatMain').classList.add('active');
            }

            messagesList.innerHTML = '';
            messageInput.disabled = false;
            sendBtn.disabled = false;
            requestAnimationFrame(() => {
                messageInput.focus();
                console.log('[User Click] Formulário ativado. Estilo computed:', {
                    display: window.getComputedStyle(sendMessageForm).display,
                    disabled: messageInput.disabled
                });
            });

            fetch(`/chat/messages/${window.usuarioAtualId}`)
                .then(res => {
                    console.log('[Fetch] Resposta recebida:', res.status);
                    return res.json();
                })
                .then(messages => {
                    console.log('[Fetch] Mensagens carregadas:', messages);
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
                })
                .catch(error => {
                    console.error('[Fetch] Erro ao carregar mensagens:', error);
                });

            escutarMensagens(window.usuarioAtualId);
        });
    });

    sendMessageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const conteudo = messageInput.value.trim();
        console.log('[Submit] Conteúdo digitado:', conteudo);
        if (!conteudo || !window.usuarioAtualId) {
            console.log('[Submit] Conteúdo ou usuário inválido');
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
            .then(res => res.json())
            .then(data => {
                console.log('[Submit] Mensagem enviada:', data);
                appendMessage(data, true);
                messageInput.value = '';
                messageInput.style.height = '40px';
            })
            .catch(err => {
                console.error('[Submit] Erro ao enviar mensagem:', err);
                alert('Erro ao enviar mensagem.');
            });
    });

    messageInput.addEventListener('input', function() {
        this.style.height = '40px';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        if (window.currentChannel && this.value.trim()) {
            window.Echo.private(window.currentChannel).whisper('typing', {
                userId: parseInt(usuarioLogadoId)
            });
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
