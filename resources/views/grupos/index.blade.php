@extends('layouts.app')

@section('title', 'Lista de Grupos | SOS-MULHER')

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Grupos de Apoio</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Grupos</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'doutor' || auth()->user()->role === 'estagiario')
                        <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Criar Novo Grupo
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Todos os Grupos Disponíveis ({{ count($grupos) }})</h4>
            </div>

            @if (count($grupos) > 0)
                <div class="list-group list-group-flush">
                    @foreach ($grupos as $grupo)
                        <a href="{{ route('grupos.show', $grupo->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 text-danger">
                                    <i class="icon-copy fa fa-users mr-2"></i> 
                                    {{ $grupo->nome }}
                                </h5>
                                <p class="mb-1 text-muted">{{ $grupo->descricao ?? 'Nenhuma descrição fornecida.' }}</p>
                                <small>Administrador: **{{ $grupo->admin->name }}**</small>
                            </div>
                            <span class="badge badge-secondary badge-pill">
                                Entrar <i class="fa fa-angle-right ml-1"></i>
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="pd-20 text-center">
                    <p class="text-muted">Nenhum grupo de apoio encontrado. Comece a criar o primeiro!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @endpush