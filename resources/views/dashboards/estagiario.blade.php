<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estagiário | SOS-MULHER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --success-color: #27ae60;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card-dashboard {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            border: none;
            margin-bottom: 20px;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
        }
        
        .stats-number {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .stats-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Conteúdo Principal -->
            <div class="col-12 main-content">
                <!-- Header -->
                <div class="header py-3 mb-4 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Dashboard Estagiário</h4>
                        <div class="d-flex align-items-center">
                            <span class="me-3">{{ Auth::user()->name }}</span>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-secondary btn-sm">
                                Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-dashboard text-center p-4">
                            <div class="card-icon mb-3">
                                <i class="fas fa-group"></i>
                            </div>
                            <h3 class="stats-number">{{ $grupos->count() }}</h3>
                            <p class="stats-label">Total de Grupos</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-dashboard text-center p-4">
                            <div class="card-icon mb-3">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h3 class="stats-number">{{ $vitimas->count() }}</h3>
                            <p class="stats-label">Total de Vítimas</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-dashboard text-center p-4">
                            <div class="card-icon mb-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="stats-number">0</h3>
                            <p class="stats-label">Atendimentos Hoje</p>
                        </div>
                    </div>
                </div>

                <!-- Conteúdo -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card-dashboard p-4">
                            <h5 class="mb-4">Grupos de Apoio</h5>
                            
                            @if($grupos->count() > 0)
                                <div class="list-group">
                                    @foreach($grupos as $grupo)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $grupo->nome }}</h6>
                                                <small class="text-muted">{{ $grupo->descricao ?? 'Sem descrição' }}</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $grupo->users_count ?? $grupo->users()->count() }} membros</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-group fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhum grupo cadastrado</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-dashboard p-4">
                            <h5 class="mb-4">Vítimas Cadastradas</h5>
                            
                            @if($vitimas->count() > 0)
                                <div class="list-group">
                                    @foreach($vitimas as $vitima)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $vitima->name }}</h6>
                                                    <small class="text-muted">{{ $vitima->telefone ?? 'N/A' }}</small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma vítima cadastrada</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>