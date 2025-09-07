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
                    <form action="{{ route('mensagem_sos') }}" method="POST"
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
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('vendors/images/android-chrome-192x192.png') }}" alt="Logo"
                    style="height: 60px;" />
            </a>
        </div>

        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">

                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-speedometer2"></span>
                            <span class="mtext">Dashboard</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-calendar-check"></span>
                            </span><span class="mtext">Consultas</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('consulta') }}">Todas as Consultas</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-person-badge"></span>
                            </span><span class="mtext">Médico</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('users.doutor') }}">Lista de Médicos</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-person-workspace"></span>
                            <span class="mtext">Lista de Assistentes</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('users.estagiario') }}">Assistntes</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-people"></span>
                            </span><span class="mtext">Vítimas</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('users.vitima') }}">>Lista de Vítimas</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-collection"></span>
                            <span class="mtext">Grupos</span>
                        </a>
                        <ul class="submenu">
                           <li><a href="{{ route('grupos.create') }}">Criar Grupo</a></li>
                            </li>
                            @foreach ($grupos as $grupo)
                                <li>
                                    <a href="{{ route('grupos.show', $grupo->id) }}">{{ $grupo->nome }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
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
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Gerir Consultas</h2>
            </div>

            <div class="card-box pb-10">
                <div class="h5 pd-20 mb-0">Consultas Recente</div>

                <!-- Botão Adicionar Consulta - Visível apenas para criadores e vítimas -->
                @if(auth()->user()->role == 'criador' || auth()->user()->role == 'vitima')
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                    data-target="#modalAdicionarConsulta">
                    Adicionar Consulta
                </button>
                @endif


                <!-- Tabela de Consulta -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Bairro</th>
                            <th>Província</th>
                            <th>Data</th>
                            <th>Médico</th>
                            <th>Criado por</th>
                            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'criador' || auth()->user()->role == 'vitima' || auth()->user()->role == 'medico')
                            <th>Ações</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultas as $consulta)
                            <tr>
                                <td>{{ $consulta->descricao }}</td>
                                <td>{{ $consulta->bairro }}</td>
                                <td>{{ $consulta->provincia }}</td>
                                <td>{{ $consulta->data }}</td>
                                <td>{{ $consulta->medico->name ?? 'N/A' }}</td>
                                <td>{{ $consulta->criador->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if(auth()->user()->role == 'admin' || auth()->user()->id == $consulta->criada_por || (auth()->user()->role == 'medico' && auth()->user()->id == $consulta->medico_id))
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#editModal" onclick="editConsulta({{ $consulta->id }})">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>

                                        <form action="{{ route('consulta.destroy', $consulta->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhuma consulta encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- Modal Criar Consulta -->
            <div class="modal fade" id="modalAdicionarConsulta" tabindex="-1"
                aria-labelledby="modalAdicionarConsultaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('consulta.store') }}"
                        class="modal-content shadow rounded">
                        @csrf

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalAdicionarConsultaLabel">Nova Consulta</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <input type="text" name="descricao" id="descricao" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="provincia" class="form-label">Província</label>
                                <input type="text" name="provincia" id="provincia" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="data" class="form-label">Data</label>
                                <input type="date" name="data" id="data" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="medico_id" class="form-label">Médico</label>
                                <select name="medico_id" id="medico_id" class="form-select" required>
                                    <option value="">Selecione um médico</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Criar Consulta</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Editar Consulta -->

            <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog">
                    <form id="editForm" method="POST" action="" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Consulta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" required>
                            </div>
                            <div class="mb-3">
                                <label for="bairro">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" required>
                            </div>
                            <div class="mb-3">
                                <label for="provincia">Província</label>
                                <input type="text" class="form-control" id="provincia" name="provincia" required>
                            </div>
                            <div class="mb-3">
                                <label for="data">Data</label>
                                <input type="date" class="form-control" id="data" name="data" required>
                            </div>
                            <div class="mb-3">
                                <label for="medico_id">Médico</label>
                                <select name="medico_id" id="medico_id" class="form-control" required>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </div>


            <script>
                function editConsulta(id) {
                    fetch(`/consultas/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('descricao').value = data.descricao;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('provincia').value = data.provincia;
                            document.getElementById('data').value = data.data;
                            document.getElementById('medico_id').value = data.medico_id;
                            document.getElementById('editForm').action = `/consultas/${id}`;
                        });
                }
            </script>

        </div>
        <script>
            document.getElementById('searchConsulta').addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const linhas = document.querySelectorAll('#consultaTableBody tr');

                linhas.forEach(function(linha) {
                    const texto = linha.textContent.toLowerCase();
                    linha.style.display = texto.includes(filtro) ? '' : 'none';
                });
            });
        </script>
        <!-- js -->
        <script>
            document.getElementById('pesquisa-consulta').addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const itens = document.querySelectorAll('#lista-consultas li');

                itens.forEach(function(item) {
                    const texto = item.textContent.toLowerCase();
                    item.style.display = texto.includes(filtro) ? '' : 'none';
                });
            });
        </script>
        <script src="{{ asset('vendors/scripts/core.js') }}"></script>
        <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/process.js') }}"></script>
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
