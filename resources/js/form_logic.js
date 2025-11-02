/**
 * Lógica para alternar a seleção de itens de parceria/voluntariado.
 * Suporta CHECKBOXES (seleção múltipla) e RADIO BUTTONS (seleção única).
 * * @param {HTMLElement} element O div .partnership-type-item clicado.
 */
window.toggleCheckboxSelection = function(element) { 
    // Tenta encontrar o input dentro do elemento clicado, independentemente do tipo.
    const input = element.querySelector('input');
    
    if (input) { 
        if (input.type === 'checkbox') {
            
            // === LÓGICA PARA CHECKBOXES (VOLUNTÁRIO: Múltiplas áreas) ===
            input.checked = !input.checked;
            element.classList.toggle('selected', input.checked);

        } else if (input.type === 'radio') {
            
            // === LÓGICA PARA RADIO BUTTONS (PARCEIRO: Seleção única) ===
            
            // 1. Marca o radio button atual
            input.checked = true;
            
            // 2. Garante que todos os outros itens do mesmo grupo fiquem desmarcados visualmente
            const radioGroupName = input.name;
            
            // Percorre todos os itens de parceria na página
            document.querySelectorAll('.partnership-type-item').forEach(item => {
                const itemInput = item.querySelector(`input[name="${radioGroupName}"]`);
                
                // Se o item pertencer ao mesmo grupo de radio buttons
                if (itemInput) {
                    // Remove a classe 'selected' de todos, exceto o input que acabou de ser clicado
                    if (itemInput === input) {
                        item.classList.add('selected'); // Adiciona ao item clicado
                    } else {
                        item.classList.remove('selected'); // Remove dos outros
                    }
                }
            });
        }
    }
};