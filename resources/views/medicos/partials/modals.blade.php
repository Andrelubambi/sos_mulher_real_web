<!-- Modal Adicionar Médico -->
<div class="modal fade" id="modalAdicionarMedico" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <!-- O ID do formulário é crucial para o AJAX -->
        <form id="formAdicionarMedico" method="POST" action="{{ route('users.doutor.store') }}" class="modal-content shadow-lg">

            @csrf
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white"> 
                    <i class="bi bi-plus-circle text-white"></i> Novo Médico
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body"> 
                <input type="hidden" name="role" value="doutor">
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" name="name" class="form-control" placeholder="Nome completo" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone *</label>
                    <input type="tel" name="telefone" class="form-control" placeholder="Número de telefone" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Senha *</label>
                    <input type="password" name="password" class="form-control" placeholder="Senha" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-check-circle"></i> Criar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Médico -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <!-- O action será definido pelo JS na função editDoutor() -->
        <form id="editForm" method="POST" class="modal-content shadow-lg">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">
                    <i class="bi bi-pencil-square text-white"></i> Editar Médico
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone *</label>
                    <input type="text" id="telefone" name="telefone" class="form-control" required>
                </div>
            
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-check-circle"></i> Salvar
                </button>
            </div>
        </form> 
    </div>
</div>

 
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-white"></i> Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fs-5">Tem certeza que deseja excluir este médico?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                 
                <form id="deleteForm" method="POST">
                    @csrf
                   
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
