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

        // No seu arquivo de chat
window.Echo.private(`chat.${senderId}-${receiverId}`)
.subscribed(() => {
    console.log('✅ CANAL AUTENTICADO com sucesso!');
})
.error((error) => {
    console.log('❌ ERRO na autenticação do canal:', error);
})
.listen('MessageSent', (e) => {
    console.log('📨 MENSAGEM RECEBIDA:', e);
    // Sua lógica para exibir a mensagem
});
// Quando enviar mensagem, adicione logs:
console.log('📤 ENVIANDO mensagem para canal:', `chat.${senderId}-${receiverId}`);
        // INTERFACE CORRIGIDA COM ENCADEAMENTO
        window.Echo = {
            connector: { socket: window.socket },
            socketId: () => window.socket.id,
            
            private: (channel) => {
                const channelObj = {
                    listen: (event, callback) => {
                        const eventName = event.startsWith('.') ? event : `.${event}`;
                        window.socket.on(`${channel}${eventName}`, callback);
                        return channelObj;
                    },
                    listenForWhisper: (event, callback) => {
                        window.socket.on(`client-${event}`, callback);
                        return channelObj;
                    },
                    whisper: (event, data) => {
                        window.socket.emit(`client-${event}`, data);
                        return channelObj;
                    },
                    error: (callback) => {
                        window.socket.on('error', callback);
                        return channelObj;
                    },
                    stopListening: (event, callback) => {
                        const eventName = event.startsWith('.') ? event : `.${event}`;
                        window.socket.off(`${channel}${eventName}`, callback);
                        return channelObj;
                    }
                };
                return channelObj;
            },
            
            leave: (channel) => {
                console.log('Left channel:', channel);
            }
        };

        console.log('🚀 WebSocket com interface corrigida');
        
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