<!-- filepath: c:\laragon\www\sos-mulher\resources\views\grupos\show.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Grupo: {{ $grupo->nome }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.css" />

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    <div class="left-side-bar">
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('index') }}">Dashboard Médico</a></li>
                            <li><a href="{{ route('index3') }}">Dashboard Administrador</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-chat-right-dots"></span><span class="mtext">Grupos</span>
                        </a>
                        <ul class="submenu">
                            @foreach ($grupos as $grupoItem)
                                <li>
                                    <a href="{{ route('grupos.show', $grupoItem->id) }}">{{ $grupoItem->nome }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="chat-container">
                    <!-- Cabeçalho do Chat -->
                    <div class="chat-header">
                        Grupo: {{ $grupo->nome }}
                    </div>

                    <!-- Mensagens -->
                    <div id="messages" class="chat-messages">
                        @forelse ($mensagens as $mensagem)
                            <div class="message {{ $mensagem->user_id === auth()->id() ? 'sent' : 'received' }}">
                                <div class="message-content">
                                    <strong>{{ $mensagem->user_id === auth()->id() ? 'Você' : $mensagem->user->name }}:</strong>
                                    {{ $mensagem->conteudo }}
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Nenhuma mensagem ainda.</div>
                        @endforelse
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
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const messagesDiv = document.getElementById('messages');
        const sendMessageForm = document.getElementById('sendMessageForm');
        const conteudoInput = document.getElementById('conteudo');

        //Escutar o canal
        window.Echo.private('grupo.{{ $grupo->id }}')
            .listen('.GroupMessageSent', function (data) {
                const isCurrentUser = data.user_id === {{ auth()->id() }};
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', isCurrentUser ? 'sent' : 'received');

                messageDiv.innerHTML = `
                    <div class="message-content">
                        <strong>${isCurrentUser ? 'Você' : data.user.name}:</strong>
                        ${data.conteudo}
                    </div>
                `;
                messagesDiv.appendChild(messageDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });

        // Enviar mensagem
        sendMessageForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const conteudo = conteudoInput.value;

            fetch(`/grupos/{{ $grupo->id }}/mensagens`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ conteudo }),
            }).then(response => response.json())
              .then(message => {
                  conteudoInput.value = '';
              });
        });
    });
</script>

    <script>
        window.laravel_echo_port = '{{ env("LARAVEL_ECHO_PORT", 6001) }}';
    </script>
    <script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT', 6001) }}/socket.io/socket.io.js"></script>

</body>

</html>
