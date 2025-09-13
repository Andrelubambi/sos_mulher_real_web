<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('vendors/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/global.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/layout.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/components.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/utilities.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/pages.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/chat.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/responsive.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.css" />

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="chat-styles.css">

</head>

<body>

<div class="chat-area" role="main" aria-label="Área de chat">
    <div id="chatHeader" class="chat-header" aria-live="polite" aria-atomic="true" style="display: flex; justify-content: space-between; align-items: center;">
        <span>Chat com: {{ $remetente->name }}</span>
        <button onclick="window.location.href='{{ route('index') }}'" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
            Fechar
        </button>
    </div>

    <div id="messages" class="chat-messages" aria-live="polite" aria-relevant="additions" tabindex="0">
        @foreach ($mensagens as $msg)
            <div class="message {{ $msg->de == auth()->id() ? 'sent' : 'received' }}">
                <strong>{{ $msg->de == auth()->id() ? 'Você' : $remetente->name }}</strong>
                <div class="content">{!! nl2br(e($msg->conteudo)) !!}</div>
                <div class="timestamp">{{ \Carbon\Carbon::parse($msg->created_at)->format('d/m/Y H:i') }}</div>
            </div>
        @endforeach
    </div>

    <form id="sendMessageForm" class="chat-input" aria-label="Formulário para enviar mensagem">
        @csrf
        <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." aria-required="true" required></textarea>
        <button type="submit" aria-label="Enviar mensagem">Enviar</button>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messagesDiv = document.getElementById('messages');
        const sendMessageForm = document.getElementById('sendMessageForm');
        const conteudoInput = document.getElementById('conteudo');
        const usuarioLogadoId = {{ auth()->id() }};
        const usuarioAtualId = {{ $remetente->id }};
        const nomeRemetente = @json($remetente->name);
        let currentChannel = null;

        function appendMessage(message, sentByMe = false) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(sentByMe ? 'sent' : 'received');

            // Formata a data para pt-BR usando Intl.DateTimeFormat
            const dateObj = new Date(message.created_at);
            const formattedDate = new Intl.DateTimeFormat('pt-BR', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            }).format(dateObj);

            messageDiv.innerHTML = `
                <strong>${sentByMe ? 'Você' : message.remetente.name}</strong>
                <div class="content">${message.conteudo.replace(/\n/g, '<br>')}</div>
                <div class="timestamp">${formattedDate}</div>
            `;

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

        escutarMensagens(usuarioAtualId);

        sendMessageForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const conteudo = conteudoInput.value.trim();
            if (!conteudo) return;

            // Desabilita botão enquanto envia
            const button = sendMessageForm.querySelector('button[type="submit"]');
            button.disabled = true;

            fetch(`/chat/send/${usuarioAtualId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ conteudo })
            })
            .then(res => {
                button.disabled = false;
                if (!res.ok) throw new Error('Erro ao enviar mensagem');
                return res.json();
            })
            .then(data => {
                appendMessage(data, true);
                conteudoInput.value = '';
                conteudoInput.focus();
            })
            .catch(err => {
                alert(err.message);
                button.disabled = false;
            });             
        });

        // Scroll inicial para o fim da conversa
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
</script>

</body>
</html>
