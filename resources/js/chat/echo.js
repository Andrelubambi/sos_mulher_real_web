export function initializeEcho() {
    try {
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: `${window.location.hostname}:6001`,
            transports: ['websocket','polling'],
            autoConnect: true,
            auth: {
                headers: {
                    'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }
        });

        window.Echo.connector.socket.on('connect', () => {
            console.log('Laravel Echo Server conectado!');
            window.echoConnected = true;
            updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.log('Laravel Echo Server desconectado:', reason);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('Erro na conexão com Laravel Echo Server:', error);
            window.echoConnected = false;
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log('Laravel Echo Server reconectado após', attemptNumber, 'tentativas');
        });

        console.log('Laravel Echo inicializado');
    } catch (error) {
        console.error('Erro ao inicializar Laravel Echo:', error);
        updateConnectionStatus(false);
    }
}

export function updateConnectionStatus(connected) {
    const connectionDot = document.getElementById('connectionDot');
    const connectionText = document.getElementById('connectionText');
    if (connected) {
        connectionDot.classList.remove('disconnected');
        connectionText.textContent = 'Conectado';
    } else {
        connectionDot.classList.add('disconnected');
        connectionText.textContent = 'Desconectado';
    }
}