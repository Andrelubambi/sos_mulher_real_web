import Echo from 'laravel-echo';
import io from 'socket.io-client'; // ← AGORA É A VERSÃO 4.7.5

// Configurar globalmente
window.io = io;

export function initializeEcho() {
    try {
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname,
            port: 6001,
            path: '/socket.io',
            transports: ['websocket', 'polling'],
            autoConnect: true,
            
            // CONFIGURAÇÕES PARA v4.7.5 (mais simples)
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }
        });

        window.Echo.connector.socket.on('connect', () => {
            console.log('✅ Conectado ao Laravel Echo Server! (v4.7.5)');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.log('🔌 Desconectado:', reason);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('💥 Erro de conexão:', error);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log('🔄 Reconectado após', attemptNumber, 'tentativas');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        console.log('🚀 Laravel Echo + Socket.IO v4.7.5 inicializado');
        
    } catch (error) {
        console.error('❌ Erro ao inicializar Laravel Echo:', error);
        updateConnectionStatus(false);
    }
}

export function updateConnectionStatus(connected) {
    const connectionDot = document.getElementById('connectionDot');
    const connectionText = document.getElementById('connectionText');
    if (connected) {
        connectionDot.classList.add('connected');
        connectionText.textContent = 'Conectado';
    } else {
        connectionDot.classList.remove('connected');
        connectionText.textContent = 'Desconectado';
    }
}