<!-- filepath: c:\laragon\www\sos-mulher\resources\views\grupos\show.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Grupo - {{ $grupo->nome }}</title>

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

                    <div
                        class="chat-header d-flex justify-content-between align-items-center bg-danger text-white px-3 py-2 rounded-top">
                        <span>Grupo: {{ $grupo->nome }}</span>

                        @if ($grupo->podeSerExcluidoPelo(auth()->user()))
                            <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este grupo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger">
                                    <i class="bi bi-trash"></i> Excluir Grupo
                                </button>
                            </form>
                        @endif
                    </div>

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
                        <button type="submit" class="btn btn-danger btn-sm" id="sendBtn">
                            <span id="sendBtnText"><i class="bi bi-send"></i> Enviar</span>
                            <span id="sendBtnLoading" class="d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Enviando...
                            </span>
                        </button>

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
        document.addEventListener('DOMContentLoaded', function() {
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');

            //Escutar o canal
            window.Echo.private('grupo.{{ $grupo->id }}')
                .listen('.GroupMessageSent', function(data) {
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
            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const conteudo = conteudoInput.value;
                const sendBtn = document.getElementById('sendBtn');
                const sendBtnText = document.getElementById('sendBtnText');
                const sendBtnLoading = document.getElementById('sendBtnLoading');

                // Mostrar loading
                sendBtn.disabled = true;
                sendBtnText.classList.add('d-none');
                sendBtnLoading.classList.remove('d-none');


                fetch(`/grupos/{{ $grupo->id }}/mensagens`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            conteudo
                        }),
                    }).then(response => response.json())
                    .then(message => {
                        conteudoInput.value = '';
                    });
            });
        });
    </script>

    <script>
        window.laravel_echo_port = '{{ env('LARAVEL_ECHO_PORT', 6001) }}';
    </script>
    <script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT', 6001) }}/socket.io/socket.io.js"></script>

</body>

</html>
