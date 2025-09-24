import Echo from 'laravel-echo';
import io from 'socket.io-client';

export function initializeEcho() {
    try {
        // USAR LARAVEL ECHO REAL COM SUA CONFIGURAÇÃO
        window.io = io;
        
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: 'https://sosmulherreal.com:6001', // Sua configuração do Laravel Echo Server
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}`,
                },
            },
            authEndpoint: '/broadcasting/auth',
            transports: ['websocket', 'polling'],
            forceNew: false,
            reconnection: true,
            timeout: 60000,
            enabledTransports: ['websocket', 'polling']
        });

        // LISTENERS DE CONEXÃO
        window.Echo.connector.socket.on('connect', () => {
            console.log('✅ CONECTADO ao Laravel Echo Server!');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.log('🔌 Desconectado do Laravel Echo Server:', reason);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('💥 Erro na conexão Laravel Echo:', error);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        // DEBUG: Listeners adicionais
        window.Echo.connector.socket.on('error', (error) => {
            console.error('🚨 Socket Error:', error);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log('🔄 Reconectado após tentativas:', attemptNumber);
            updateConnectionStatus(true);
        });

        console.log('🚀 Laravel Echo inicializado com sucesso!');
        
    } catch (error) {
        console.error('❌ Erro ao inicializar Laravel Echo:', error);
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
        } else {
            connectionDot.classList.remove('connected');
            connectionText.textContent = 'Desconectado';
        }
    }
}