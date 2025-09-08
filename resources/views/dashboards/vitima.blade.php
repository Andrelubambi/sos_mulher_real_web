<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta charset="utf-8" />
<title>Dashboard da Vítima | SOS-MULHER</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-patient {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 15px;
            background-color: #fff;
        }
        .patient-name {
            font-weight: bold;
            color: #333;
        }
        .patient-info {
            color: #666;
            font-size: 14px;
        }
    </style>
    <style>
    .mensagem-alerta.has-messages .mensagem-icone {
        color: #ff5b5b;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
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
            <div class="header-search">
            </div>
        </div>
        <div class="header-right">
            @if (auth()->user()->role == 'vitima')
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
            <div class="row pb-10">
                <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">
                                    {{ $minhasConsultas->where('status', 'Marcada')->count() }}
                                </div>
                                <div class="font-14 text-secondary weight-500">
                                    Consultas Marcadas
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <i class="dw dw-calendar1" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">
                                    {{ $minhasConsultas->where('status', 'Realizada')->count() }}
                                </div>
                                <div class="font-14 text-secondary weight-500">
                                    Consultas Realizadas
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#0d6efd">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">
                                    {{ $minhasConsultas->where('status', 'Cancelada')->count() }}
                                </div>
                                <div class="font-14 text-secondary weight-500">
                                    Consultas Canceladas
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-30">
                <div class="col-md-12 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 p-4">Minha Distribuição de Consultas</h5>
                        <div class="p-4">
                            <canvas id="consultasChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
                
                 <div class="col-md-12 col-xl-6 mb-30">
                     <div class="card-box">
                        <h5 class="h5 text-dark mb-20 p-4">Minhas Próximas Consultas</h5>
                         <div class="row pl-20 pr-20">
                            @forelse($minhasConsultas->where('status', 'Marcada') as $consulta)
                                <div class="col-md-6 mb-20">
                                     <div class="card-patient">
                                         <div class="patient-name">{{ $consulta->medico->name ?? 'Médico Indisponível' }}</div>
                                         <div class="patient-info">
                                             <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y H:i') }}</p>
                                             <p><strong>Status:</strong> {{ $consulta->status }}</p>
                                         </div>
                                     </div>
                                </div>
                            @empty
                                <div class="col-12 text-center p-4">
                                     <p>Não há consultas marcadas no momento.</p>
                                </div>
                            @endforelse
                         </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 col-xl-12 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Todas as Minhas Consultas</h5>
                        <div class="row pl-20 pr-20">
                            @forelse($minhasConsultas as $consulta)
                                <div class="col-md-3 mb-20">
                                    <div class="card-patient">
                                        <div class="patient-name">
                                            {{ $consulta->medico->name ?? 'Médico Indisponível' }}
                                        </div>
                                        <div class="patient-info">
                                            <p><strong>Data:</strong>
                                                {{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y H:i') }}
                                            </p>
                                            <p><strong>Status:</strong>
                                                {{ $consulta->status }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center p-4">
                                    <p>Não há consultas cadastradas para você.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('vendors/scripts/core.js') }}"></script>
        <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/process.js') }}"></script>
        <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
        <script>
            // Gráfico de Donut para distribuição de consultas da vítima
            const ctx1 = document.getElementById('consultasChart').getContext('2d');
            const consultasChart = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Realizadas', 'Marcadas', 'Canceladas'],
                    datasets: [{
                        label: 'Minhas Consultas',
                        data: [
                            {{ $minhasConsultas->where('status', 'Realizada')->count() }},
                            {{ $minhasConsultas->where('status', 'Marcada')->count() }},
                            {{ $minhasConsultas->where('status', 'Cancelada')->count() }}
                        ],
                        backgroundColor: [
                            '#0d6efd',
                            '#09cc06',
                            '#ff5b5b'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Minha Distribuição de Consultas'
                        }
                    }
                }
            });

            // Código para mensagens SOS (mantido do original)
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
                        alerta.classList.add('has-messages');
                        texto.textContent = `Nova mensagem (${mensagensPendentes.length})`;
                    } else {
                        alerta.classList.add('hidden');
                        alerta.classList.remove('has-messages');
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