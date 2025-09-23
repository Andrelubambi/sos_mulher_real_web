import Echo from 'laravel-echo';
import io from 'socket.io-client'; // ← AGORA É A VERSÃO 2.4.0

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
            
            // CONFIGURAÇÕES ESPECÍFICAS PARA v2.4.0
            client: {
                // Forçar compatibilidade com Echo Server (v2)
                forceNode: false,
                reconnection: true,
                reconnectionAttempts: Infinity,
                reconnectionDelay: 1000,
                reconnectionDelayMax: 5000,
                randomizationFactor: 0.5,
                timeout: 20000,
            },
            
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }
        });

        window.Echo.connector.socket.on('connect', () => {
            console.log('✅ Conectado ao Laravel Echo Server! (v2.4.0)');
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

        console.log('🚀 Laravel Echo inicializado com Socket.IO v2.4.0');
        
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