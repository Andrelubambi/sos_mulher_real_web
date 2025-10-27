<div class="modal fade" id="confirmDeleteConsultaModal" tabindex="-1" aria-labelledby="confirmDeleteConsultaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title text-white" id="confirmDeleteConsultaModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja excluir esta consulta?
            </div>
            <div class="modal-footer border-0"> 
                <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger shadow-sm" id="confirmDeleteConsultaButton">
                    <i class="bi bi-trash"></i> Sim, Excluir
                </button>
            </div>
        </div>
    </div>
</div>