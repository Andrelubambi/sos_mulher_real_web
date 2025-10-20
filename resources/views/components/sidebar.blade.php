@props(['grupos' => collect()])


<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('vendors/images/logo-mulher-real.png') }}" alt="Logo" style="height: 60px;" />
        </a>
        <div class="sidebar-close" data-toggle="left-side-bar-close">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="menu-block customscroll">  
       <div class="sidebar-menu">
        <ul id="accordion-menu">
            <!-- Dashboard (todos têm acesso) -->
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-speedometer2"></span>
                    <span class="mtext">Dashboard</span>
                </a>
                <ul class="submenu">
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    @elseif(auth()->user()->role === 'doutor')
                        <li><a href="{{ route('doutor.dashboard') }}">Dashboard Médico</a></li>
                    @elseif(auth()->user()->role === 'estagiario')
                        <li><a href="{{ route('estagiario.dashboard') }}">Dashboard Assistente</a></li>
                    @elseif(auth()->user()->role === 'vitima')
                        <li><a href="{{ route('vitima.dashboard') }}">Dashboard Vítima</a></li>
                    @endif
                </ul>
            </li>

            <!-- Consultas -->
            @if(in_array(auth()->user()->role, ['admin', 'doutor', 'vitima']))
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-calendar-check"></span>

                        <span class="mtext">Consultas</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('consulta') }}"><i class="fa fa-calendar" aria-hidden="true"
                         style="margin-right:6px;"></i> Todas as Consultas</a></li>
                    @if(in_array(auth()->user()->role, ['admin', 'doutor', 'estagiario,vitima']))
                         <li><a href="{{ route('consulta') }}"><i class="fa fa-calendar" aria-hidden="true"
                         style="margin-right:6px;"></i> Minhas Consultas</a></li>
                        @endif
                    </ul>
                </li>
                @endif

            <!-- Médicos (apenas admin) -->
            @if(auth()->user()->role === 'admin')
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-person-badge"></span>
                    <span class="mtext">Médico</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('users.doutor') }}">Lista de Médicos</a></li>
                </ul>
            </li>
            @endif

            <!-- Assistentes (apenas admin) -->
            @if(auth()->user()->role === 'admin')
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-person-workspace"></span>
                    <span class="mtext">Lista de Assistentes</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('users.estagiario') }}">Assistentes</a></li>
                </ul>
            </li> 
            @endif

            <!-- Vítimas (admin e médicos) -->
            @if(in_array(auth()->user()->role, ['admin', 'doutor']))
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-people"></span>
                    <span class="mtext">Vítimas</span>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('users.vitima') }}">Lista de Vítimas</a></li>
                </ul>
            </li>
            @endif

 
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle">
                    <span class="micon bi bi-collection"></span>
                    <span class="mtext">Grupos</span>
                </a>
                <ul class="submenu">
                    @if(auth()->user()->role === 'admin')
                       <li><a href="{{ route('grupos.create') }}">Criar Grupo</a></li>
                    @endif
                    @foreach ($grupos as $grupo)
                        <li>
                            <a href="{{ route('grupos.show', $grupo->id) }}">{{ $grupo->nome }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
       

            <!-- Chat (todos têm acesso) -->
            <li>
                <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                    <span class="micon bi bi-chat-right-dots"></span>
                    <span class="mtext">Chat</span>
                </a>
            </li>
        </ul>
    </div>
</div>