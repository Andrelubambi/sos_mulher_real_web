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

        .sidebar {
            width: 250px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            background-color: #f9f9f9;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar h5 {
            font-weight: bold;
            padding: 10px;
            background-color: #eee;
            margin: 0;
        }

        .user-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .user-item:hover {
            background: #f0f0f0;
        }

        .chat-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .chat-input {
            display: flex;
            padding: 10px;
        }

        .chat-input textarea {
            flex-grow: 1;
            resize: none;
        }

        .chat-input button {
            margin-left: 10px;
        }

        .message {
            margin: 5px 0;
            padding: 10px;
            border-radius: 8px;
            background: #f1f1f1;
            max-width: 70%;
        }

        .sent {
            text-align: right;
            align-self: flex-end;
            background-color: #d1e7dd;
        }

        .received {
            text-align: left;
            align-self: flex-start;
            background-color: #f8d7da;
        }

        /* Botões de aba */
        .tab-buttons {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .tab-buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .tab-buttons button:hover {
            background-color: #0056b3;
        }

        /* Menu hambúrguer */
        .hamburger {
            display: none;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            width: 100%;
        }

        .hamburger-menu {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                z-index: 10;
                height: 100%;
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .hamburger {
                display: block;
            }
        }
    </style>
</head>

<body>
    <button class="hamburger" onclick="toggleSidebar()">☰ Menu</button>

    <div class="chat-layout">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="tab-buttons">
                <button onclick="mostrarMensagens()">Mensagens</button>
                <button onclick="mostrarUsuarios()">Usuários</button>
            </div>

            <!-- Mensagens Recentes -->
            <div id="mensagensRecentes" style="display: none;">
                <h5>Mensagens Recentes</h5>
                @forelse ($chatsRecentes as $chat)
                    <div class="user-item" data-user-id="{{ $chat['user']->id }}" data-user-name="{{ $chat['user']->name }}">
                        <strong>{{ $chat['user']->name }}</strong><br>
                        <small>{{ \Carbon\Carbon::parse($chat['mensagem']->created_at)->format('d/m/Y H:i') }}</small><br>
                        <div>{{ \Illuminate\Support\Str::limit($chat['mensagem']->conteudo, 40) }}</div>
                    </div>
                @empty
                    <p style="padding: 10px;">Nenhuma conversa recente.</p>
                @endforelse
            </div>

            <!-- Lista de Usuários -->
            <div id="listaUsuarios" style="display: block;">
                <h5>Usuários</h5>
                @forelse ($usuariosNaoDoutores as $user)
                    <div class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                        {{ $user->name }}
                    </div>
                @empty
                    <p style="padding: 10px;">Nenhum usuário encontrado.</p>
                @endforelse
            </div>
        </div>

        <!-- Área de Chat -->
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

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function mostrarMensagens() {
            document.getElementById('mensagensRecentes').style.display = 'block';
            document.getElementById('listaUsuarios').style.display = 'none';
        }

        function mostrarUsuarios() {
            document.getElementById('mensagensRecentes').style.display = 'none';
            document.getElementById('listaUsuarios').style.display = 'block';
        }

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
                    <strong>${sentByMe ? 'Você' : message.remetente.name}:</strong><br>
                    <small>${new Date(message.created_at).toLocaleString()}</small><br>
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
                    // Fechar sidebar no mobile
                    document.getElementById('sidebar').classList.remove('active');
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
