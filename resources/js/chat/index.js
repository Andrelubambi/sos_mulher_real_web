// resources/js/chat/index.js
import { EchoService } from './services/EchoService.js';
import { ChatService } from './services/ChatService.js';
import { UIService } from './services/UIService.js';
import { VideoCallService } from './services/VideoCallService.js';
import { NotificationService } from './services/NotificationService.js';

class ChatApp {
    constructor() {
        this.usuarioLogadoId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        this.usuarioAtualId = null;
        
        this.echoService = new EchoService();
        this.chatService = new ChatService();
        this.uiService = new UIService();
        this.videoCallService = new VideoCallService();
        this.notificationService = new NotificationService();
        
        this.init();
    }

    init() {
        this.initializeServices();
        this.bindEvents();
        this.loadInitialData();
    }

    initializeServices() {
        this.echoService.initialize();
        this.uiService.initialize();
        this.notificationService.initialize();
        this.videoCallService.initialize();
    }

    bindEvents() {
        // Mobile menu
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            this.uiService.showSidebar();
        });

        document.getElementById('closeSidebar')?.addEventListener('click', () => {
            this.uiService.hideSidebar();
        });

        // Tab switching
        document.getElementById('tabMensagens')?.addEventListener('click', () => {
            this.uiService.showTab('mensagens');
        });

        document.getElementById('tabUsuarios')?.addEventListener('click', () => {
            this.uiService.showTab('usuarios');
        });

        // User selection
        document.querySelectorAll('.user-item, .chat-item').forEach(item => {
            item.addEventListener('click', () => {
                this.selectUser(item.dataset.userId, item.dataset.userName);
            });
        });

        // Send message
        document.getElementById('sendMessageForm')?.addEventListener('submit', (e) => {
            this.handleSendMessage(e);
        });

        // Video call
        document.getElementById('videoCallBtn')?.addEventListener('click', () => {
            this.videoCallService.startCall(this.usuarioAtualId);
        });

        // Auto-resize textarea
        const textarea = document.getElementById('conteudo');
        textarea?.addEventListener('input', () => {
            this.uiService.autoResizeTextarea(textarea);
        });

        // Enter to send
        textarea?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('sendMessageForm').dispatchEvent(new Event('submit'));
            }
        });
    }

    async selectUser(userId, userName) {
        this.usuarioAtualId = userId;
        
        // Update UI
        this.uiService.updateChatHeader(userName);
        this.uiService.showChatArea();
        this.videoCallService.updateButton(userId !== this.usuarioLogadoId);
        
        // Load messages
        await this.loadMessages(userId);
        
        // Start listening for new messages
        this.echoService.listenToUser(this.usuarioLogadoId, userId, (message) => {
            this.chatService.appendMessage(message, false);
        });
    }

    async loadMessages(userId) {
        try {
            const messages = await fetch(`/chat/messages/${userId}`).then(r => r.json());
            this.chatService.displayMessages(messages, this.usuarioLogadoId);
        } catch (error) {
            console.error('Erro ao carregar mensagens:', error);
        }
    }

    async handleSendMessage(e) {
        e.preventDefault();
        
        const textarea = document.getElementById('conteudo');
        const content = textarea.value.trim();
        
        if (!content || !this.usuarioAtualId) return;

        try {
            const message = await this.chatService.sendMessage(this.usuarioAtualId, content);
            this.chatService.appendMessage(message, true);
            textarea.value = '';
            this.uiService.resetTextareaHeight(textarea);
        } catch (error) {
            console.error('Erro ao enviar mensagem:', error);
            alert('Erro ao enviar mensagem');
        }
    }

    loadInitialData() {
        this.notificationService.loadPendingMessages();
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ChatApp();
});