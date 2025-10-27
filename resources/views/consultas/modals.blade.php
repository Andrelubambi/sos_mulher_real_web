<!-- Modal Criar Consulta -->
<div class="modal fade" id="modalAdicionarConsulta" tabindex="-1" aria-labelledby="modalAdicionarConsultaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('consulta.store') }}" class="modal-content shadow-lg rounded-4 border-0">
            @csrf
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title text-white" id="modalAdicionarConsultaLabel">
                    <i class="bi bi-plus-circle"></i> Nova Consulta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="descricao" class="form-label fw-bold">Descrição *</label>
                    <input type="text" name="descricao" id="descricao" class="form-control shadow-sm" placeholder="Ex: Consulta de rotina" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bairro" class="form-label fw-bold">Bairro *</label>
                        <input type="text" name="bairro" id="bairro" class="form-control shadow-sm" placeholder="Ex: Ingombota" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="provincia" class="form-label fw-bold">Província *</label>
                        <input type="text" name="provincia" id="provincia" class="form-control shadow-sm" placeholder="Ex: Luanda" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="data" class="form-label fw-bold">Data *</label>
                    <input type="date" name="data" id="data" class="form-control shadow-sm" required>
                </div>

                <div class="mb-3">
                    <label for="medico_id" class="form-label fw-bold">Médico *</label>
                    <select name="medico_id" id="medico_id" class="form-select shadow-sm" required>
                        <option value="">Selecione um médico</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="bi bi-check-circle"></i> Criar Consulta
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Consulta -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" method="POST" action="" class="modal-content shadow-lg rounded-4 border-0">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title text-white">
                    <i class="bi bi-pencil-square"></i> Editar Consulta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_descricao" class="form-label fw-bold">Descrição *</label>
                    <input type="text" class="form-control shadow-sm" id="edit_descricao" name="descricao" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_bairro" class="form-label fw-bold">Bairro *</label>
                        <input type="text" class="form-control shadow-sm" id="edit_bairro" name="bairro" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="edit_provincia" class="form-label fw-bold">Província *</label>
                        <input type="text" class="form-control shadow-sm" id="edit_provincia" name="provincia" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="edit_data" class="form-label fw-bold">Data *</label>
                    <input type="date" class="form-control shadow-sm" id="edit_data" name="data" required>
                </div>
                
                <div class="mb-3">
                    <label for="edit_medico_id" class="form-label fw-bold">Médico *</label>
                    <select name="medico_id" id="edit_medico_id" class="form-select shadow-sm" required>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="bi bi-check-circle"></i> Salvar alterações
                </button>
            </div>
        </form>
    </div>
</div>
