// Importa jQuery e outras dependências se você não as incluiu globalmente no app.js
// Se você está usando o jQuery via CDN/app.js, ele deve estar disponível globalmente (window.$).

// Garante que o código só é executado após o documento estar totalmente carregado
// E o jQuery está disponível.
$(function() { 
    
    console.log("medicos.js: DOM Ready. Inicializando handlers de formulário AJAX.");
    
    // Funções globais (assumidas como existentes no layout.blade.php)
    if (typeof showToast !== 'function') {
        window.showToast = (message, type) => console.log(`[Toast ${type.toUpperCase()}]: ${message}`);
    }
    if (typeof showLoading !== 'function') {
        window.showLoading = (show) => console.log(`[Loading]: ${show ? 'Ativado' : 'Desativado'}`);
    }

    // ===============================================
    // FUNÇÃO AUXILIAR: Extrai e consolida erros 422
    // ===============================================

    /**
     * Auxiliar para extrair e consolidar TODAS as mensagens de erro de validação (422).
     * @param {Object} xhr - Objeto XMLHttpRequest do erro.
     * @param {string} defaultMessage - Mensagem padrão para erros não-422.
     * @returns {string} Lista formatada de mensagens de erro ou a padrão.
     */
    function getValidationErrorMessage(xhr, defaultMessage) {
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            let messages = [];
            
            // Itera sobre o objeto de erros do Laravel
            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    // Concatena todas as mensagens de erro para todos os campos (inclui unicidade)
                    messages = messages.concat(errors[key]); 
                }
            }
            
            // Retorna todas as mensagens formatadas com quebras de linha (<br>- )
            if (messages.length > 0) {
                return 'Por favor, corrija os seguintes erros:<br>- ' + messages.join('<br>- ');
            }
        }
        
        // Fallback para erros não-422 ou sem resposta JSON
        return xhr.responseJSON?.message || defaultMessage;
    }


    // ===============================================
    // 1. SUBMISSÃO DO FORMULÁRIO DE ADIÇÃO (AJAX)
    // ===============================================
    $('#formAdicionarMedico').submit(function(event) {
        event.preventDefault(); 
        
        const form = $(this);
        window.showLoading(true); 

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            data: form.serialize(),
            
            success: function(response) {
                window.showToast(response.message || 'Médico adicionado com sucesso.', 'success');
                
                $('#modalAdicionarMedico').modal('hide');
                form[0].reset(); 
                
                setTimeout(() => { 
                    window.location.reload(); 
                }, 1000); 
            },
            
            error: function(xhr) {
                // 💥 USAR A NOVA FUNÇÃO DE CONSOLIDAÇÃO AQUI
                const errorMessage = getValidationErrorMessage(xhr, 'Ocorreu um erro desconhecido ao adicionar o médico.'); 
                window.showToast(errorMessage, 'error');
            },
            
            complete: function() {
                window.showLoading(false); 
            }
        });
    });

    // ===============================================
    // 2. FUNÇÃO CONFIRM DELETE (AJAX)
    // ===============================================
    window.confirmDelete = function(deleteUrl) { 
        console.log(`Chamada global confirmDelete para URL: ${deleteUrl}.`);
 
        $('#confirmDeleteModal').modal('show');
 
        $('#deleteForm').off('submit').on('submit', function(event) {
            event.preventDefault(); 
            
            const formToDelete = $(this);
            window.showLoading(true); 
 
            const dataWithMethod = formToDelete.serialize() + '&_method=DELETE';

            $.ajax({
                url: deleteUrl, 
                method: 'POST',  
                data: dataWithMethod,  
                
                success: function(response) {
                    window.showToast(response.message || 'Médico excluído com sucesso.', 'success');
 
                    $('#confirmDeleteModal').modal('hide'); 
                     
                    setTimeout(() => { 
                        window.location.reload(); 
                    }, 1000); 
                },
                
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'Ocorreu um erro ao excluir. Tente novamente.'; 
                    window.showToast(errorMessage, 'error');
                },
                
                complete: function() {
                    window.showLoading(false);
                }
            });
        });
    }

    // ===============================================
    // 3. CARREGAR DADOS DE EDIÇÃO E CONFIGURAR FORM
    // ===============================================
    window.editDoutor = function(doctorId) {
        window.showLoading(true);
        
        const editForm = $('#editForm'); 
        const fetchUrl = `/users/${doctorId}/edit`; 
        const updateUrl = `/users/${doctorId}`;     
        
        editForm.attr('action', updateUrl); 
 
        $.ajax({
            url: fetchUrl, 
            method: 'GET',
            success: function(response) {
                if (response.user) {  
                    $('#editForm #name').val(response.user.name);
                    $('#editForm #email').val(response.user.email);
                    $('#editForm #telefone').val(response.user.telefone);
                    
                    $('#editModal').modal('show');
                } else {
                    window.showToast('Não foi possível carregar os dados do médico.', 'error');
                }
            },
            error: function(xhr) {
                window.showToast('Erro ao buscar dados para edição. Verifique o console.', 'error');
            },
            complete: function() {
                window.showLoading(false);
            }
        });
    };
    
    // ===============================================
    // 4. SUBMISSÃO DO FORMULÁRIO DE EDIÇÃO (AJAX)
    // ===============================================
     $('#editForm').submit(function(event) {
        event.preventDefault(); 
        const form = $(this);
        window.showLoading(true);
 
        const dataWithMethod = form.serialize() + '&_method=PUT';

        $.ajax({
            url: form.attr('action'), 
            method: 'POST',  
            data: dataWithMethod,
            
            success: function(response) {
                window.showToast(response.message || 'Médico atualizado com sucesso.', 'success');
                $('#editModal').modal('hide'); 
                
                setTimeout(() => { 
                    window.location.reload(); 
                }, 1000); 
            },
            
            error: function(xhr) {
                // 💥 USAR A NOVA FUNÇÃO DE CONSOLIDAÇÃO AQUI
                const errorMessage = getValidationErrorMessage(xhr, 'Ocorreu um erro ao atualizar o médico.'); 
                window.showToast(errorMessage, 'error');
            },
            
            complete: function() {
                window.showLoading(false);
            }
        });
    });
});
