<!DOCTYPE html>
<html lang="pt-BR">
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
        /* Reset básico */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat-area {
            display: flex;
            flex-direction: column;
            height: 90vh;
            max-width: 700px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .chat-header {
            padding: 18px 24px;
            background-color: #dc3545;
            color: #fff;
            font-weight: 700;
            font-size: 20px;
            border-bottom: 1px solid #842029;
            user-select: none;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px 24px;
            overflow-y: auto;
            background-color: #f9fbff;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Barra de rolagem estilizada */
        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }
        .chat-messages::-webkit-scrollbar-thumb {
            background-color: rgba(245, 137, 140, 0.3);
            border-radius: 4px;
        }
        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .message {
            max-width: 70%;
            padding: 14px 18px;
            border-radius: 16px;
            line-height: 1.4;
            word-wrap: break-word;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Mensagens enviadas */
        .sent {
            align-self: flex-end;
            background: linear-gradient(135deg, #dc3545, #842029);
            color: #fff;
            border-bottom-right-radius: 4px;
            animation: slideInRight 0.3s ease forwards;
        }

        /* Mensagens recebidas */ 
        .received {
            align-self: flex-start;
            background-color: #e2e3e5;
            color: #333;
            border-bottom-left-radius: 4px;
            animation: slideInLeft 0.3s ease forwards;
        }

        /* Nome remetente */
        .message strong {
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* Conteúdo da mensagem */
        .message .content {
            white-space: pre-wrap;
        }

        /* Data/hora da mensagem */
        .message .timestamp {
            font-size: 11px;
            margin-top: 8px;
            opacity: 0.6;
            align-self: flex-end;
            user-select: none;
        }

        /* Formulário de envio */
        .chat-input {
            display: flex;
            padding: 16px 20px;
            border-top: 1px solid #ddd;
            background-color: #fff;
        }

        .chat-input textarea {
            flex-grow: 1;
            resize: none;
            border: 1.5px solid #ccc;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.2s;
            min-height: 60px;
            max-height: 120px;
        }

        .chat-input textarea:focus {
            outline: none;
            border-color: #dc3545;
            box-shadow: 0 0 6px rgba(247, 121, 121, 0.5);
        }

        .chat-input button {
            margin-left: 14px;
            padding: 0 28px;
            background-color: #dc3545;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .chat-input button:hover:not(:disabled) {
            background-color: #842029;
        }

        .chat-input button:disabled {
            background-color:rgb(243, 138, 148);
            cursor: not-allowed;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .chat-area {
                height: 100vh;
                border-radius: 0;
                max-width: 100%;
            }

            .chat-header {
                font-size: 18px;
                padding: 14px 18px;
            }

            .chat-messages {
                padding: 16px 18px;
                gap: 12px;
            }

            .chat-input {
                padding: 12px 16px;
            }
        }

        /* Animações */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
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
