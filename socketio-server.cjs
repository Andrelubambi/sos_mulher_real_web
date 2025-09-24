const server = require('http').createServer();
const io = require('socket.io')(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
  },
  transports: ['websocket', 'polling'],
});
const Redis = require('ioredis');

const redis = new Redis({
  host: process.env.REDIS_HOST || 'laravel_redis',
  port: process.env.REDIS_PORT || 6379,
});

redis.on('connect', () => {
  console.log('✅ Conectado ao Redis');
});

redis.on('error', (err) => {
  console.error('❌ Erro de conexão com o Redis:', err);
});

// Subscrever com curinga aos canais privados do Laravel (ex.: private-chat.6-11)
// Usamos psubscribe para receber todos os canais que começam com "private-"
redis.psubscribe('private-*', (err, count) => {
  if (err) {
    console.error('❌ Erro de subscrição (pattern):', err);
    return;
  }
  console.log(`✅ Conectado ao Redis, escutando canais: ${count}`);
});

redis.on('pmessage', (pattern, channel, message) => {
  try {
    const data = JSON.parse(message);
    const event = data.event; // e.g., 'MessageSent'
    const payload = data.data; // dados do evento

    if (channel && event) {
      console.log(`📣 Mensagem recebida no Redis: ${channel}, Evento: ${event}`);
      // Emitir para a sala com o mesmo nome do canal Redis (ex.: 'private-chat.6-11')
      io.of('/').to(channel).emit(event, payload);
    }
  } catch (e) {
    console.error('❌ Erro ao analisar a mensagem do Redis:', e);
  }
});

io.on('connection', (socket) => {
  console.log(`🔌 Cliente conectado: ${socket.id}`);

  socket.on('disconnect', () => {
    console.log(`🔌 Cliente desconectado: ${socket.id}`);
  });

  socket.on('subscribe', (data) => {
    // O Echo envia um objeto { channel: 'private-chat.6-11', auth: {...} }
    const channel = typeof data === 'string' ? data : data?.channel;
    if (!channel) {
      console.warn(`⚠️ Subscribe sem canal válido do cliente ${socket.id}:`, data);
      return;
    }
    console.log(`🎧 Cliente ${socket.id} subscrevendo ao canal: ${channel}`);
    socket.join(channel);
  });

  socket.on('unsubscribe', (data) => {
    const channel = typeof data === 'string' ? data : data?.channel;
    if (!channel) {
      console.warn(`⚠️ Unsubscribe sem canal válido do cliente ${socket.id}:`, data);
      return;
    }
    console.log(`👋 Cliente ${socket.id} saindo do canal: ${channel}`);
    socket.leave(channel);
  });

  // Ouve whispers diretamente no socket
  socket.on('whisper', (data) => {
    const { channel, event, payload } = data;
    // Emite o whisper para o canal correto (incluindo o próprio remetente)
    io.of('/').to(channel).emit(`whisper-${event}`, payload);
  });
});

const port = process.env.PORT || 6001;
server.listen(port, () => {
  console.log(`🚀 Socket.IO Server rodando na porta ${port}`);
  console.log(`🌐 Endpoint: http://0.0.0.0:${port}`);
});
