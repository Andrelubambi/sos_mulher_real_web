@php
    $user = Auth::user();
    $fotoPerfil = $user && $user->profile_photo
        ? asset('storage/' . $user->profile_photo)
        : asset('vendors/images/user-default.png');
@endphp

<div class="header">
    <div class="header-left">
        <div class="menu-icon" data-toggle="left-side-bar">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <div class="header-right">
        <!-- SOS Button (somente para vítimas) -->
        @if ($user && $user->role === 'vitima')
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

        <!-- Alerta de Mensagens -->
        <div id="mensagemAlerta" class="mensagem-alerta hidden">
            <span class="mensagem-icone"><i class="fas fa-envelope"></i></span>
            <span id="mensagemTextoCompleto" class="mensagem-texto"></span>
        </div>

        <!-- Dropdown do Usuário -->
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle flex items-center" href="#" role="button" data-toggle="dropdown">
                     @if (!empty(Auth::user()->profile_photo))
                    <img id="profilePreview"
                         src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                         alt="Foto de perfil"
                         class="profile-photo-large">
                @else
                    <i class="fas fa-user-circle" style="font-size: 42px; color: #666;"></i>
                @endif
                    <span class="user-name text-dark">
                        {{ $user->name ?? 'Usuário' }}<br>
                        <small class="text-muted">{{ ucfirst($user->role ?? 'Usuário') }}</small>
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-user"></i> Perfil
                    </a>
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

<style>
    .profile-photo-small {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e3e3e3;
    }

    .sos-btn {
        background-color: #ff4b4b;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 6px 12px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .sos-btn:hover {
        background-color: #cc0000;
    }

    .mensagem-alerta {
        display: flex;
        align-items: center;
        gap: 6px;
        background-color: #ffe8b3;
        border: 1px solid #f1c40f;
        border-radius: 5px;
        padding: 4px 8px;
        margin-right: 12px;
        cursor: pointer;
    }

    .mensagem-alerta.hidden {
        display: none;
    }

    .mensagem-icone {
        color: #f39c12;
    }

    .mensagem-texto {
        font-size: 14px;
        color: #444;
    }
</style>
