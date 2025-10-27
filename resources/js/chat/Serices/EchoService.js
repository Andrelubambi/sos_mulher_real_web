export class EchoService {
    constructor() {
        this.echo = null;
        this.connected = false;
        this.currentChannel = null;
    }

    initialize() {
        try {
            const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
            const hostUrl = `${protocol}://${window.location.hostname}:6001`;
            window.Echo = new Echo({
                broadcaster: 'socket.io',
                host: hostUrl,
                transports: ['websocket'],
                autoConnect: true,
                auth: {
                    headers: {
                        'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                }
            });

            this.bindConnectionEvents();
            this.echo = window.Echo;
        } catch (error) {
            console.error('Erro ao inicializar Echo:', error);
            this.updateConnectionStatus(false);
        }
    }

    bindConnectionEvents() {
        window.Echo.connector.socket.on('connect', () => {
            console.log('Echo Server conectado!');
            this.connected = true;
            this.updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.log('Echo Server desconectado:', reason);
            this.connected = false;
            this.updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('Erro na conexão:', error);
            this.connected = false;
            this.updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log('Reconectado após', attemptNumber, 'tentativas');
        });
    }

    listenToUser(userId1, userId2, callback) {
        if (!this.echo) return;

        try {
            if (this.currentChannel) {
                this.echo.leave(this.currentChannel);
            }

            const channel = this.generateChatChannel(userId1, userId2);
            this.currentChannel = channel;

            this.echo.private(channel)
                .listen('MessageSent', callback)
                .error((error) => {
                    console.error('Erro no canal:', error);
                });
        } catch (error) {
            console.error('Erro ao escutar mensagens:', error);
        }
    }

    listenToSOS(callback) {
        if (!this.echo) return;

        this.echo.channel('mensagem_sos')
            .listen('.NovaMensagemSosEvent', callback);
    }

    generateChatChannel(userId1, userId2) {
        const minId = Math.min(userId1, userId2);
        const maxId = Math.max(userId1, userId2);
        return `chat.${minId}-${maxId}`;
    }

    updateConnectionStatus(connected) {
        const dot = document.getElementById('connectionDot');
        const text = document.getElementById('connectionText');

        if (dot && text) {
            if (connected) {
                dot.classList.remove('disconnected');
                text.textContent = 'Conectado';
            } else {
                dot.classList.add('disconnected');
                text.textContent = 'Desconectado';
            }
        }
    }

    disconnect() {
        if (this.currentChannel && this.echo) {
            this.echo.leave(this.currentChannel);
            this.currentChannel = null;
        }
    }
}