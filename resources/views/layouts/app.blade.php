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
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/profile-photo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />  
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style> 
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7); /* Fundo escuro semitransparente */
        display: none; /* Oculto por padrão */
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Garante que fique acima de tudo */
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .loading-overlay.active {
        display: flex; /* Exibe quando ativo */
        opacity: 1;
    }

    .loading-content {
        text-align: center;
        color: white;
    }

    /* Estilo do Spinner (exemplo básico) */
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 4px solid #fff;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Estilos para o Toast Container (usando classes Bootstrap/Custom) */
    .toast-container {
        z-index: 1090; /* Acima de modais Bootstrap */
    }

    /* Estilos para o Toast */
    .custom-toast {
        min-width: 250px;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-top: 10px;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .custom-toast.show {
        display: block;
        opacity: 1;
    }

    .toast-success { background-color: #28a745; }  
    .toast-error { background-color: #dc3545; } 
</style>
   
 
   <!-- @vite(['resources/js/app.js']) -->
</head>

<body data-success-message="{{ session('success_message') }}"
    data-error-messages="{{ json_encode($errors->all()) }}"> 
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>
 
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
                        <input type="hidden" name="mensagem" value="Estou precisando de ajuda, urgente!">
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
    {{-- 1. Check for authenticated user with a profile photo --}}
    @if (Auth::check() && Auth::user()->profile_photo)
        <img src="{{ Auth::user()->profile_photo }}"
             alt="Foto de perfil"
             class="profile-photo-small me-2">
        <span class="user-name">Olá, seja bem-vindo {{ Auth::user()->name }}</span>
        
    {{-- 2. If no photo, or if the user is authenticated (but not a guest) --}}
    @else
        <i class="fa fa-user-circle"></i>  
        
        {{-- Check if the user is authenticated (using @auth for clarity) --}}
        @auth
            <span class="user-name">Olá, seja bem-vindo {{ Auth::user()->name }}</span>
        @endauth
        
        {{-- Check if the user is a guest (not authenticated) --}}
        @guest
            <span class="user-name text-muted">Olá, visitante</span>
        @endguest
    @endif
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
    @vite(['resources/js/app.js'])
    @vite('resources/js/sos/message.js')
    
<script>
  
    function showLoading(show = true) {
        const overlay = document.getElementById('loadingOverlay');
        if (!overlay) return;
        if (show) {
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            setTimeout(() => {
                overlay.classList.remove('active');
                document.body.style.overflow = ''; 
            }, 300); 
        }
    }

    
    function showToast(message, type) { 
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toastEl = document.createElement('div');
        toastEl.classList.add('custom-toast', `toast-${type}`);
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.innerHTML = `
            <div style="font-weight: bold;">${type === 'success' ? 'Sucesso! 🎉' : 'Erro! 🚨'}</div>
            <div>${message}</div>
        `;

        container.appendChild(toastEl);

        setTimeout(() => {
            toastEl.classList.add('show');
        }, 100);
 
        setTimeout(() => {
            toastEl.classList.remove('show');
            setTimeout(() => {
                toastEl.remove();
            }, 300); 
        }, 5000);
    }

    window.showLoading = showLoading;
    window.showToast = showToast;
     
    document.addEventListener('DOMContentLoaded', () => { 
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                setTimeout(() => showLoading(true), 50); 
            });
        });
 
        window.addEventListener('load', () => {
             showLoading(false); 
        }); 
        if (document.readyState === 'loading') {
            showLoading(true);
        }
    });
 
</script>

@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
