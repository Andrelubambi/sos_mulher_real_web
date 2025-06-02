<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Broadcast Redis Socket io - Messages</title>

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.css" />

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f8;
    display: flex;
    justify-content: center;
    padding: 40px;
  }

  .chat-container {
    width: 100%;
    max-width: 600px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    padding: 20px;
  }

  h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
  }

  #chat-box {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
    background-color: #fafafa;
  }

  .message {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    max-width: 80%;
    line-height: 1.4;
  }

  .message.user {
    background-color: #d1e7dd;
    align-self: flex-end;
    text-align: right;
    margin-left: auto;
  }

  .message.other {
    background-color: #e2e3e5;
    align-self: flex-start;
    text-align: left;
    margin-right: auto;
  }

  #chat-form {
    display: flex;
    gap: 10px;
  }

  #message-input {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
  }

  #chat-form button {
    padding: 10px 20px;
    background-color: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
  }

  #chat-form button:hover {
    background-color: #0b5ed7;
  }
</style>

</head>
<body>
    <div class="chat-container">
  <h1>Mensagens em Grupo</h1>

  <div id="chat-box">
    <!-- As mensagens serão inseridas aqui via JS ou blade -->
  </div>

  <form id="chat-form">
    <input type="text" id="message-input" placeholder="Digite sua mensagem..." required />
    <button type="submit">Enviar</button>
  </form>
</div>


    {{-- Porta definida para o Laravel Echo (opcional, pode ser usado direto no JS) --}}
    <script>
        window.laravel_echo_port = '{{ env("LARAVEL_ECHO_PORT", 6001) }}';
    </script>

    {{-- Socket.io (importado via CDN baseado no host + porta) --}}
    <script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT', 6001) }}/socket.io/socket.io.js"></script>

    {{-- laravel-echo-setup.js deve ser incluído via Vite também (veja nota abaixo) --}}

<script>
    
  const messages = [
    { user_id: 1, name: 'João', conteudo: 'Olá, pessoal!' },
    { user_id: 2, name: 'Maria', conteudo: 'Oi João!' },
    { user_id: 1, name: 'João', conteudo: 'Tudo bem com vocês?' },
  ];

  const currentUserId = 1; // ID do usuário logado
  const chatBox = document.getElementById('chat-box');

  function renderMessages() {
    chatBox.innerHTML = '';
    messages.forEach(msg => {
      const div = document.createElement('div');
      div.classList.add('message');
      div.classList.add(msg.user_id === currentUserId ? 'user' : 'other');
      div.innerHTML = `<strong>${msg.name}</strong><br>${msg.conteudo}`;
      chatBox.appendChild(div);
    });
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  renderMessages();

  // Enviar nova mensagem
  document.getElementById('chat-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const input = document.getElementById('message-input');
    if (input.value.trim() !== '') {
      messages.push({ user_id: currentUserId, name: 'Você', conteudo: input.value });
      renderMessages();
      input.value = '';
    }
  });
</script>

</body>
</html>
