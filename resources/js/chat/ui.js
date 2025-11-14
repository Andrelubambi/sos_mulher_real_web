export function setupUI() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const backBtn = document.getElementById('backBtn');
    const chatMain = document.getElementById('chatMain');
    const tabMensagens = document.getElementById('tabMensagens');
    const tabUsuarios = document.getElementById('tabUsuarios');
    const mensagensRecentes = document.getElementById('mensagensRecentes');
    const listaUsuarios = document.getElementById('listaUsuarios');
    const newChatBtn = document.getElementById('newChatBtn');

    if (!mobileMenuBtn || !sidebar || !overlay || !chatMain) {
        console.warn("UI Setup Warning: Elementos essenciais de navegação (mobileMenuBtn, sidebar, overlay ou chatMain) não encontrados. Navegação desativada.");
    }
    
    if (mobileMenuBtn && sidebar && overlay && chatMain) {
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            chatMain.classList.remove('active');
        });
    }

    if (overlay && sidebar && chatMain) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            chatMain.classList.remove('active');
        });
    }

    if (backBtn && sidebar && overlay && chatMain) {
        backBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            chatMain.classList.remove('active');
        });
    }

    if (!tabMensagens || !tabUsuarios || !mensagensRecentes || !listaUsuarios) {
        console.warn("UI Setup Warning: Elementos de tabulação (mensagens/usuários) não encontrados. Tabs desativadas.");
    }

    if (tabMensagens && mensagensRecentes && listaUsuarios && tabUsuarios) {
        tabMensagens.addEventListener('click', () => { 
            mensagensRecentes.classList.add('active');
            listaUsuarios.classList.remove('active');
            tabMensagens.classList.add('active');
            tabUsuarios.classList.remove('active');
        });
    }

    if (tabUsuarios && mensagensRecentes && listaUsuarios && tabMensagens) {
        tabUsuarios.addEventListener('click', () => {
            mensagensRecentes.classList.remove('active');
            listaUsuarios.classList.add('active');
            tabMensagens.classList.remove('active');
            tabUsuarios.classList.add('active');
        });
    }

    if (newChatBtn && tabUsuarios) {
        newChatBtn.addEventListener('click', () => {
            tabUsuarios.click();
        });
    }
}