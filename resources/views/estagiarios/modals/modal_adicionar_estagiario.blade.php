<div class="modal fade" id="modalAdicionarEstagiario" tabindex="-1">
    <div class="modal-dialog">
        <form id="formAdicionarEstagiario" method="POST" action="{{ route('users.estagiario.store') }}" class="modal-content">
            @csrf
             <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">Novo Estagiário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="role" value="estagiario">
                <input type="text" name="name" class="form-control mb-3" placeholder="Nome" required>
                <input type="tel" name="telefone" class="form-control mb-3" placeholder="Telefone" required>
                 <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                <input type="password" name="password" class="form-control" placeholder="Senha" required>
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
