
function handleFormFeedback() { 
    const successMessage = document.querySelector('body').dataset.successMessage; 
    if (successMessage && typeof window.showToast === 'function') { 
        window.showToast(successMessage, 'success');
    }
 
    const errorMessagesJson = document.querySelector('body').dataset.errorMessages;
    if (errorMessagesJson && typeof window.showToast === 'function') {
        try {
            const errorMessages = JSON.parse(errorMessagesJson);
            if (errorMessages.length > 0) {
  
                const formattedMessages = errorMessages.join('<br>- ');
                window.showToast('- ' + formattedMessages, 'error');
            }
        } catch (e) {
            console.error("Erro ao analisar mensagens de erro do Laravel:", e);
        }
    }
}
 
function setupLoadingOnSubmit() {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            if (typeof window.showLoading === 'function') {
                window.showLoading(true);
            }
        });
    });
}


function toggleCheckboxSelection(element) {
    const checkbox = element.querySelector('input[type="checkbox"]');
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        element.classList.toggle('selected', checkbox.checked);
    }
}
function setupVolunteerAreaSelection() {
    document.querySelectorAll('.partnership-type-item').forEach(item => {
        item.addEventListener('click', function() {
            toggleCheckboxSelection(this);
        });
    });
}

 
document.addEventListener('DOMContentLoaded', function() {
 
    setupLoadingOnSubmit();
   if (document.querySelector('.partnership-types')) {
        setupVolunteerAreaSelection();
    }
    handleFormFeedback();
});
window.toggleCheckboxSelection = toggleCheckboxSelection;
