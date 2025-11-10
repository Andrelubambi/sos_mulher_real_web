import io from 'socket.io-client';

export function initializeEcho() {
    try {
        // --- ⬇️ INÍCIO DA CORREÇÃO DE PROTOCOLO E PORTA ⬇️ ---
        const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
        const isHttps = window.location.protocol === 'https:';
        
        // Define o host: se for localhost/HTTP, usa ws://localhost:6001. Caso contrário, usa o host da página.
        const hostUrl = (isLocalhost || !isHttps) 
            ? 'http://localhost:6001' // Força HTTP/WS na porta do Socket.IO para ambientes de desenvolvimento
            : `https://${window.location.hostname}`;
            
        window.socket = io(hostUrl, {
            path: '/socket.io',
            transports: ['websocket', 'polling'],
            // O parâmetro 'secure' agora é dinâmico, baseado no protocolo da página
            secure: isHttps, 
            // --- ⬆️ FIM DA CORREÇÃO DE PROTOCOLO E PORTA ⬆️ ---

            auth: {
                userId: document.querySelector('meta[name="user-id"]')?.getAttribute('content'),
                token: localStorage.getItem('auth_token') || ''
            }
        });

        window.socket.on('connect', () => {
            console.log('✅ CONECTADO ao WebSocket!');
            
            const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
            window.socket.emit('user-online', { userId: userId });
            
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

        window.socket.on('new-message', (data) => {
            console.log('📨 NOVA MENSAGEM RECEBIDA via Socket.IO:', data);
            
            const event = new CustomEvent('messageReceived', {
                detail: data
            });
            document.dispatchEvent(event);
        });

        window.Echo = {
            connector: { socket: window.socket },
            socketId: () => window.socket.id,
            
            private: (channel) => {
                console.log('🔐 Conectando ao canal:', channel);
                
                window.socket.emit('join-channel', { 
                    channel: channel,
                    userId: document.querySelector('meta[name="user-id"]')?.getAttribute('content')
                });

                const channelObj = {
                    subscribed: (callback) => {
                        window.socket.on(`${channel}:subscribed`, () => {
                            console.log(`✅ Canal ${channel} autenticado!`);
                            callback();
                        });
                        return channelObj;
                    },
                    
                    error: (callback) => {
                        window.socket.on(`${channel}:error`, (error) => {
                            console.log(`❌ Erro no canal ${channel}:`, error);
                            callback(error);
                        });
                        return channelObj;
                    },
                    
                    listen: (event, callback) => {
                        const eventName = event.startsWith('.') ? event.substring(1) : event;
                        const fullEventName = `${channel}:${eventName}`;
                        
                        console.log(`👂 Escutando: ${fullEventName}`);
                        
                        window.socket.on(fullEventName, (data) => {
                            console.log(`📨 Evento recebido ${fullEventName}:`, data);
                            callback(data);
                        });
                        
                        window.socket.on(`message:${eventName}`, (data) => { 
                            if (data.channel === channel) {
                                console.log(`📨 Mensagem global para ${channel}:`, data);
                                callback(data);
                            }
                        });
                        
                        return channelObj;
                    },
                    
                    listenForWhisper: (event, callback) => {
                        window.socket.on(`whisper:${event}`, callback);
                        return channelObj;
                    },
                    
                    whisper: (event, data) => {
                        window.socket.emit(`whisper:${event}`, data);
                        return channelObj;
                    },
                    
                    stopListening: (event, callback) => {
                        const eventName = event.startsWith('.') ? event.substring(1) : event;
                        window.socket.off(`${channel}:${eventName}`, callback);
                        return channelObj;
                    }
                };
                
                return channelObj;
            },
            
            leave: (channel) => {
                console.log(`👋 Saindo do canal: ${channel}`);
                window.socket.emit('leave-channel', { channel });
            }
        };

        console.log('🚀 WebSocket com real-time implementado!');
        
    } catch (error) {
        console.error('❌ Erro:', error);
        updateConnectionStatus(false);
    }
}

export function updateConnectionStatus(connected) {
    const connectionDot = document.getElementById('connectionDot');
    const connectionText = document.getElementById('connectionText');
    if (connected) {
        connectionDot?.classList.add('connected');
        if (connectionText) connectionText.textContent = 'Conectado';
    } else {
        connectionDot?.classList.remove('connected');
        if (connectionText) connectionText.textContent = 'Desconectado';
    }
}  