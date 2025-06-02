<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('vendors/styles/core.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/styles/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.css" />

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .chat-layout { display: flex; height: 90vh; }
        .sidebar { width: 250px; border-right: 1px solid #ddd; overflow-y: auto; }
        .chat-area { flex-grow: 1; display: flex; flex-direction: column; }
        .user-item { padding: 10px; cursor: pointer; }
        .user-item:hover { background: #f0f0f0; }
        .chat-messages { flex-grow: 1; overflow-y: auto; padding: 10px; border-bottom: 1px solid #ddd; }
        .chat-input { display: flex; padding: 10px; }
        .chat-input textarea { flex-grow: 1; resize: none; }
        .chat-input button { margin-left: 10px; }
        .message { margin: 5px 0; }
        .sent { text-align: right; }
        .received { text-align: left; }
    </style>
</head>

<body>
    <div class="chat-layout">
        <div class="sidebar">
            <h5 style="padding:10px;">Usuários</h5>
            @foreach ($usuariosNaoDoutores as $user)
                <div class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                    {{ $user->name }}
                </div>
            @endforeach
        </div>

        <div class="chat-area">
            <div id="chatHeader" class="chat-header">Selecione um usuário</div>
            <div id="messages" class="chat-messages"></div>

            <form id="sendMessageForm" class="chat-input" style="display: none;">
                @csrf
                <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>

    <!-- Socket.IO Client -->
    <script src="http://{{ request()->getHost() }}:6001/socket.io/socket.io.js"></script>

    <!-- Echo Configuration -->
    <script>
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        });
    </script>

    <!-- Lógica de Chat -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');
            const chatHeader = document.getElementById('chatHeader');
            const usuarioLogadoId = {{ auth()->id() }};
            let usuarioAtualId = null;
            let currentChannel = null;

            function appendMessage(message, sentByMe = false) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', sentByMe ? 'sent' : 'received');
                messageDiv.innerHTML = `<div class="message-content">
                    <strong>${sentByMe ? 'Você' : message.remetente.name}:</strong>
                    ${message.conteudo.replace(/\n/g, '<br>')}
                </div>`;
                messagesDiv.appendChild(messageDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            function escutarMensagens(usuarioId) {
                const minId = Math.min(usuarioLogadoId, usuarioId);
                const maxId = Math.max(usuarioLogadoId, usuarioId);
                const canal = `chat.${minId}-${maxId}`;

                if (currentChannel) Echo.leave(currentChannel);
                currentChannel = `private-${canal}`;

                Echo.private(canal)
                    .listen('MessageSent', (e) => {
                        if (e.de !== usuarioLogadoId) {
                            appendMessage(e, false);
                        }
                    });
            }

            document.querySelectorAll('.user-item').forEach(item => {
                item.addEventListener('click', () => {
                    usuarioAtualId = item.dataset.userId;
                    chatHeader.textContent = 'Chat com: ' + item.dataset.userName;
                    messagesDiv.innerHTML = '';
                    sendMessageForm.style.display = 'flex';

                    fetch(`/chat/messages/${usuarioAtualId}`)
                        .then(res => res.json())
                        .then(messages => {
                            messages.forEach(msg => {
                                appendMessage(msg, msg.de == usuarioLogadoId);
                            });
                            messagesDiv.scrollTop = messagesDiv.scrollHeight;
                        });

                    escutarMensagens(usuarioAtualId);
                });
            });

            sendMessageForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const conteudo = conteudoInput.value.trim();
                if (!conteudo || !usuarioAtualId) return;

                fetch(`/chat/send/${usuarioAtualId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ conteudo }),
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
        });
    </script>
</body>
</html>
