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
    /* GLOBAL */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
    }

    /* LAYOUT */
    .chat-layout {
        display: flex;
        height: 90vh;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* SIDEBAR */
    .sidebar {
        width: 260px;
        border-right: 1px solid #ddd;
        background-color: #fafafa;
        overflow-y: auto;
        transition: transform 0.3s ease-in-out;
    }

    .sidebar h5 {
        font-weight: bold;
        padding: 12px 16px;
        margin: 0;
        font-size: 15px;
        background-color: #e9ecef;
        border-bottom: 1px solid #ccc;
    }

    .user-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
    }

    .user-item:hover,
    .user-item.active {
        background-color: #e2e6ea;
    }

    /* CHAT AREA */
    .chat-area {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background-color: #fefefe;
    }

    .chat-header {
        padding: 16px;
        font-weight: bold;
        font-size: 17px;
        border-bottom: 1px solid #ddd;
        background-color: #f8f9fa;
    }

    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* MESSAGE BUBBLES */
    .message {
        max-width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        line-height: 1.5;
        word-wrap: break-word;
        position: relative;
    }


    /* CHAT INPUT */
    .chat-input {
        display: flex;
        padding: 12px 16px;
        border-top: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .chat-input textarea {
        flex-grow: 1;
        resize: none;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
        height: 60px;
        background-color: #fff;
    }

    .chat-input button {
        margin-left: 12px;
        padding: 10px 20px;
        background-color: #007bff;
        border: none;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .chat-input button:hover {
        background-color: #0056b3;
    }

    /* TABS */
    .tab-buttons {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        background-color: #f0f0f0;
        border-bottom: 1px solid #ccc;
    }

    .tab-buttons button {
        background-color: #dee2e6;
        color: #333;
        border: none;
        padding: 6px 14px;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
    }

    .tab-buttons button.active {
        background-color: #adb5bd;
        color: white;
    }

    .tab-buttons button:hover {
        background-color: #ced4da;
    }

    /* HAMBURGER */
    .hamburger {
        display: none;
        padding: 12px;
        background: #007bff;
        color: white;
        border: none;
        width: 100%;
        text-align: left;
        font-size: 18px;
    }

    @media (max-width: 768px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        transform: none !important;
        z-index: 1;
    }

    .chat-area {
        margin-top: 0;
    }

    .hamburger {
        display: none;
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
            <div id="mensagensRecentes" style="display: block;">
                <h5 class="mb-3">Mensagens Recentes</h5>

                @forelse ($chatsRecentes as $chat)
                    <div class="card mb-2 user-item" data-user-id="{{ $chat['user']->id }}" data-user-name="{{ $chat['user']->name }}">
                        <div class="card-body">
                            <strong>{{ $chat['user']->name }}</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($chat['mensagem']->created_at)->format('d/m/Y H:i') }}</small>
                            <div class="mt-1">{{ \Illuminate\Support\Str::limit($chat['mensagem']->conteudo, 40) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-secondary p-2" role="alert">
                        Nenhuma conversa recente.
                    </div>
                @endforelse
            </div>

            <!-- Lista de Usuários -->
            <div id="listaUsuarios" style="display: none;">
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
            const buttons = document.querySelectorAll('.tab-buttons button');
            buttons[0].classList.add('active');
            buttons[1].classList.remove('active');
        }

        function mostrarUsuarios() {
            document.getElementById('mensagensRecentes').style.display = 'none';
            document.getElementById('listaUsuarios').style.display = 'block';
            const buttons = document.querySelectorAll('.tab-buttons button');
            buttons[0].classList.remove('active');
            buttons[1].classList.add('active');
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
                    ${message.conteudo.replace(/\n/g, '<br>')}<br>
                    <small style="font-size: 0.75em; color: #666;">${new Date(message.created_at).toLocaleString()}</small>
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
