const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const Redis = require('ioredis');

const app = express();
const server = http.createServer(app);

// CONFIGURAR CORS PARA SEU DOMÍNIO
const io = socketIo(server, {
    cors: {
        origin: ["https://sosmulherreal.com", "http://localhost"],
        methods: ["GET", "POST"],
        credentials: true
    },
    path: '/socket.io'
});

// CONECTAR AO REDIS (usando nome do container Docker)
const redis = new Redis({
    host: 'laravel_redis', // Nome do container Redis no Docker
    port: 6379,
    password: '', 
    keyPrefix: 'laravel_database_'
});

const connectedUsers = new Map();

io.on('connection', (socket) => {
    console.log('🔌 Cliente conectado:', socket.id);

    // REGISTRAR USUÁRIO ONLINE
    socket.on('user-online', (data) => {
        console.log('👤 Usuário online:', data.userId);
        connectedUsers.set(data.userId, {
            socketId: socket.id,
            userId: data.userId
        });
        socket.userId = data.userId;
    });

    // JUNTAR-SE A UM CANAL
    socket.on('join-channel', (data) => {
        console.log('🔐 Juntando-se ao canal:', data.channel);
        socket.join(data.channel);
        socket.emit(`${data.channel}:subscribed`);
    });

    // SAIR DE UM CANAL
    socket.on('leave-channel', (data) => {
        console.log('👋 Saindo do canal:', data.channel);
        socket.leave(data.channel);
    });

    // WHISPER (TYPING)
    socket.on('whisper:typing', (data) => {
        socket.broadcast.emit('whisper:typing', data);
    });

    // NOVA MENSAGEM (vinda do Laravel)
    socket.on('new-message', (data) => {
        console.log('📨 Nova mensagem recebida:', data);
        
        // REENVIAR PARA O CANAL ESPECÍFICO
        if (data.channel) {
            io.to(data.channel).emit(`${data.channel}:MessageSent`, data);
            console.log(`📡 Mensagem reenviada para canal: ${data.channel}`);
        }
        
        // TAMBÉM ENVIAR COMO EVENTO GLOBAL
        io.emit('message:MessageSent', data);
    });

    // DESCONEXÃO
    socket.on('disconnect', () => {
        console.log('🔌 Cliente desconectado:', socket.id);
        if (socket.userId) {
            connectedUsers.delete(socket.userId);
        }
    });
});

// ESCUTAR EVENTOS DO REDIS (Laravel Broadcasting)
redis.psubscribe('*', (err, count) => {
    if (err) {
        console.error('❌ Erro ao conectar Redis:', err);
    } else {
        console.log('✅ Conectado ao Redis, escutando canais:', count);
    }
});

redis.on('pmessage', (pattern, channel, message) => {
    try {
        console.log('📡 Evento Redis recebido:', channel);
        const data = JSON.parse(message);
        
        if (data.event && data.data) {
            const channelClean = channel.replace('laravel_database_', '').replace('private-', '');
            
            console.log('🎯 Processando evento:', data.event, 'Canal:', channelClean);
            
            // DADOS DA MENSAGEM
            const messageData = {
                ...data.data,
                channel: channelClean,
                event: data.event
            };
            
            // REENVIAR PARA CANAL ESPECÍFICO
            io.to(channelClean).emit(`${channelClean}:${data.event}`, messageData);
            
            // EVENTO GLOBAL
            io.emit(`message:${data.event}`, messageData);
            
            console.log(`📡 Mensagem redistribuída para canal: ${channelClean}`);
        }
    } catch (error) {
        console.error('❌ Erro ao processar mensagem Redis:', error);
    }
});

// ROTA DE HEALTH CHECK
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        connectedUsers: connectedUsers.size,
        timestamp: new Date().toISOString()
    });
});

// INICIAR SERVIDOR
const PORT = process.env.SOCKET_PORT || 6001;
server.listen(PORT, '0.0.0.0', () => {
    console.log(`🚀 Socket.IO Server rodando na porta ${PORT}`);
    console.log(`🌐 Endpoint: http://0.0.0.0:${PORT}`);
});

// TRATAMENTO DE ERROS
process.on('uncaughtException', (error) => {
    console.error('💥 Erro não tratado:', error);
});

process.on('unhandledRejection', (error) => {
    console.error('💥 Rejeição não tratada:', error);
});