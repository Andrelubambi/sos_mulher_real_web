{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard | SOS-MULHER')

@section('content')
<div class="xs-pd-20-10 pd-ltr-20">
    <!-- Cards principais -->
    <div class="row pb-10">
        @foreach ([
            ['count' => $consultasMarcadasCount, 'label' => 'Consultas Marcadas', 'icon' => 'dw dw-calendar1', 'color' => '#00eccf'],
            ['count' => $vitimasCount, 'label' => 'Total de Vítimas', 'icon' => 'ti-heart', 'color' => '#ff5b5b'],
            ['count' => $doutoresCount, 'label' => 'Total de Doutores', 'icon' => 'fa fa-stethoscope', 'color' => '#0d6efd'],
            ['count' => $estagiariosCount, 'label' => 'Total de Estagiários', 'icon' => 'fa fa-money', 'color' => '#09cc06']
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

    <!-- Gráficos -->
        <div class="row mb-30">
        <div class="col-md-6 col-xl-6 mb-30">
            <div class="card-box">
                <h5 class="h5 text-dark mb-20 p-4">Distribuição de Usuários</h5>
                <div class="p-4">
                    <canvas id="userChart" width="400" height="400"
                        data-doutores="{{ $doutoresCount }}"
                        data-estagiarios="{{ $estagiariosCount }}"
                        data-vitimas="{{ $vitimasCount }}"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-30">
            <div class="card-box">
                <h5 class="h5 text-dark mb-20 p-4">Distribuição de Consultas por Status</h5>
                <div class="p-4">
                    <canvas id="consultasChart" width="400" height="400"
                        data-labels='@json($consultasPorStatus->pluck("status"))'
                        data-values='@json($consultasPorStatus->pluck("total"))'></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Listagens -->
    <div class="row">
        <!-- Lista de Doutores -->
        <div class="col-md-6 col-xl-6 mb-30">
            <div class="card-box pt-4">
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
            <div class="card-box pt-4">
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
            <div class="card-box pt-4">
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
    <div class="card-box pt-4">
        <h5 class="h5 text-dark mb-20 pl-20">Lista de Consultas</h5>
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

    <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráficos
    const ctx1 = document.getElementById('userChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Doutores','Estagiários','Vítimas'],
            datasets: [{
                data: [{{ $doutoresCount }},{{ $estagiariosCount }},{{ $vitimasCount }}],
                backgroundColor: ['#0d6efd','#09cc06','#ff5b5b'],
                hoverOffset: 4
            }]
        }
    });

    const ctx2 = document.getElementById('consultasChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: @json($consultasPorStatus->pluck('status')),
            datasets: [{
                data: @json($consultasPorStatus->pluck('total')),
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#E7E9ED','#6A5ACD','#F08080'],
                hoverOffset: 4
            }]
        }
    });
</script>

    <script>
        // Filtro de consultas
        document.getElementById('searchConsulta').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const linhas = document.querySelectorAll('#consultaTableBody tr');
            linhas.forEach(function(linha) {
                const texto = linha.textContent.toLowerCase();
                linha.style.display = texto.includes(filtro) ? '' : 'none';
            });
        });

        // Gráficos
        const ctx1 = document.getElementById('userChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Doutores','Estagiários','Vítimas'],
                datasets: [{ data: [{{ $doutoresCount }},{{ $estagiariosCount }},{{ $vitimasCount }}],
                    backgroundColor: ['#0d6efd','#09cc06','#ff5b5b'], hoverOffset: 4 }]
            }
        });

        const ctx2 = document.getElementById('consultasChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: @json($consultasPorStatus->pluck('status')),
                datasets: [{
                    data: @json($consultasPorStatus->pluck('total')),
                    backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#E7E9ED','#6A5ACD','#F08080'],
                    hoverOffset: 4
                }]
            }
        });
    </script>
@endsection
