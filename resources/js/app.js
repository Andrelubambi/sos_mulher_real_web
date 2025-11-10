    import './bootstrap';
    import './sos/message.js';
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

    

    document.addEventListener('DOMContentLoaded', () => {
        // Inicialize o Echo
        const echoService = new EchoService();
        echoService.initialize();
        
            
            echoService.listenToSOS((data) => {
                console.log('🔔 SOS RECEBIDO EM TEMPO REAL:', data);
                
                // 1. Mostrar o toast de alerta (usando sua função showToast)
                if (typeof window.showToast === 'function') {
                    window.showToast(`SOS de urgência recebido! Enviado por: ${data.enviado_por}`, 'error'); 
                }
                
                fetchMensagensNaoLidas(); // Chamar a função do seu mensagens.js
            });
        // }
    });