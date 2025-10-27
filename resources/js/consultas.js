document.addEventListener('DOMContentLoaded', function() {
 
    window.editConsulta = function(id) {
        fetch(`/consultas/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_descricao').value = data.descricao;
                document.getElementById('edit_bairro').value = data.bairro;
                document.getElementById('edit_provincia').value = data.provincia;
                document.getElementById('edit_data').value = data.data;
                document.getElementById('edit_medico_id').value = data.medico_id;
                document.getElementById('editForm').action = `/consultas/${id}`;
            });
    };
 
    const hoje = new Date().toISOString().split('T')[0];
    document.getElementById('data').setAttribute('min', hoje);
    document.getElementById('edit_data').setAttribute('min', hoje);

    // Pesquisar consulta
    const searchInput = document.getElementById('searchConsulta');
    if(searchInput){
        searchInput.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll('#consultaTableBody tr').forEach(function(linha) {
                linha.style.display = linha.textContent.toLowerCase().includes(filtro) ? '' : 'none';
            });
        });
    }
 
    let mensagensPendentes = [];
    const userIdLogado = document.querySelector('meta[name="user-id"]').getAttribute('content');

    fetch('/mensagens_nao_lidas')
        .then(res => res.json())
        .then(dados => {
            mensagensPendentes = dados || [];
            atualizarAlerta();
        });

    function atualizarAlerta() {
        const alerta = document.getElementById('mensagemAlerta');
        const texto = document.getElementById('mensagemTextoCompleto');
        if(mensagensPendentes.length > 0){
            alerta.classList.remove('hidden');
            texto.textContent = `Nova mensagem (${mensagensPendentes.length})`;
        } else {
            alerta.classList.add('hidden');
            texto.textContent = '';
        }
    }
});


