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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .chat-area {
            height: 90vh;
            margin: 20px auto;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #ccc;
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
            background-color: #fdfdfd;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 10px;
            line-height: 1.5;
            word-wrap: break-word;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            color: white;
            font-size: 15px;
        }

        .sent {
            background-color: #007bff;
            align-self: flex-end;
            border-bottom-right-radius: 0;
            text-align: right;
        }

        .received {
            background-color: #6c757d;
            align-self: flex-start;
            border-bottom-left-radius: 0;
            text-align: left;
        }

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

        @media (max-width: 768px) {
            .chat-area {
                width: 100%;
                margin: 0;
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

<div class="chat-area">
    <div id="chatHeader" class="chat-header">
        Chat com: {{ $remetente->name }}
    </div>
    <div id="messages" class="chat-messages">
        @foreach ($mensagens as $msg)
            <div class="message {{ $msg->de == auth()->id() ? 'sent' : 'received' }}">
                <div><strong>{{ $msg->de == auth()->id() ? 'Você' : $remetente->name }}</strong></div>
                <div style="font-size: 13px; margin-top: 4px;">{!! nl2br(e($msg->conteudo)) !!}</div>
                <div style="font-size: 11px; margin-top: 6px;">{{ \Carbon\Carbon::parse($msg->created_at)->format('d/m/Y H:i') }}</div>
            </div>
        @endforeach
    </div>

    <form id="sendMessageForm" class="chat-input">
        @csrf
        <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit">Enviar</button>
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

            messageDiv.innerHTML = `
                <div><strong>${sentByMe ? 'Você' : message.remetente.name}</strong></div>
                <div style="font-size: 13px; margin-top: 4px;">${message.conteudo.replace(/\n/g, '<br>')}</div>
                <div style="font-size: 11px; margin-top: 6px;">${new Date(message.created_at).toLocaleString()}</div>
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

            fetch(`/chat/send/${usuarioAtualId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ conteudo })
            })
            .then(res => res.json())
            .then(data => {
                appendMessage(data, true);
                conteudoInput.value = '';
            });
        });
    });
</script>

</body>
</html>
