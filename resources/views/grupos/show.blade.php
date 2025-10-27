@extends('layouts.app') 
  
@section('title', 'Chat do Grupo | ' . $grupo->nome)

@section('head_scripts_styles')
    {{-- Scripts/Styles existentes --}}
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    
    {{-- CSS para o SelectPicker (Adicionar Membros) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <style>
        /* Estilos do Chat (você pode movê-los para um arquivo CSS dedicado se preferir) */
        .chat-container {
            display: flex;
            flex-direction: column;
            /* Ajustado para não quebrar o layout se estiver muito alto */
            height: calc(100vh - 120px); 
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chat-header {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f8f9fa;
            display: flex; /* Adicionado display flex para alinhamento */
            flex-direction: column;
        }

        .message {
            margin-bottom: 10px;
            max-width: 80%;
            padding: 8px 12px;
            border-radius: 15px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message.sent {
            align-self: flex-end;
            margin-left: auto;
            background-color: #f8d7da; /* Vermelho mais claro para 'sent' */
            color: #721c24; /* Texto escuro */
            text-align: right;
        }
        
        .message.sent strong {
            color: #dc3545; /* Admin/Eu em destaque */
        }

        .message.received {
            align-self: flex-start;
            margin-right: auto;
            background-color: #fff; /* Branco */
            border: 1px solid #e9e9e9;
            color: #000;
            text-align: left;
        }
        
        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background-color: #fff;
        }

        .chat-input textarea {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
            resize: none;
            height: 40px; /* Altura inicial */
            overflow-y: hidden;
        }

        /* Estilos do Modal/Gerenciamento de Grupo */
        .admin-label {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.75em;
            margin-left: 5px;
        }

        .member-list {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Estilo do Modal (Nav Links Ativos) */
        .modal-body .nav-link.active {
            background-color: #dc3545 !important; 
            color: white !important;
            border-color: #dc3545 !important; 
        }
        /* Estilo do Hover para Abas Não Selecionadas */
        .modal-body .nav-link:not(.active):hover {
            background-color: #f8d7da !important; /* Vermelho claro */
            color: #dc3545 !important; /* Texto em vermelho */
            border-color: #f8d7da !important;
        }
    </style>
@endsection

@section('content')
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="chat-container">

                {{-- --- CHAT HEADER E BOTÕES DE AÇÃO --- --}}
                <div
    class="chat-header d-flex justify-content-between align-items-center bg-danger text-white px-3 py-2 rounded-top text-white">
    
    {{-- O nome do grupo pode envolver o texto para não forçar quebra --}}
    <span class="h5 mb-0 text-white me-2">Grupo: {{ $grupo->nome }}</span>
    
    {{-- AQUI ESTÁ A CORREÇÃO PRINCIPAL: --}}
    <div class="d-flex align-items-center flex-wrap gap-2">
        @if (auth()->id() === $grupo->admin_id)
            {{-- 1. Botão Gerenciar (Apenas Admin) - Removido o me-2, pois o gap-2 faz o trabalho --}}
            <button class="btn btn-sm btn-light text-danger" data-toggle="modal"
                data-target="#groupManagementModal">
                <i class="icon-copy fa fa-cog" aria-hidden="true"></i> Gerenciar Grupo
            </button>

            {{-- 2. Botão Excluir (Apenas Admin) --}}
            <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST"
                onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja excluir este grupo? Esta ação é irreversível.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-light text-danger">
                    <i class="icon-copy fa fa-trash" aria-hidden="true"></i> Excluir
                </button>
            </form>
        @elseif (!$grupo->users->contains(auth()->id()))
            {{-- Botão Entrar (Se o usuário NÃO for membro) --}}
            <form action="{{ route('grupos.entrar', $grupo->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-light text-success">
                    <i class="icon-copy fa fa-sign-in" aria-hidden="true"></i> Entrar no Grupo
                </button>
            </form>
        @else
            {{-- Botão Sair (Se o usuário for membro, mas não o admin) --}}
            <form action="{{ route('grupos.sair', $grupo->id) }}" method="POST"
                onsubmit="return confirm('Tem certeza que deseja sair deste grupo?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-light text-danger">
                    <i class="icon-copy fa fa-sign-out" aria-hidden="true"></i> Sair do Grupo
                </button>
            </form>
        @endif
    </div>
</div>
                {{-- --- CHAT MESSAGES --- --}}
                <div id="messages" class="chat-messages">
                    @forelse ($mensagens as $mensagem)
                        <div class="message {{ $mensagem->user_id === auth()->id() ? 'sent' : 'received' }}">
                            <div class="message-content">
                                <strong>{{ $mensagem->user_id === auth()->id() ? 'Você' : $mensagem->user->name }}:</strong>
                                {{ $mensagem->conteudo }}
                            </div>
                            <small class="text-muted" style="font-size: 0.65em;">
                                {{ $mensagem->created_at->format('H:i') }}
                            </small>
                        </div>
                    @empty
                        <div class="text-muted text-center w-100">Nenhuma mensagem ainda. Envie a primeira!</div>
                    @endforelse
                </div>

                {{-- --- CHAT INPUT --- --}}
                <form id="sendMessageForm" class="chat-input" data-grupo-id="{{ $grupo->id }}">
                    @csrf
                    <textarea name="conteudo" id="conteudo" placeholder="Digite sua mensagem..." required></textarea>
                    <button type="submit" class="btn btn-danger btn-sm" id="sendBtn">
                        <span id="sendBtnText"><i class="icon-copy fa fa-send" aria-hidden="true"></i> Enviar</span>
                        <span id="sendBtnLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                            Enviando...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- --- MODAL DE GERENCIAMENTO DE GRUPO --- --}}
    <div class="modal fade" id="groupManagementModal" tabindex="-1" role="dialog" aria-labelledby="groupManagementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="groupManagementModalLabel">Gerenciar Grupo: {{ $grupo->nome }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Nav Tabs --}}
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#details" role="tab"
                                aria-selected="true">Detalhes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#members" role="tab"
                                aria-selected="false">Membros ({{ $usuarios->count() }})</a>
                        </li>
                        @if (auth()->id() === $grupo->admin_id)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#addRemove" role="tab"
                                    aria-selected="false">Adicionar/Remover</a>
                            </li>
                        @endif
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content pt-3">
                        {{-- 1. Detalhes do Grupo --}}
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <p><strong>Nome:</strong> {{ $grupo->nome }}</p>
                            <p><strong>Descrição:</strong> {{ $grupo->descricao ?: 'Nenhuma descrição fornecida.' }}</p>
                            <p><strong>Admin:</strong> {{ $grupo->admin->name }} 
                                <span class="admin-label">Admin</span>
                            </p>
                            <p><strong>Criado em:</strong> {{ $grupo->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        {{-- 2. Membros --}}
                        <div class="tab-pane fade" id="members" role="tabpanel">
                            <div class="member-list list-group">
                                @foreach ($usuarios as $usuario)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            {{ $usuario->name }} ({{ $usuario->role }})
                                            @if ($usuario->id === $grupo->admin_id)
                                                <span class="admin-label">Admin</span>
                                            @endif
                                        </span>
                                        {{-- Botão de Remover Membro (Visível apenas para o Admin e se não for ele mesmo) --}}
                                        @if (auth()->id() === $grupo->admin_id && $usuario->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-member-btn"
                                                data-user-id="{{ $usuario->id }}"
                                                data-user-name="{{ $usuario->name }}">
                                                <i class="icon-copy fa fa-user-times" aria-hidden="true"></i> Remover
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($usuarios->isEmpty())
                                <p class="text-center text-muted mt-3">Nenhum membro neste grupo além do administrador.</p>
                            @endif
                        </div>

                        {{-- 3. Adicionar/Remover Membros (Apenas Admin) --}}
                        @if (auth()->id() === $grupo->admin_id)
                            <div class="tab-pane fade" id="addRemove" role="tabpanel">
                                <h5>Adicionar Novos Membros</h5>
                                <form id="addMembersForm" data-grupo-id="{{ $grupo->id }}">
                                    @csrf
                                    <div class="form-group">
                                        <select name="new_members[]" class="form-control selectpicker" multiple
                                            data-live-search="true"
                                            title="Selecione usuários para adicionar">
                                            @foreach ($usuariosDisponiveis as $usuario)
                                                {{-- Garante que o usuário não esteja na lista de membros atuais --}}
                                                @if (!$usuarios->contains($usuario->id))
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->role }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Adicionar Selecionados</button>
                                </form>
                                <p class="text-muted mt-3">Você pode remover membros usando o botão "Remover" na aba "Membros".</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts do Bootstrap-Select para a funcionalidade de Adicionar Membros --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    
    <script>
        const currentUserId = '{{ auth()->id() }}';
        const groupId = '{{ $grupo->id }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Função para rolar para a última mensagem
        function scrollToBottom() {
            const messages = document.getElementById('messages');
            messages.scrollTop = messages.scrollHeight;
        }

        // Função de notificação (apenas um placeholder, você deve ter a implementação real)
        function showToast(message, type) {
            // Implementação de um Toast/SweetAlert/Notificação aqui. 
            // Ex: alert(`${type.toUpperCase()}: ${message}`); 
            console.log(`${type.toUpperCase()}: ${message}`);
        }

        // Função para renderizar uma nova mensagem no chat
        function renderMessage(message, isSent) {
            const messagesContainer = document.getElementById('messages');
            // ... (restante da função renderMessage)
            const messageEl = document.createElement('div');
            messageEl.classList.add('message', isSent ? 'sent' : 'received');
            messageEl.innerHTML = `
                <div class="message-content">
                    <strong>${isSent ? 'Você' : message.user.name}:</strong>
                    ${message.conteudo}
                </div>
                <small class="text-muted" style="font-size: 0.65em;">
                    ${new Date(message.created_at).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })}
                </small>
            `;
            messagesContainer.appendChild(messageEl);
            scrollToBottom();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa o selectpicker dentro do modal (se for admin)
            $('.selectpicker').selectpicker();
            
            // Rola para a última mensagem ao carregar
            scrollToBottom();

            // --- CHAT MESSAGE SUBMISSION (AJAX) ---
            const form = document.getElementById('sendMessageForm');
            const textarea = document.getElementById('conteudo');
            const sendBtn = document.getElementById('sendBtn');
            const sendBtnText = document.getElementById('sendBtnText');
            const sendBtnLoading = document.getElementById('sendBtnLoading');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const content = textarea.value.trim();
                if (!content) return;

                sendBtn.disabled = true;
                sendBtnText.classList.add('d-none');
                sendBtnLoading.classList.remove('d-none');

                fetch(`/grupos/${groupId}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ conteudo: content })
                })
                .then(response => response.json())
                .then(data => {
                    renderMessage(data, true); 
                    textarea.value = ''; 
                })
                .catch(error => {
                    console.error('Erro ao enviar mensagem:', error);
                    showToast('Não foi possível enviar a mensagem.', 'error');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtnText.classList.remove('d-none');
                    sendBtnLoading.classList.add('d-none');
                });
            });

            // --- Lógica de Auto-Resize para a textarea ---
            textarea.addEventListener('input', function() {
                this.style.height = '40px'; // Altura mínima
                this.style.height = (this.scrollHeight) + 'px';
            });


            // --- LÓGICA DE GERENCIAMENTO DE MEMBROS (REMOVER) ---
            document.querySelectorAll('.remove-member-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userIdToRemove = this.dataset.userId;
                    const userName = this.dataset.userName;
                    
                    if (!confirm(`Tem certeza que deseja remover ${userName} do grupo?`)) {
                        return;
                    }

                    showLoading(true);

                    fetch(`/grupos/${groupId}/remover-usuario/${userIdToRemove}`, {
                        method: 'POST', 
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'X-HTTP-Method-Override': 'DELETE' 
                        },
                        body: JSON.stringify({ _method: 'DELETE' }) 
                    })
                    .then(response => {
                        if (response.ok || response.redirected) {
                            showToast(`Usuário ${userName} removido com sucesso.`, 'success');
                            setTimeout(() => {
                                window.location.reload(); 
                            }, 500); 
                        } else {
                            return response.json().then(error => Promise.reject(error));
                        }
                    })
                    .catch(error => {
                        showLoading(false);
                        console.error('Erro ao remover usuário:', error);
                        showToast('Erro ao remover usuário. Verifique se você é o Admin.', 'error');
                    });
                });
            });
            
            // --- LÓGICA DE GERENCIAMENTO DE MEMBROS (ADICIONAR - Apenas Admin) ---
            const addMembersForm = document.getElementById('addMembersForm');
            if(addMembersForm) {
                addMembersForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const selectedMembers = $(this).find('select[name="new_members[]"]').val();
                    if (selectedMembers.length === 0) return;

                    showLoading(true);

                    fetch(`/grupos/${groupId}/adicionar-membros`, { 
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ membros: selectedMembers })
                    })
                    .then(response => {
                         if (!response.ok) {
                            return response.json().then(error => Promise.reject(error));
                        }
                        return response.json();
                    })
                    .then(data => {
                        showLoading(false);
                        showToast(data.message || 'Membros adicionados com sucesso!', 'success');
                        setTimeout(() => { window.location.reload(); }, 500);
                    })
                    .catch(error => {
                        showLoading(false);
                        console.error('Erro ao adicionar membros:', error);
                        showToast('Erro ao adicionar membros.', 'error');
                    });
                });
            }
        });

    </script>
@endpush