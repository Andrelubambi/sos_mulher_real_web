import io from 'socket.io-client';

export function initializeEcho() {
    try {
        window.socket = io(`https://${window.location.hostname}`, {
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

        // INTERFACE SIMULADA COMPLETA
        window.Echo = {
            connector: { socket: window.socket },
            socketId: () => window.socket.id,
            private: (channel) => ({
                listen: (event, callback) => {
                    const eventName = event.startsWith('.') ? event : `.${event}`;
                    window.socket.on(`${channel}${eventName}`, callback);
                    return this;
                },
                listenForWhisper: (event, callback) => {
                    window.socket.on(`client-${event}`, callback);
                    return this;
                },
                whisper: (event, data) => {
                    window.socket.emit(`client-${event}`, data);
                },
                error: (callback) => {
                    window.socket.on('error', callback);
                    return this;
                },
                stopListening: (event, callback) => {
                    const eventName = event.startsWith('.') ? event : `.${event}`;
                    window.socket.off(`${channel}${eventName}`, callback);
                    return this;
                }
            }),
            channel: (channel) => ({
                listen: (event, callback) => {
                    const eventName = event.startsWith('.') ? event : `.${event}`;
                    window.socket.on(`${channel}${eventName}`, callback);
                    return this;
                }
            }),
            leave: (channel) => {
                // Implementação simplificada
                console.log('Left channel:', channel);
            },
            leaveChannel: (channel) => {
                console.log('Left channel:', channel);
            }
        };

        console.log('🚀 WebSocket configurado com interface completa');
        
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