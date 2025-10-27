import '../bootstrap';
import { initializeEcho } from './chat/echo.js';       
import { setupUI } from './chat/ui.js';                 
import { setupChat } from './chat/chat.js';              
 

document.addEventListener('DOMContentLoaded', () => {
    initializeEcho();
    setupUI();
    setupChat();
   

    setInterval(() => {
        if (!window.echoConnected && window.Echo) {
            console.log('Tentando reconectar...');
            try {
                window.Echo.connector.socket.connect(); 
            } catch (error) {
                console.error('Erro ao tentar reconectar:', error);
            }
        }
    }, 10000);
});