@extends('layouts.app')

@section('title', 'Gerir Vítimas')

@section('content')
<div class="xs-pd-20-10 pd-ltr-20">
<div class="title pb-20">
            <h2 class="h3 mb-0 text-danger">
                <i class="bi bi-person-badge"></i> Gerir Vítimas
            </h2>
        </div> 
     <div class="card-box pb-10 shadow-sm rounded">
         <div class="h5 pd-20 mb-0">Vítimas Recentes</div>

        <div class="pl-20 mb-3">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAdicionarVitima">
                <i class="bi bi-plus-circle"></i> Adicionar Vítima
            </button>
        </div>

        <div class="table-responsive">
            <table id="vitimasTable" class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="vitimasBody">
                    @foreach ($users as $vitima)
                    <tr data-id="{{ $vitima->id }}">
                        <td>{{ $vitima->name }}</td>
                        <td>{{ $vitima->telefone }}</td>
                        <td>{{ $vitima->email }}</td>
                         <td class="text-center">
                            <div class="d-flex" style="gap: 20px !important;">
                                <button type="button" class="btn btn-outline-danger btn-sm me-2" data-bs-toggle="modal" 
                                data-bs-target="#editVitimaModal" onclick="editVitima({{ $vitima->id }})">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>

                                 <button class="btn btn-outline-dark btn-sm"  
                                onclick="confirmDelete({{ $vitima->id }})">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('vitimas.partials.vitimas-modals')

@endsection

@push('scripts')
@vite('resources/js/vitimas.js')
@vite('resources/js/notifications.js') 
@endpush
  