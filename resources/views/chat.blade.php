<!DOCTYPE html>
<html>

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">


    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title> CHAT | SOS-MULHER</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Link Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>

    </style>
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <div class="card mb-2 user-item" data-user-id="{{ $chat['user']->id }}"
                        data-user-name="{{ $chat['user']->name }}">
                        <div class="card-body">
                            <strong>{{ $chat['user']->name }}</strong><br>
                            <small
                                class="text-muted">{{ \Carbon\Carbon::parse($chat['mensagem']->created_at)->format('d/m/Y H:i') }}</small>
                            <div class="mt-1">{{ \Illuminate\Support\Str::limit($chat['mensagem']->conteudo, 40) }}
                            </div>
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


        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
    <!-- js -->
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script>
        let mensagensPendentes = [];
        let carregamentoConcluido = false;

        document.addEventListener('DOMContentLoaded', function() {
            const userIdLogado = document.querySelector('meta[name="user-id"]').getAttribute('content');
            fetch('/mensagens_nao_lidas')
                .then(res => res.json())
                .then(dados => {
                    if (dados && dados.length > 0) {
                        mensagensPendentes = dados;
                        atualizarAlerta();
                    }
                    carregamentoConcluido = true;
                });
            if (!window.echoRegistered) {
                Echo.channel('mensagem_sos')
                    .listen('.NovaMensagemSosEvent', (e) => {
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
                    });
                window.echoRegistered = true;
            }
            document.getElementById('mensagemAlerta').addEventListener('click', () => {
                mostrarProximaMensagem();
            });
            document.getElementById('fecharModal').addEventListener('click', () => {
                const mensagemAtual = mensagensPendentes.shift();
                document.getElementById('mensagemModal').classList.add('hidden');

                fetch('/mensagem_lida', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: mensagemAtual.id
                    })
                });
                if (mensagensPendentes.length > 0) {
                    setTimeout(() => mostrarProximaMensagem(), 300);
                } else {
                    document.getElementById('mensagemAlerta').classList.add('hidden');
                }
                atualizarAlerta();
            });

            function atualizarAlerta() {
                const alerta = document.getElementById('mensagemAlerta');
                const texto = document.getElementById('mensagemTextoCompleto');

                if (mensagensPendentes.length > 0) { 
                    alerta.classList.remove('hidden');
                    texto.textContent = `Nova mensagem (${mensagensPendentes.length})`;
                } else {
                    alerta.classList.add('hidden');
                    texto.textContent = '';
                } 
            }

            function mostrarProximaMensagem() {
                const mensagem = mensagensPendentes[0];
                if (!mensagem) return;
                document.getElementById('mensagemConteudo').textContent = mensagem.conteudo;
                document.getElementById('mensagemData').textContent = formatarData(mensagem.data);
                document.getElementById('mensagemModal').classList.remove('hidden');
            }

            function formatarData(dataString) {
                const data = new Date(dataString);
                return data.toLocaleString('pt-PT', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        });

        function abrirModalMensagem(mensagem) {
            const modal = document.getElementById('mensagemModal');
            const conteudo = document.getElementById('mensagemConteudo');
            const data = document.getElementById('mensagemData');
            conteudo.textContent = mensagem.conteudo;
            data.textContent = mensagem.data;
            modal.dataset.mensagemId = mensagem.id;
            modal.classList.remove('hidden');
        }
        document.getElementById('enviarResposta').addEventListener('click', () => {
            const mensagemAtual = mensagensPendentes[0];
            if (mensagemAtual && mensagemAtual.id) {
                window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
            } else {
                alert('Mensagem inválida para responder.');
            }
        });
    </script>

</body>

</html>
