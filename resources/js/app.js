// resources/js/app.js

import './bootstrap';
import { initializeEcho } from './chat/echo.js'; 
import { setupUI } from './chat/ui.js';
import { setupChat } from './chat/chat.js';
// Importe a função SOS que será usada globalmente
import { fetchMensagensNaoLidas } from './sos/message.js'; 

document.addEventListener('DOMContentLoaded', () => { 
    
     initializeEcho(); 
    
     if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('mensagem_sos')
            .listen('.NovaMensagemSosEvent', (e) => {
                console.log('🔔 SOS RECEBIDO EM TEMPO REAL:', e);
   
                if (typeof window.showToast === 'function') {
                    window.showToast(`SOS de urgência recebido! Enviado por: ${e.enviado_por}`, 'error'); 
                }
  
                fetchMensagensNaoLidas(); 
            });
    }
 
    const chatContainer = document.querySelector('.chat-container'); 
    
    if (chatContainer) {
        
        setupUI();
        setupChat(); 
         
    }
});