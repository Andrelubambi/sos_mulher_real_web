// resources/js/chat/echo.js
import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io;

export function initializeEcho() {
    try {
        console.log('🚀 [ECHO] Iniciando configuração do WebSocket...');
        
        // CORREÇÃO CRÍTICA: Sempre usar a porta 6001 do Socket.IO
        const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
        const socketHost = isLocalhost ? 'http://localhost:6001' : `${window.location.protocol}//${window.location.hostname}:6001`;
        
        console.log(`🔌 [ECHO] Conectando em: ${socketHost}`);
        
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            client: io,
            host: socketHost,
            path: '/socket.io/',
            transports: ['websocket', 'polling'],
            autoConnect: true,
            reconnectionAttempts: 5,
            reconnectionDelay: 3000,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}`
                }
            }
        });

        // Eventos de conexão
        window.Echo.connector.socket.on('connect', () => {
            console.log('✅ [ECHO] CONECTADO ao WebSocket!');
            console.log(`🆔 [ECHO] Socket ID: ${window.Echo.socketId()}`);
            updateConnectionStatus(true);
            
            const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
            if (userId) {
                window.Echo.connector.socket.emit('user-online', { userId });
                console.log(`👤 [ECHO] Usuário ${userId} marcado como online`);
            }
        });

        window.Echo.connector.socket.on('disconnect', (reason) => {
            console.warn(`🔌 [ECHO] Desconectado. Razão: ${reason}`);
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('❌ [ECHO] Erro de conexão:', error);
            console.error('📍 [ECHO] Verifique se o Socket.IO está rodando na porta 6001');
            updateConnectionStatus(false);
        });

        window.Echo.connector.socket.on('reconnect', (attemptNumber) => {
            console.log(`🔄 [ECHO] Reconectado após ${attemptNumber} tentativa(s)`);
            updateConnectionStatus(true);
        });

        window.Echo.connector.socket.on('reconnect_attempt', (attemptNumber) => {
            console.log(`🔄 [ECHO] Tentativa de reconexão ${attemptNumber}...`);
        });

        window.Echo.connector.socket.on('reconnect_error', (error) => {
            console.error('❌ [ECHO] Erro na reconexão:', error);
        });

        console.log('✅ [ECHO] Configuração concluída!');
        
    } catch (error) {
        console.error('💥 [ECHO] Erro fatal na inicialização:', error);
        updateConnectionStatus(false);
    }
}

export function updateConnectionStatus(connected) {
    // Atualizar indicador no header
    const headerDot = document.getElementById('headerConnectionDot');
    if (headerDot) {
        if (connected) {
            headerDot.classList.add('online');
            headerDot.classList.remove('offline');
            headerDot.style.backgroundColor = '#10b981';
        } else {
            headerDot.classList.remove('online');
            headerDot.classList.add('offline');
            headerDot.style.backgroundColor = '#ef4444';
        }
    }

    // Atualizar status textual (se existir)
    const connectionText = document.getElementById('connectionText');
    if (connectionText) {
        connectionText.textContent = connected ? 'Conectado' : 'Desconectado';
    }
    
    console.log(`📊 [ECHO] Status de conexão: ${connected ? 'ONLINE ✅' : 'OFFLINE ❌'}`);
}