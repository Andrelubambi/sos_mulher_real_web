export function setupChat() {
    console.log('[setupChat] Iniciado');
    const messagesDiv = document.getElementById('messages');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const conteudoInput = document.getElementById('conteudo');
    const chatHeader = document.getElementById('chatHeader');
    const currentUserAvatar = document.getElementById('currentUserAvatar');
    const userStatus = document.getElementById('userStatus');
    const videoCallBtn = document.getElementById('videoCallBtn');
    const usuarioLogadoId = document.querySelector('meta[name="user-id"]').getAttribute('content');

    console.log('[setupChat] Elementos capturados:', {
        messagesDiv,
        sendMessageForm,
        conteudoInput,
        chatHeader,
        currentUserAvatar,
        userStatus,
        videoCallBtn,
        usuarioLogadoId
    });

    window.usuarioAtualId = null;
    window.currentChannel = null;

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

    function escutarMensagens(usuarioId) {
        console.log('[Echo] Escutando mensagens para usuário:', usuarioId);
        console.log('[Echo] Echo disponível:', window.Echo);
        if (!window.Echo) {
            console.log('Echo não disponível');
            return;
        }

        try {
            if (window.currentChannel) {
                window.Echo.leave(window.currentChannel);
                console.log('Saiu do canal anterior:', window.currentChannel);
            }
            
            const minId = Math.min(usuarioLogadoId, usuarioId);
            const maxId = Math.max(usuarioLogadoId, usuarioId);
            const canal = `chat.${minId}-${maxId}`;
            
            window.currentChannel = canal;
            
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

    document.querySelectorAll('.user-item, .chat-item').forEach(item => {
        item.addEventListener('click', () => {
            console.log('[User Click] Usuário selecionado:', item.dataset.userName);
            window.usuarioAtualId = item.dataset.userId;
            console.log('[User Click] ID do usuário atual:', window.usuarioAtualId);
            const userName = item.dataset.userName;
            
            chatHeader.textContent = userName;
            currentUserAvatar.textContent = userName.charAt(0);
            userStatus.textContent = 'Online';
            updateVideoCallButton();
            
            if (window.innerWidth < 992) {
                document.getElementById('sidebar').classList.remove('active');
                document.getElementById('chatArea').classList.add('active');
            }
            
            messagesDiv.innerHTML = '';
            sendMessageForm.classList.remove('hidden');
            console.log('[User Click] Caixa de mensagem exibida');



            fetch(`/chat/messages/${window.usuarioAtualId}`)
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

            escutarMensagens(window.usuarioAtualId);
        });
    });

    sendMessageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const conteudo = conteudoInput.value.trim();
        console.log('[Submit] Conteúdo digitado:', conteudo);
        if (!conteudo || !window.usuarioAtualId) return;

        fetch(`/chat/send/${window.usuarioAtualId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

    conteudoInput.addEventListener('input', function() {
        this.style.height = '45px';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    conteudoInput.addEventListener('keydown', function(e) {
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

    window.appendMessageToChat = function (data) {
    const messagesContainer = document.getElementById('messages');
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');

    messageElement.innerHTML = `
        <div class="message-bubble">
            <strong>${data.remetente.name}:</strong> ${data.conteudo}
            <span class="timestamp">${new Date(data.created_at).toLocaleTimeString()}</span>
        </div>
    `;

    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
};

}