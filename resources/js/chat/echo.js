import Echo from 'laravel-echo';
import io from 'socket.io-client';

// Configuração global do Socket.IO
window.io = io;

export function initializeEcho() {
    try {
        // VERIFICAÇÃO: Use HTTP se estiver em desenvolvimento, HTTPS em produção
        const useHttps = window.location.protocol === 'https:';
        const host = window.location.hostname;
        
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: host,
            port: useHttps ? 443 : 6001, // Porta 443 para HTTPS, 6001 para HTTP
            path: '/socket.io',
            transports: ['websocket', 'polling'],
            forceWebsockets: false,
            autoConnect: true,
            withCredentials: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                }
            }
        });

        // Event listeners para debug
        window.Echo.connector.socket.on('connect', () => {
            console.log('✅ Conectado ao Laravel Echo Server!');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.log('❌ Desconectado do Echo Server:', reason);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('💥 Erro na conexão Echo:', error);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('reconnecting', (attemptNumber) => {
            console.log('🔄 Reconectando... Tentativa:', attemptNumber);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log('✅ Reconectado após', attemptNumber, 'tentativas');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        console.log('🚀 Laravel Echo inicializado com sucesso');
        
    } catch (error) {
        console.error('💥 Erro crítico ao inicializar Echo:', error);
        updateConnectionStatus(false);
    }
}

export function updateConnectionStatus(connected) {
    const connectionDot = document.getElementById('connectionDot');
    const connectionText = document.getElementById('connectionText');
    
    if (connectionDot && connectionText) {
        if (connected) {
            connectionDot.classList.add('connected');
            connectionText.textContent = 'Conectado';
            connectionDot.style.backgroundColor = '#4CAF50';
        } else {
            connectionDot.classList.remove('connected');
            connectionText.textContent = 'Desconectado';
            connectionDot.style.backgroundColor = '#f44336';
        }
    }
}

// Export para uso global
export default window.Echo;