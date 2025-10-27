// Verifica se window.showToast está disponível (usado para notificações no estilo medicos.js)
if (typeof showToast !== 'function') {
    // Fallback: se showToast não existe, usa console.log, adaptando a assinatura (message, type)
    window.showToast = (message, type) => console.log(`[Toast ${type.toUpperCase()}]: ${message}`);
}
if (typeof showLoading !== 'function') {
    window.showLoading = (show) => console.log(`[Loading]: ${show ? 'Ativado' : 'Desativado'}`);
}

// =========================================================================
// FUNÇÕES AUXILIARES DE MANIPULAÇÃO DE DOM (REMOVIDAS/SIMPLIFICADAS)
// A recarga de página é usada em vez da manipulação direta da tabela.
// =========================================================================


// =========================================================================
// LÓGICA DE CRUD (CREATE, READ, UPDATE, DELETE)
// =========================================================================

$(document).ready(function() {

    // --- VARIÁVEL GLOBAL PARA EXCLUSÃO ---
    let victimIdToDelete = null; 
    
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


    // 1. CRIAÇÃO (CREATE)
    $('#formAdicionarVitima').on('submit', function(e) {
        e.preventDefault();
        window.showLoading(true);
        console.log('Tentativa de Criação de Vítima...', $(this).serialize()); 

        $.ajax({
            url: '/users', 
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                console.log('Sucesso na Criação:', res); 
                window.showToast('Vítima criada com sucesso.', 'success');
                $('#modalAdicionarVitima').modal('hide');
                $('#formAdicionarVitima')[0].reset(); 
                
                // Recarrega a página para mostrar a nova vítima
                setTimeout(() => { 
                    window.location.reload(); 
                }, reloadDelay); 
            },
            error: function(xhr) {
                const message = getErrorMessage(xhr, 'Erro ao criar vítima.');
                console.error('Erro na Criação:', xhr.responseJSON || xhr.responseText); 
                window.showToast(message, 'error');
            },
            complete: function() {
                window.showLoading(false);
            }
        });
    });

    // 2. LEITURA (READ) e PREENCHIMENTO para Edição
    window.editVitima = function(id) {
        window.showLoading(true);
        console.log('Carregando dados da vítima ID:', id); 
        fetch(`/users/${id}/edit`) 
          .then(r => {
            if (!r.ok) throw new Error('Erro na resposta do servidor.');
            return r.json();
          })
          .then(data => {
            console.log('Dados da vítima carregados:', data.user); 
            const user = data.user; 
            
            // Preenche os campos do modal de edição
            $('#edit_vitima_id').val(user.id); 
            $('#name_edit').val(user.name); 
            $('#telefone_edit').val(user.telefone); 
            $('#email_edit').val(user.email); 

            // Limpa os campos de senha
            $('#password_edit').val('');
            $('#password_confirmation_edit').val('');

            // Define a action do formulário
            $('#formEditarVitima').attr('action', `/users/${id}`); 
            $('#editVitimaModal').modal('show');
          }).catch(e => {
            console.error('Erro ao carregar dados:', e); 
            window.showToast('Erro ao carregar dados da vítima.', 'error');
          })
          .finally(() => {
            window.showLoading(false);
          });
      };
      
    // 3. ATUALIZAÇÃO (UPDATE)
    $('#formEditarVitima').on('submit', function(e) {
        e.preventDefault();
        window.showLoading(true);
        const action = $(this).attr('action');
        console.log('Tentativa de Atualização da Vítima ID:', action.split('/').pop(), $(this).serialize());

        $.ajax({
            url: action,
            method: 'POST', // Usamos POST com _method=PUT/PATCH
            data: $(this).serialize(),
            success: function(res) {
                 console.log('Sucesso na Atualização:', res);
                window.showToast('Vítima atualizada com sucesso.', 'success');
                $('#editVitimaModal').modal('hide');
                
                // Recarrega a página
                setTimeout(() => { 
                    window.location.reload(); 
                }, reloadDelay); 
            },
            error: function(xhr) {
                const message = getErrorMessage(xhr, 'Erro ao salvar alterações.');
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
    window.confirmDeleteAjax = function(id) {
        victimIdToDelete = id; // Guarda o ID globalmente
        console.log('ID de Vítima marcado para exclusão:', id); 
        
        // CORREÇÃO: Usamos o método jQuery do Bootstrap para maior robustez, 
        // evitando o erro 'backdrop' que ocorre na inicialização nativa em alguns casos.
        const modalElement = $('#confirmDeleteModal');
        if (modalElement.length) {
             modalElement.modal('show');
        } else {
             console.error("Elemento 'confirmDeleteModal' não encontrado no DOM.");
        }
    };

    // Handler para o botão "Excluir" dentro do modal de confirmação
    $('#confirmDeleteButton').on('click', function() {
        if (!victimIdToDelete) return;

        window.showLoading(true);
        console.log('Iniciando exclusão da Vítima ID:', victimIdToDelete); 

        $.ajax({
            url: `/users/${victimIdToDelete}`, 
            method: 'POST', 
            data: { 
                _method: 'DELETE', 
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(res) {
                console.log('Sucesso na Exclusão:', res); 
                window.showToast('Vítima excluída com sucesso.', 'success');
                
                // Fecha o modal (usando jQuery)
                $('#confirmDeleteModal').modal('hide');

                // Recarrega a página
                setTimeout(() => { 
                    window.location.reload(); 
                }, reloadDelay);
            },
            error: function(xhr) {
                const message = getErrorMessage(xhr, 'Erro ao excluir vítima.');
                console.error('Erro na Exclusão:', xhr.responseJSON || xhr.responseText); 
                window.showToast(message, 'error');
                
                // Fecha o modal em caso de erro (usando jQuery)
                $('#confirmDeleteModal').modal('hide');
            },
            complete: function() {
                window.showLoading(false);
                victimIdToDelete = null; // Limpa o ID após a operação
            }
        });
    });
});
