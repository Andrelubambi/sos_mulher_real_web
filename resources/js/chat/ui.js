 
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

    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        chatMain.classList.remove('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        chatMain.classList.remove('active');
    });

    backBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        chatMain.classList.remove('active');
    });

    tabMensagens.addEventListener('click', () => {
        mensagensRecentes.classList.add('active');
        listaUsuarios.classList.remove('active');
        tabMensagens.classList.add('active');
        tabUsuarios.classList.remove('active');
    });

    tabUsuarios.addEventListener('click', () => {
        mensagensRecentes.classList.remove('active');
        listaUsuarios.classList.add('active');
        tabMensagens.classList.remove('active');
        tabUsuarios.classList.add('active');
    });

    newChatBtn.addEventListener('click', () => {
        tabUsuarios.click();
    });
}
