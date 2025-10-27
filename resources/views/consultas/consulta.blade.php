@extends('layouts.app')

@section('title', 'Consulta | SOS-MULHER')

@section('content')
<div class="xs-pd-20-10 pd-ltr-20">
    <div class="title pb-20">
        <h2 class="h3 mb-0 text-danger">
            <i class="bi bi-calendar-check"></i> Gerir Consultas
        </h2>
    </div>

    <div class="card-box pb-10 shadow-sm rounded">
        <div class="h5 pd-20 mb-0">Consultas Recentes</div>

        <div class="pl-20 mb-3"> 
            {{-- CRÍTICO: Removido data-bs-toggle/data-bs-target para forçar abertura via JS --}}
            <button type="button" class="btn btn-danger" id="btnAbrirAdicionarConsulta">
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
                                    
                                    {{-- Edição: REMOVIDO data-bs-toggle/target --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm me-2"
                                        onclick="editConsulta({{ $consulta->id }})">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>

                                    {{-- Exclusão: Substituído por chamada JS --}}
                                    <button type="button"
                                       class="btn btn-outline-dark btn-sm"  
                                        onclick="confirmDeleteConsulta({{ $consulta->id }})">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
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

    @include('consultas.modals') 
    @include('consultas.confirm-delete-modal') {{-- NOVO MODAL --}}
</div>
@endsection

@push('scripts')
{{-- Certifique-se de que os paths estão corretos --}}
@vite('resources/js/consultas.js') 
@vite('resources/js/notifications.js') 
@endpush