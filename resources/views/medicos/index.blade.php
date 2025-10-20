@extends('layouts.app')

@section('title', 'Gerir Médicos')

@section('content') 
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0 text-danger">
                <i class="bi bi-person-badge"></i> Gerir Médicos
            </h2>
        </div>

        <div class="card-box pb-10 shadow-sm rounded">
            <div class="h5 pd-20 mb-0">Médicos Recentes</div>

             
                <div class="pl-20 mb-3">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modalAdicionarMedico">
                        <i class="bi bi-plus-circle"></i> Adicionar Médico
                    </button>
                </div>
              
            <div id="tabela-medicos" class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $doutor)
                        <tr>
                            <td>{{ $doutor->name }}</td>
                            <td>{{ $doutor->telefone }}</td>
                            <td>{{ $doutor->email }}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-danger btn-sm me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    onclick="editDoutor({{ $doutor->id }})">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                                <button class="btn btn-outline-dark btn-sm"
                                    onclick="confirmDelete('{{ route('users.destroy', $doutor->id) }}')">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> 

@include('medicos.partials.modals')
@endsection

@push('scripts')
<script src="{{ asset('js/medicos.js') }}"></script>
@endpush
