<!DOCTYPE html>
<html>

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">


    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title> Dashboard | SOS-MULHER</title>

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
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="{{ asset('vendors/images/sos-progress.jpg') }}" alt=""
                    style="width: 120px; height: auto;" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Por favor, aguarde ...</div>
        </div>
    </div>
    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
            <div class="header-search">

            </div>
        </div>

        <div class="header-right">
            <!-- Settings Icon -->

            @if (auth()->user()->role == 'vitima')
                <!-- SOS Button -->
                <div class="user-notification">
                    <form action="{{ route('mensagem_sos.send') }}" method="POST"
                        style="display:inline-block; margin-left: 10px;">
                        @csrf
                        <input type="hidden" name="mensagem" value="conteudo da mensagem sos">
                        <button type="submit" title="Enviar SOS" style="background:none; border:none; cursor:pointer;">
                            <i class="fa fa-exclamation-triangle" style="color:red; font-size: 20px;"></i>
                        </button>
                    </form>
                </div>
            @endif

            <div id="mensagemAlerta" class="mensagem-alerta hidden" style="cursor:pointer;">
                <span class="mensagem-icone"><i class="fa fa-envelope"></i></span>
                <span id="mensagemTextoCompleto" class="mensagem-texto"></span>
            </div>

            <div id="mensagemModal" class="mensagem-modal hidden" data-mensagem-id="">
                <div class="mensagem-modal-conteudo">
                    <h4>Mensagem Recebida</h4>
                    <p id="mensagemConteudo"></p>
                    <small id="mensagemData" style="display:block;margin-top:10px;color:#666;"></small>

                    <div style="margin-top: 10px; text-align: right;">
                        <button id="enviarResposta" style="margin-right: 10px;">Responder</button>
                        <button id="fecharModal">OK</button>
                    </div>
                </div>
            </div>


            <!-- User Info -->
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <i class="fa fa-user-circle" style="font-size: 35px; color: #555;"></i>
                        </span>
                        @guest
                            <p>Olá, seja bem-vindo visitante! Faça login para acessar suas informações.</p>
                        @else
                            <span class="user-name">Olá, seja bem-vindo {{ Auth::user()->name }}!</span>
                        @endguest
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf</form>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="dw dw-logout"></i>Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
             <a href="">
                <img src="{{ asset('vendors/images/android-chrome-192x192.png') }}" alt="Logo" style="height: 60px;" />
            </a>
        </div>
       <div class="menu-block customscroll">
    <div class="sidebar-menu">
        <ul id="accordion-menu">
            <!-- Dashboard (todos têm acesso) -->
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-speedometer2"></span>
                    <span class="mtext">Dashboard</span>
                </a>
                <ul class="submenu">
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    @elseif(auth()->user()->role === 'doutor')
                        <li><a href="{{ route('doutor.dashboard') }}">Dashboard Médico</a></li>
                    @elseif(auth()->user()->role === 'estagiario')
                        <li><a href="{{ route('estagiario.dashboard') }}">Dashboard Assistente</a></li>
                    @elseif(auth()->user()->role === 'vitima')
                        <li><a href="{{ route('vitima.dashboard') }}">Dashboard Vítima</a></li>
                    @endif
                </ul>
            </li>

            <!-- Consultas -->
            @if(in_array(auth()->user()->role, ['admin', 'doutor', 'estagiario']))
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-calendar-check"></span>
                    <span class="mtext">Consultas</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('consulta') }}">Todas as Consultas</a></li>
                </ul>
            </li>
            @endif

            <!-- Médicos (apenas admin) -->
            @if(auth()->user()->role === 'admin')
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-person-badge"></span>
                    <span class="mtext">Médico</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('users.doutor') }}">Lista de Médicos</a></li>
                </ul>
            </li>
            @endif

            <!-- Assistentes (apenas admin) -->
            @if(auth()->user()->role === 'admin')
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-person-workspace"></span>
                    <span class="mtext">Lista de Assistentes</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('users.estagiario') }}">Assistentes</a></li>
                </ul>
            </li> 
            @endif

            <!-- Vítimas (admin e médicos) -->
            @if(in_array(auth()->user()->role, ['admin', 'doutor']))
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-people"></span>
                    <span class="mtext">Vítimas</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('users.vitima') }}">Lista de Vítimas</a></li>
                </ul>
            </li>
            @endif

            <!-- Grupos (admin, médicos e assistentes) -->
            @if(in_array(auth()->user()->role, ['admin', 'doutor', 'estagiario']))
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-collection"></span>
                    <span class="mtext">Grupos</span>
                </a>
                <ul class="submenu">
                    @if(auth()->user()->role === 'admin')
                       <li><a href="{{ route('grupos.create') }}">Criar Grupo</a></li>
                    @endif
                    @foreach ($grupos as $grupo)
                        <li>
                            <a href="{{ route('grupos.show', $grupo->id) }}">{{ $grupo->nome }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @endif

            <!-- Chat (todos têm acesso) -->
            <li>
                <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                    <span class="micon bi bi-chat-right-dots"></span>
                    <span class="mtext">Chat</span>
                </a>
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
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
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
        const sendBtn = document.getElementById('sendBtn');
        const sendBtnText = document.getElementById('sendBtnText');
        const sendBtnLoading = document.getElementById('sendBtnLoading');


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
                // Limpa o input
                conteudoInput.value = '';

                // Esconde loading e reativa o botão
                sendBtn.disabled = false;
                sendBtnText.classList.remove('d-none');
                sendBtnLoading.classList.add('d-none');
            })
            .catch(error => {
                console.error('Erro ao enviar mensagem:', error);
                
                // Em caso de erro, reativa o botão
                sendBtn.disabled = false;
                sendBtnText.classList.remove('d-none');
                sendBtnLoading.classList.add('d-none');
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
