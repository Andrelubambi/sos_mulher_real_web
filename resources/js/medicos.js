// Importa jQuery e outras dependências se você não as incluiu globalmente no app.js
// Se você está usando o jQuery via CDN/app.js, ele deve estar disponível globalmente (window.$).

// Garante que o código só é executado após o documento estar totalmente carregado
// E o jQuery está disponível.
$(function() { 
    // O $(function(){ ... }) é um atalho para $(document).ready(function(){ ... })
    
    // Teste de Log e Toast
    console.log("medicos.js: DOM Ready. Inicializando handlers de formulário AJAX.");
    // showToast("medicos.js carregado com sucesso.","success"); // Descomente após o teste para limpar o console
    
    // As funções globais showLoading e showToast foram movidas para o layout.
    
    // ===============================================
    // 1. SUBMISSÃO DO FORMULÁRIO DE ADIÇÃO (AJAX)
    // ===============================================
    $('#formAdicionarMedico').submit(function(event) {
        event.preventDefault(); 
        
        const form = $(this);
        window.showLoading(true); 
        console.log("AJAX Adicionar Doutor: Iniciando requisição para", form.attr('action'));

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            data: form.serialize(),
            
            success: function(response) {
                console.log("AJAX Adicionar Doutor: Sucesso.", response);
                window.showToast(response.message, 'success');
                
                $('#modalAdicionarMedico').modal('hide');
                form[0].reset(); 
                
                setTimeout(() => { 
                    window.location.reload(); 
                }, 1000); 
            },
            
            error: function(xhr) {
                console.error("AJAX Adicionar Doutor: Erro.", xhr);
                let errorMessage = 'Ocorreu um erro desconhecido ao adicionar o médico.'; 
                
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errorKeys = Object.keys(xhr.responseJSON.errors);
                    if (errorKeys.length > 0) {
                        errorMessage = xhr.responseJSON.errors[errorKeys[0]][0]; 
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } 

                window.showToast(errorMessage, 'error');
            },
            
            complete: function() {
                window.showLoading(false); 
                console.log("AJAX Adicionar Doutor: Requisição concluída.");
            }
        });
    });

    // ===============================================
    // 2. FUNÇÃO CONFIRM DELETE (AJAX)
    // ===============================================
    // Funções globais devem ser definidas fora do escopo de $(document).ready
    // mas são redefinidas aqui para garantir o acesso ao jQuery.
    window.confirmDelete = function(deleteUrl) { 
        console.log(`Chamada global confirmDelete para URL: ${deleteUrl}.`);
 
        $('#confirmDeleteModal').modal('show');
 
        $('#deleteForm').off('submit').on('submit', function(event) {
            event.preventDefault(); 
            
            const formToDelete = $(this);
            window.showLoading(true); 
            console.log("AJAX Deletar Doutor: Iniciando requisição DELETE para", deleteUrl);
 
            // Laravel usa um campo oculto _method=DELETE para simular o método DELETE
            const dataWithMethod = formToDelete.serialize() + '&_method=DELETE';

            $.ajax({
                url: deleteUrl, 
                method: 'POST',  
                data: dataWithMethod,  
                
                success: function(response) {
                    console.log("AJAX Deletar Doutor: Sucesso.", response);
                    window.showToast(response.message, 'success');
 
                    $('#confirmDeleteModal').modal('hide'); 
                     
                    setTimeout(() => { 
                        window.location.reload(); 
                    }, 1000); 
                },
                
                error: function(xhr) {
                    console.error("AJAX Deletar Doutor: Erro.", xhr);
                    let errorMessage = 'Ocorreu um erro ao excluir. Tente novamente.'; 
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } 
                    window.showToast(errorMessage, 'error');
                },
                
                complete: function() {
                    window.showLoading(false);
                    console.log("AJAX Deletar Doutor: Requisição concluída.");
                }
            });
        });
    }

    // ===============================================
    // 3. CARREGAR DADOS DE EDIÇÃO E CONFIGURAR FORM
    // ===============================================
 
    window.editDoutor = function(doctorId) {
        console.log(`Chamada global editDoutor para ID: ${doctorId}.`);
        window.showLoading(true);
        console.log("AJAX Editar Doutor: Buscando dados para ID:", doctorId);
        
        const editForm = $('#editForm'); 
        const fetchUrl = `/users/${doctorId}/edit`; // Exemplo: Sua rota GET para buscar dados
        const updateUrl = `/users/${doctorId}`;     // Exemplo: Sua rota PUT para atualizar dados
        
        editForm.attr('action', updateUrl); 
 
        $.ajax({
            url: fetchUrl, 
            method: 'GET',
            success: function(response) {
                console.log("AJAX Editar Doutor: Dados recebidos.", response);
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
                console.error("AJAX Editar Doutor: Erro ao buscar dados.", xhr);
                window.showToast('Erro ao buscar dados para edição. Verifique o console.', 'error');
            },
            complete: function() {
                window.showLoading(false);
                console.log("AJAX Editar Doutor: Busca de dados concluída.");
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
        console.log("AJAX Atualizar Doutor: Iniciando requisição PUT para", form.attr('action'));
 
        const dataWithMethod = form.serialize() + '&_method=PUT';

        $.ajax({
            url: form.attr('action'), 
            method: 'POST',  
            data: dataWithMethod,
            
            success: function(response) {
                console.log("AJAX Atualizar Doutor: Sucesso.", response);
                window.showToast(response.message, 'success');
                $('#editModal').modal('hide'); 
                
                setTimeout(() => { 
                    window.location.reload(); 
                }, 1000); 
            },
            
            error: function(xhr) {
                console.error("AJAX Atualizar Doutor: Erro.", xhr);
                let errorMessage = 'Ocorreu um erro ao atualizar o médico.'; 
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errorKeys = Object.keys(xhr.responseJSON.errors);
                    if (errorKeys.length > 0) {
                        errorMessage = xhr.responseJSON.errors[errorKeys[0]][0]; 
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } 
                window.showToast(errorMessage, 'error');
            },
            
            complete: function() {
                window.showLoading(false);
                console.log("AJAX Atualizar Doutor: Requisição concluída.");
            }
        });
    });

});