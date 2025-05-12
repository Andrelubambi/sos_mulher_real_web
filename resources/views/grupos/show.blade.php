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
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Grupo: {{ $grupo->nome }}</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('index') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $grupo->nome }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-radius-4 box-shadow mb-30">
                    <div class="row no-gutters">
                        <div class="chat-container">
                            <!-- Caixa de Chat -->
                            <div class="chat-box">
                                <div class="chat-header">
                                    <h4 id="chat-with">Mensagens do Grupo</h4>
                                </div>
                                <div id="messages" class="chat-messages">
                                    <div class="text-muted">Nenhuma mensagem ainda.</div>
                                </div>
                                <form id="sendMessageForm" class="chat-input">
                                    @csrf
                                    <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..."
                                        required></textarea>
                                    <button type="submit" class="btn-send">Enviar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');

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

            // Enviar nova mensagem
            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const conteudo = conteudoInput.value;

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