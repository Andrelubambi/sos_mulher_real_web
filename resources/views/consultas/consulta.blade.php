@extends('layouts.app')

@section('title', 'Consulta | SOS-MULHER')

@section('content')
<div class="xs-pd-20-10 pd-ltr-20">
    <div class="title pb-20">
        <h2 class="h3 mb-0">Gerir Consultas</h2>
    </div>

    <!-- Mensagens de Feedback -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Card com Tabela de Consultas -->
    <div class="card-box pb-10">
        <div class="h5 pd-20 mb-0">Consultas Recentes</div>

        <div class="pl-20 mb-3"> 
            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                data-bs-target="#modalAdicionarConsulta">
                <i class="bi bi-plus-circle"></i> Adicionar Consulta
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Descrição</th>
                        <th>Bairro</th>
                        <th>Província</th>
                        <th>Data</th>
                        <th>Médico</th>
                        <th>Criado por</th>
                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'criador' || auth()->user()->role == 'vitima' || auth()->user()->role == 'doutor')
                        <th>Ações</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultas as $consulta)
                        <tr>
                            <td data-label="Descrição">{{ $consulta->descricao }}</td>
                            <td data-label="Bairro">{{ $consulta->bairro }}</td>
                            <td data-label="Província">{{ $consulta->provincia }}</td>
                            <td data-label="Data">{{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y') }}</td>
                            <td data-label="Médico">{{ $consulta->medico->name ?? 'N/A' }}</td>
                            <td data-label="Criado por">{{ $consulta->criador->name ?? 'N/A' }}</td>
                            <td data-label="Ações">
                               <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @if(auth()->user()->role == 'admin' || auth()->user()->id == $consulta->criada_por || (auth()->user()->role == 'doutor' && auth()->user()->id == $consulta->medico_id))
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal" onclick="editConsulta({{ $consulta->id }})">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>

                                    <form action="{{ route('consulta.destroy', $consulta->id) }}" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta consulta?')">
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
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2">Nenhuma consulta encontrada.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('consultas.modals') {{-- Aqui você pode colocar os modais separados --}}
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/consultas.js') }}"></script> {{-- JS separado --}}
@endpush
