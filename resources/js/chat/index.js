import { initializeEcho, updateConnectionStatus } from './echo.js';
import { setupUI } from './ui.js';
import { setupChat } from './chat.js';
import { setupVideoCall } from './video.js';

// Inicializar o aplicativo
document.addEventListener('DOMContentLoaded', () => {
    initializeEcho();
    setupUI();
    setupChat();
    setupVideoCall();

    // Cleanup ao fechar a página
    window.addEventListener('beforeunload', () => {
        if (window.isCallActive) {
            window.endVideoCall();
        }
        if (window.currentChannel && window.Echo) {
            window.Echo.leave(window.currentChannel);
        }
    });

    // Tentar reconectar a cada 10 segundos se desconectado
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