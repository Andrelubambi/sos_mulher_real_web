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

// Subscrever a canais privados do Laravel
// Não é necessária autenticação, pois a lógica foi removida do backend
redis.subscribe('private-chat', (err, count) => {
  if (err) {
    console.error('❌ Erro de subscrição:', err);
    return;
  }
  console.log(`✅ Conectado ao Redis, escutando canais: ${count}`);
});

redis.on('message', (channel, message) => {
  try {
    const data = JSON.parse(message);
    const event = data.event;
    const channelName = data.channel;
    const payload = data.data;

    if (channelName && event) {
      console.log(`📣 Mensagem recebida no Redis: ${channelName}, Evento: ${event}`);
      io.of('/').to(channelName).emit(event, payload);
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

  socket.on('subscribe', (channel) => {
    console.log(`🎧 Cliente ${socket.id} subscrevendo ao canal: ${channel}`);
    socket.join(channel);
  });

  socket.on('unsubscribe', (channel) => {
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
