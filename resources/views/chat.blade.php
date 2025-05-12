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
                        <!-- filepath: c:\laragon\www\sos-mulher\resources\views\chat.blade.php -->
                        <div class="chat-container">
                            <!-- Lista de Usuários -->
                            <div class="user-list">
                                <div class="chat-header">
                                    <h4>Usuários Disponíveis</h4>
                                    <input type="text" class="chat-search" placeholder="Pesquisar usuário..." />
                                </div>
                                <ul class="user-items">
                                    @foreach ($usuariosNaoDoutores as $usuario)
                                        <li class="user-item" data-id="{{ $usuario->id }}">
                                            <img src="{{ $usuario->profile_photo_url ?? 'vendors/images/default-avatar.png' }}"
                                                alt="Foto de {{ $usuario->name }}" class="user-avatar" />
                                            <div class="user-info">
                                                <h5>{{ $usuario->name }}</h5>
                                                <p>Última mensagem...</p>
                                            </div>
                                            <span class="message-time">13:02</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Caixa de Chat -->
                            <div class="chat-box">
                                <div class="chat-header">
                                    <h4 id="chat-with">Selecione um usuário para iniciar o chat</h4>
                                </div>
                                <div id="messages" class="chat-messages">
									<div class="text-muted">Nenhuma mensagem ainda.</div>
								</div>
                                <form id="sendMessageForm" class="chat-input">
                                    @csrf
                                    <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." required></textarea>
                                    <button type="submit" class="btn-send">Enviar</button>
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
            const userList = document.querySelectorAll('.user-item');
            const messagesDiv = document.getElementById('messages');
            const sendMessageForm = document.getElementById('sendMessageForm');
            const conteudoInput = document.getElementById('conteudo');
            const chatWithHeader = document.getElementById('chat-with');
            let selectedUserId = null;

            // Carregar mensagens ao clicar em um usuário
            userList.forEach(user => {
                user.addEventListener('click', function() {
                    selectedUserId = this.dataset.id;
                    const userName = this.querySelector('.user-info h5').textContent;

                    chatWithHeader.textContent = `Chat com ${userName}`;
                    fetch(`/chat/messages/${selectedUserId}`)
                        .then(response => response.json())
                        .then(messages => {
                            messagesDiv.innerHTML = '';
                            messages.forEach(message => {
                                const messageDiv = document.createElement('div');
                                messageDiv.classList.add('message', message.de ===
                                    {{ auth()->user()->id }} ? 'sent' : 'received');
                                messageDiv.innerHTML = `
									<img src="${message.remetente.profile_photo_url ?? 'vendors/images/default-avatar.png'}" alt="${message.remetente.name}" class="message-avatar">
									<div class="message-content">
										<strong>${message.de === {{ auth()->user()->id }} ? 'Você' : message.remetente.name}:</strong>
										${message.conteudo}
									</div>
								`;
                                messagesDiv.appendChild(messageDiv);
                            });

                            sendMessageForm.style.display = 'flex';
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
                        messageDiv.classList.add('message', 'sent');
                        messageDiv.innerHTML = `
							<img src="{{ auth()->user()->profile_photo_url ?? 'vendors/images/default-avatar.png' }}" alt="Você" class="message-avatar">
							<div class="message-content">
								<strong>Você:</strong>
								${message.conteudo}
							</div>
						`;
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
