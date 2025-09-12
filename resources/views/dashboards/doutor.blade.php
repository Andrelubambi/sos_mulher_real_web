<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta charset="utf-8" />
    <title>Dashboard | SOS-MULHER</title>
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
        /* CSS para o novo modal de lista */
        .mensagem-list-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .mensagem-list-conteudo {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-height: 80vh;
            overflow-y: auto;
        }
        .mensagem-list-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mensagem-list-item:hover {
            background-color: #f4f4f4;
        }
        .mensagem-list-item h5 {
            margin: 0;
            font-size: 16px;
        }
        .mensagem-list-item p {
            margin: 0;
            font-size: 14px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 70%;
        }
        .mensagem-list-item small {
            font-size: 12px;
            color: #999;
        }
    </style>
    <style>
    /* ... seu CSS existente ... */

    .mensagem-alerta.has-messages .mensagem-icone {
        color: #ff5b5b; /* Mudar a cor para um vermelho vibrante */
        animation: pulse 1.5s infinite; /* Aplicar a animação */
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
            <form action="{{ route('mensagem_sos.send') }}" method="POST" style="display:inline-block; margin-left: 10px;">
                @csrf
                <input type="hidden" name="mensagem" value="conteudo da mensagem sos">
                <button type="submit" title="Enviar SOS" style="background:none; border:none; cursor:pointer;">
                    <i class="fa fa-exclamation-triangle" style="color:red; font-size: 20px;"></i>
                </button>
            </form>
        </div>
    @endif

               @if(in_array(auth()->user()->role, ['admin', 'doutor', 'estagiario']))
        <div id="mensagemAlerta" class="mensagem-alerta" style="cursor:pointer;">
            <span class="mensagem-icone"><i class="fa fa-envelope"></i></span>
            <span id="mensagemTextoCompleto" class="mensagem-texto"></span>
        </div>
    @endif

        <div id="mensagemListModal" class="mensagem-list-modal hidden">
            <div class="mensagem-list-conteudo">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Mensagens Pendentes</h4>
                    <button id="fecharListModal" class="btn btn-sm btn-danger">Fechar</button>
                </div>
                <div id="listaDeMensagens">
                    </div>
            </div>
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

            
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-calendar-check"></span>
                    <span class="mtext">Consultas</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('consulta') }}">Todas as Consultas</a></li>
                </ul>
            </li>
           

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

     
<li>
    <a href="{{ route('nao_suicidio') }}" class="dropdown-toggle no-arrow">
        <span class="micon bi bi-heart-fill"></span>
        <span class="mtext">NÃO AO SUICÍDIO</span>
    </a>
