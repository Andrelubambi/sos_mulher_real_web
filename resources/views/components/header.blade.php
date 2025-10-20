<div class="header">
    <div class="header-left">
        <div class="menu-icon" data-toggle="left-side-bar">
            <i class="fas fa-bars"></i>
        </div>
        <div class="search-toggle-icon" data-toggle="header-search">
            <i class="fas fa-search"></i>
        </div>
        <div class="header-search">
            <form>
                <div class="form-group mb-0">
                    <input type="text" class="form-control search-input" placeholder="Pesquisar...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="header-right">
        <!-- SOS Button for Victims -->
        @if (auth()->user()->role == 'vitima')
        <div class="user-notification">
            <form action="{{ route('mensagem_sos.send') }}" method="POST">
                @csrf
                <input type="hidden" name="mensagem" value="Preciso de ajuda urgente!">
                <button type="submit" class="sos-btn" title="Enviar SOS">
                    <i class="fas fa-exclamation-triangle"></i> SOS
                </button>
            </form>
        </div>
        @endif

        <!-- Message Alert -->
        <div id="mensagemAlerta" class="mensagem-alerta hidden">
            <span class="mensagem-icone"><i class="fas fa-envelope"></i></span>
            <span id="mensagemTextoCompleto" class="mensagem-texto"></span>
        </div>

        <!-- User Info -->
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <i class="fas fa-user-circle"></i>
                    </span>
                    <span class="user-name">
                        {{ Auth::user()->name }}
                        <small>{{ ucfirst(Auth::user()->role) }}</small>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-user"></i> Perfil
                    </a>
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-cog"></i> Configurações
                    </a>  
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
