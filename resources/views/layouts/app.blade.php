<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">

    <meta charset="utf-8" />  
    <title>SOS-MULHER</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/profile-photo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />


    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   
 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  <div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <p>Carregando, por favor aguarde...</p>
    </div>
</div>
    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
            <div class="header-search"></div>
        </div>
        <div class="header-right">
            @if (auth()->user()->role == 'vitima')
                <div class="user-notification">
                    <form action="{{ route('mensagem_sos.send') }}" method="POST"
                        style="display:inline-block; margin-left: 10px;">
                        @csrf
                        <input type="hidden" name="mensagem" value="conteudo da mensagem sos">
                        <button type="submit" title="Enviar SOS" style="background:none; border:none; cursor:pointer;">
                            <i class="fa fa-exclamation-triangle" style="color:red; font-size: 20px;"></i>
                        </button>
                    </form>
                </div>
            @endif

            <div id="mensagemAlerta" class="mensagem-alerta hidden" style="cursor:pointer;">
                <span class="mensagem-icone"><i class="fa fa-envelope"></i></span>
                <span id="mensagemTextoCompleto" class="mensagem-texto"></span>
            </div>

            <div id="mensagemModal" class="mensagem-modal hidden" data-mensagem-id="">
                <div class="mensagem-modal-conteudo">
                    <h4>Mensagem Recebida</h4>
                    <p id="mensagemConteudo"></p>
                    <small id="mensagemData" style="display:block;margin-top:10px;color:#666;"></small>
                    <div style="margin-top: 10px; text-align: right;">
                        <button id="enviarResposta" style="margin-right: 10px;">Responder</button>
                        <button id="fecharModal">OK</button>
                    </div>
                </div>
            </div>
<div class="user-info-dropdown">
    <div class="dropdown">
        <a class="dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown">
            <img src="{{ Auth::user() && Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : asset('vendors/images/user-default.png') }}"
     alt="Foto de perfil"
     class="profile-photo-small me-2">

            @guest
                <span class="user-name text-muted">Olá, visitante</span>
            @else
                <span class="user-name">Olá, seja bem-vindo {{ Auth::user()->name }}</span>
            @endguest
        </a>
     <div class="dropdown-menu custom-user-dropdown">

            @auth
                <a class="dropdown-item" href="{{ route('profile', Auth::user()->id) }}">
                    <i class="fa fa-user-circle"></i> Ver Perfil
                </a>
            @endauth

            <a class="dropdown-item text-danger" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out"></i> Sair
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>

        </div>
    </div>
 
         <x-sidebar :grupos="$grupos" />
    </div>
    <div class="mobile-menu-overlay"></div>
    
    <div class="main-container">
        @yield('content')
    </div>
    
        <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script>
        let mensagensPendentes = [];
        let carregamentoConcluido = false;

        document.addEventListener('DOMContentLoaded', function() {
            const userIdLogado = document.querySelector('meta[name="user-id"]').getAttribute('content');
            fetch('/mensagens_nao_lidas')
                .then(res => res.json())
                .then(dados => {
                    if (dados && dados.length > 0) {
                        mensagensPendentes = dados;
                        atualizarAlerta();
                    }
                    carregamentoConcluido = true;
                });
            if (!window.echoRegistered) {
                Echo.channel('mensagem_sos')
                    .listen('.NovaMensagemSosEvent', (e) => {
                        if (String(e.user_id) !== userIdLogado) {
                            return;
                        }
                        const mensagem = {
                            id: e.id,
                            conteudo: e.conteudo,
                            data: e.data
                        };
                        mensagensPendentes.unshift(mensagem);
                        atualizarAlerta();
                    });
                window.echoRegistered = true;
            }
            document.getElementById('mensagemAlerta').addEventListener('click', () => {
                mostrarProximaMensagem();
            });
            document.getElementById('fecharModal').addEventListener('click', () => {
                const mensagemAtual = mensagensPendentes.shift();
                document.getElementById('mensagemModal').classList.add('hidden');

                fetch('/mensagem_lida', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: mensagemAtual.id
                    })
                });
                if (mensagensPendentes.length > 0) {
                    setTimeout(() => mostrarProximaMensagem(), 300);
                } else {
                    document.getElementById('mensagemAlerta').classList.add('hidden');
                }
                atualizarAlerta();
            });

            function atualizarAlerta() {
                const alerta = document.getElementById('mensagemAlerta');
                const texto = document.getElementById('mensagemTextoCompleto');

                if (mensagensPendentes.length > 0) {
                    alerta.classList.remove('hidden');
                    texto.textContent = `Nova mensagem (${mensagensPendentes.length})`;
                } else {
                    alerta.classList.add('hidden');
                    texto.textContent = '';
                }
            }

            function mostrarProximaMensagem() {
                const mensagem = mensagensPendentes[0];
                if (!mensagem) return;
                document.getElementById('mensagemConteudo').textContent = mensagem.conteudo;
                document.getElementById('mensagemData').textContent = formatarData(mensagem.data);
                document.getElementById('mensagemModal').classList.remove('hidden');
            }

            function formatarData(dataString) {
                const data = new Date(dataString);
                return data.toLocaleString('pt-PT', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        });

        function abrirModalMensagem(mensagem) {
            const modal = document.getElementById('mensagemModal');
            const conteudo = document.getElementById('mensagemConteudo');
            const data = document.getElementById('mensagemData');
            conteudo.textContent = mensagem.conteudo;
            data.textContent = mensagem.data;
            modal.dataset.mensagemId = mensagem.id;
            modal.classList.remove('hidden');
        }
        document.getElementById('enviarResposta').addEventListener('click', () => {
            const mensagemAtual = mensagensPendentes[0];
            if (mensagemAtual && mensagemAtual.id) {
                window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
            } else {
                alert('Mensagem inválida para responder.');
            }
        });
    </script>


<script>
function showLoading(show = true) {
    const overlay = document.getElementById('loadingOverlay');
    if (!overlay) return;
    if (show) {
        overlay.classList.add('active');
    } else {
        overlay.classList.remove('active');
    }
}

// Exibir enquanto a página carrega
document.addEventListener('readystatechange', () => {
    if (document.readyState === 'loading') {
        showLoading(true);
    }
});

window.addEventListener('load', () => {
    setTimeout(() => showLoading(false), 600);
});

// Mostrar enquanto envia formulários
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => showLoading(true));
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>