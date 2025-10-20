@extends('layouts.app')

@section('title', 'Gerir Médicos Assistentes')

@section('content')
<div class="title pb-20">
    <h2 class="h3 mb-0">Gerir Médicos Assistentes</h2>
</div>

<div class="card-box pb-10">
    <div class="h5 pd-20 mb-0">Assistentes</div>

    <div class="pl-20 mb-3">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalAdicionarEstagiario">
            Adicionar Estagiário
        </button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $estagiario)
                <tr>
                    <td>{{ $estagiario->name }}</td>
                    <td>{{ $estagiario->telefone }}</td>
                    <td>
                        <button class="btn btn-outline-danger btn-sm me-2"
                         onclick="editEstagiario({{ $estagiario->id }})">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <form action="{{ route('users.destroy', $estagiario->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('estagiarios.modals.modal_adicionar_estagiario')
@include('estagiarios.modals.modal_editar_estagiario')

@endsection
