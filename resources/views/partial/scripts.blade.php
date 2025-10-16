<script>
// Mensagens SOS functionality
let mensagensPendentes = [];
let carregamentoConcluido = false;

document.addEventListener('DOMContentLoaded', function() {
    const userIdLogado = document.querySelector('meta[name="user-id"]').getAttribute('content');
    
    // Load unread messages
    fetch('/mensagens_nao_lidas')
        .then(res => res.json())
        .then(dados => {
            if (dados && dados.length > 0) {
                mensagensPendentes = dados;
                atualizarAlerta();
            }
            carregamentoConcluido = true;
        });
    
    // Echo listener for new messages
    if (!window.echoRegistered) {
        Echo.channel('mensagem_sos')
            .listen('.NovaMensagemSosEvent', (e) => {
                if (String(e.user_id) !== userIdLogado) return;
                
                const mensagem = {
                    id: e.id,
                    conteudo: e.conteudo,
                    data: e.data
                };
                
                mensagensPendentes.unshift(mensagem);
                atualizarAlerta();
            });
        
        window.echoRegistered = true;
    }
    
    // Message alert click handler
    const mensagemAlerta = document.getElementById('mensagemAlerta');
    if (mensagemAlerta) {
        mensagemAlerta.addEventListener('click', mostrarProximaMensagem);
    }
    
    // Modal close handler
    const fecharModal = document.getElementById('fecharModal');
    if (fecharModal) {
        fecharModal.addEventListener('click', fecharMensagemModal);
    }
    
    // Reply button handler
    const enviarResposta = document.getElementById('enviarResposta');
    if (enviarResposta) {
        enviarResposta.addEventListener('click', responderMensagem);
    }
});

function atualizarAlerta() {
    const alerta = document.getElementById('mensagemAlerta');
    const texto = document.getElementById('mensagemTextoCompleto');
    
    if (!alerta || !texto) return;
    
    if (mensagensPendentes.length > 0) {
        alerta.classList.remove('hidden');
        texto.textContent = `Nova mensagem (${mensagensPendentes.length})`;
    } else {
        alerta.classList.add('hidden');
        texto.textContent = '';
    }
}

function mostrarProximaMensagem() {
    if (mensagensPendentes.length === 0) return;
    
    const mensagem = mensagensPendentes[0];
    const conteudo = document.getElementById('mensagemConteudo');
    const data = document.getElementById('mensagemData');
    const modal = document.getElementById('mensagemModal');
    
    if (conteudo && data && modal) {
        conteudo.textContent = mensagem.conteudo;
        data.textContent = formatarData(mensagem.data);
        modal.classList.remove('hidden');
    }
}

function fecharMensagemModal() {
    const modal = document.getElementById('mensagemModal');
    const mensagemAtual = mensagensPendentes.shift();
    
    if (modal) modal.classList.add('hidden');
    
    // Mark as read
    if (mensagemAtual) {
        fetch('/mensagem_lida', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: mensagemAtual.id })
        });
    }
    
    // Show next message if exists
    if (mensagensPendentes.length > 0) {
        setTimeout(mostrarProximaMensagem, 300);
    } else {
        atualizarAlerta();
    }
}

function responderMensagem() {
    const mensagemAtual = mensagensPendentes[0];
    if (mensagemAtual && mensagemAtual.id) {
        window.location.href = `/responder_mensagem_sos/${mensagemAtual.id}`;
    } else {
        alert('Mensagem inválida para responder.');
    }
}

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

// Chart initialization
function initCharts() {
    // User distribution chart
    const ctx1 = document.getElementById('userChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Doutores', 'Estagiários', 'Vítimas'],
                datasets: [{
                    data: [{{ $doutoresCount }}, {{ $estagiariosCount }}, {{ $vitimasCount }}],
                    backgroundColor: ['#0d6efd', '#09cc06', '#ff5b5b'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Distribuição de Usuários' }
                }
            }
        });
    }
    
    // Consultas chart
    const ctx2 = document.getElementById('consultasChart');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($consultasPorStatus->pluck('status')),
                datasets: [{
                    data: @json($consultasPorStatus->pluck('total')),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Consultas por Status' }
                }
            }
        });
    }
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', initCharts);
</script>