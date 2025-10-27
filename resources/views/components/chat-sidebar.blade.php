@props(['chatsRecentes', 'usuariosNaoDoutores'])
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-title">
            <h2>Mensagens</h2>
            <button class="new-chat-btn" id="newChatBtn" title="Nova conversa">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="sidebar-actions">
            <button class="tab-btn active" id="tabMensagens">Recentes</button>
            <button class="tab-btn" id="tabUsuarios">Usuários</button>
        </div>
    </div>
    
    <div class="tab-content">
        {{-- Conversas Recentes --}}
        <div id="mensagensRecentes" class="tab-panel active">
            <div class="conversations-list">
                @forelse ($chatsRecentes as $chat)
                <div class="conversation-item chat-item" data-user-id="{{ $chat['user']->id }}" data-user-name="{{ $chat['user']->name }}">
                    <div class="avatar-container">
                        <div class="avatar">{{ substr($chat['user']->name, 0, 1) }}</div>
                        {{-- Presume que Carbon está disponível --}}
                        <div class="online-status {{ $chat['user']->last_seen && \Carbon\Carbon::parse($chat['user']->last_seen)->gt(now()->subMinutes(5)) ? 'online' : 'offline' }}"></div>
                    </div>
                    <div class="conversation-info">
                        <div class="user-name">{{ $chat['user']->name }}</div>
                        <div class="preview-message">{{ \Illuminate\Support\Str::limit($chat['mensagem']->conteudo, 50) }}</div>
                    </div>
                    <div class="conversation-meta">
                        <span class="timestamp">{{ \Carbon\Carbon::parse($chat['mensagem']->created_at)->format('H:i') }}</span>
                        @if($chat['unread_count'] > 0)
                        <div class="unread-badge">{{ $chat['unread_count'] }}</div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-comments"></i>
                    <p>Nenhuma conversa recente</p>
                    <small>Inicie uma nova conversa</small>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Lista de Usuários --}}
        <div id="listaUsuarios" class="tab-panel">
            <div class="users-list">
                @forelse ($usuariosNaoDoutores as $user)
                <div class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                    <div class="avatar-container">
                        <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                        <div class="online-status {{ $user->last_seen && \Carbon\Carbon::parse($user->last_seen)->gt(now()->subMinutes(5)) ? 'online' : 'offline' }}"></div>
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-status">{{ $user->last_seen && \Carbon\Carbon::parse($user->last_seen)->gt(now()->subMinutes(5)) ? 'Online' : 'Offline' }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Nenhum usuário encontrado</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</aside>