</li>
<li>
    <a href="{{ route('testemunhos') }}" class="dropdown-toggle no-arrow">
        <span class="micon bi bi-chat-quote-fill"></span>
        <span class="mtext">TESTEMUNHOS</span>
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
                @foreach ([
                    ['count' => $pacientesCount, 'label' => 'Total de Pacientes', 'icon' => 'fa fa-users', 'color' => '#00eccf'], 
                    ['count' => $consultasHoje, 'label' => 'Consultas Hoje', 'icon' => 'dw dw-calendar1', 'color' => '#ff5b5b'], 
                    ['count' => $consultasRealizadas, 'label' => 'Consultas Realizadas', 'icon' => 'fa fa-check-circle', 'color' => '#0d6efd'], 
                    ['count' => $proximasConsultas->count(), 'label' => 'Próximas Consultas', 'icon' => 'fa fa-calendar-check-o', 'color' => '#09cc06']
                ] as $card)
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark">{{ $card['count'] }}</div>
                                    <div class="font-14 text-secondary weight-500">{{ $card['label'] }}</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon" data-color="{{ $card['color'] }}">
                                        <i class="icon-copy {{ $card['icon'] }}" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="row mb-30">
                <div class="col-md-6 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 p-4">Distribuição de Consultas</h5>
                        <div class="p-4">
                            <canvas id="consultasChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 p-4">Próximas Consultas (7 dias)</h5>
                        <div class="p-4">
                            <canvas id="proximasConsultasChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-30">
                <div class="col-md-12 col-xl-12 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Próximas Consultas</h5>
                        <div class="row pl-20 pr-20">
                            @forelse($proximasConsultas as $consulta)
                                <div class="col-md-4 mb-20">
                                    <div class="card-patient">
                                        <div class="patient-name">{{ $consulta->vitima->name ?? 'N/A' }}</div>
                                        <div class="patient-info">
                                            <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y H:i') }}</p>
                                            <p><strong>Status:</strong> {{ $consulta->status ?? 'Marcada' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center p-4">
                                    <p>Não há consultas agendadas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 col-xl-12 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Meus Pacientes</h5>
                        <input type="text" id="searchPaciente" class="form-control mb-3 ml-20 mr-20" placeholder="Pesquisar paciente por nome">
                        <div class="row pl-20 pr-20" id="pacientesContainer">
                            @forelse($pacientes as $paciente)
                                <div class="col-md-3 mb-20 paciente-card">
                                    <div class="card-patient">
                                        <div class="patient-name">{{ $paciente->name }}</div>
                                        <div class="patient-info">
                                            <p><strong>Email:</strong> {{ $paciente->email ?? 'N/A' }}</p>
                                            <p><strong>Telefone:</strong> {{ $paciente->telefone ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center p-4">
                                    <p>Não há pacientes cadastrados</p>
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
            // Função para filtrar pacientes
            document.getElementById('searchPaciente').addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const cards = document.querySelectorAll('.paciente-card');
                
                cards.forEach(function(card) {
                    const nomePaciente = card.querySelector('.patient-name').textContent.toLowerCase();
                    card.style.display = nomePaciente.includes(filtro) ? '' : 'none';
                });
            });
            
            // Gráfico de Donut para distribuição de consultas
            const ctx1 = document.getElementById('consultasChart').getContext('2d');
            const consultasChart = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Realizadas', 'Agendadas para Hoje', 'Próximas'],
                    datasets: [{
                        label: 'Número de Consultas',
                        data: [
                            {{ $consultasRealizadas }}, 
                            {{ $consultasHoje }}, 
                            {{ $proximasConsultas->count() }}
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
                            text: 'Distribuição de Consultas'
                        }
                    }
                }
            });
            
            // Gráfico de barras para próximas consultas (7 dias)
            @php
                // Preparar dados para o gráfico de próximas consultas
                $proximos7Dias = [];
                $consultasPorDia = [];
                
                for ($i = 0; $i < 7; $i++) {
                    $data = now()->addDays($i)->format('Y-m-d');
                    $proximos7Dias[] = now()->addDays($i)->format('d/m');
                    $consultasPorDia[$data] = 0;
                }
                
                foreach ($proximasConsultas as $consulta) {
                    $dataConsulta = \Carbon\Carbon::parse($consulta->data)->format('Y-m-d');
                    if (isset($consultasPorDia[$dataConsulta])) {
                        $consultasPorDia[$dataConsulta]++;
                    }
                }
                
                $consultasCountPorDia = array_values($consultasPorDia);
            @endphp
            
            const ctx2 = document.getElementById('proximasConsultasChart').getContext('2d');
            const proximasConsultasChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: @json($proximos7Dias),
                    datasets: [{
                        label: 'Número de Consultas',
                        data: @json($consultasCountPorDia),
                        backgroundColor: '#4e73df',
                        hoverBackgroundColor: '#2e59d9',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Consultas nos Próximos 7 Dias'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Código para mensagens SOS
            let mensagensPendentes = [];

            document.addEventListener('DOMContentLoaded', function() {
                const mensagemAlerta = document.getElementById('mensagemAlerta');
                const mensagemListModal = document.getElementById('mensagemListModal');
                const listaDeMensagens = document.getElementById('listaDeMensagens');
                const mensagemModal = document.getElementById('mensagemModal');
                const enviarRespostaBtn = document.getElementById('enviarResposta');
                const fecharModalBtn = document.getElementById('fecharModal');
                const fecharListModalBtn = document.getElementById('fecharListModal');

                 console.log('DOM carregado.');

                if (mensagemAlerta) {
                     console.log('Elemento mensagemAlerta encontrado.');
                    fetchMensagens();

                    if (!window.echoRegistered) {
                           console.log('Registrando Echo Listener...');
                        Echo.channel('mensagem_sos')
                            .listen('.NovaMensagemSosEvent', (e) => {
                                   console.log('Registrando Echo Listener...'); 
                                const mensagem = {
                                    id: e.id,
                                    conteudo: e.conteudo,
                                    data: e.data,
                                    // Adicione outras propriedades relevantes, como 'vitima_nome'
                                    vitima_nome: e.vitima_nome || 'Vítima Desconhecida'
                                };
                                mensagensPendentes.unshift(mensagem);
                                atualizarAlerta();
                            });
                        window.echoRegistered = true;
                    }

                     mensagemAlerta.addEventListener('click', () => {
                        console.log('Clique no alerta detectado. Tentando exibir lista de mensagens...');
                        exibirListaDeMensagens();
                    });
                } else {
                    console.error('Elemento #mensagemAlerta não encontrado. Verifique se o usuário tem permissão para vê-lo.');
                }
                
              fecharListModalBtn.addEventListener('click', () => {
                    console.log('Clique no botão fecharListModal detectado.');
                    mensagemListModal.classList.add('hidden');
                });

                fecharModalBtn.addEventListener('click', () => {
                    console.log('Clique no botão fecharModal detectado.');
                    mensagemModal.classList.add('hidden');
                });

                 enviarRespostaBtn.addEventListener('click', () => {
                    const mensagemId = mensagemModal.dataset.mensagemId;
                    console.log('Clique no botão enviarResposta detectado. ID da mensagem:', mensagemId);
                    if (mensagemId) {
                        window.location.href = `/responder_mensagem_sos/${mensagemId}`;
                    } else {
                        console.error('ID da mensagem inválido para responder.');
                        alert('Mensagem inválida para responder.');
                    }
                });

                     function fetchMensagens() {
                    console.log('Iniciando fetch para /mensagens_nao_lidas...');
                    fetch('/mensagens_nao_lidas')
                        .then(res => {
                            console.log('Resposta do servidor recebida.');
                            if (!res.ok) {
                                console.error('Erro na resposta da rede:', res.statusText);
                                throw new Error('Network response was not ok');
                            }
                            return res.json();
                        })
                        .then(dados => {
                            console.log('Dados de mensagens recebidos:', dados);
                            mensagensPendentes = dados;
                            atualizarAlerta();
                        })
                        .catch(err => console.error("Erro ao buscar mensagens:", err));
                }

                  

                  function atualizarAlerta() {
                    const alerta = document.getElementById('mensagemAlerta');
                    const texto = document.getElementById('mensagemTextoCompleto');
                    if (!alerta || !texto) return;

                    console.log('Atualizando alerta. Mensagens pendentes:', mensagensPendentes.length);
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

                 function exibirListaDeMensagens() {
                    console.log('Exibindo lista de mensagens. Mensagens pendentes:', mensagensPendentes.length);
                    listaDeMensagens.innerHTML = '';
                    if (mensagensPendentes.length === 0) {
                        listaDeMensagens.innerHTML = '<p class="text-center text-muted">Não há novas mensagens.</p>';
                    } else {
                        mensagensPendentes.forEach(mensagem => {
                            console.log('Criando item para a mensagem:', mensagem);
                            const item = document.createElement('div');
                            item.className = 'mensagem-list-item';
                            item.innerHTML = `
                                <div>
                                    <h5>Mensagem de ${mensagem.vitima_nome || 'Vítima Desconhecida'}</h5>
                                    <p>${mensagem.conteudo.substring(0, 50)}...</p>
                                </div>
                                <small>${formatarData(mensagem.data)}</small>
                            `;
                            item.addEventListener('click', () => {
                                console.log('Clique em um item da lista. Mensagem selecionada:', mensagem);
                                exibirMensagemIndividual(mensagem);
                            });
                            listaDeMensagens.appendChild(item);
                        });
                    }
                    console.log('Mostrando o modal da lista.');
                    mensagemListModal.classList.remove('hidden');
                }

                function exibirMensagemIndividual(mensagem) {
                    console.log('Exibindo mensagem individual:', mensagem);
                    mensagemListModal.classList.add('hidden');
                    document.getElementById('mensagemConteudo').textContent = mensagem.conteudo;
                    document.getElementById('mensagemData').textContent = formatarData(mensagem.data);
                    document.getElementById('mensagemModal').dataset.mensagemId = mensagem.id;
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
        </script>
    </div>
</body>
</html>
