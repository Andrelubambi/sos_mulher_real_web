// Verifica se window.showToast e window.showLoading estão disponíveis (de 'notifications.js')
if (typeof showToast !== 'function') {
    window.showToast = (message, type) => console.log(`[Toast ${type.toUpperCase()}]: ${message}`);
}
if (typeof showLoading !== 'function') {
    window.showLoading = (show) => console.log(`[Loading]: ${show ? 'Ativado' : 'Desativado'}`);
}

$(document).ready(function() {

    // --- VARIÁVEL GLOBAL PARA EXCLUSÃO ---
    let consultaIdToDelete = null; 
    
    // Tempo de espera antes de recarregar a página após o sucesso
    const reloadDelay = 1000; 

    /**
     * Auxiliar para extrair a primeira mensagem de erro de validação.
     * @param {Object} xhr - Objeto XMLHttpRequest do erro.
     * @param {string} defaultMessage - Mensagem padrão.
     * @returns {string} Mensagem de erro mais específica ou a padrão.
     */
    function getErrorMessage(xhr, defaultMessage) {
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            const errorKeys = Object.keys(xhr.responseJSON.errors);
            if (errorKeys.length > 0) {
                return xhr.responseJSON.errors[errorKeys[0]][0]; 
            }
        }
        return xhr.responseJSON && xhr.responseJSON.message 
            ? xhr.responseJSON.message 
            : defaultMessage;
    }


    // 1. CRIAÇÃO (STORE) - Substituindo o submit padrão
    $('#formAdicionarConsulta').on('submit', function(e) {
        e.preventDefault();
        window.showLoading(true);
        console.log('Tentativa de Criação de Consulta...'); 

        $.ajax({
            url: $(this).attr('action'), // Pega a URL do action do form Blade
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                console.log('Sucesso na Criação:', res); 
                window.showToast('Consulta criada com sucesso.', 'success');
                $('#modalAdicionarConsulta').modal('hide');
                $('#formAdicionarConsulta')[0].reset(); 
                
                setTimeout(() => { 
                    window.location.reload(); 
                }, reloadDelay); 
            },
            error: function(xhr) {
                const message = getErrorMessage(xhr, 'Erro ao criar consulta.');
                console.error('Erro na Criação:', xhr.responseJSON || xhr.responseText); 
                window.showToast(message, 'error');
            },
            complete: function() {
                window.showLoading(false);
            }
        });
    });


    // 2. LEITURA (READ) e PREENCHIMENTO para Edição
    // Esta função é chamada via onclick no Blade
    window.editConsulta = function(id) {
        window.showLoading(true);
        console.log('Carregando dados da Consulta ID:', id); 
        
        // Rota de busca para edição (Ex: /consulta/1/edit)
        fetch(`/consulta/${id}/edit`) 
          .then(r => {
            if (!r.ok) throw new Error('Erro na resposta do servidor.');
            return r.json();
          })
          .then(data => {
            console.log('Dados da consulta carregados:', data.consulta); 
            const consulta = data.consulta; 
            
            // Preenche os campos do modal de edição
            $('#edit_descricao').val(consulta.descricao); 
            $('#edit_bairro').val(consulta.bairro); 
            $('#edit_provincia').val(consulta.provincia); 
            $('#edit_data').val(consulta.data); 
            $('#edit_medico_id').val(consulta.medico_id); 
            
            // Define a action do formulário (Ex: /consulta/1)
            $('#editForm').attr('action', `/consulta/${id}`); 
            $('#editModal').modal('show');
            
          }).catch(e => {
            console.error('Erro ao carregar dados:', e); 
            window.showToast('Erro ao carregar dados da consulta.', 'error');
          })
          .finally(() => {
            window.showLoading(false);
          });
      };
      
    // 3. ATUALIZAÇÃO (UPDATE) - Substituindo o submit padrão
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        window.showLoading(true);
        const action = $(this).attr('action');
        console.log('Tentativa de Atualização da Consulta ID:', action.split('/').pop());

        $.ajax({
            url: action,
            method: 'POST', // Usamos POST com _method=PUT/PATCH
            data: $(this).serialize(),
            success: function(res) {
                 console.log('Sucesso na Atualização:', res);
                window.showToast('Consulta atualizada com sucesso.', 'success');
                $('#editModal').modal('hide');
                
                setTimeout(() => { 
                    window.location.reload(); 
                }, reloadDelay); 
            },
            error: function(xhr) {
                const message = getErrorMessage(xhr, 'Erro ao salvar alterações na consulta.');
                console.error('Erro na Atualização:', xhr.responseJSON || xhr.responseText);
                window.showToast(message, 'error');
            },
            complete: function() {
                window.showLoading(false);
            }
        });
    });


    // 4. EXCLUSÃO (DELETE)
    
    // Auxiliar para abrir o modal de confirmação e guardar o ID
    window.confirmDeleteConsulta = function(id) {
        consultaIdToDelete = id; // Guarda o ID globalmente
        console.log('ID de Consulta marcado para exclusão:', id); 
        
        const modalElement = $('#confirmDeleteConsultaModal');
        if (modalElement.length) {
             modalElement.modal('show');
        } else {
             console.error("Elemento 'confirmDeleteConsultaModal' não encontrado no DOM.");
        }
    };

    // Handler para o botão "Excluir" dentro do modal de confirmação
    $('#confirmDeleteConsultaButton').on('click', function() {
        if (!consultaIdToDelete) return;

        window.showLoading(true);
        console.log('Iniciando exclusão da Consulta ID:', consultaIdToDelete); 

        $.ajax({
            // Rota de exclusão (Ex: /consulta/1)
            url: `/consulta/${consultaIdToDelete}`, 
            method: 'POST', 
            data: { 
                _method: 'DELETE', 
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(res) {
                console.log('Sucesso na Exclusão:', res); 
                window.showToast('Consulta excluída com sucesso.', 'success');
                
                $('#confirmDeleteConsultaModal').modal('hide');

                setTimeout(() => { 
                    window.location.reload(); 
                }, reloadDelay);
            },
            error: function(xhr) {
                const message = getErrorMessage(xhr, 'Erro ao excluir consulta.');
                console.error('Erro na Exclusão:', xhr.responseJSON || xhr.responseText); 
                window.showToast(message, 'error');
                
                $('#confirmDeleteConsultaModal').modal('hide');
            },
            complete: function() {
                window.showLoading(false);
                consultaIdToDelete = null; // Limpa o ID após a operação
            }
        });
    });
});
