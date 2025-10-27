<!-- Add Victim Modal (AJAX) -->
<div class="modal fade" id="modalAdicionarVitima" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formAdicionarVitima" class="modal-content">
      @csrf
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title text-white">Nova Vítima</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="role" value="vitima">
        <div class="mb-3"><label class="form-label">Nome</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Telefone</label><input type="tel" name="telefone" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control" required></div>
        
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
        <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Criar</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Victim Modal -->
<div class="modal fade" id="editVitimaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formEditarVitima" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title text-white">
                    <i class="bi bi-pencil-square text-white"></i> Editar Vítima
                </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Nome</label><input type="text" id="edit_vitima_name" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Telefone</label><input type="text" id="edit_vitima_telefone" name="telefone" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" id="edit_vitima_email" name="email" class="form-control"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
        <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirm Modal (generic) -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirmar Exclusão</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5">Tem certeza que deseja excluir este item?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
        <form id="deleteForm" method="POST"><input type="hidden" name="_method" value="DELETE">@csrf
          <button type="submit" class="btn btn-danger">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>
