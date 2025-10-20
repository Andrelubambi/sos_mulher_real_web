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

    @php
    $dashboardRoute = 'admin.dashboard';
    switch(auth()->user()->role) {
        case 'doutor':
            $dashboardRoute = 'doutor.dashboard';
            break;
        case 'estagiario':
            $dashboardRoute = 'estagiario.dashboard';
            break;
        case 'vitima':
            $dashboardRoute = 'vitima.dashboard';
            break;
        default: // 'admin' ou qualquer outra role
            $dashboardRoute = 'admin.dashboard';
            break;
    }
@endphp

    <x-sidebar :grupos="$grupos" />

    </div>
    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <!-- Cards principais -->
            <div class="row pb-10">
                @foreach ([['count' => $consultasMarcadasCount, 'label' => 'Consultas Marcadas', 'icon' => 'dw dw-calendar1', 'color' => '#00eccf'], ['count' => $vitimasCount, 'label' => 'Total de Vítimas', 'icon' => 'ti-heart', 'color' => '#ff5b5b'], ['count' => $doutoresCount, 'label' => 'Total de Doutores', 'icon' => 'fa fa-stethoscope', 'color' => '#0d6efd'], ['count' => $estagiariosCount, 'label' => 'Total de Estagiários', 'icon' => 'fa fa-money', 'color' => '#09cc06']] as $card)
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
            <!-- Gráficos -->
            <div class="row mb-30">
                <div class="col-md-6 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 p-4">Distribuição de Usuários</h5>
                        <div class="p-4">
                            <canvas id="userChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 p-4">Distribuição de Consultas por Status</h5>
                        <div class="p-4">
                            <canvas id="consultasChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Listagens -->
            <div class="row">
                <!-- Lista de Doutores -->
                <div class="col-md-6 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Lista de Doutores</h5>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Função</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doutores as $doutor)
                                    <tr>
                                        <td>{{ $doutor->name }}</td>
                                        <td>{{ $doutor->email ?? 'N/A' }}</td>
                                        <td>{{ $doutor->telefone ?? 'N/A' }}</td>
                                        <td>Doutor</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Lista de Estagiários -->
                <div class="col-md-6 col-xl-6 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Lista de Estagiários</h5>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Função</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estagiarios as $estagiario)
                                    <tr>
                                        <td>{{ $estagiario->name }}</td>
                                        <td>{{ $estagiario->email ?? 'N/A' }}</td>
                                        <td>{{ $estagiario->telefone ?? 'N/A' }}</td>
                                        <td>Estagiário</td>
                                             </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Lista de Vítimas -->
                <div class="col-md-12 col-xl-12 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Lista de Vítimas</h5>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nome</th>
                                    <th>ID</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vitimas as $vitima)
                                    <tr>
                                        <td>{{ $vitima->name }}</td>
                                        <td>{{ $vitima->id }}</td>
                                        <td>{{ $vitima->telefone ?? 'N/A' }}</td>
                                        <td>{{ $vitima->email ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Lista de Consultas -->
                <div class="col-md-12 col-xl-12 mb-30">
                    <div class="card-box">
                        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Lista de Consultas</h5>
                        <input type="text" id="searchConsulta" class="form-control mb-3" placeholder="Pesquisar por vítima ou médico">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome da Vítima</th>
                                        <th>Médico</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="consultaTableBody">
                                    @foreach ($consultasMarcadas as $consulta)
                                        <tr>
                                            <td>{{ $consulta->id }}</td>
                                            <td>{{ $consulta->vitima->nome ?? 'N/A' }}</td>
                                            <td>{{ $consulta->medico->name ?? 'N/A' }}</td>
                                            <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $consulta->status ?? 'Marcada' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            </div>
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
            // Gráfico de Donut
            const ctx1 = document.getElementById('userChart').getContext('2d');
            const userChart = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Doutores', 'Estagiários', 'Vítimas'],
                    datasets: [{
                        label: 'Número de Usuários',
                        data: [{{ $doutoresCount }}, {{ $estagiariosCount }}, {{ $vitimasCount }}],
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
                            text: 'Distribuição de Usuários por Função'
                        }
                    }
                }
            });
            // Gráfico de Donut para Consultas
            const consultasData = @json($consultasPorStatus->pluck('total'));
            const consultasLabels = @json($consultasPorStatus->pluck('status'));
            const ctx2 = document.getElementById('consultasChart').getContext('2d');
            const consultasChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: consultasLabels,
                    datasets: [{
                        label: 'Número de Consultas',
                        data: consultasData,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40',
                            '#E7E9ED',
                            '#6A5ACD',
                            '#F08080'
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
                            text: 'Distribuição de Consultas por Status'
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>
