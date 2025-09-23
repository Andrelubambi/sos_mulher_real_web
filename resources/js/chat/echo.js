
import io from 'socket.io-client';

export function initializeEcho() {
    try {
         
        window.socket = io(`https://${window.location.hostname}`, {  // ← SEM porta, usa Nginx
            path: '/socket.io',
            transports: ['websocket', 'polling'],
            secure: true
        });

        window.socket.on('connect', () => {
            console.log('✅ CONECTADO ao WebSocket!');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        window.socket.on('disconnect', (reason) => {
            console.log('🔌 Desconectado:', reason);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.socket.on('connect_error', (error) => {
            console.error('💥 Erro:', error);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        // Interface simulada para o chat.js funcionar
        window.Echo = {
            private: (channel) => ({
                listen: (event, callback) => {
                    window.socket.on(`${channel}.${event}`, callback);
                    return this;
                },
                listenForWhisper: (event, callback) => {
                    window.socket.on(`client-${event}`, callback);
                    return this;
                },
                whisper: (event, data) => {
                    window.socket.emit(`client-${event}`, data);
                }
            })
        };

        console.log('🚀 WebSocket configurado com sucesso');
        
    } catch (error) {
        console.error('❌ Erro:', error);
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