<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Dashboard | SOS-MULHER</title>
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
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    </style>
    <!-- Vite -->
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
            <div class="header-search">
            </div>
        </div>
        <div class="header-right">
            <!-- Settings Icon -->
            @if (auth()->user()->role == 'vitima')
                <!-- SOS Button -->
                <div class="user-notification">
                    <form action="{{ route('mensagem_sos.send') }}" method="POST" style="display:inline-block; margin-left: 10px;">
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
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dw dw-logout"></i>Sair</a>
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
           @if(in_array(auth()->user()->role, ['admin', 'doutor', 'vitima']))
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-calendar-check"></i></span>
                        <span class="mtext">Consultas</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('consulta') }}">Todas as Consultas</a></li>
                    @if(in_array(auth()->user()->role, ['admin', 'doutor', 'estagiario,vitima']))
                        <li><a href="{{ route('minhas.consultas') }}">Minhas Consultas</a></li>
                        @endif
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
    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Gerir Médicos</h2>
            </div>
            <div class="card-box pb-10">
                <div class="h5 pd-20 mb-0 pl-20">Médicos Recentes</div>
                <!-- Botão -->
                           <div class="pl-20 mb-3">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                    data-target="#modalAdicionarMedico">
                    Adicionar Médico
                </button>
                         </div>
                <!-- Tabela de doutores -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $doutor)
                            <tr>
                                <td>{{ $doutor->name }}</td>
                                <td>{{ $doutor->telefone }}</td>
                                <td>
                                    <div class="d-flex" style="gap: 20px !important;">
                                        <button type="button"
                                            class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                            data-toggle="modal" data-target="#editModal"
                                            onclick="editDoutor({{ $doutor->id }})">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <form action="{{ route('users.destroy', $doutor->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Modal Criar Doutor -->
            <div class="modal fade" id="modalAdicionarMedico" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('users.doutor.store') }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Novo Doutor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="role" value="doutor">
                            <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Nome" required></div>
                            <div class="mb-3"><input type="tel" name="telefone" class="form-control" placeholder="Telefone" required></div>
                            <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Senha" required></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Criar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Editar Doutor -->
            <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog">
                    <form id="editForm" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Doutor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"><input type="text" class="form-control" id="name" name="name" required></div>
                            <div class="mb-3"><input type="telefone" class="form-control" id="telefone" name="telefone" required></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                function editDoutor(id) {
                    fetch(`/users/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('name').value = data.name;
                            document.getElementById('telefone').value = data.telefone;
                            document.getElementById('editForm').action = `/users/${id}`;
                        });
                }
            </script>
        </div>
        <script src="{{ asset('vendors/scripts/core.js') }}"></script>
        <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/process.js') }}"></script>
        <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
        <!-- js -->
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
    </div>
</body>
</html>
