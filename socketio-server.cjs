const server = require('http').createServer();
const io = require('socket.io')(server, {
  cors: {
    origin: ['*', 'http://localhost:8080'],
    methods: ['GET', 'POST'],
  },
  transports: ['websocket', 'polling'],
});
const Redis = require('ioredis');



const redisHost = process.env.REDIS_HOST || 'redis'; 
const redisPort = process.env.REDIS_PORT || 6379; 
// ...
const pubClient = new Redis(redisPort, redisHost);
const subClient = new Redis(redisPort, redisHost);


const redis = new Redis({
  host: process.env.REDIS_HOST || 'redis',
  port: process.env.REDIS_PORT || 6379,
});

redis.on('connect', () => {
  console.log('✅ Conectado ao Redis');
});

redis.on('error', (err) => {
  console.error('❌ Erro de conexão com o Redis:', err);
});
 
redis.psubscribe('private-*', (err, count) => {
  if (err) {
    console.error('❌ Erro de subscrição (pattern):', err);
    return;
  }
  console.log(`✅ Conectado ao Redis, escutando canais: ${count}`);
});

// Também assinar canais sem prefixo 'private-'
redis.psubscribe('chat-*', (err, count) => {
  if (err) {
    console.error('❌ Erro de subscrição (pattern chat-*):', err);
    return;
  }
  console.log(`✅ Conectado ao Redis, escutando canais adicionais: ${count}`);
});

redis.on('pmessage', (pattern, channel, message) => {
  try {
    const data = JSON.parse(message);
    const event = data.event; // e.g., 'MessageSent'
    const payload = data.data; // dados do evento

    if (channel && event) {
      console.log(`📣 Mensagem recebida no Redis: ${channel}, Evento: ${event}`);
      // Canal original do Redis (ex.: 'private-chat.6-11')
      const originalChannel = channel;
      // Alias sem o prefixo 'private-' para compatibilidade com clientes que usam 'chat.6-11'
      const aliasChannel = channel.startsWith('private-') ? channel.replace(/^private-/, '') : `private-${channel}`;

      // 1) Formato esperado pelo Laravel Echo: `${canal}:${evento}`
      io.of('/').to(originalChannel).emit(`${originalChannel}:${event}`, payload);
      io.of('/').to(aliasChannel).emit(`${aliasChannel}:${event}`, payload);

      // 2) Emissão simples somente pelo nome do evento (alguns clientes escutam assim)
      io.of('/').to(originalChannel).emit(event, payload);
      io.of('/').to(aliasChannel).emit(event, payload);
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
    // Entrar no canal informado e no alias correspondente
    const alias = channel.startsWith('private-') ? channel.replace(/^private-/, '') : `private-${channel}`;
    socket.join(channel);
    if (alias !== channel) {
      socket.join(alias);
      console.log(`🎧 Alias adicional unido: ${alias}`);
    }
  });

  socket.on('unsubscribe', (data) => {
    const channel = typeof data === 'string' ? data : data?.channel;
    if (!channel) {
      console.warn(`⚠️ Unsubscribe sem canal válido do cliente ${socket.id}:`, data);
      return;
    }
    console.log(`👋 Cliente ${socket.id} saindo do canal: ${channel}`);
    const alias = channel.startsWith('private-') ? channel.replace(/^private-/, '') : `private-${channel}`;
    socket.leave(channel);
    if (alias !== channel) {
      socket.leave(alias);
      console.log(`👋 Alias adicional removido: ${alias}`);
    }
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
