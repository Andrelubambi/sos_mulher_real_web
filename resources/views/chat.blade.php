<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-GBZ3SGGX85");
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
    </script>
    <!-- End Google Tag Manager -->
</head>

<body>




    <div class="left-side-bar">

        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="index.html">Dashboard style 1</a></li>
                            <li><a href="index2.html">Dashboard style 2</a></li>
                            <li><a href="index3.html">Dashboard style 3</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Chat</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Chat
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-radius-4 box-shadow mb-30">
                    <div class="row no-gutters">
                        <!-- Lista de Usuários -->
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="chat-list bg-light-gray">
                                <div class="chat-search">
                                    <span class="ti-search"></span>
                                    <input type="text" placeholder="Search Contact" />
                                </div>
                                <div class="notification-list chat-notification-list customscroll">
                                    <ul>
                                        @foreach ($usuariosNaoDoutores as $usuario)
                                            <li>
                                                <a href="javascript:void(0);" class="user"
                                                    data-id="{{ $usuario->id }}">
                                                    <img src="vendors/images/profile-photo.jpg" alt="" />
                                                    <h3 class="clearfix">{{ $usuario->name }}</h3>
                                                    <p>
                                                        <i class="fa fa-circle text-light-green"></i>
                                                        {{ $usuario->role }}
                                                    </p>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <div class="chat-box">
                                <div id="messages" class="chat-desc customscroll">
                                    @forelse($messages ?? [] as $message)
                                        <div class="{{ $message->de === auth()->user()->id ? 'sent' : 'received' }}">
                                            <strong>{{ $message->de === auth()->user()->id ? 'Você' : $message->remetente->name }}:</strong>
                                            {{ $message->conteudo }}
                                        </div>
                                    @empty
                                        <div class="text-muted">Nenhuma mensagem ainda.</div>
                                    @endforelse
                                </div>
                                <form id="sendMessageForm" style="display: none;">
                                    @csrf
                                    <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." required></textarea>
                                    <button type="submit">Enviar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </div>
    </div>
    <!-- welcome modal start -->


    <!-- js -->

    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userId = {{ auth()->user()->id }};
            const otherUserId = {{ $usuario->id }};

            const channelName = 'chat.' + Math.min(userId, otherUserId) + '-' + Math.max(userId, otherUserId);

            Echo.private(channelName)
                .listen('MessageSent', (e) => {
                    console.log('Mensagem recebida:', e);

                    const chatBox = document.querySelector('.chat-desc');
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', 'mb-2');
                    messageDiv.innerHTML = `<strong>${e.remetente.name}:</strong> ${e.conteudo}`;
                    chatBox.appendChild(messageDiv);

                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userList = document.querySelectorAll('.user');
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');
            let selectedUserId = null;

            userList.forEach(user => {
                user.addEventListener('click', function() {
                    selectedUserId = this.dataset.id;

                    fetch(`/chat/messages/${selectedUserId}`)
                        .then(response => response.json())
                        .then(messages => {
                            messagesDiv.innerHTML = '';
                            messages.forEach(message => {
                                const messageDiv = document.createElement('div');
                                messageDiv.classList.add(
                                    message.de === {{ auth()->user()->id }} ?
                                    'sent' : 'received'
                                );
                                messageDiv.textContent = message.conteudo;
                                messagesDiv.appendChild(messageDiv);
                            });
                        });
                });
            });


            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!selectedUserId) {
                    alert('Selecione um usuário para enviar a mensagem.');
                    return;
                }

                const conteudo = conteudoInput.value;

                fetch(`/chat/send/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            conteudo
                        }),
                    })
                    .then(response => response.json())
                    .then(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('sent');
                        messageDiv.textContent = message.conteudo;
                        messagesDiv.appendChild(messageDiv);
                        conteudoInput.value = '';
                    });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userList = document.querySelectorAll('.user');
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');
            let selectedUserId = null;

            // Carregar mensagens ao clicar em um usuário
            userList.forEach(user => {
                user.addEventListener('click', function() {
                    selectedUserId = this.dataset.id;

                    fetch(`/chat/messages/${selectedUserId}`)
                        .then(response => response.json())
                        .then(messages => {
                            messagesDiv.innerHTML = '';
                            messages.forEach(message => {
                                const messageDiv = document.createElement('div');
                                messageDiv.classList.add(
                                    message.de === {{ auth()->user()->id }} ?
                                    'sent' : 'received'
                                );
                                messageDiv.textContent = message.conteudo;
                                messagesDiv.appendChild(messageDiv);
                            });

                            // Exibir o formulário de envio de mensagens
                            sendMessageForm.style.display = 'block';
                        });
                });
            });

            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!selectedUserId) {
                    alert('Selecione um usuário para enviar a mensagem.');
                    return;
                }

                const conteudo = conteudoInput.value;

                fetch(`/chat/send/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            conteudo
                        }),
                    })
                    .then(response => response.json())
                    .then(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('sent');
                        messageDiv.textContent = message.conteudo;
                        messagesDiv.appendChild(messageDiv);
                        conteudoInput.value = '';
                    });
            });
        });
    </script>

    <script class="">
        document.addEventListener('DOMContentLoaded', function() {
            const userList = document.querySelectorAll('.user');
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');
            let selectedUserId = null;

            // Carregar mensagens ao clicar em um usuário
            userList.forEach(user => {
                user.addEventListener('click', function() {
                    selectedUserId = this.dataset.id;

                    fetch(`/chat/messages/${selectedUserId}`)
                        .then(response => response.json())
                        .then(messages => {
                            messagesDiv.innerHTML = '';
                            messages.forEach(message => {
                                const messageDiv = document.createElement('div');
                                messageDiv.classList.add(
                                    message.de === {{ auth()->user()->id }} ?
                                    'sent' : 'received'
                                );
                                messageDiv.innerHTML =
                                    `<strong>${message.de === {{ auth()->user()->id }} ? 'Você' : message.remetente.name}:</strong> ${message.conteudo}`;
                                messagesDiv.appendChild(messageDiv);
                            });

                            // Exibir o formulário de envio de mensagens
                            sendMessageForm.style.display = 'block';
                        });
                });
            });

            // Enviar nova mensagem
            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!selectedUserId) {
                    alert('Selecione um usuário para enviar a mensagem.');
                    return;
                }

                const conteudo = conteudoInput.value;

                fetch(`/chat/send/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            conteudo
                        }),
                    })
                    .then(response => response.json())
                    .then(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('sent');
                        messageDiv.innerHTML = `<strong>Você:</strong> ${message.conteudo}`;
                        messagesDiv.appendChild(messageDiv);
                        conteudoInput.value = '';
                    });
            });
        });
    </script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
