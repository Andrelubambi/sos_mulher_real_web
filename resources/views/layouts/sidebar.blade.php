<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('vendors/images/android-chrome-192x192.png') }}" alt="Logo" style="height: 60px;" />
        </a>
        <div class="sidebar-close" data-toggle="left-side-bar-close">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <!-- Dashboard -->
                <li class="dropdown">
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-tachometer-alt"></i></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>

                <!-- Consultas -->
            @if(in_array(auth()->user()->role, ['admin', 'doutor', 'vitima']))
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-calendar-check"></i></span>
                        <span class="mtext">Consultas</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('consulta') }}"><i class="fa fa-calendar" aria-hidden="true" style="margin-right:6px;"></i> Todas as Consultas</a></li>
                    @if(in_array(auth()->user()->role, ['admin', 'doutor', 'estagiario,vitima']))
                        <li><a href="{{ route('consulta') }}">Minhas Consultas</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Médicos -->
                @if(in_array(auth()->user()->role, ['admin', 'estagiario']))
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-user-md"></i></span>
                        <span class="mtext">Médicos</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('users.doutor') }}">Lista de Médicos</a></li>
                        @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('users.doutor.create') }}">Adicionar Médico</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- Estagiários -->
                @if(auth()->user()->role === 'admin')
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-user-graduate"></i></span>
                        <span class="mtext">Estagiários</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('users.estagiario') }}">Lista de Estagiários</a></li>
                        <li><a href="{{ route('users.estagiario.create') }}">Adicionar Estagiário</a></li>
                    </ul>
                </li>
                @endif

                <!-- Vítimas -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-users"></i></span>
                        <span class="mtext">Vítimas</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('users.vitima') }}">Lista de Vítimas</a></li>
                        @if(in_array(auth()->user()->role, ['admin', 'estagiario']))
                        <li><a href="{{ route('users.vitima.create') }}">Adicionar Vítima</a></li>
                        @endif
                    </ul>
                </li>

                <!-- Grupos -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-layer-group"></i></span>
                        <span class="mtext">Grupos</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#" data-toggle="modal" data-target="#createGroupModal">Criar Grupo</a></li>
                        @foreach ($grupos as $grupo)
                        <li>
                            <a href="{{ route('grupos.show', $grupo->id) }}">{{ $grupo->nome }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <!-- Chat -->
                <li>
                    <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                        <span class="micon"><i class="fas fa-comments"></i></span>
                        <span class="mtext">Chat</span>
                    </a>
                </li>

                <!-- Relatórios -->
                @if(auth()->user()->role === 'admin')
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon"><i class="fas fa-chart-bar"></i></span>
                        <span class="mtext">Relatórios</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('reports.consultas') }}">Consultas</a></li>
                        <li><a href="{{ route('reports.pacientes') }}">Pacientes</a></li>
                        <li><a href="{{ route('reports.financeiro') }}">Financeiro</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>