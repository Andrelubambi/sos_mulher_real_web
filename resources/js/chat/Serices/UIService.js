// resources/js/chat/services/UIService.js
export class UIService {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.chatArea = document.getElementById('chatArea');
        this.currentTab = 'mensagens';
    }

    initialize() {
        this.updateConnectionStatus(false);
    }

    showSidebar() {
        this.sidebar?.classList.add('active');
    }

    hideSidebar() {
        this.sidebar?.classList.remove('active');
    }

    showChatArea() {
        if (window.innerWidth < 992) {
            this.sidebar?.classList.remove('active');
            this.chatArea?.classList.add('active');
        }
        
        const sendForm = document.getElementById('sendMessageForm');
        if (sendForm) {
            sendForm.style.display = 'flex';
        }
    }

    showTab(tabName) {
        const mensagensDiv = document.getElementById('mensagensRecentes');
        const usuariosDiv = document.getElementById('listaUsuarios');
        const tabMensagens = document.getElementById('tabMensagens');
        const tabUsuarios = document.getElementById('tabUsuarios');

        if (tabName === 'mensagens') {
            mensagensDiv.style.display = 'block';
            usuariosDiv.style.display = 'none';
            tabMensagens?.classList.add('active');
            tabUsuarios?.classList.remove('active');
        } else {
            mensagensDiv.style.display = 'none';
            usuariosDiv.style.display = 'block';
            tabMensagens?.classList.remove('active');
            tabUsuarios?.classList.add('active');
        }
        
        this.currentTab = tabName;
    }

    updateChatHeader(userName) {
        const chatHeader = document.getElementById('chatHeader');
        const userAvatar = document.getElementById('currentUserAvatar');
        const userStatus = document.getElementById('userStatus');

        if (chatHeader) chatHeader.textContent = userName;
        if (userAvatar) userAvatar.textContent = userName.charAt(0);
        if (userStatus) userStatus.textContent = 'Online';
    }

    autoResizeTextarea(textarea) {
        textarea.style.height = '45px';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }

    resetTextareaHeight(textarea) {
        textarea.style.height = '45px';
    }

    showEmptyState(container, icon, message, submessage = '') {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-${icon}"></i>
                <p>${message}</p>
                ${submessage ? `<small>${submessage}</small>` : ''}
            </div>
        `;
    }

    clearEmptyState(container) {
        const emptyState = container.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }
    }

    showNotification(message, type = 'info') {
        console.log(`[${type.toUpperCase()}] ${message}`);
    }

    updateConnectionStatus(connected) {
        const dot = document.getElementById('connectionDot');
        const text = document.getElementById('connectionText');

        if (dot && text) {
            if (connected) {
                dot.classList.remove('disconnected');
                text.textContent = 'Conectado';
            } else {
                dot.classList.add('disconnected');
                text.textContent = 'Desconectado';
            }
        }
    }
}