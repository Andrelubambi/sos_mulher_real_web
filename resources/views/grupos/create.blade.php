@extends('layouts.app') 

@section('title', 'Criar Grupo | SOS-MULHER') 

@section('head_scripts_styles')
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    
    {{-- Garanta que o CSS do selectpicker seja incluído, ou mova para o layout base --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="card-box pb-10">
            <div class="pd-20">
                {{-- ALTERADO: text-blue para text-danger --}}
                <h4 class="text-danger h4">Criar Novo Grupo</h4>
            </div>

            <form action="{{ route('grupos.store') }}" method="POST" id="createGroupForm">
                @csrf
                <div class="pd-20">
                    <div class="form-group">
                        <label for="nomeGrupo">Nome do Grupo</label>
                        <input type="text" class="form-control" id="nomeGrupo" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricaoGrupo">Descrição do Grupo (Opcional)</label>
                        <textarea class="form-control" id="descricaoGrupo" name="descricao" rows="3"></textarea>
                    </div>
                    
                    <h5 class="mt-4">Adicionar Membros</h5>
                    <div class="form-group">
                        <small class="form-text text-muted">
                            Selecione os usuários para adicionar ao grupo. O administrador do grupo será adicionado automaticamente.
                        </small>
                        
                        <select name="membros[]" class="form-control selectpicker" multiple data-live-search="true">
                            @foreach ($usuariosDisponiveis as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->role }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="text-right pd-20">
                    {{-- ALTERADO: btn-primary para btn-danger --}}
                    <button type="submit" class="btn btn-danger">Criar Grupo</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- Script do Bootstrap-Select --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    {{-- Script de Notificações Toast e manipulação de formulário --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa o selectpicker
            $('.selectpicker').selectpicker();

            const form = document.getElementById('createGroupForm');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                setTimeout(() => showLoading(true), 50);

                // Prepara os dados do formulário
                const selectedMembros = Array.from(document.querySelector('.selectpicker').options)
                                            .filter(option => option.selected)
                                            .map(option => option.value);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        nome: document.getElementById('nomeGrupo').value,
                        descricao: document.getElementById('descricaoGrupo').value,
                        membros: selectedMembros
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => Promise.reject(error));
                    }
                    return response.json();
                })
                .then(data => {
                    // 4. Lógica de Sucesso
                    showLoading(false);
                    showToast(data.message || 'Grupo criado com sucesso!', 'success');
                     
                    form.reset(); // Limpa todos os campos do formulário
                    $('.selectpicker').selectpicker('val', ''); 

                   
                    
                })
                .catch(error => {
                    // 5. Lógica de Erro
                    showLoading(false);
                    console.error('Erro detalhado:', error);
                    
                    let errorMessage = 'Ocorreu um erro desconhecido.';
                    
                    if (error.errors) {
                        const firstError = Object.values(error.errors)[0][0];
                        errorMessage = `Erro de Validação: ${firstError}`;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    showToast(errorMessage, 'error');
                });
            });
        });
    </script>
@endpush