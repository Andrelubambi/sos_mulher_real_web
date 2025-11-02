/**
 * Lógica de manipulação de formulários de autenticação (voluntariado, login, etc.)
 * Centraliza o feedback ao usuário (Loading e Toasts).
 */
function handleFormFeedback() {
    // Verifica e exibe mensagem de sucesso (session('success'))
    const successMessage = document.querySelector('body').dataset.successMessage;
    // Usa window.showToast, assumindo que foi definido no layout
    if (successMessage && typeof window.showToast === 'function') { 
        window.showToast(successMessage, 'success');
    }

    // Verifica e exibe mensagens de erro de validação ($errors->all())
    const errorMessagesJson = document.querySelector('body').dataset.errorMessages;
    if (errorMessagesJson && typeof window.showToast === 'function') {
        try {
            const errorMessages = JSON.parse(errorMessagesJson);
            if (errorMessages.length > 0) {
                // Junta todas as mensagens de erro em um único Toast, separadas por linha.
                const formattedMessages = errorMessages.join('<br>- ');
                window.showToast('- ' + formattedMessages, 'error');
            }
        } catch (e) {
            console.error("Erro ao analisar mensagens de erro do Laravel:", e);
        }
    }
}

/**
 * Adiciona um listener a todos os formulários para exibir o Loading Overlay na submissão.
 */
function setupLoadingOnSubmit() {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            // Assume que window.showLoading está definido no layout
            if (typeof window.showLoading === 'function') {
                window.showLoading(true);
            }
        });
    });
}


// --- Funções Específicas do Formulário de Voluntariado ---

/**
 * Lógica para alternar a seleção visual e o estado do checkbox.
 */
function toggleCheckboxSelection(element) {
    const checkbox = element.querySelector('input[type="checkbox"]');
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        element.classList.toggle('selected', checkbox.checked);
    }
}

/**
 * Configura os listeners para a seleção de áreas no formulário de voluntariado.
 */
function setupVolunteerAreaSelection() {
    document.querySelectorAll('.partnership-type-item').forEach(item => {
        // Usa o listener de click para gerenciar o estado da seleção
        item.addEventListener('click', function() {
            toggleCheckboxSelection(this);
        });
    });
}


// --- Inicialização ---
document.addEventListener('DOMContentLoaded', function() {
    // 1. Configura o carregamento ao submeter qualquer formulário
    setupLoadingOnSubmit();

    // 2. Tenta configurar a seleção de áreas (se o elemento existir)
    if (document.querySelector('.partnership-types')) {
        setupVolunteerAreaSelection();
    }
    
    // 3. Exibe os Toasts (Sucesso ou Erros de Validação)
    handleFormFeedback();
});

// Expor a função de seleção para uso caso ela seja chamada via onclick inline (como no seu HTML)
window.toggleCheckboxSelection = toggleCheckboxSelection;

// --- FIM ---