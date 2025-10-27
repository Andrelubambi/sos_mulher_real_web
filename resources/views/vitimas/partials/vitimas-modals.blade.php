<!-- Modal Adicionar Vítima -->
<div class="modal fade" id="modalAdicionarVitima" tabindex="-1" aria-labelledby="modalAdicionarVitimaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalAdicionarVitimaLabel">Adicionar Nova Vítima</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAdicionarVitima">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <input type="hidden" name="role" value="vitima">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Salvar Vítima</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Vítima -->
<div class="modal fade" id="editVitimaModal" tabindex="-1" aria-labelledby="editVitimaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="editVitimaModalLabel">Editar Vítima</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarVitima">
                @csrf
                @method('PUT') {{-- O jQuery AJAX usará isto para simular o método PUT/PATCH --}}
                <div class="modal-body">
                    <input type="hidden" id="edit_vitima_id" name="id">
                    <div class="mb-3">
                        <label for="name_edit" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name_edit" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefone_edit" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone_edit" name="telefone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_edit" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_edit" name="email" required>
                    </div>
                    
                    {{-- Campos de Senha (Opcional) --}}
                    <p class="mt-4 text-muted">Preencha os campos de senha apenas se desejar alterá-la.</p>
                    <div class="mb-3">
                        <label for="password_edit" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="password_edit" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation_edit" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="password_confirmation_edit" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal de Confirmação de Exclusão (CRÍTICO para vitimas.js) -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja excluir esta vítima? Esta ação não pode ser desfeita.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Sim, Excluir</button>
            </div>
        </div>
    </div>
</div>
