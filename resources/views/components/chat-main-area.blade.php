{{-- Este componente representa a área de mensagens e o formulário de envio --}}
<main class="chat-main" id="chatMain">
    
    {{-- 1. HEADER DO CHAT (Topo) --}}
    <div class="chat-header">
        <div class="chat-header-left">
            <button class="back-btn" id="backBtn" title="Voltar">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="chat-participant">
                <div class="avatar-container">
                    <div class="avatar" id="currentAvatar">U</div>
                    <div class="online-status online"></div>
                </div>
                <div class="participant-info">
                    <div class="participant-name" id="participantName">Selecione um usuário</div>
                    <div class="participant-status" id="participantStatus">Online</div>
                </div>
            </div>
        </div>
        <div class="chat-header-right">
            <button class="more-options" title="Mais opções">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>

    {{-- 2. LISTA DE MENSAGENS (Scrollable Content) --}}
    <div class="messages-container" id="messagesContainer">
        
        {{-- CORREÇÃO: messagesList e welcome-message DEVEM ESTAR AQUI --}}
        <div class="messages-list" id="messagesList">
            <div class="welcome-message">
                <i class="fas fa-comment-dots"></i>
                <p>Selecione uma conversa para começar a bater papo</p>
            </div>
        </div>

        {{-- Indicador de digitação --}}
        <div class="typing-indicator" id="typingIndicator" style="display: none;">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <span class="typing-text">Digitando...</span>
        </div>
    </div>

    {{-- 3. COMPOSER (Formulário Fixo no Fundo) --}}
    <div class="message-composer" id="messageComposer">
        <form id="sendMessageForm">
            @csrf
            <div class="composer-input-container">
                <div class="composer-input-wrapper">
                    <textarea 
                        name="conteudo" 
                        id="messageInput" 
                        placeholder="Digite sua mensagem..." 
                        rows="1"
                        disabled
                    ></textarea>
                   
                </div>
                <button type="submit" class="send-btn" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</main>  