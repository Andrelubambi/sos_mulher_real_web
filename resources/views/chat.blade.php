<!-- filepath: c:\laragon\www\sos-mulher\resources\views\grupos\show.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Grupo: {{ $grupo->nome ?? '' }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />

    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 100px);
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .chat-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color: #fff;
        }

        .chat-input {
            display: flex;
            gap: 10px;
            padding: 15px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }

        .chat-input textarea {
            flex: 1;
            resize: none;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .chat-input button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .chat-input button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 15px;
        }

        .message.sent {
            text-align: right;
        }

        .message.received {
            text-align: left;
        }

        .message-content {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
            background-color: #e9ecef;
            max-width: 70%;
        }

        .message.sent .message-content {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="chat-container">
                    <!-- Cabeçalho do Chat -->
                    <div class="chat-header">
                        Grupo: {{ $grupo->nome ?? ''}}
                    </div>

                    <!-- Mensagens -->
                    <div id="messages" class="chat-messages">
                        <div class="text-muted">Carregando mensagens...</div>
                    </div>

                    <!-- Formulário de Envio -->
                    <form id="sendMessageForm" class="chat-input">
                        @csrf
                        <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." required></textarea>
                        <button type="submit">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');

            @if($grupo ?? null)
                // Carregar mensagens do grupo
            fetch(`/grupos/{{ $grupo->id }}/mensagens`)
                .then(response => response.json())
                .then(messages => {
                    messagesDiv.innerHTML = '';
                    if (messages.length === 0) {
                        messagesDiv.innerHTML = '<div class="text-muted">Nenhuma mensagem ainda.</div>';
                    } else {
                        messages.forEach(message => {
                            const messageDiv = document.createElement('div');
                            messageDiv.classList.add('message', message.user_id === {{ auth()->id() }} ? 'sent' : 'received');
                            messageDiv.innerHTML = `
                                <div class="message-content">
                                    <strong>${message.user_id === {{ auth()->id() }} ? 'Você' : message.user.name}:</strong>
                                    ${message.conteudo}
                                </div>
                            `;
                            messagesDiv.appendChild(messageDiv);
                        });
                    }
                });
            @endif

            // Enviar nova mensagem
            sendMessageForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const conteudo = conteudoInput.value;

                @if ($grupo ?? null)
                fetch(`/grupos/{{ $grupo->id }}/mensagens`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        conteudo
                    }),
                })
                @endif
               
                    .then(response => response.json())
                    .then(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', 'sent');
                        messageDiv.innerHTML = `
                            <div class="message-content">
                                <strong>Você:</strong>
                                ${message.conteudo}
                            </div>
                        `;
                        messagesDiv.appendChild(messageDiv);
                        conteudoInput.value = '';
                    });
            });
        });
    </script>
</body>

</html>