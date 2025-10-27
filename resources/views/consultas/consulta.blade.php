@extends('layouts.app')

@section('title', 'Consulta | SOS-MULHER')

@section('content')

<div class="xs-pd-20-10 pd-ltr-20">
<div class="title pb-20">
<h2 class="h3 mb-0 text-danger">
<i class="bi bi-calendar-check"></i> Gerir Consultas
</h2>
</div>

{{-- Removidas as mensagens de Feedback nativas do Blade para usar o Toast AJAX --}}

<!-- Card com Tabela de Consultas -->
<div class="card-box pb-10 shadow-sm rounded">
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
                    <tr data-id="{{ $consulta->id }}">
                        <td data-label="Descrição">{{ $consulta->descricao }}</td>
                        <td data-label="Bairro">{{ $consulta->bairro }}</td>
                        <td data-label="Província">{{ $consulta->provincia }}</td>
                        <td data-label="Data">{{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y') }}</td>
                        <td data-label="Médico">{{ $consulta->medico->name ?? 'N/A' }}</td>
                        <td data-label="Criado por">{{ $consulta->criador->name ?? 'N/A' }}</td>
                        <td data-label="Ações">
                           <div class="d-flex flex-wrap" style="gap: 10px;">
                                {{-- Botão Editar agora usa a função JS correta e passa apenas o ID --}}
                                @if(auth()->user()->role == 'admin' || auth()->user()->id == $consulta->criada_por || (auth()->user()->role == 'doutor' && auth()->user()->id == $consulta->medico_id))
                                <button type="button" class="btn btn-outline-danger btn-sm me-2" data-bs-toggle="modal"
                                    onclick="editConsulta({{ $consulta->id }})">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>

                                {{-- Botão Excluir agora usa a função JS que abre o modal de confirmação --}}
                                <button type="button" class="btn btn-outline-dark btn-sm"
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

@include('consultas.modals') {{-- Certifique-se de que este include aponta para o arquivo atualizado --}}


</div>
@endsection

@push('scripts')
{{-- Alterado para usar o novo consultas.js que gerencia o CRUD via AJAX --}}
@vite('resources/js/consultas.js')
@vite('resources/js/notifications.js')
@endpush