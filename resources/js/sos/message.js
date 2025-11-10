// resources/js/mensagens.js

// --- Variáveis Globais (DOM Elements) ---
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const alertaEl = document.getElementById('mensagemAlerta');
const textoAlertaEl = document.getElementById('mensagemTextoCompleto');
const modalEl = document.getElementById('mensagemModal');
const conteudoModalEl = document.getElementById('mensagemConteudo');
const dataModalEl = document.getElementById('mensagemData');
const fecharModalBtn = document.getElementById('fecharModal');
const responderBtn = document.getElementById('enviarResposta');

let mensagensPendentes = []; // Armazena as mensagens SOS não lidas

// --- Funções de API e Manipulação de Dados ---

/**
 * Busca mensagens SOS não lidas do backend.
 */

document.addEventListener('DOMContentLoaded', function() {
    const userIdLogado = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
    
    // 1. Carrega mensagens não lidas no início (para não perder SOS recebidos offline)
    fetchMensagensNaoLidas();

    // 2. CONFIGURAÇÃO DO LISTENER EM TEMPO REAL (SOS)
    // Usaremos a lógica de escuta que já estava no seu Blade.
    if (typeof Echo !== 'undefined' && !window.echoRegistered) {
        
        console.log('[SOS RT] 🚨 Tentando escutar o canal SOS: mensagem_sos');

        Echo.channel('mensagem_sos')
            .listen('.NovaMensagemSosEvent', (e) => {
                console.log('[SOS RT] 🔔 SOS Recebido em tempo real:', e);
                
                // Estrutura o objeto de mensagem recebida
                const novaMensagem = {
                    id: e.mensagem.id, // O evento deve retornar o ID correto da MensagemSos
                    conteudo: e.mensagem.conteudo,
                    data: e.mensagem.created_at || new Date().toISOString()
                };
                
                // 1. Adiciona a nova mensagem à lista de pendentes
                mensagensPendentes.unshift(novaMensagem);
                
                // 2. Atualiza o alerta visual
                atualizarAlerta();
                
                // 3. (RECOMENDADO) Exibe um Toast de alerta para notificação imediata
                if (typeof window.showToast === 'function') {
                    // O Controller não retorna quem enviou, apenas o ID, mas isso é suficiente.
                    window.showToast('Emergência SOS recebida!', 'error'); 
                }
            })
            .error((e) => {
                console.error('[SOS RT] ❌ Erro na escuta do canal SOS:', e);
            });
            
        window.echoRegistered = true;
    }
    
    // 3. Event Listeners para botões (Alerta, OK, Responder)
    document.getElementById('mensagemAlerta')?.addEventListener('click', () => {
        mostrarProximaMensagem();
    });
    
    document.getElementById('fecharModal')?.addEventListener('click', fecharEMarcarLida);
    
    document.getElementById('enviarResposta')?.addEventListener('click', iniciarResposta);
});


function fetchMensagensNaoLidas() {
    fetch('/mensagens_nao_lidas')
        .then(res => res.json())
        .then(dados => {
            if (dados && dados.length > 0) {
                mensagensPendentes = dados;
                atualizarAlerta();
            }
        })
        .catch(error => console.error('Erro ao buscar mensagens SOS não lidas:', error));
}

/**
 * Marca uma MensagemSos específica como lida no servidor.
 * @param {number} mensagemId
 */
function marcarMensagemComoLida(mensagemId) {
    fetch('/mensagem_lida', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            id: mensagemId
        })
    })
    .catch(error => console.error('Erro de rede ao marcar como lida:', error));
}

// --- Funções de Interface (UI) ---

/**
 * Atualiza a visibilidade e o texto do alerta de novas mensagens.
 */
function atualizarAlerta() {
    if (alertaEl && textoAlertaEl) {
        if (mensagensPendentes.length > 0) {
            alertaEl.classList.remove('hidden');
            textoAlertaEl.textContent = `Nova(s) mensagem(s) SOS (${mensagensPendentes.length})`;
        } else {
            alertaEl.classList.add('hidden');
            textoAlertaEl.textContent = '';
        }
    }
}

/**
 * Exibe a primeira mensagem SOS pendente no modal.
 */
function mostrarProximaMensagem() {
    const mensagem = mensagensPendentes[0];
    if (!mensagem || !modalEl) {
        atualizarAlerta();
        return;
    }

    conteudoModalEl.textContent = mensagem.conteudo;
    dataModalEl.textContent = formatarData(mensagem.data);
    modalEl.dataset.mensagemId = mensagem.id;
    
    modalEl.classList.remove('hidden');
}

/**
 * Fecha o modal, remove a mensagem da lista pendente e marca como lida.
 */
function fecharEMarcarLida() {
    const mensagemAtual = mensagensPendentes.shift(); // Remove a primeira da fila
    if (mensagemAtual) {
        marcarMensagemComoLida(mensagemAtual.id);
    }
    
    if (modalEl) modalEl.classList.add('hidden');
    
    // Se ainda houver mensagens, mostra a próxima
    if (mensagensPendentes.length > 0) {
        setTimeout(() => mostrarProximaMensagem(), 300);
    }
    
    atualizarAlerta();
}

/**
 * Inicia o redirecionamento para responder a mensagem SOS.
 */
function iniciarResposta() {
    const mensagemAtual = mensagensPendentes[0]; // Pega a primeira para obter o ID
    if (mensagemAtual && mensagemAtual.id) {
        // Redireciona para a rota de chat (ChatController@responderMensagemSos)
        window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
    } else {
        // Assume que window.showToast existe no seu core.js ou app.js
        if (typeof window.showToast === 'function') {
            window.showToast('Mensagem inválida para responder.', 'error');
        }
    }
}

/**
 * Formata a string de data para um formato legível.
 * @param {string} dataString
 */
function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleString('pt-PT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// --- Inicialização (Event Listeners) ---

document.addEventListener('DOMContentLoaded', function() {
    // 1. Carregar mensagens SOS ao iniciar
    fetchMensagensNaoLidas();

    // 2. Event Listeners para Interação
    if (alertaEl) alertaEl.addEventListener('click', mostrarProximaMensagem);
    if (fecharModalBtn) fecharModalBtn.addEventListener('click', fecharEMarcarLida);
    if (responderBtn) responderBtn.addEventListener('click', iniciarResposta);
});