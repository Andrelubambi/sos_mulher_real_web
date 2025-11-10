@extends('layouts.app')

@section('title', 'Dashboard da Vítima | SOS-MULHER')

@push('styles')
<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
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
@endpush

@section('content')
<div class="xs-pd-20-10 pd-ltr-20">
    <div class="row pb-10">
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">
                            {{ $minhasConsultas->where('status', 'pendente')->count() }}
                        </div>
                        <div class="font-14 text-secondary weight-500">
                            Consultas Pendentes
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
        <div class="col-md-12 col-xl-12 mb-30">
            <div class="card-box pt-4">
                <h5 class="h5 text-dark mb-20 p-4">Minha Distribuição de Consultas</h5>
                <div class="p-4">
                    <canvas id="consultasChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 col-xl-12 mb-30">
            <div class="card-box pt-4">
                <h5 class="h5 text-dark mb-20 pl-20 mt-4">Todas as Minhas Consultas</h5>
                <div class="row pl-20 pr-20">
                    @forelse($minhasConsultas as $consulta)
                        <div class="col-md-3 mb-20">
                            <div class="card-patient">
                                <div class="patient-name">
                                    @if ($consulta->medico)
                                        {{ $consulta->medico->name }}
                                    @else
                                        Médico Indisponível
                                    @endif
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
@endsection

@push('scripts')
<script src="{{ asset('vendors/scripts/process.js') }}"></script>
<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
<script>
    // Gráfico de Donut para distribuição de consultas da vítima
    const ctx1 = document.getElementById('consultasChart').getContext('2d');
    const consultasChart = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Realizadas', 'Pendentes', 'Canceladas'],
            datasets: [{
                label: 'Minhas Consultas',
                data: [
                    {{ $minhasConsultas->where('status', 'Realizada')->count() }},
                    {{ $minhasConsultas->where('status', 'pendente')->count() }},
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

    // Código para mensagens SOS
    let mensagensPendentes = [];
    let carregamentoConcluido = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        const userIdLogado = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        
        fetch('/mensagens_nao_lidas')
            .then(res => res.json())
            .then(dados => {
                if (dados && dados.length > 0) {
                    mensagensPendentes = dados;
                    atualizarAlerta();
                }
                carregamentoConcluido = true;
            });
        
        if (!window.echoRegistered && typeof Echo !== 'undefined') {
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
        
        document.getElementById('mensagemAlerta')?.addEventListener('click', () => {
            mostrarProximaMensagem();
        });
        
        document.getElementById('fecharModal')?.addEventListener('click', () => {
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
                alerta?.classList.remove('hidden');
                alerta?.classList.add('has-messages');
                if (texto) texto.textContent = `Nova mensagem (${mensagensPendentes.length})`;
            } else {
                alerta?.classList.add('hidden');
                alerta?.classList.remove('has-messages');
                if (texto) texto.textContent = '';
            }
        }
        
        function mostrarProximaMensagem() {
            const mensagem = mensagensPendentes[0];
            if (!mensagem) return;
            
            const conteudo = document.getElementById('mensagemConteudo');
            const data = document.getElementById('mensagemData');
            const modal = document.getElementById('mensagemModal');
            
            if (conteudo) conteudo.textContent = mensagem.conteudo;
            if (data) data.textContent = formatarData(mensagem.data);
            modal?.classList.remove('hidden');
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
    
    document.getElementById('enviarResposta')?.addEventListener('click', () => {
        const mensagemAtual = mensagensPendentes[0];
        if (mensagemAtual && mensagemAtual.id) {
            window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
        } else {
            alert('Mensagem inválida para responder.');
        }
    });
</script>
@endpush