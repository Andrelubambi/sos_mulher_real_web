export function setupUI() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    const backButton = document.getElementById('backButton');
    const chatArea = document.getElementById('chatArea');
    const tabMensagens = document.getElementById('tabMensagens');
    const tabUsuarios = document.getElementById('tabUsuarios');
    const mensagensRecentes = document.getElementById('mensagensRecentes');
    const listaUsuarios = document.getElementById('listaUsuarios');

    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
    });

    closeSidebar.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });

    backButton.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            chatArea.classList.remove('active');
            sidebar.style.display = 'flex';
        }
    });

    tabMensagens.addEventListener('click', () => {
        mensagensRecentes.style.display = 'block';
        listaUsuarios.style.display = 'none';
        tabMensagens.classList.add('active');
        tabUsuarios.classList.remove('active');
    });

    tabUsuarios.addEventListener('click', () => {
        mensagensRecentes.style.display = 'none';
        listaUsuarios.style.display = 'block';
        tabMensagens.classList.remove('active');
        tabUsuarios.classList.add('active');
    });
}