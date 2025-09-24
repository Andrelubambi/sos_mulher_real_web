const express = require('express');
const { createServer } = require('http');
const { Server } = require('socket.io');
const Redis = require('ioredis');

const app = express();
const server = createServer(app);

// Socket.IO com configuração compatível com Laravel Echo
const io = new Server(server, {
    cors: {
        origin: ["https://sosmulherreal.com", "http://localhost"],
        methods: ["GET", "POST"],
        credentials: true
    },
    // IMPORTANTE: Use Engine.IO v3 para compatibilidade com Laravel Echo
    allowEIO3: true,
    transports: ['websocket', 'polling']
});

// Conexão com Redis
const redis = new Redis({
    host: 'laravel_redis',
    port: 6379,
    retryDelayOnFailover: 100,
    enableReadyCheck: false,
    maxRetriesPerRequest: null,
});

// Verificar conexão Redis
redis.on('connect', () => {
    console.log('✅ Conectado ao Redis');
});

redis.on('error', (err) => {
    console.error('❌ Erro Redis:', err);
});

// Subscribe nos canais do Laravel
redis.psubscribe('laravel_database_*', (err, count) => {
    if (err) {
        console.error('❌ Erro ao se inscrever:', err);
    } else {
        console.log(`✅ Conectado ao Redis, escutando canais: ${count}`);
    }
});

// Processar mensagens do Redis
redis.on('pmessage', (pattern, channel, message) => {
    try {
        console.log('📡 Evento Redis recebido:', channel);
        
        const data = JSON.parse(message);
        const eventName = data.event || 'message';
        const eventData = data.data || {};
        
        // CORREÇÃO: Usar uma expressão regular para capturar o nome do canal
        const match = channel.match(/^laravel_database_(.*)/);

        let chatChannel;

        if (match && match[1]) {
            chatChannel = match[1];
        } else {
            // Se o canal não corresponder, retornamos o canal original
            chatChannel = channel;
        }
        
        console.log(`🎯 Processando evento: ${eventName} no canal: ${chatChannel}`);
        
        // Emitir para todos os clientes conectados no canal específico
        io.to(chatChannel).emit(eventName, eventData);
        
        // Log para debug
        console.log(`📡 Mensagem redistribuída para canal: ${chatChannel}`);
        
    } catch (error) {
        console.error('❌ Erro ao processar mensagem Redis:', error);
    }
});

// Quando um cliente se conecta
io.on('connection', (socket) => {
    console.log('🔌 Cliente conectado:', socket.id);
    
    // Cliente se junta a um canal específico
    socket.on('join', (channel) => {
        socket.join(channel);
        console.log(`👥 Socket ${socket.id} entrou no canal: ${channel}`);
    });
    
    // Cliente sai de um canal
    socket.on('leave', (channel) => {
        socket.leave(channel);
        console.log(`👋 Socket ${socket.id} saiu do canal: ${channel}`);
    });
    
    // Quando cliente desconecta
    socket.on('disconnect', () => {
        console.log('🔌 Cliente desconectado:', socket.id);
    });
    
    // Echo do Laravel - compatibilidade
    socket.on('echo', (data) => {
        console.log('📨 Echo recebido:', data);
    });
});

// Rota de health check
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        timestamp: new Date().toISOString(),
        connections: io.engine.clientsCount
    });
});

// Iniciar servidor
const PORT = process.env.SOCKET_PORT || 6001;
server.listen(PORT, '0.0.0.0', () => {
    console.log('🚀 Socket.IO Server rodando na porta', PORT);
    console.log('🌐 Endpoint:', `http://0.0.0.0:${PORT}`);
});

// Graceful shutdown
process.on('SIGTERM', () => {
    console.log('🛑 Recebido SIGTERM, fechando servidor...');
    server.close(() => {
        redis.disconnect();
        process.exit(0);
    });
});
