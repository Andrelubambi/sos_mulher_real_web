//==============================================================================
// consultas.js — VERSÃO FINAL (AJAX, Toast, Modal Confirmação)
// ==============================================================================

// Funções globais de fallback para Toast e Loading (Garante compatibilidade)
if (typeof showToast !== 'function') {
    window.showToast = (message, type) => console.log(`[Toast ${type.toUpperCase()}]: ${message}`);
}
if (typeof showLoading !== 'function') {
    window.showLoading = (show) => console.log(`[Loading]: ${show ? 'Ativado' : 'Desativado'}`);
}

/**
 * Garante que todos os modais abertos ou semi-abertos sejam fechados.
 * ESSENCIAL para prevenir o erro 'reading backdrop' ao abrir modais em sequência.
 */
function closeOtherModals() {
    const openModals = document.querySelectorAll('.modal.show');
    openModals.forEach(modalEl => {
        const instance = bootstrap.Modal.getInstance(modalEl);
        if (instance) {
            instance.hide(); // Fecha via Bootstrap JS
        }
    });
}

$(document).ready(function () {
    let consultaIdToDelete = null;
    const reloadDelay = 1000;
    const $btnAddConsulta = $('#btnAbrirAdicionarConsulta');

    // ========================================================
    // FUNÇÃO AUXILIAR: Extrai erro de validação (422)
    // ========================================================
    function getErrorMessage(xhr, defaultMessage) {
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            const errorKeys = Object.keys(xhr.responseJSON.errors);
            if (errorKeys.length > 0) {
                return xhr.responseJSON.errors[errorKeys[0]][0];
            }
        }
        return xhr.responseJSON?.message || defaultMessage;
    }


    // ========================================================
    // 1. CRIAR CONSULTA (AJAX)
    // ========================================================

    // Abertura do modal de criação (usando ID)
    if ($btnAddConsulta.length) {
        $btnAddConsulta.on('click', function(e) {
            e.preventDefault();
            closeOtherModals(); 
            $('#modalAdicionarConsulta').modal('show');
            $('#formAdicionarConsulta')[0].reset();
        });
    }

    // Submissão do formulário de criação
    $('#modalAdicionarConsulta form').on('submit', function (e) {
        e.preventDefault();
        window.showLoading(true);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                window.showToast('Consulta criada com sucesso.', 'success');
                $('#modalAdicionarConsulta').modal('hide'); 
                setTimeout(() => window.location.reload(), reloadDelay);
            },
            error: function (xhr) {
                const msg = getErrorMessage(xhr, 'Erro ao criar consulta.');
                window.showToast(msg, 'error');
            },
            complete: function () {
                window.showLoading(false);
            },
        });
    });


    // ========================================================
    // 2. EDITAR CONSULTA (AJAX)
    // ========================================================
    window.editConsulta = function (id) {
        window.showLoading(true);

        fetch(`/consultas/${id}/edit`)
            .then((r) => {
                if (!r.ok) throw new Error('Erro na resposta do servidor.');
                return r.json();
            })
            .then((data) => {
                const consulta = data.consulta;

                // Preenchimento dos campos
                $('#edit_descricao').val(consulta.descricao);
                $('#edit_bairro').val(consulta.bairro);
                $('#edit_provincia').val(consulta.provincia);
                $('#edit_data').val(consulta.data);
                $('#edit_medico_id').val(consulta.medico_id);

                $('#editForm').attr('action', `/consultas/${id}`); // Rota corrigida

                closeOtherModals(); 
                
               // Timeout para abertura
               setTimeout(() => {
                    window.showLoading(false);
                    $('#editModal').modal('show');
                }, 100); 
            })
            .catch((e) => {
                window.showToast('Erro ao carregar dados da consulta.', 'error');
                window.showLoading(false);
            })
    };

    // Submissão do formulário de edição
    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        window.showLoading(true);
        const action = $(this).attr('action');

        $.ajax({
            url: action,
            method: 'POST', // Envia como POST
            data: $(this).serialize() + '&_method=PUT', // Simula o método PUT
            success: function (res) {
                window.showToast('Consulta atualizada com sucesso.', 'success');
                $('#editModal').modal('hide'); 
                setTimeout(() => window.location.reload(), reloadDelay);
            },
            error: function (xhr) {
                const msg = getErrorMessage(xhr, 'Erro ao atualizar consulta.');
                window.showToast(msg, 'error');
            },
            complete: function () {
                window.showLoading(false);
            },
        });
    });


    // ========================================================
    // 3. EXCLUIR CONSULTA (AJAX com Modal de Confirmação)
    // ========================================================

    // Abre o modal de confirmação
    window.confirmDeleteConsulta = function (id) {
        consultaIdToDelete = id;

        closeOtherModals(); 
        
        // Timeout para abertura
        setTimeout(() => {
            $('#confirmDeleteConsultaModal').modal('show');
        }, 100);
    };
    
    // Executa a exclusão (clique no botão 'Sim, Excluir')
    $('#confirmDeleteConsultaButton').on('click', function () {
        if (!consultaIdToDelete) return;

        window.showLoading(true);

        $.ajax({
            url: `/consultas/${consultaIdToDelete}`, // Rota corrigida
            method: 'POST', // Envia como POST
            data: {
                _method: 'DELETE', // Simula o método DELETE
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (res) {
                window.showToast('Consulta excluída com sucesso.', 'success');
                $('#confirmDeleteConsultaModal').modal('hide');
                setTimeout(() => window.location.reload(), reloadDelay);
            },
            error: function (xhr) {
                const msg = getErrorMessage(xhr, 'Erro ao excluir consulta.');
                window.showToast(msg, 'error');
                $('#confirmDeleteConsultaModal').modal('hide');
            },
            complete: function () {
                window.showLoading(false);
                consultaIdToDelete = null;
            },
        });
    });

    // ========================================================
    // 4. LÓGICA DE VALIDAÇÃO DE DATA E LIMPEZA (MANTIDA)
    // ========================================================
    
    // Limpar formulário ao fechar (apenas para garantir)
    $('#editModal, #modalAdicionarConsulta').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });

    // Validação de data (amanhã a 15 dias)
    const inputData = document.getElementById('data');
    const inputEditData = document.getElementById('edit_data');

    if (inputData && inputEditData) {
        const hoje = new Date();
        const amanha = new Date(hoje);
        amanha.setDate(hoje.getDate() + 1);

        const limite = new Date(hoje);
        limite.setDate(hoje.getDate() + 15);

        const formatar = (data) => data.toISOString().split('T')[0];
        
        // Aplica limites para ambos os campos
        inputData.min = formatar(amanha);
        inputData.max = formatar(limite);
        inputEditData.min = formatar(amanha);
        inputEditData.max = formatar(limite);
        
        // Adiciona a validação em tempo real
        function validarData(event) {
            const selecionada = new Date(event.target.value);
            if (selecionada < amanha || selecionada > limite) {
                window.showToast('Por favor, selecione uma data entre amanhã e os próximos 15 dias.', 'error');
                event.target.value = '';
            }
        }
        inputData.addEventListener('input', validarData);
        inputEditData.addEventListener('input', validarData);
    }
});