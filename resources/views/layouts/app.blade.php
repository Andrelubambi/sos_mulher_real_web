<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">

    <meta charset="utf-8" />
    <title>SOS-MULHER</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
.pre-loader {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background: #fff; /* ou a cor de fundo que preferir */
    z-index: 9999;
}

.pre-loader-box {
    text-align: center;
    max-width: 400px;
    width: 100%;
    padding: 20px;
}

.loader-progress {
    margin: 20px auto;
    max-width: 300px;
}

.percent {
    margin: 10px 0;
    font-size: 18px;
    font-weight: bold;
}

.loading-text {
    margin-top: 10px;
    font-size: 16px;
    color: #666;
}
</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  <div class="pre-loader">
    <div class="pre-loader-box">
        <div class="loader-logo">
            <img src="{{ asset('vendors/images/sos-progress.jpg') }}" alt="" style="width: 120px; height: auto;" />
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
            <div class="header-search"></div>
        </div>
        <div class="header-right">
            @if (auth()->user()->role == 'vitima')
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
            <a href="{{ route('index') }}">
                <img src="{{ asset('vendors/images/android-chrome-192x192.png') }}" alt="" style="height: 60px;" />
            </a>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    {{-- Conteúdo comum a todos os usuários logados --}}
                     
                    
                    {{-- Conteúdo para a Vítima --}}
                    @if (Auth::user()->role === 'vitima')
                        <li>
                            <a href="{{ route('consulta') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-calendar-check"></span>
                                <span class="mtext">Minhas Consultas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-chat-right-dots"></span>
                                <span class="mtext">Chat</span>
                            </a>
                        </li>
                    @endif
                    
                    {{-- Conteúdo para o Doutor --}}
                    @if (Auth::user()->role === 'doutor')
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon bi bi-person-circle"></span>
                                <span class="mtext">Pacientes</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('users.vitima') }}">Lista de Vítimas</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-chat-right-dots"></span>
                                <span class="mtext">Chat</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('consulta') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-calendar-check"></span>
                                <span class="mtext">Consultas</span>
                            </a>
                        </li>
                    @endif
    
                    {{-- Conteúdo para o Estagiário --}}
                    @if (Auth::user()->role === 'estagiario')
                        <li>
                            <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-chat-right-dots"></span>
                                <span class="mtext">Chat</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.vitima') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-people"></span>
                                <span class="mtext">Vítimas</span>
                            </a>
                        </li>
                    @endif
                    
                    {{-- Conteúdo para o Admin --}}
                    @if (Auth::user()->role === 'admin')
                        <li class="dropdown">
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-speedometer2"></span>
                                <span class="mtext">Dashboard</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon bi bi-person-badge"></span>
                                <span class="mtext">Usuários</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('users.doutor') }}">Doutores</a></li>
                                <li><a href="{{ route('users.estagiario') }}">Estagiários</a></li>
                                <li><a href="{{ route('users.vitima') }}">Vítimas</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('consulta') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-calendar-check"></span>
                                <span class="mtext">Consultas</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon bi bi-collection"></span>
                                <span class="mtext">Grupos</span>
                            </a>
                            <ul class="submenu">
                               <li><a href="{{ route('grupos.create') }}">Criar Grupo</a></li>
                                @foreach ($grupos as $grupo)
                                    <li><a href="{{ route('grupos.show', $grupo->id) }}">{{ $grupo->nome }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                                <span class="micon bi bi-chat-right-dots"></span>
                                <span class="mtext">Chat</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div> 
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>
    
    <div class="main-container">
        @yield('content')
    </div>
    
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
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